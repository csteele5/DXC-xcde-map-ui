
DECLARE @dbName nvarchar(128);
DECLARE @dbTableName nvarchar(128);
DECLARE @dbSQL nvarchar(MAX);
DECLARE @dbResult nvarchar(20);
DECLARE @tableExists bit;
DECLARE @recordExists bit;
SET @dbName = N'XCDEUI';

--IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = N'UsersV2') BEGIN PRINT 'Yes' END ELSE BEGIN PRINT 'No' End

--=================  Add UsersV2 Table  ========================
SET @dbTableName = N'UsersV2';
SET @dbSQL = 'SELECT TOP 1 * FROM ['+@dbName+'].[dbo].['+@dbTableName+']';

SET @tableExists = 1;
BEGIN TRY  
    exec(@dbSQL);

END TRY  
BEGIN CATCH  
    SET @tableExists = 0;
END CATCH; 
--PRINT @tableExists;

if (@tableExists = 0) 
BEGIN

	SET @dbSQL = 'USE ['+@dbname+'];
				SET ANSI_NULLS ON
				SET QUOTED_IDENTIFIER ON

				CREATE TABLE [dbo].[UsersV2](
					[UserRightsID] [int] IDENTITY(1,1) NOT NULL,
					[UserID] [nvarchar](50) NULL,
					[EmployeeName] [nvarchar](150) NULL,
					[Phone] [nvarchar](50) NULL,
					[cellPhone] [nvarchar](50) NULL,
					[Address] [nvarchar](255) NULL,
					[City] [nvarchar](150) NULL,
					[StateProvID] [int] NOT NULL,
					[StateProv] [nvarchar](50) NULL,
					[CountryID] [int] NOT NULL,
					[Country] [nvarchar](50) NULL,
					[TZID] [int] NOT NULL,
					[MgrSname] [nvarchar](50) NULL,
					[Subk] [bit] NOT NULL,
					[UserAdmin] [bit] NOT NULL,
					[prefDateFormat] [nvarchar](50) NULL,
					[TWOSPWord] [nvarchar](50) NULL,
					[ResetRequired] [bit] NOT NULL,
					[pwdResetValue] [nvarchar](50) NULL,
					[nonDXCEmail] [nvarchar](150) NULL,
					[useNonDXCEmail] [bit] NOT NULL,
					[DisableCommRepeat] [bit] NOT NULL,
					[MgmtNotes] [nvarchar](max) NULL,
					[LastUpdated] [datetime] NULL,
					[UpdatedBy] [nvarchar](50) NULL,
					[Deleted] [bit] NOT NULL,
					[Terminated] [bit] NOT NULL,
					[AttritionDate] [datetime] NULL,
					[AttritionComments] [nvarchar](512) NULL,
					[DateCreated] [datetime] NULL,
				 CONSTRAINT [PK_UsersV2] PRIMARY KEY CLUSTERED 
				(
					[UserRightsID] ASC
				)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
				) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]

				ALTER TABLE [dbo].[UsersV2] ADD  CONSTRAINT [DF_UsersV2_StateProvID]  DEFAULT ((0)) FOR [StateProvID]

				ALTER TABLE [dbo].[UsersV2] ADD  CONSTRAINT [DF_UsersV2_CountryID]  DEFAULT ((0)) FOR [CountryID]

				ALTER TABLE [dbo].[UsersV2] ADD  CONSTRAINT [DF_UsersV2_TZID]  DEFAULT ((0)) FOR [TZID]

				ALTER TABLE [dbo].[UsersV2] ADD  CONSTRAINT [DF_UsersV2_Subk]  DEFAULT ((0)) FOR [Subk]

				ALTER TABLE [dbo].[UsersV2] ADD  CONSTRAINT [DF_UsersV2_UserAdmin]  DEFAULT ((0)) FOR [UserAdmin]

				ALTER TABLE [dbo].[UsersV2] ADD  CONSTRAINT [DF_UsersV2_TWOSPWord]  DEFAULT (N''P@ssw0rd'') FOR [TWOSPWord]

				ALTER TABLE [dbo].[UsersV2] ADD  CONSTRAINT [DF_UsersV2_ResetRequired]  DEFAULT ((0)) FOR [ResetRequired]

				ALTER TABLE [dbo].[UsersV2] ADD  CONSTRAINT [DF_UsersV2_useNonDXCEmail]  DEFAULT ((0)) FOR [useNonDXCEmail]

				ALTER TABLE [dbo].[UsersV2] ADD  CONSTRAINT [DF_UsersV2_DisableCommRepeat]  DEFAULT ((0)) FOR [DisableCommRepeat]

				ALTER TABLE [dbo].[UsersV2] ADD  CONSTRAINT [DF_UsersV2_Deleted]  DEFAULT ((0)) FOR [Deleted]

				ALTER TABLE [dbo].[UsersV2] ADD  CONSTRAINT [DF_UsersV2_Terminated]  DEFAULT ((0)) FOR [Terminated]

				ALTER TABLE [dbo].[UsersV2] ADD  CONSTRAINT [DF_UsersV2_DateCreated]  DEFAULT (getutcdate()) FOR [DateCreated] ';

	exec(@dbSQL);
