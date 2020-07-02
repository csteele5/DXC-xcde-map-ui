#!/bin/bash

# Create / Update CloudFormation Stack Function
create_update_stack() {
  STACK_NAME=$1
  TEMPLATE_FILE=$2
  PARAMETER_OVERRIDES=$3

  STACK_STATUS=$(aws cloudformation describe-stacks --stack-name ${STACK_NAME} --region ${AWS_DEFAULT_REGION} --query 'Stacks[0].StackStatus' --output text || true)
  if [[ ("${STACK_STATUS}" = "DELETE_FAILED") || ("${STACK_STATUS}" = "ROLLBACK_FAILED") || ("${STACK_STATUS}" = "ROLLBACK_COMPLETE") || ("${STACK_STATUS}" = "DELETE_FAILED") ]]; then
      aws cloudformation delete-stack --stack-name ${STACK_NAME}
      aws cloudformation wait stack-delete-complete --stack-name ${STACK_NAME}
  fi

  set +e
  aws cloudformation deploy --stack-name ${STACK_NAME} --template-file ${TEMPLATE_FILE} --capabilities CAPABILITY_IAM --parameter-overrides ${PARAMETER_OVERRIDES} --no-fail-on-empty-changeset

  # Describe stack events on failure
  if [ $? -ne 0 ]
  then
    aws cloudformation describe-stack-events --stack-name ${STACK_NAME}
    exit 1
  fi

  set -e
}

set -e

export AWS_ACCOUNT=$(aws sts get-caller-identity --output text --query Account)
export AWS_REGION=$AWS_DEFAULT_REGION
export STACK_NAME="cfgXcdeMapUI"

CRED_EXIST=$(aws secretsmanager describe-secret --secret-id cfgRepoCredentials --query 'Name' --output text | grep cfgRepoCredentials || echo NONE)
if [ "${CRED_EXIST}" = "NONE" ]; then
    aws secretsmanager create-secret --name cfgRepoCredentials
    aws secretsmanager put-secret-value --secret-id cfgRepoCredentials --secret-string "{\"username\":\"${ARTIFACTORY_USER}\",\"password\":\"${ARTIFACTORY_PASSWORD2}\"}"
fi

CERTS=$(aws resourcegroupstaggingapi get-resources --tag-filters Key=Name,Values=PDXC-WILDCARD-CERT --resource-type-filters acm:certificate --region ${AWS_DEFAULT_REGION} --output text --query 'ResourceTagMappingList[*].ResourceARN')
for arn in $CERTS
do
  create_at=$(aws acm describe-certificate --certificate-arn ${arn} --query "Certificate.CreatedAt")
  if [ -z  $LATEST_DATE ] && [ -z $SSL_CERT ]
  then
    LATEST_DATE=$create_at
    SSL_CERT=$arn
  fi
  if (( $(echo "$create_at > $LATEST_DATE" |bc -l) ))
  then
    LATEST_DATE=$create_at
    SSL_CERT=$arn
  fi
done

ECS_VPC=$(aws ec2 describe-vpcs --filters "Name=tag:Name,Values=PDXC Core Shared VPC" --region ${AWS_DEFAULT_REGION} --output text --query 'Vpcs[*].VpcId')
PRIVATE_SUBNET_IDS=$(aws ec2 describe-subnets --filters "Name=tag:Name,Values=PDXC Core Shared Private*" "Name=vpc-id,Values=${ECS_VPC}" --output text --query 'Subnets[*].SubnetId' --region=${AWS_DEFAULT_REGION} | sed 's/\s/,/g')
PUBLIC_SUBNET_IDS=$(aws ec2 describe-subnets --filters "Name=tag:Name,Values=PDXC Core Shared Public Subnet*" "Name=vpc-id,Values=${ECS_VPC}" --output text --query 'Subnets[*].SubnetId' --region=${AWS_DEFAULT_REGION} | sed 's/\s/,/g')
HOSTEDZONENAME=$(aws resourcegroupstaggingapi get-resources --tag-filters Key=Name,Values=PDXC-Hosted-Domain --resource-type-filters route53 --query 'ResourceTagMappingList[*].ResourceARN' --output json --region us-east-1 --output text | awk -F'/' '{print $2"."}')

