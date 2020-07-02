FROM mcr.microsoft.com/mssql-tools

RUN apt-get update -y
RUN apt-get install python3 python3-pip -y
RUN pip3 install awscli --upgrade

RUN mkdir -p /data/xcde-map-ui-db
COPY ./ /data/xcde-map-ui-db
RUN find .

CMD cd /data/xcde-map-ui-db && ./deploy_db_code.sh