END



--=================  Add Admin User Entry  ========================
SET @dbTableName = N'UsersV2';

--test query to see if csteele5 is present
SET @dbSQL = 'SELECT TOP 1 * FROM ['+@dbName+'].[dbo].['+@dbTableName+'] WHERE UserID = ''csteele5''';

exec(@dbSQL);
SET @recordExists = @@ROWCOUNT; 

if (@recordExists = 0) 
BEGIN

	SET @dbSQL = 'USE ['+@dbname+'];
				SET ANSI_NULLS ON
				SET QUOTED_IDENTIFIER ON

				INSERT INTO [dbo].[UsersV2]
                         (UserID, EmployeeName, TWOSPWord)
				VALUES        (N''csteele5'', N''Steele, Charles'', N''123456'') ';

	exec(@dbSQL);
END

--test query to see if edzul2 is present
SET @dbSQL = 'SELECT TOP 1 * FROM ['+@dbName+'].[dbo].['+@dbTableName+'] WHERE UserID = ''edzul2''';

exec(@dbSQL);
SET @recordExists = @@ROWCOUNT; 

if (@recordExists = 0) 
BEGIN

	SET @dbSQL = 'USE ['+@dbname+'];
				SET ANSI_NULLS ON
				SET QUOTED_IDENTIFIER ON

				INSERT INTO [dbo].[UsersV2]
                         (UserID, EmployeeName, TWOSPWord)
				VALUES        (N''edzul2'', N''Dzul, Esdras'', N''123456'') ';

	exec(@dbSQL);
END




--=================  Add XCDE_User table  ========================
SET @dbTableName = N'XCDE_User';

--test query to see if table is present
SET @dbSQL = 'SELECT TOP 1 * FROM ['+@dbName+'].[dbo].['+@dbTableName+']';

SET @tableExists = 1;
BEGIN TRY  
    exec(@dbSQL);

END TRY  
BEGIN CATCH  
    SET @tableExists = 0;
END CATCH; 
--PRINT @tableExists;

