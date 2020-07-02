# changelog title

A description of your component

## [a.b.c]

* [keyword] description
* [keyword] description
* [keyword] description  (PDXC-xyz)

## [a.b.d]

* [keyword] description
* [keyword] description (PDXC-fgh)
* [keyword] description

<!---
keyword amongst: SECURITY, BUGFIX, FEATURE, ENHANCEMENT or PERFORMANCE
and a.b.c matching the version number of the package. (mandatory)
check https://github.dxc.com/Platform-DXC/release-pipeline/blob/master/docs/CHANGE.md
-->


## [1.0.0]
* [ADDED] Added the IaC component.

## [1.0.1]
* [CHANGED] Updated to set RDS endpoint and secret ARN as environment variables.

## [1.0.2]
* [ADDED] Added a php SQL test file and updated.

## [1.0.3]
* [CHANGED] Updated Dockerfile to install sqlserver driver for PHP.

## [1.0.4]
* [CHANGED] Updated test file. 

## [1.0.5]
* [ADDED] Sample PHP file to connect to RDS. 

## [1.0.6]
* [CHANGED] Tweak RDS PHP file.

## [1.0.7]
* [CHANGED] Tweak RDS PHP file, testing DB name and credentials

## [1.0.8]
* [CHANGED] Tweak RDS PHP file, testing query to server without DB name.

## [1.0.9]
* [CHANGED] Tweak RDS PHP file, testing query to server directly.

## [1.0.10]
* [ADDED] Add new test page for connection to manually created database.

## [1.0.11]
* [CHANGED] Updated pages for SQL Driver test again.

## [1.0.12]
* [CHANGED] Updated pages for SQL Driver test using secrets to connect to dynamically created database

## [1.0.13]
* [ADDED] DB deployment in deploy.sh

## [1.0.14]
* [CHANGED] Tweak SQL PHP file

## [1.0.15]
* [ADDED] DB deployment in db_create_DB sql files

## [1.0.16]
* [CHANGED] Tweak db_create_DB sql files

## [1.0.17]
* [CHANGED] Tweak db_create_DB sql files - typo

## [1.0.18]
* [ADDED] DB deployment in db_create_TBL sql files

## [1.0.19]
* [CHANGED] Tweak SQL PHP file to list DB tables

## [1.0.20]
* [CHANGED] Tweak SQL PHP file to list DB tables, fix error

## [1.0.21]
* [CHANGED] Tweak db_create_TBL sql file to include minimum tables required for basic security framework

## [1.0.22]
* [CHANGED] Tweak db_create_TBL sql - fix code

## [1.0.23]
* [ADDED] DB deployment in deploy.sh

## [1.0.24]
* [CHANGED] Fixed Jenkins file for DEV deployment

## [1.0.25]
* [ADDED] Set up temporary test script folder and dbconn include

## [1.0.26]
* [CHANGED] Tweak dbconn for AWS. Again

## [1.0.27]
* [CHANGED] Add status table creation to db_create_TBL.sql, change PHP version to 7.2

## [1.0.28]
* [ADDED] Add seeddata folder with initial csv for populating foundational data

## [1.0.29]
* [CHANGED] Add sysbulletin table creation to db_create_TBL.sql

## [1.0.30]
* [ADDED] Add basic login, security, user preference, header/footer, home page and all supporting files, libraries and other foundational assets.

## [1.0.31]
* [ADDED] Added view creations script, updated

## [1.0.32]
* [ADDED] Added user list page

## [1.0.33]
* [CHANGED] Loading seed data 

## [1.0.34]
* [CHANGED] Change initial redirect when hitting base URL.  From phpinfo page to login 

## [1.0.35]
* [CHANGED] Add Esdras to the table creation script for dev purposes. Security page update.  Auto refresh rights on home page

