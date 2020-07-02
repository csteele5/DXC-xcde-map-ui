pipeline {
  agent any
  environment {
    XCDE_MAP_UI_VER_PREFIX = "1.0"
    XCDE_MAP_UI_FULL_VER = ""
    XCDE_MAP_UI_SHORT_VER = ""
    GIT_ORIGIN_COMMIT = ""
    GIT_VERSION_TAG = ""
    PACKAGE_PATH = "config-management-xcde-map-ui"
    PACKAGE_VERSION = ""
    PACKAGE_NAME = ""
    PACKAGE_URL = ""
    PUBLISH_PACKAGE = ""
    TEST_PACKAGE_NAME = ""
    AWS_DEFAULT_REGION = "us-east-1"
  }
  stages {
    stage("PREPARE") {
      steps {
        dir("${WORKSPACE}") {
          script {
            withCredentials([[$class: "UsernamePasswordMultiBinding", credentialsId: "pdxc-jenkins", usernameVariable: "ARTIFACTORY_USER", passwordVariable: "ARTIFACTORY_PASSWORD"]]) {
              sh "docker login docker.dxc.com:8085 --username $ARTIFACTORY_USER --password $ARTIFACTORY_PASSWORD"
              sh "docker pull docker.dxc.com:8085/config-utility:latest"
              sh "docker pull docker.dxc.com:8085/release-pipeline-deploy:latest"
              def COMMIT_ID_SHORT = sh(script: "git log --pretty=format:'%h' -n 1", returnStdout: true).toString().trim()
              if (env.BRANCH_NAME == 'master') {
                XCDE_MAP_UI_FULL_VER = "${XCDE_MAP_UI_VER_PREFIX}.${BUILD_NUMBER}.${COMMIT_ID_SHORT}"
                XCDE_MAP_UI_SHORT_VER = "${XCDE_MAP_UI_VER_PREFIX}.${BUILD_NUMBER}"
              } else {
                FULL_BUILD_NAME = "${XCDE_MAP_UI_VER_PREFIX}.${JOB_NAME}-${BUILD_NUMBER}"
                MOD_VER = FULL_BUILD_NAME.substring(FULL_BUILD_NAME.lastIndexOf("/") + 1)
                XCDE_MAP_UI_FULL_VER = "${XCDE_MAP_UI_VER_PREFIX}.${MOD_VER}.${COMMIT_ID_SHORT}"
                XCDE_MAP_UI_SHORT_VER = "${XCDE_MAP_UI_VER_PREFIX}.${MOD_VER}"
              }
              PACKAGE_NAME = "xcde-map-ui-${XCDE_MAP_UI_SHORT_VER}.zip"
              XCDE_MAP_UI_SHORT_VER = "${XCDE_MAP_UI_SHORT_VER}"
              sh "echo ${env.PACKAGE_NAME}"
              sh "echo ${env.XCDE_MAP_UI_SHORT_VER}"
            }
          }
        }
      }
    }
    stage("Check CHANGELOG") {
      steps {
        dir("${WORKSPACE}") {
          script 
          {
            GIT_BRANCH = 'origin/' + env.BRANCH_NAME
            sh "echo ${GIT_BRANCH}"
            if (changeRequest() && GIT_BRANCH != 'origin/master') {
              def MODIFIED_FILES = sh(script: "git diff --name-only `git merge-base origin/master HEAD`", returnStdout: true).toString().trim()
              sh "echo '${MODIFIED_FILES}'"
              if (MODIFIED_FILES.contains('CHANGELOG.md') == false) {
                sh "echo 'CHANGELOG was not updated'"
                currentBuild.result = 'ABORTED'
                error('Stopping early…')
              }
            }
            if (GIT_BRANCH == 'origin/master' || params.FORCE_DEPLOY){
              XCDE_MAP_UI_SHORT_VER = "${XCDE_MAP_UI_VER_PREFIX}.${BUILD_NUMBER}"
              sh "echo ${XCDE_MAP_UI_SHORT_VER}"
              sh "grep -Fq '[${XCDE_MAP_UI_SHORT_VER}]' ${WORKSPACE}/CHANGELOG.md"
            }
          }
        }
      }
    }
    stage("SCAN") {
      when { changeRequest() }
      parallel {
        //Note - while this may find issues, git-secrets shuold be installed on each developer's laptop
        //to catch secrets BEFORE they are committed. If this stage errors, the secret must be
        //immediately changed as it's already been checked into GitHub and available in the commit history
        stage("Scan for secrets") {
          environment {
            PATH = "${WORKSPACE}/git-secrets:$PATH"
          }
          steps {
            sh "rm -rf ${WORKSPACE}/git-secrets"
            sh "git clone https://github.com/awslabs/git-secrets.git ${WORKSPACE}/git-secrets"
            //sh "cp -f git-secrets /usr/local/bin || true"
            dir("${WORKSPACE}/git-secrets") {
              sh '''
              cd ..
              git secrets --install --force
              git secrets --register-aws
              git secrets --add --allowed 'curl[^|]+\\|\\sjq\\s-r.*'
              git secrets --add --allowed '[^(]+\\(curl[^)]+\\)'
              git secrets --add --allowed 'if\\s\\[[^]]+];\\sthen'
              git secrets --add --allowed 'if\\s\\[\\[[^]]+\\]\\];\\sthen'
              git secrets --scan -r
              '''
            }
          }
        }
      }
    }
    stage("BUILD ARTIFACTS") {
      steps {
        dir("${WORKSPACE}") {
          script {
            dir("${WORKSPACE}") {
              withCredentials ([string(credentialsId: 'CFG_SB2_ARN', variable: 'ROLE_ARN')
                     ,string(credentialsId: 'CFG_SB2_EXT_ID', variable: 'EXT_ID')]) {
                withAWS (role: "${ROLE_ARN}", externalId: "${EXT_ID}") {
                  withCredentials([
                  [$class: "UsernamePasswordMultiBinding", credentialsId: "pdxc-jenkins", usernameVariable: "ARTIFACTORY_USER", passwordVariable: "ARTIFACTORY_PASSWORD"]]) {
                    // Build and deploy xcde-map-ui image
                    sh "docker build -t 'xcde-map-ui:latest' ."
                    sh "docker login docker.dxc.com:8085 --username $ARTIFACTORY_USER --password $ARTIFACTORY_PASSWORD"
                    sh "docker tag xcde-map-ui:latest docker.dxc.com:8085/xcde-map-ui:latest"
                    sh "docker push docker.dxc.com:8085/xcde-map-ui:latest"
                    sh "docker tag xcde-map-ui:latest docker.dxc.com:8085/xcde-map-ui:${XCDE_MAP_UI_SHORT_VER}"
                    sh "docker push docker.dxc.com:8085/xcde-map-ui:${XCDE_MAP_UI_SHORT_VER}"

                    // Build and deploy xcde-map-db image
                    sh "docker build -t 'xcde-map-db:latest' sql"
                    sh "docker login docker.dxc.com:8085 --username $ARTIFACTORY_USER --password $ARTIFACTORY_PASSWORD"
                    sh "docker tag xcde-map-db:latest docker.dxc.com:8085/xcde-map-db:latest"
                    sh "docker push docker.dxc.com:8085/xcde-map-db:latest"
                    sh "docker tag xcde-map-db:latest docker.dxc.com:8085/xcde-map-db:${XCDE_MAP_UI_SHORT_VER}"
                    sh "docker push docker.dxc.com:8085/xcde-map-db:${XCDE_MAP_UI_SHORT_VER}"
                  }
                }
              }
            }
          }
        }
      }
    }
    stage("DEPLOY") {
      when { changeRequest() }
      agent {
        docker {
          image 'release-pipeline-deploy:latest'
          registryUrl 'https://docker.dxc.com:8085'
          registryCredentialsId 'pdxc-jenkins'
          args '-u root -v $WORKSPACE:/xcde-map-ui'
        }
      }
      environment {
        PDXC_ENV = "sandbox"
        AWS_DEFAULT_REGION = "us-east-1"
      }
      steps {
        withCredentials ([string(credentialsId: 'CFG_SB2_ARN', variable: 'ROLE_ARN')
                ,string(credentialsId: 'CFG_SB2_EXT_ID', variable: 'EXT_ID')]) {
          withAWS (role: "${ROLE_ARN}", externalId: "${EXT_ID}") {
            withCredentials([
              [$class: "UsernamePasswordMultiBinding", credentialsId: "pdxc-jenkins", usernameVariable: "ARTIFACTORY_USER", passwordVariable: "ARTIFACTORY_PASSWORD"]
            ]) {
              sh "echo ${PACKAGE_NAME}"
              sh "echo ${XCDE_MAP_UI_SHORT_VER} > ${WORKSPACE}/version.txt"
              sh "bash -x deploy.sh"
            }
          }
        }
      }
    }
    stage("SANDBOX TEST") {
      when { changeRequest() }
      agent {
        docker {
          image 'release-pipeline-test:latest'
          registryUrl 'https://docker.dxc.com:8085'
          registryCredentialsId 'pdxc-jenkins'
          args '-u root -v $WORKSPACE:/xcde-map-ui'
        }
      }
      steps {
        script {
          try {
            withCredentials ([string(credentialsId: 'CFG_SB2_ARN', variable: 'ROLE_ARN')
                ,string(credentialsId: 'CFG_SB2_EXT_ID', variable: 'EXT_ID')]) {
              withAWS (role: "${ROLE_ARN}", externalId: "${EXT_ID}") {
                withCredentials([
                  [$class: "UsernamePasswordMultiBinding", credentialsId: "pdxc-jenkins", usernameVariable: "ARTIFACTORY_USER", passwordVariable: "ARTIFACTORY_PASSWORD"]]) {
                  sh "(cd tests; bash -x deploy.sh)"
                }
              }
            }
          } catch (err) {
            currentBuild.result = 'ABORTED'
            error('Stopping early tests failed…')
          }
        }
      }
      post {
        always {
          junit "**/*.xml"
          archive "**/*.xml"
        }
      }
    }
    stage("PACKAGE ARTIFACTS") {
      when {
        expression {
          GIT_BRANCH = 'origin/' + env.BRANCH_NAME
          return GIT_BRANCH == 'origin/master' || params.FORCE_DEPLOY
        }
      }
      parallel {
        stage("Map UI Image: unstable") {
          steps {
            dir("${WORKSPACE}") {
              script {
                withCredentials([[$class: "UsernamePasswordMultiBinding", credentialsId: "pdxc-jenkins", usernameVariable: "ARTIFACTORY_USER", passwordVariable: "ARTIFACTORY_PASSWORD"]]) {
                  sh "docker login docker.dxc.com:8085 --username $ARTIFACTORY_USER --password $ARTIFACTORY_PASSWORD"
                  sh "docker tag docker.dxc.com:8085/xcde-map-ui:${XCDE_MAP_UI_SHORT_VER} docker.dxc.com:8085/xcde-map-ui:unstable"
                  sh "docker push docker.dxc.com:8085/xcde-map-ui:unstable"
                }
              }
            }
          }
        }
        stage("Deploy Artifacts: unstable") {
          steps {
            dir("${WORKSPACE}") {
              script {
                withCredentials([
                  [$class: "UsernamePasswordMultiBinding", credentialsId: 'pdxc-jenkins-github-2', usernameVariable: 'GIT_USER', passwordVariable: 'GIT_PASSWORD'],
                  [$class: "UsernamePasswordMultiBinding", credentialsId: "pdxc-jenkins", usernameVariable: "ARTIFACTORY_USER", passwordVariable: "ARTIFACTORY_PASSWORD"]
                ]) {
                  GIT_ORIGIN_COMMIT = sh(script: 'git rev-parse refs/remotes/origin/${GIT_BRANCH}', returnStdout: true).trim()
                  sh "git fetch --tags \"\$(echo \"${GIT_URL}\" | sed -e 's!://!://'${GIT_USER}:${GIT_PASSWORD}'@!')\""
                  GIT_VERSION_TAG = sh(script: 'git describe --tags --exact-match "${GIT_ORIGIN_COMMIT}" 2>/dev/null | grep -E "^[0-9]" || echo "NO_VERSION_TAG"', returnStdout: true).trim()
                  PACKAGE_VERSION = (GIT_VERSION_TAG == 'NO_VERSION_TAG' ? "${XCDE_MAP_UI_FULL_VER}" : GIT_VERSION_TAG)
                  PUBLISH_PACKAGE = (GIT_VERSION_TAG == 'NO_VERSION_TAG' ? 'PUBLISH' : 'DO_NOT_PUBLISH')
                  sh "git tag \"${XCDE_MAP_UI_FULL_VER}\" \"${GIT_ORIGIN_COMMIT}\""
                  sh "git push \"\$(echo \"${GIT_URL}\" | sed -e 's!://!://'${GIT_USER}:${GIT_PASSWORD}'@!')\" \"${XCDE_MAP_UI_FULL_VER}\""
                  sh "echo ${XCDE_MAP_UI_SHORT_VER} > ${WORKSPACE}/version.txt"

                  sh "docker run -v ${WORKSPACE}:${WORKSPACE} -w ${WORKSPACE} docker.dxc.com:8085/config-utility:latest zip ${PACKAGE_NAME} deploy.sh version.txt CHANGELOG.md xcde-map-ui-cfn.yaml"
                  sh "curl -X PUT --user ${ARTIFACTORY_USER}:${ARTIFACTORY_PASSWORD} --data-binary @${PACKAGE_NAME} https://artifactory.csc.com/artifactory/platformdxc-generic/${PACKAGE_PATH}/${XCDE_MAP_UI_SHORT_VER}/${PACKAGE_NAME};status=unstable"
                  sh "curl -X PUT --user ${ARTIFACTORY_USER}:${ARTIFACTORY_PASSWORD} https://artifactory.csc.com/artifactory/api/storage/platformdxc-generic/${PACKAGE_PATH}/${XCDE_MAP_UI_SHORT_VER}/${PACKAGE_NAME}?properties=status=unstable"
                  TEST_PACKAGE_NAME = "tests-xcde-map-ui-${XCDE_MAP_UI_SHORT_VER}.zip"
                  sh "docker run -v ${WORKSPACE}:${WORKSPACE} -w ${WORKSPACE} docker.dxc.com:8085/config-utility:latest zip -rj ${WORKSPACE}/${TEST_PACKAGE_NAME} ${WORKSPACE}/tests/*"
                  sh "curl -X PUT --user ${ARTIFACTORY_USER}:${ARTIFACTORY_PASSWORD} --data-binary @${TEST_PACKAGE_NAME} https://artifactory.csc.com/artifactory/platformdxc-generic/tests-${PACKAGE_PATH}/${XCDE_MAP_UI_SHORT_VER}/${TEST_PACKAGE_NAME};status=unstable"
                  sh "curl -X PUT --user ${ARTIFACTORY_USER}:${ARTIFACTORY_PASSWORD} https://artifactory.csc.com/artifactory/api/storage/platformdxc-generic/tests-${PACKAGE_PATH}/${XCDE_MAP_UI_SHORT_VER}/${TEST_PACKAGE_NAME}?properties=status=unstable"
                }
              }
            }
          }
        }
      }
    }
    //Apply OOSS CaC Assessments
    stage("OOSS COMPLIANCE") {
      when {
        expression {
          GIT_BRANCH = 'origin/' + env.BRANCH_NAME
          return GIT_BRANCH == 'origin/master' || params.FORCE_DEPLOY
        }
      }
      agent {
        docker {
          image 'ooss-cac:stable'
          registryUrl 'https://docker.dxc.com:8085'
          registryCredentialsId 'pdxc-jenkins'
        }
      }
      steps {
        script {
          try {
            withCredentials ([
              usernamePassword(credentialsId: 'pdxc-jenkins-github-2', usernameVariable: 'GIT_USER', passwordVariable: 'GIT_PASSWORD'),
              usernamePassword(credentialsId:'OOSS_ARN_SB',usernameVariable: 'AWS_S3_KEY', passwordVariable:'AWS_S3_SECRET'),
              usernamePassword(credentialsId:'OOSS_MSTEAM',usernameVariable: 'MSTEAM_CHANNEL', passwordVariable:'MSTEAM_ROOM'),
              usernamePassword(credentialsId:'OOSS_PAPI_DEV',usernameVariable: 'PAPI_URL', passwordVariable:'PAPI_TOKEN')
            ]) {
              env.PDXC_ENV = "Dev"
              sh 'ooss-scanner'
            }
            //junit 'ooss-reports/junit*.xml'
            archiveArtifacts 'ooss-reports/*.*'
          } catch (err) {
            sh "echo ooss-scanner error: ${err}"
          }
        }
      }
    }
    stage("DEV DEPLOY") {
      when {
        expression {
          GIT_BRANCH = 'origin/' + env.BRANCH_NAME
          return GIT_BRANCH == 'origin/master' || params.FORCE_DEPLOY
        }
      }
      agent {
        docker {
          image 'release-pipeline-deploy:latest'
          registryUrl 'https://docker.dxc.com:8085'
          registryCredentialsId 'pdxc-jenkins'
          args '-u root -v $WORKSPACE:/xcde'
        }
      }
      environment {
        PDXC_ENV = "dev"
        AWS_DEFAULT_REGION = "us-east-1"
      }
      steps {
        withCredentials([
          string(credentialsId: 'CFGINSTALL_KEY_DEV', variable: 'AWS_ACCESS_KEY_ID'),
          string(credentialsId: 'CFGINSTALL_SECRET_DEV', variable: 'AWS_SECRET_ACCESS_KEY'),
          [$class: "UsernamePasswordMultiBinding", credentialsId: "pdxc-jenkins", usernameVariable: "ARTIFACTORY_USER", passwordVariable: "ARTIFACTORY_PASSWORD"]
        ]) {
          sh '''
            LOCATION=$(pwd)
            XCDE_MAP_UI_SHORT_VER="${XCDE_MAP_UI_VER_PREFIX}.${BUILD_NUMBER}"
            PACKAGE_NAME="xcde-map-ui-${XCDE_MAP_UI_SHORT_VER}.zip"
            env
            rm -rf ${PDXC_ENV} && mkdir ${PDXC_ENV}
            cd ${PDXC_ENV}
            packageURL="https://artifactory.csc.com/artifactory/platformdxc-generic/${PACKAGE_PATH}/${XCDE_MAP_UI_SHORT_VER}/${PACKAGE_NAME}"
            curl -f -s -u "${ARTIFACTORY_USER}:${ARTIFACTORY_PASSWORD}" -O "${packageURL}"
            unzip ${PACKAGE_NAME}
            pwd
            ls -al
            bash -x deploy.sh
          '''
        }
      }
    }
    stage("DEV TEST") {
      when {
        expression {
          GIT_BRANCH = 'origin/' + env.BRANCH_NAME
          return GIT_BRANCH == 'origin/master' || params.FORCE_DEPLOY
        }
      }
      agent {
        docker {
          image 'release-pipeline-test:latest'
          registryUrl 'https://docker.dxc.com:8085'
          registryCredentialsId 'pdxc-jenkins'
          args '-u root'
        }
      }
      environment {
        PDXC_ENV = "dev"
        AWS_DEFAULT_REGION = "us-east-1"
      }
      steps {
        script {
          try {
            withCredentials([
              string(credentialsId: 'CFGINSTALL_KEY_DEV', variable: 'AWS_ACCESS_KEY_ID'),
              string(credentialsId: 'CFGINSTALL_SECRET_DEV', variable: 'AWS_SECRET_ACCESS_KEY')
            ]) {
              sh "(cd tests; bash -x deploy.sh)"
            }
          } catch (err) {
            currentBuild.result = 'ABORTED'
            error('Stopping early tests failed…')
          }
        }
      }
      post {
        always {
          junit "**/*.xml"
          archive "**/*.xml"
          archive "**/*.html"
        }
      }
    }
    stage("UPDATE ARTIFACTS") {
      when {
        expression {
          GIT_BRANCH = 'origin/' + env.BRANCH_NAME
          return GIT_BRANCH == 'origin/master' || params.FORCE_DEPLOY
        }
      }
      parallel {
        stage("MAP UI Image: stable") {
          steps {
            dir("${WORKSPACE}") {
              script {
                withCredentials([[$class: "UsernamePasswordMultiBinding", credentialsId: "pdxc-jenkins", usernameVariable: "ARTIFACTORY_USER", passwordVariable: "ARTIFACTORY_PASSWORD"]]) {
                  sh "docker login docker.dxc.com:8085 --username $ARTIFACTORY_USER --password $ARTIFACTORY_PASSWORD"
                  sh "docker tag docker.dxc.com:8085/xcde-map-ui:${XCDE_MAP_UI_SHORT_VER} docker.dxc.com:8085/xcde-map-ui:stable"
                  sh "docker push docker.dxc.com:8085/xcde-map-ui:stable"
                }
              }
            }
          }
        }
        stage("Deploy Artifacts: stable") {
          steps {
            dir("${WORKSPACE}") {
              script {
                withCredentials ([
                  [$class: "UsernamePasswordMultiBinding", credentialsId: 'pdxc-jenkins-github-2', usernameVariable: 'GIT_USER', passwordVariable: 'GIT_PASSWORD'],
                  [$class: "UsernamePasswordMultiBinding", credentialsId: "pdxc-jenkins", usernameVariable: "ARTIFACTORY_USER", passwordVariable: "ARTIFACTORY_PASSWORD"]
                ]) {
                  if (PUBLISH_PACKAGE == "PUBLISH") {
                    sh "curl -X PUT --user ${ARTIFACTORY_USER}:${ARTIFACTORY_PASSWORD} https://artifactory.csc.com/artifactory/api/storage/platformdxc-generic/${PACKAGE_PATH}/${XCDE_MAP_UI_SHORT_VER}/${PACKAGE_NAME}?properties=status=stable"
                    sh "curl -X PUT --user ${ARTIFACTORY_USER}:${ARTIFACTORY_PASSWORD} https://artifactory.csc.com/artifactory/api/storage/platformdxc-generic/tests-${PACKAGE_PATH}/${XCDE_MAP_UI_SHORT_VER}/${TEST_PACKAGE_NAME}?properties=status=stable"
                  } else {
                    echo "DID NOT PUBLISH_PACKAGE. CHECK LOGS. VALUE OF PUBLISH_PACKAGE WAS: ${PUBLISH_PACKAGE}"
                  }
                }
              }
            }
          }
        }
      }
    }
  }
  post {
    always {
      dir("${WORKSPACE}") {
        archive "tests/**/*.xml"
      }
      dir("${WORKSPACE}") {
        deleteDir()
      }
      dir("${WORKSPACE}@tmp") {
        deleteDir()
      }
      dir("${WORKSPACE}@2") {
        deleteDir()
      }
      dir("${WORKSPACE}@2@tmp") {
        deleteDir()
      }
      dir("${WORKSPACE}@3") {
        deleteDir()
      }
      dir("${WORKSPACE}@3@tmp") {
        deleteDir()
      }
      dir("${WORKSPACE}@4") {
        deleteDir()
      }
      dir("${WORKSPACE}@4@tmp") {
        deleteDir()
      }
    }
  }
}