if (@tableExists = 0) 
BEGIN

	SET @dbSQL = 'USE ['+@dbname+'];
				SET ANSI_NULLS ON
				SET QUOTED_IDENTIFIER ON
				
				CREATE TABLE [dbo].[XCDE_User](
					[XCDEUserID] [int] IDENTITY(1,1) NOT NULL,
					[UserID] [nvarchar](50) NOT NULL,
					[UserRights] [bit] NOT NULL,
					[AdminRights] [bit] NOT NULL,
					[DateAdded] [datetime] NOT NULL,
					[AddedBy] [nvarchar](50) NOT NULL,
				 CONSTRAINT [PK_XCDE_User] PRIMARY KEY CLUSTERED 
				(
					[XCDEUserID] ASC
				)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
				) ON [PRIMARY]

				ALTER TABLE [dbo].[XCDE_User] ADD  CONSTRAINT [DF_XCDE_User_UserRights]  DEFAULT ((0)) FOR [UserRights]
				ALTER TABLE [dbo].[XCDE_User] ADD  CONSTRAINT [DF_XCDE_User_AdminRights]  DEFAULT ((0)) FOR [AdminRights]
				ALTER TABLE [dbo].[XCDE_User] ADD  CONSTRAINT [DF_XCDE_User_DateAdded]  DEFAULT (getutcdate()) FOR [DateAdded]
				ALTER TABLE [dbo].[XCDE_User] ADD  CONSTRAINT [DF_XCDE_User_AddedBy]  DEFAULT (N''system'') FOR [AddedBy]
				';

	exec(@dbSQL);
END


--=================  Add Admin User Entry  ========================
SET @dbTableName = N'XCDE_User';

--test query to see if csteele5 is present
SET @dbSQL = 'SELECT TOP 1 * FROM ['+@dbName+'].[dbo].['+@dbTableName+'] WHERE UserID = ''csteele5''';

exec(@dbSQL);
SET @recordExists = @@ROWCOUNT; 
--PRINT 'Record Exists '+CAST(@recordExists AS nvarchar(10));

if (@recordExists = 0) 
BEGIN

	SET @dbSQL = 'USE ['+@dbname+'];
				SET ANSI_NULLS ON
				SET QUOTED_IDENTIFIER ON

				INSERT INTO [dbo].[XCDE_User]
                         (UserID, AdminRights, AddedBy)
				VALUES        (N''csteele5'', 1, N''system'') ';

	exec(@dbSQL);
END

--test query to see if edzul2 is present
SET @dbSQL = 'SELECT TOP 1 * FROM ['+@dbName+'].[dbo].['+@dbTableName+'] WHERE UserID = ''edzul2''';

exec(@dbSQL);
SET @recordExists = @@ROWCOUNT; 
--PRINT 'Record Exists '+CAST(@recordExists AS nvarchar(10));

if (@recordExists = 0) 
BEGIN

	SET @dbSQL = 'USE ['+@dbname+'];
				SET ANSI_NULLS ON
				SET QUOTED_IDENTIFIER ON

				INSERT INTO [dbo].[XCDE_User]
                         (UserID, AdminRights, AddedBy)
				VALUES        (N''edzul2'', 1, N''system'') ';

	exec(@dbSQL);
END



--=================  Add Orga_Country table  ========================
SET @dbTableName = N'Orga_Country';

--test query to see if table is present
SET @dbSQL = 'SELECT TOP 1 * FROM ['+@dbName+'].[dbo].['+@dbTableName+']';

SET @tableExists = 1;
BEGIN TRY  
    exec(@dbSQL);

END TRY  
BEGIN CATCH  
    SET @tableExists = 0;
END CATCH; 
--PRINT @tableExists;

if (@tableExists = 0) 
BEGIN

	SET @dbSQL = 'USE ['+@dbname+'];
				SET ANSI_NULLS ON
				SET QUOTED_IDENTIFIER ON

				CREATE TABLE [dbo].[Orga_Country](
					[OrgCountryID] [int] IDENTITY(1,1) NOT NULL,
					[Country] [nvarchar](50) NULL,
					[Unvalidated] [tinyint] NULL,
					[Createdby] [nvarchar](50) NULL,
					[Deleted] [tinyint] NULL,
				 CONSTRAINT [PK_Orga_Country] PRIMARY KEY CLUSTERED 
				(
					[OrgCountryID] ASC
				)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
				) ON [PRIMARY] ';

	exec(@dbSQL);
END


--=================  Add Orga_StateProv table  ========================
SET @dbTableName = N'Orga_StateProv';

--test query to see if table is present
SET @dbSQL = 'SELECT TOP 1 * FROM ['+@dbName+'].[dbo].['+@dbTableName+']';

