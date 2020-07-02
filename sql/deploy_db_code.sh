#!/bin/bash

set -v
set -e

DB_USER="admin"
DB_PASSWORD=$(/usr/local/bin/aws secretsmanager get-secret-value --secret-id ${DB_SECRET_ARN} --query SecretString --output text)

/opt/mssql-tools/bin/sqlcmd -S $DB_HOSTNAME -U $DB_USER -P $DB_PASSWORD -i db_init.sql

/opt/mssql-tools/bin/sqlcmd -S $DB_HOSTNAME -U $DB_USER -P $DB_PASSWORD -i db_create_DB.sql

/opt/mssql-tools/bin/sqlcmd -S $DB_HOSTNAME -U $DB_USER -P $DB_PASSWORD -i db_create_TBLs.sql

/opt/mssql-tools/bin/sqlcmd -S $DB_HOSTNAME -U $DB_USER -P $DB_PASSWORD -i db_vXCDE_UserList_remove.sql

/opt/mssql-tools/bin/sqlcmd -S $DB_HOSTNAME -U $DB_USER -P $DB_PASSWORD -i db_vXCDE_UserList_create.sql

# Seed data
/opt/mssql-tools/bin/sqlcmd -S $DB_HOSTNAME -U $DB_USER -P $DB_PASSWORD -i seeddata/01_DataMap_Status.sql