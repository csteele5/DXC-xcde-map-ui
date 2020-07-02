
USE [master]
GO

DECLARE @dbName nvarchar(128);
DECLARE @dbLogName nvarchar(128);
DECLARE @dbPhysicalLoc nvarchar(128);
DECLARE @dbFileName nvarchar(128);
DECLARE @dbLogFileName nvarchar(128);
DECLARE @dbFileNameFull nvarchar(255);
DECLARE @dbLogFileNameFull nvarchar(255);
DECLARE @dbSQL nvarchar(MAX);
DECLARE @ParmDefinition nvarchar(500); 
SET @dbName = N'XCDEUI';
SET @dbLogName = @dbName+'_log';
SET @dbPhysicalLoc = N'C:\Program Files\Microsoft SQL Server\MSSQL14.MSSQLSERVER\MSSQL\DATA\';

SET @dbFileName = @dbName+'.mdf';
SET @dbLogFileName = @dbLogName+'.ldf';

SET @dbFileNameFull = @dbPhysicalLoc+@dbFileName;
SET @dbLogFileNameFull = @dbPhysicalLoc+@dbLogFileName;

IF (NOT EXISTS (SELECT name 
FROM master.dbo.sysdatabases 
WHERE ('[' + name + ']' = @dbname 
OR name = @dbname)))
BEGIN
	--PRINT @dbname+' does not exist';
	/*
	SET @dbSQL = 'CREATE DATABASE '+@dbName +' ON  PRIMARY ';
	SET @dbSQL = @dbSQL + '( NAME = '''+@dbName +''', FILENAME = '''+@dbPhysicalLoc+@dbFileName +''' , SIZE = 3072KB , MAXSIZE = UNLIMITED, FILEGROWTH = 1024KB ) ';
	SET @dbSQL = @dbSQL + ' LOG ON ';
	SET @dbSQL = @dbSQL + '( NAME = '''+@dbLogName +''', FILENAME = '''+@dbPhysicalLoc+@dbLogName +''' , SIZE = 1024KB , MAXSIZE = 2048GB , FILEGROWTH = 10%)';
	exec(@dbSQL);
	*/
	
	SET @dbSQL = 'CREATE DATABASE '+@dbName ;
	exec(@dbSQL);

	SET @dbSQL = 'ALTER DATABASE ['+@dbname+'] ADD FILEGROUP [MappingUI];

	ALTER DATABASE ['+@dbname+'] SET COMPATIBILITY_LEVEL = 140;

	IF (1 = FULLTEXTSERVICEPROPERTY(''IsFullTextInstalled''))
	begin
	EXEC ['+@dbname+'].[dbo].[sp_fulltext_database] @action = ''enable'';
	end;

	ALTER DATABASE ['+@dbname+'] SET ANSI_NULL_DEFAULT OFF 

	ALTER DATABASE ['+@dbname+'] SET ANSI_NULLS OFF 

	ALTER DATABASE ['+@dbname+'] SET ANSI_PADDING OFF 

	ALTER DATABASE ['+@dbname+'] SET ANSI_WARNINGS OFF 

	ALTER DATABASE ['+@dbname+'] SET ARITHABORT OFF 

	ALTER DATABASE ['+@dbname+'] SET AUTO_CLOSE OFF 

	ALTER DATABASE ['+@dbname+'] SET AUTO_SHRINK OFF 

	ALTER DATABASE ['+@dbname+'] SET AUTO_UPDATE_STATISTICS ON 

	ALTER DATABASE ['+@dbname+'] SET CURSOR_CLOSE_ON_COMMIT OFF 

	ALTER DATABASE ['+@dbname+'] SET CURSOR_DEFAULT  GLOBAL 

	ALTER DATABASE ['+@dbname+'] SET CONCAT_NULL_YIELDS_NULL OFF 

	ALTER DATABASE ['+@dbname+'] SET NUMERIC_ROUNDABORT OFF 

	ALTER DATABASE ['+@dbname+'] SET QUOTED_IDENTIFIER OFF 

	ALTER DATABASE ['+@dbname+'] SET RECURSIVE_TRIGGERS OFF 

	ALTER DATABASE ['+@dbname+'] SET  DISABLE_BROKER 

	ALTER DATABASE ['+@dbname+'] SET AUTO_UPDATE_STATISTICS_ASYNC OFF 

	ALTER DATABASE ['+@dbname+'] SET DATE_CORRELATION_OPTIMIZATION OFF 

	ALTER DATABASE ['+@dbname+'] SET TRUSTWORTHY OFF 

	ALTER DATABASE ['+@dbname+'] SET ALLOW_SNAPSHOT_ISOLATION OFF 

	ALTER DATABASE ['+@dbname+'] SET PARAMETERIZATION SIMPLE 

	ALTER DATABASE ['+@dbname+'] SET READ_COMMITTED_SNAPSHOT OFF 

	ALTER DATABASE ['+@dbname+'] SET HONOR_BROKER_PRIORITY OFF 

	ALTER DATABASE ['+@dbname+'] SET RECOVERY FULL 

	ALTER DATABASE ['+@dbname+'] SET  MULTI_USER 

	ALTER DATABASE ['+@dbname+'] SET PAGE_VERIFY CHECKSUM  

	ALTER DATABASE ['+@dbname+'] SET DB_CHAINING OFF 

	ALTER DATABASE ['+@dbname+'] SET FILESTREAM( NON_TRANSACTED_ACCESS = OFF ) 

	ALTER DATABASE ['+@dbname+'] SET TARGET_RECOVERY_TIME = 60 SECONDS 

	ALTER DATABASE ['+@dbname+'] SET DELAYED_DURABILITY = DISABLED 

	ALTER DATABASE ['+@dbname+'] SET QUERY_STORE = OFF

	ALTER DATABASE ['+@dbname+'] SET  READ_WRITE ';

	--PRINT @dbSQL;
	EXECUTE sp_executesql @dbSQL;

END