SET @tableExists = 1;
BEGIN TRY  
    exec(@dbSQL);

END TRY  
BEGIN CATCH  
    SET @tableExists = 0;
END CATCH; 
PRINT @tableExists;

if (@tableExists = 0) 
BEGIN

	SET @dbSQL = 'USE ['+@dbname+'];
				SET ANSI_NULLS ON
				SET QUOTED_IDENTIFIER ON

				CREATE TABLE [dbo].[Orga_StateProv](
					[OrgStateProvID] [int] IDENTITY(1,1) NOT NULL,
					[StateProv] [nvarchar](150) NULL,
					[Country] [nvarchar](150) NULL,
					[OrgCountryID] [int] NULL,
					[Unvalidated] [tinyint] NOT NULL,
					[Createdby] [nvarchar](50) NULL,
					[Deleted] [tinyint] NULL,
				 CONSTRAINT [PK_Orga_StateProv] PRIMARY KEY CLUSTERED 
				(
					[OrgStateProvID] ASC
				)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 90) ON [PRIMARY]
				) ON [PRIMARY] ';

	exec(@dbSQL);
END


--=================  Add Orga_StateProvLabel table  ========================
SET @dbTableName = N'Orga_StateProvLabel';

--test query to see if table is present
SET @dbSQL = 'SELECT TOP 1 * FROM ['+@dbName+'].[dbo].['+@dbTableName+']';

SET @tableExists = 1;
BEGIN TRY  
    exec(@dbSQL);

END TRY  
BEGIN CATCH  
    SET @tableExists = 0;
END CATCH; 
PRINT @tableExists;

if (@tableExists = 0) 
BEGIN

	SET @dbSQL = 'USE ['+@dbname+'];
				SET ANSI_NULLS ON
				SET QUOTED_IDENTIFIER ON

				CREATE TABLE [dbo].[Orga_StateProvLabel](
					[SPLabelID] [int] IDENTITY(1,1) NOT NULL,
					[SPLabel] [nvarchar](20) NOT NULL,
					[OrgCountryID] [int] NOT NULL,
				 CONSTRAINT [PK_Orga_StateProvLabel] PRIMARY KEY CLUSTERED 
				(
					[SPLabelID] ASC
				)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 90) ON [PRIMARY]
				) ON [PRIMARY]

				ALTER TABLE [dbo].[Orga_StateProvLabel] ADD  CONSTRAINT [DF_Orga_StateProvLabel_OrgCountryID]  DEFAULT ((0)) FOR [OrgCountryID]
				';

	exec(@dbSQL);
END


--=================  Add XCDE_DataMap_Status table  ========================
SET @dbTableName = N'XCDE_DataMap_Status';

--test query to see if table is present
SET @dbSQL = 'SELECT TOP 1 * FROM ['+@dbName+'].[dbo].['+@dbTableName+']';

SET @tableExists = 1;
BEGIN TRY  
    exec(@dbSQL);

END TRY  
BEGIN CATCH  
    SET @tableExists = 0;
END CATCH; 
PRINT @tableExists;

if (@tableExists = 0) 
BEGIN

	SET @dbSQL = 'USE ['+@dbname+'];
				SET ANSI_NULLS ON
				SET QUOTED_IDENTIFIER ON

				CREATE TABLE [dbo].[XCDE_DataMap_Status](
					[MapStatusID] [int] IDENTITY(1,1) NOT NULL,
					[MapStatus] [nvarchar](50) NOT NULL,
					[DisplayOrder] [int] NOT NULL,
					[Inactive] [bit] NOT NULL,
				 CONSTRAINT [PK_XCDE_DataMap_Status] PRIMARY KEY CLUSTERED 
				(
					[MapStatusID] ASC
				)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
				) ON [PRIMARY]

				ALTER TABLE [dbo].[XCDE_DataMap_Status] ADD  CONSTRAINT [DF_XCDE_DataMap_Status_DisplayOrder]  DEFAULT ((0)) FOR [DisplayOrder]

				ALTER TABLE [dbo].[XCDE_DataMap_Status] ADD  CONSTRAINT [DF_XCDE_DataMap_Status_Inactive]  DEFAULT ((0)) FOR [Inactive]

				';

	exec(@dbSQL);
