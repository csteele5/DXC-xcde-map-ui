DECLARE @dbName nvarchar(128);
DECLARE @dbTableName nvarchar(128);
DECLARE @dbSQL nvarchar(MAX);

SET @dbName = N'XCDEUI';


	SET @dbSQL = 'USE ['+@dbname+'];
				SET ANSI_NULLS ON
				SET QUOTED_IDENTIFIER ON

                SET IDENTITY_INSERT [dbo].[XCDE_DataMap_Status] ON

				INSERT INTO [dbo].[XCDE_DataMap_Status]
                         (MapStatusID, MapStatus, DisplayOrder, Inactive)
				VALUES       (1, ''Draft'', 10, 0)

                INSERT INTO [dbo].[XCDE_DataMap_Status]
                         (MapStatusID, MapStatus, DisplayOrder, Inactive)
				VALUES       (2, ''Approved for Deployment'', 40, 0)

                INSERT INTO [dbo].[XCDE_DataMap_Status]
                         (MapStatusID, MapStatus, DisplayOrder, Inactive)
				VALUES       (3, ''Retired'', 50, 0)

                INSERT INTO [dbo].[XCDE_DataMap_Status]
                         (MapStatusID, MapStatus, DisplayOrder, Inactive)
				VALUES       (4, ''Submitted For Review'', 20, 0)

                INSERT INTO [dbo].[XCDE_DataMap_Status]
                         (MapStatusID, MapStatus, DisplayOrder, Inactive)
				VALUES       (5, ''Under Review'', 30, 0)

                INSERT INTO [dbo].[XCDE_DataMap_Status]
                         (MapStatusID, MapStatus, DisplayOrder, Inactive)
				VALUES       (6, ''Cancelled'', 60, 0)

                SET IDENTITY_INSERT [dbo].[XCDE_DataMap_Status] OFF
                
                ';

	exec(@dbSQL);