IMAGE_VERSION=`cat version.txt`

LOCATION=$(pwd)
create_update_stack cfgXcdeMapUI ${LOCATION}/xcde-map-ui-cfn.yaml "PublicSubnets=${PUBLIC_SUBNET_IDS} HostedZoneName=${HOSTEDZONENAME} PDXCENV=${PDXCENV} Certificate=${SSL_CERT} VpcId=${ECS_VPC} TaskSubnets=${PRIVATE_SUBNET_IDS} ImageVersion=${IMAGE_VERSION}"

SECURITY_GROUP_ID=$(aws cloudformation --region ${AWS_DEFAULT_REGION} describe-stacks --stack-name cfgXcdeMapUI --query "Stacks[0].Outputs[?OutputKey=='SecurityGroup'].OutputValue" --output text)
DB_TASK_DEFINITION=$(aws cloudformation --region ${AWS_DEFAULT_REGION} describe-stacks --stack-name cfgXcdeMapUI --query "Stacks[0].Outputs[?OutputKey=='DBTaskDef'].OutputValue" --output text)

RUN_TASK_OUTPUT=$(aws ecs run-task --cluster cfgXcdeMapUI --count 1 --launch-type FARGATE \
  --network-configuration "awsvpcConfiguration={subnets=[${PRIVATE_SUBNET_IDS}],securityGroups=[${SECURITY_GROUP_ID}],assignPublicIp=DISABLED}" \
  --task-definition ${DB_TASK_DEFINITION})

TASK_ARN=$(echo $RUN_TASK_OUTPUT | jq '.tasks[0].taskArn' | sed -e 's/^"//' -e 's/"$//')
aws ecs wait tasks-stopped --tasks "${TASK_ARN}" --cluster cfgXcdeMapUI

# Install ECS CLI
curl -o /tmp/ecs-cli https://amazon-ecs-cli.s3.amazonaws.com/ecs-cli-linux-amd64-latest
chmod +x /tmp/ecs-cli
TASK_ID=$(echo $TASK_ARN  |tr '/', ' ' |awk {'print $NF'})
/tmp/ecs-cli logs --task-id $TASK_ID --task-def ${DB_TASK_DEFINITION}

DESC_TASK_OUT=$(aws ecs describe-tasks --cluster cfgXcdeMapUI --tasks ${TASK_ARN})
EXIT_CODE=$(echo $DESC_TASK_OUT | jq '.tasks[0].containers[0].exitCode')
if [ "$EXIT_CODE" -ne "0" ]
then
  exit 1
fi


# DB Deployment
#pip2 install mssql-cli
#mkdir /opt/ssl
#curl -sSL -o /opt/ssl/rds-combined-ca-bundle.pem https://s3.amazonaws.com/rds-downloads/rds-combined-ca-bundle.pem

#DB_HOSTNAME=$(aws cloudformation --region ${AWS_DEFAULT_REGION} describe-stacks --stack-name cfgXcdeMapUI --query "Stacks[0].Outputs[?OutputKey=='DBHostname'].OutputValue" --output text)
#DB_PORT=$(aws cloudformation --region ${AWS_DEFAULT_REGION} describe-stacks --stack-name cfgXcdeMapUI --query "Stacks[0].Outputs[?OutputKey=='DBPort'].OutputValue" --output text)
#DB_SECRET_ARN=$(aws cloudformation --region ${AWS_DEFAULT_REGION} describe-stacks --stack-name cfgXcdeMapUI --query "Stacks[0].Outputs[?OutputKey=='DBSecretARN'].OutputValue" --output text)
#DB_SECRET_VALUE=$(aws secretsmanager get-secret-value --secret-id ${DB_SECRET_ARN} --query "type(SecretString)")

#export MSSQL_CLI_TELEMETRY_OPTOUT="True"
#find / -name mssqlclirc
#MSSQL_CLI_TELEMETRY_OPTOUT=1 mssql-cli -S $DB_HOSTNAME -N -C -U admin -P $DB_SECRET_VALUE -i sql/db_init.sql
#