END


--=================  Add SysBulletins table  ========================
SET @dbTableName = N'SysBulletins';

--test query to see if table is present
SET @dbSQL = 'SELECT TOP 1 * FROM ['+@dbName+'].[dbo].['+@dbTableName+']';

SET @tableExists = 1;
BEGIN TRY  
    exec(@dbSQL);

END TRY  
BEGIN CATCH  
    SET @tableExists = 0;
END CATCH; 
PRINT @tableExists;

if (@tableExists = 0) 
BEGIN

	SET @dbSQL = 'USE ['+@dbname+'];
				SET ANSI_NULLS ON
				SET QUOTED_IDENTIFIER ON

				CREATE TABLE [dbo].[SysBulletins](
					[BulletinID] [int] IDENTITY(1,1) NOT NULL,
					[PublishDate] [datetime] NULL,
					[SubjectLine] [nvarchar](150) NULL,
					[Message] [nvarchar](512) NULL,
					[ExpirationDate] [datetime] NULL,
					[DateCreated] [datetime] NOT NULL,
					[CreatedBy] [nvarchar](150) NOT NULL,
				 CONSTRAINT [PK_SysBulletins] PRIMARY KEY CLUSTERED 
				(
					[BulletinID] ASC
				)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 90) ON [PRIMARY]
				) ON [PRIMARY]

				ALTER TABLE [dbo].[SysBulletins] ADD  CONSTRAINT [DF_SysBulletins_DateCreated]  DEFAULT (getutcdate()) FOR [DateCreated]

				';

	exec(@dbSQL);
END

--=================  Add XCDE_User table  ========================
SET @dbTableName = N'XCDE_User';

--test query to see if table is present
SET @dbSQL = 'SELECT TOP 1 * FROM ['+@dbName+'].[dbo].['+@dbTableName+']';

SET @tableExists = 1;
BEGIN TRY  
    exec(@dbSQL);

END TRY  
BEGIN CATCH  
    SET @tableExists = 0;
END CATCH; 
PRINT @tableExists;

if (@tableExists = 0) 
BEGIN

	SET @dbSQL = 'USE ['+@dbname+'];
				SET ANSI_NULLS ON
				SET QUOTED_IDENTIFIER ON

				CREATE TABLE [dbo].[XCDE_User](
					[XCDEUserID] [int] IDENTITY(1,1) NOT NULL,
					[UserID] [nvarchar](50) NOT NULL,
					[UserRights] [bit] NOT NULL,
					[AdminRights] [bit] NOT NULL,
					[DateAdded] [datetime] NOT NULL,
					[AddedBy] [nvarchar](50) NOT NULL,
				 CONSTRAINT [PK_XCDE_User] PRIMARY KEY CLUSTERED 
				(
					[XCDEUserID] ASC
				)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
				) ON [PRIMARY]

				ALTER TABLE [dbo].[XCDE_User] ADD  CONSTRAINT [DF_XCDE_User_UserRights]  DEFAULT ((0)) FOR [UserRights]

				ALTER TABLE [dbo].[XCDE_User] ADD  CONSTRAINT [DF_XCDE_User_AdminRights]  DEFAULT ((0)) FOR [AdminRights]

				ALTER TABLE [dbo].[XCDE_User] ADD  CONSTRAINT [DF_XCDE_User_DateAdded]  DEFAULT (getutcdate()) FOR [DateAdded]

				ALTER TABLE [dbo].[XCDE_User] ADD  CONSTRAINT [DF_XCDE_User_AddedBy]  DEFAULT (N''system'') FOR [AddedBy]
				';

	exec(@dbSQL);
END