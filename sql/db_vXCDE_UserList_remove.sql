USE XCDEUI;
GO

DECLARE @dbName nvarchar(128);
DECLARE @dbViewName nvarchar(128);
DECLARE @dbSQL nvarchar(MAX);
DECLARE @dbResult nvarchar(20);
DECLARE @viewExists bit;
DECLARE @recordExists bit;
SET @dbName = N'XCDEUI';


--=================  Remove vXCDE_UserList View  ========================
SET @dbViewName = N'vXCDE_UserList';
SET @dbSQL = 'SELECT TOP 1 * FROM [dbo].['+@dbViewName+']';

SET @viewExists = 1;
BEGIN TRY  
    exec(@dbSQL);

END TRY  
BEGIN CATCH  
    SET @viewExists = 0;
END CATCH; 
SELECT @viewExists;

IF @viewExists = 1
BEGIN	
	SET @dbSQL = 'DROP VIEW [dbo].['+@dbViewName+']';
	exec(@dbSQL);
END

