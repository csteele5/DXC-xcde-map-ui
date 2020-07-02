
<?php
/*-------------------------------------------------- 
|	 Name: Charles Steele                          |
|	 Payroll Number: cs13514                       |
|	 E-mail: csteele5@dxc.com                      |
|	 Phone: 310-321-8776                           |
| 	 Date Created: 9/18/18 					   |
---------------------------------------------------*/
/*-------------------------------------------------------------------------
	9/18/18  - xcde php functions that are not ajax
----------------------------------------------------------------------------*/



	function XCDE_MapConnectionGroupDelete($ConnectionGroupID, $inputconn) {
		$params = array($ConnectionGroupID);

		$query ="DELETE FROM     XCDE_DataMap_ConnectionGroup  ";		
		$query .= "	 WHERE 	   ConnectionGroupID = ? ";

		//echo $query.'<br>mappingConfigurationID='.$mappingConfigurationID; //exit;
		$delResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $delResult === false ) {
			 echo ' line 527 ';
			 die( print_r( sqlsrv_errors(), true));			
		}

		$query ="DELETE FROM     XCDE_DataMap_Connection  ";		
		$query .= "	 WHERE 	   ConnectionGroupID = ? ";

		//echo $query.'<br>mappingConfigurationID='.$mappingConfigurationID; //exit;
		$delResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $delResult === false ) {
			 echo ' line 527 ';
			 die( print_r( sqlsrv_errors(), true));			
		}

		//echo 'DONE'; exit;
	}


	function XCDE_MapConfigDelete($mappingConfigurationID, $inputconn) {
		$params = array($mappingConfigurationID);

		$query ="DELETE FROM     XCDE_DataMap_MappingConfiguration  ";		
		$query .= "	 WHERE 	   MappingConfigurationID = ? ";

		//echo $query.'<br>mappingConfigurationID='.$mappingConfigurationID; //exit;
		$delResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $delResult === false ) {
			 echo ' line 527 ';
			 die( print_r( sqlsrv_errors(), true));			
		}

		//echo 'DONE'; exit;
	}


	function XCDE_MapRelSchemaDelete($relationshipSchemaID, $inputconn) {
		$params = array($relationshipSchemaID);

		$query ="DELETE FROM     XCDE_DataMap_RelationshipPairs  ";		
		$query .= "	 WHERE 	   RelationshipSchemaID = ? ";

		//echo $query.'<br>RelationshipSchemaID='.$relationshipSchemaID; //exit;
		$delResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $delResult === false ) {
			 echo ' line 527 ';
			 die( print_r( sqlsrv_errors(), true));			
		}


		$query ="DELETE FROM     XCDE_DataMap_RelationshipSchema  ";		
		$query .= "	 WHERE 	   RelationshipSchemaID = ? ";

		//echo $query.'<br>RelationshipSchemaID='.$relationshipSchemaID; //exit;
		$delResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $delResult === false ) {
			 echo ' line 527 ';
			 die( print_r( sqlsrv_errors(), true));			
		}

		//echo 'DONE'; exit;
	}

	function XCDE_MapDelete($mapID, $inputconn) {

		$params = array($mapID);



		// for each segment, loop
		$query = "SELECT        MapSegmentID
					FROM            XCDE_DataMap_Segment
					WHERE        (MapID = ?)";


		$result = sqlsrv_query($inputconn,$query,$params);	
		/*	
		*/	
		if ($result === false ) {
			 die( print_r( sqlsrv_errors(), true));
		}

		try {	  
			$test = sqlsrv_has_rows($result);
						
			if ($test) {
				while($row = sqlsrv_fetch_array($result)) {
					$MapSegmentID = $row['MapSegmentID'];

					XCDE_MapSegmentDelete($MapSegmentID, $inputconn);

				}			
			} 								

		} catch (exception $e) {
			print_r($e);
		}

		sqlsrv_free_stmt($result);



		$query = "DELETE FROM XCDE_DataMap
					WHERE MapID = ?";
									
		$delResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $delResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		}

			 				
	}	


	function XCDE_MapSegmentDelete($mapSegmentID, $inputconn) {

		$params = array($mapSegmentID);

		// for each refObject, loop
		$query = "SELECT        RefObjectID
					FROM            XCDE_DataMap_RefObject
					WHERE        (MapSegmentID = ?)";


		$result = sqlsrv_query($inputconn,$query,$params);	
		/*	
		*/	
		if ($result === false ) {
			 die( print_r( sqlsrv_errors(), true));
		}

		try {	  
			$test = sqlsrv_has_rows($result);
						
			if ($test) {
				while($row = sqlsrv_fetch_array($result)) {
					$RefObjectID = $row['RefObjectID'];

					XCDE_RefObjectDelete($RefObjectID, $inputconn);

				}			
			} 								

		} catch (exception $e) {
			print_r($e);
		}

		sqlsrv_free_stmt($result);


		// for each attribute, loop
		$query = "SELECT        AttribMappingID
					FROM            XCDE_DataMap_Attribute_Mapping
					WHERE        (MapSegmentID = ?)";


		$result = sqlsrv_query($inputconn,$query,$params);	
		/*	
		*/	
		if ($result === false ) {
			 die( print_r( sqlsrv_errors(), true));
		}

		try {	  
			$test = sqlsrv_has_rows($result);
						
			if ($test) {
				while($row = sqlsrv_fetch_array($result)) {
					$AttribMappingID = $row['AttribMappingID'];

					XCDE_AttribMappingDelete($AttribMappingID, $inputconn);

				}			
			} 								

		} catch (exception $e) {
			print_r($e);
		}

		sqlsrv_free_stmt($result);



		// for each CI, loop
		$query = "SELECT        CIMappingID
					FROM            XCDE_DataMap_CI_Mapping
					WHERE        (MapSegmentID = ?)";


		$result = sqlsrv_query($inputconn,$query,$params);	
		/*	
		*/	
		if ($result === false ) {
			 die( print_r( sqlsrv_errors(), true));
		}

		try {	  
			$test = sqlsrv_has_rows($result);
						
			if ($test) {
				while($row = sqlsrv_fetch_array($result)) {
					$CIMappingID = $row['CIMappingID'];
					
					XCDE_CIMappingDelete($CIMappingID, $inputconn);

				}			
			} 								

		} catch (exception $e) {
			print_r($e);
		}

		sqlsrv_free_stmt($result);



		$query = "DELETE FROM XCDE_DataMap_DeploymentHistory
					FROM            XCDE_DataMap_DeploymentHistory 
					WHERE        (XCDE_DataMap_DeploymentHistory.MapSegmentID = ?)";
									
		$delResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $delResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		}


		$query = "DELETE FROM XCDE_DataMap_Segment
					WHERE MapSegmentID = ?";
									
		$delResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $delResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		}

			 				
	}	


	function XCDE_CIMappingDelete($CIMappingID, $inputconn) {

		$params = array($CIMappingID);


		$query = "DELETE FROM XCDE_DataMap_Attribute_Mapping_List
					FROM            XCDE_DataMap_Attribute_Mapping_List INNER JOIN
					                         XCDE_DataMap_CI_Mapping ON XCDE_DataMap_Attribute_Mapping_List.CIDerivationID = XCDE_DataMap_CI_Mapping.CIDerivationID
					WHERE        (XCDE_DataMap_CI_Mapping.CIMappingID = ?) AND (XCDE_DataMap_Attribute_Mapping_List.AttribMappingID = 0) AND (XCDE_DataMap_CI_Mapping.CIDerivationID > 0)";		
									
		$delResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $delResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		}


		$query = "DELETE FROM XCDE_DataMap_CI_Mapping_Derivation
					FROM            XCDE_DataMap_CI_Mapping_Derivation INNER JOIN
                         XCDE_DataMap_CI_Mapping ON XCDE_DataMap_CI_Mapping_Derivation.CIDerivationID = XCDE_DataMap_CI_Mapping.CIDerivationID
					WHERE        (XCDE_DataMap_CI_Mapping.CIMappingID = ?)";			
									
		$delResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $delResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		}

		$query = "DELETE FROM XCDE_DataMap_CI
					FROM            XCDE_DataMap_CI INNER JOIN
					                         XCDE_DataMap_CI_Mapping ON XCDE_DataMap_CI.CI_ID = XCDE_DataMap_CI_Mapping.Source_CI_ID
					WHERE        (XCDE_DataMap_CI_Mapping.CIMappingID = ?)";
									
		$delResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $delResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		}

		$query = "DELETE FROM XCDE_DataMap_CI
					FROM            XCDE_DataMap_CI INNER JOIN
					                         XCDE_DataMap_CI_Mapping ON XCDE_DataMap_CI.CI_ID = XCDE_DataMap_CI_Mapping.Target_CI_ID
					WHERE        (XCDE_DataMap_CI_Mapping.CIMappingID = ?)";
									
		$delResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $delResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		}


		$query = "DELETE FROM XCDE_DataMap_CI_Mapping
					WHERE        (CIMappingID = ?)";			
									
		$delResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $delResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		}
			 				
	}	



	function XCDE_AttribMappingDelete($AttribMappingID, $inputconn) {

		$params = array($AttribMappingID);

		$query = "DELETE FROM XCDE_DataMap_Attribute_Mapping_AttribValList
					FROM            XCDE_DataMap_Attribute_Mapping_AttribValList INNER JOIN
                         XCDE_DataMap_Attribute_Mapping ON XCDE_DataMap_Attribute_Mapping_AttribValList.AttribMappingID = XCDE_DataMap_Attribute_Mapping.AttribMappingID
					WHERE        (XCDE_DataMap_Attribute_Mapping.AttribMappingID = ?)";
									
		$delResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $delResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		}

		$params = array($AttribMappingID);

		$query = "DELETE FROM XCDE_DataMap_Attribute_Mapping_Derivation_Option
					FROM            XCDE_DataMap_Attribute_Mapping INNER JOIN
                         XCDE_DataMap_Attribute_Mapping_Derivation ON XCDE_DataMap_Attribute_Mapping.AttributeDerivationID = XCDE_DataMap_Attribute_Mapping_Derivation.AttributeDerivationID INNER JOIN
                         XCDE_DataMap_Attribute_Mapping_Derivation_Option ON 
                         XCDE_DataMap_Attribute_Mapping_Derivation.AttributeDerivationID = XCDE_DataMap_Attribute_Mapping_Derivation_Option.AttributeDerivationID
					WHERE        (XCDE_DataMap_Attribute_Mapping.AttribMappingID = ?)";
									
		$delResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $delResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		}

		$query = "DELETE FROM XCDE_DataMap_Attribute_Mapping_Derivation
					FROM            XCDE_DataMap_Attribute_Mapping INNER JOIN
                         XCDE_DataMap_Attribute_Mapping_Derivation ON XCDE_DataMap_Attribute_Mapping.AttributeDerivationID = XCDE_DataMap_Attribute_Mapping_Derivation.AttributeDerivationID
					WHERE        (XCDE_DataMap_Attribute_Mapping.AttribMappingID = ?)";
									
		$delResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $delResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		}


		$query = "DELETE FROM XCDE_DataMap_Attribute_Mapping_List
					WHERE        (AttribMappingID = ?)";
									
		$delResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $delResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		}


		$query = "DELETE FROM XCDE_DataMap_Attribute_Mapping_Concatenation
					WHERE        (AttribMappingID = ?)";
									
		$delResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $delResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		}


		$query = "DELETE FROM XCDE_DataMap_Attribute
					FROM            XCDE_DataMap_Attribute INNER JOIN
					                         XCDE_DataMap_Attribute_Mapping ON XCDE_DataMap_Attribute.Attrib_ID = XCDE_DataMap_Attribute_Mapping.Source_Attrib_ID
					WHERE        (XCDE_DataMap_Attribute_Mapping.AttribMappingID = ?)";
									
		$delResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $delResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		}

		
		$query = "DELETE FROM XCDE_DataMap_Attribute
					FROM            XCDE_DataMap_Attribute INNER JOIN
					                         XCDE_DataMap_Attribute_Mapping ON XCDE_DataMap_Attribute.Attrib_ID = XCDE_DataMap_Attribute_Mapping.Target_Attrib_ID
					WHERE        (XCDE_DataMap_Attribute_Mapping.AttribMappingID = ?)";							
		
		$delResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $delResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		}


		
		$query = "DELETE FROM XCDE_DataMap_Attribute_Mapping
					WHERE        (AttribMappingID = ?)";			
									
		$delResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $delResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		}


			 				
	}	


	function XCDE_RefObjectDelete($RefObjectID, $inputconn) {

		$params = array($RefObjectID);

		$query = "DELETE FROM XCDE_DataMap_RefObject_CI
					FROM            XCDE_DataMap_RefObject_CI INNER JOIN
					                         XCDE_DataMap_RefObject ON XCDE_DataMap_RefObject_CI.RefObjectID = XCDE_DataMap_RefObject.RefObjectID
					WHERE        (XCDE_DataMap_RefObject.RefObjectID = ?)";
									
		$delResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $delResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		}


		$query = "DELETE FROM XCDE_DataMap_RefObject_Attribute_Mapping
					FROM            XCDE_DataMap_RefObject_Attribute_Mapping INNER JOIN
					                         XCDE_DataMap_RefObject ON XCDE_DataMap_RefObject_Attribute_Mapping.RefObjectID = XCDE_DataMap_RefObject.RefObjectID
					WHERE        (XCDE_DataMap_RefObject.RefObjectID = ?)";
									
		$delResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $delResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		}


		$query = "DELETE FROM XCDE_DataMap_RefObject
					WHERE        (RefObjectID = ?)";
									
		$delResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $delResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		}


			 				
	}	


	function XCDE_CloneReferenceObject($RefObjectID, $newMapSegmentID, $inputconn) {
		$newRefObjectID = 0;

		$query = "INSERT INTO XCDE_DataMap_RefObject
                         (MapSegmentID, TriggeringAttributeName, MapMultipleAttributes, Required, TargetCIClassName, SingleTargetAttributeName, SingleTarget_ConstantValue, TargetFieldTypeID, UpdatedBy, LastUpdateDate, Inactive, 
                         Source_Attrib_Condition_Code, RequestorMappingNotes, ProgrammerMappingNotes)
					SELECT        ?, TriggeringAttributeName, MapMultipleAttributes, Required, TargetCIClassName, SingleTargetAttributeName, SingleTarget_ConstantValue, TargetFieldTypeID, UpdatedBy, LastUpdateDate, Inactive, 
					                         Source_Attrib_Condition_Code, RequestorMappingNotes, ProgrammerMappingNotes
					FROM            XCDE_DataMap_RefObject AS XCDE_DataMap_RefObject_1
					WHERE        (RefObjectID = ?) ";
		$query .= " SELECT SCOPE_IDENTITY() AS newRefObjectID";
		//echo $query.' '.$newMapSegmentID.' '.$RefObjectID;exit;
		$params = array($newMapSegmentID, $RefObjectID);


		$insResult = sqlsrv_query($inputconn, $query, $params); 
		if( $insResult === false ) {
			 die( print_r( sqlsrv_errors(), true));
		} else {
			$newRefObjectID = lastId($insResult);
		}


  		$query = "INSERT INTO XCDE_DataMap_RefObject_Attribute_Mapping
                         (Source_Attribute, Target_Attribute, Target_ConstantValue, ExistsInRef, RefObjectID)
					SELECT        Source_Attribute, Target_Attribute, Target_ConstantValue, ExistsInRef, ?
					FROM            XCDE_DataMap_RefObject_Attribute_Mapping AS XCDE_DataMap_RefObject_Attribute_Mapping_1
					WHERE        (RefObjectID = ?)";
		$params = array($newRefObjectID, $RefObjectID);


		//echo $query.' '.$newMapSegmentID.' '.$RefObjectID;exit;
		$insResult = sqlsrv_query($inputconn, $query, $params); 
		if( $insResult === false ) {
			 die( print_r( sqlsrv_errors(), true));
		} 


		$query = "INSERT INTO XCDE_DataMap_RefObject_CI
                         (RefObjectID, CIClassName)
					SELECT        ?, CIClassName
					FROM            XCDE_DataMap_RefObject_CI AS XCDE_DataMap_RefObject_CI_1
					WHERE        (RefObjectID = ?)";
		$params = array($newRefObjectID, $RefObjectID);


		$insResult = sqlsrv_query($inputconn, $query, $params); 
		if( $insResult === false ) {
			 die( print_r( sqlsrv_errors(), true));
		} 

        return $newRefObjectID;
			 				
	}	


	function XCDE_CloneAttributeMapping($AttribMappingID, $newMapSegmentID, $inputconn) {

		$Source_Attrib_ID = 0;
		$Target_Attrib_ID = 0;
		$MapTypeID = 0;
		$Source_Attrib_Condition_Code = '';
		$RequestorMappingNotes = '';
		$ProgrammerMappingNotes = '';
		$MapType = '';
		$SourceAttributeName = '';
		$SourceDisplayName = '';
		$SourceFieldType = '';
		$TargetAttributeName = '';
		$TargetDisplayName = '';
		$TargetFieldType = '';
		$TargetFieldFormat = '';
		$Required = 0;
		$Inactive = 0;
		//$CIMappingID = $row['CIMappingID'];
		$Source_ConstantValue = '';
		$MapSegmentID = 0;
		$AttributeDerivationID = 0;
		$MatchRelType = '';
		$RelatedCIIsParent = '';
		$AttributeScope = 0;
		$ElseSourceAttribute = '';
		$ReverseCondition = 0;
		$FalseConditionAction = 0;

		$params = array($AttribMappingID);

		$query = 'SELECT        XCDE_DataMap_Attribute_Mapping.Source_Attrib_ID, XCDE_DataMap_Attribute_Mapping.Target_Attrib_ID, XCDE_DataMap_Attribute_Mapping.MapTypeID, 
                         XCDE_DataMap_Attribute_Mapping.Source_Attrib_Condition_Code, XCDE_DataMap_Attribute_Mapping.RequestorMappingNotes, XCDE_DataMap_Attribute_Mapping.ProgrammerMappingNotes, 
                         XCDE_DataMap_MapType.MapType, SourceAttrib.AttributeName AS SourceAttributeName, SourceAttrib.DisplayName AS SourceDisplayName, SourceAttrib.FieldType AS SourceFieldType, 
                         TargetAttrib.AttributeName AS TargetAttributeName, TargetAttrib.DisplayName AS TargetDisplayName, TargetAttrib.FieldType AS TargetFieldType, TargetAttrib.Required, TargetAttrib.FieldFormat AS TargetFieldFormat, 
                         XCDE_DataMap_Attribute_Mapping.CIMappingID, XCDE_DataMap_Attribute_Mapping.AttribMappingID, XCDE_DataMap_Attribute_Mapping.Source_ConstantValue,
                         XCDE_DataMap_Attribute_Mapping.MapSegmentID, XCDE_DataMap_Attribute_Mapping.AttributeDerivationID,
                         XCDE_DataMap_Attribute_Mapping.MatchRelType, XCDE_DataMap_Attribute_Mapping.RelatedCIIsParent,
                         XCDE_DataMap_Attribute_Mapping.AttributeScope, XCDE_DataMap_Attribute_Mapping.ElseSourceAttribute, XCDE_DataMap_Attribute_Mapping.ReverseCondition,
                         XCDE_DataMap_Attribute_Mapping.FalseConditionAction, XCDE_DataMap_Attribute_Mapping.Inactive
				FROM            XCDE_DataMap_Attribute_Mapping LEFT OUTER JOIN
				                         XCDE_DataMap_Attribute AS TargetAttrib ON XCDE_DataMap_Attribute_Mapping.Target_Attrib_ID = TargetAttrib.Attrib_ID LEFT OUTER JOIN
				                         XCDE_DataMap_MapType ON XCDE_DataMap_Attribute_Mapping.MapTypeID = XCDE_DataMap_MapType.MapTypeID LEFT OUTER JOIN
				                         XCDE_DataMap_Attribute AS SourceAttrib ON XCDE_DataMap_Attribute_Mapping.Source_Attrib_ID = SourceAttrib.Attrib_ID
				WHERE        XCDE_DataMap_Attribute_Mapping.AttribMappingID = ?';

		//echo $query.'<br>AttribMappingID='.$AttribMappingID;exit;

		$result = sqlsrv_query($inputconn,$query,$params);	

	
		if ($result === false ) {
			 die( print_r( sqlsrv_errors(), true));
		}

		try {	  
			$test = sqlsrv_has_rows($result);
						
			if ($test) {
				while($row = sqlsrv_fetch_array($result)) {
					$Source_Attrib_ID = $row['Source_Attrib_ID'];
					$Target_Attrib_ID = $row['Target_Attrib_ID'];
					$MapTypeID = $row['MapTypeID'];
					$Source_Attrib_Condition_Code = $row['Source_Attrib_Condition_Code'];
					$RequestorMappingNotes = $row['RequestorMappingNotes'];
					$ProgrammerMappingNotes = $row['ProgrammerMappingNotes'];
					$MapType = $row['MapType'];
					$SourceAttributeName = $row['SourceAttributeName'];
					$SourceDisplayName = $row['SourceDisplayName'];
					$SourceFieldType = $row['SourceFieldType'];
					$TargetAttributeName = $row['TargetAttributeName'];
					$TargetDisplayName = $row['TargetDisplayName'];
					$TargetFieldType = $row['TargetFieldType'];
					$TargetFieldFormat = $row['TargetFieldFormat'];
					$Required = $row['Required'];
					//$CIMappingID = $row['CIMappingID'];
					$Source_ConstantValue = $row['Source_ConstantValue'];
					$MapSegmentID = $row['MapSegmentID'];
					$AttributeDerivationID = $row['AttributeDerivationID'];
					$MatchRelType = $row['MatchRelType'];
					$RelatedCIIsParent = $row['RelatedCIIsParent'];
					$AttributeScope = $row['AttributeScope'];
					$ElseSourceAttribute = $row['ElseSourceAttribute'];
					$ReverseCondition = $row['ReverseCondition'];
					$FalseConditionAction = $row['FalseConditionAction'];
					$Inactive = $row['Inactive'];
					 

				}			
			} 								

		} catch (exception $e) {
			print_r($e);
		}

		sqlsrv_free_stmt($result);

		// get new source and target attributeID
		$newSource_Attrib_ID = 0;
		$newTarget_Attrib_ID = 0;
		if ($Source_Attrib_ID > 0){
			$params = array($Source_Attrib_ID);
			$query = "INSERT INTO XCDE_DataMap_Attribute
                         (AttributeName, DisplayName, FieldType, Required, Inactive)
						SELECT        AttributeName, DisplayName, FieldType, Required, Inactive
						FROM            XCDE_DataMap_Attribute AS XCDE_DataMap_Attribute_1
						WHERE        (Attrib_ID = ?) ";
			$query .= "SELECT SCOPE_IDENTITY() AS newSource_Attrib_ID";

			$insResult = sqlsrv_query($inputconn, $query, $params); 	
			if( $insResult === false ) {
				 die( print_r( sqlsrv_errors(), true));			
			} else {
				$newSource_Attrib_ID = lastId($insResult);
			}	

			//echo $query.'<br>newSource_Attrib_ID='.$newSource_Attrib_ID;//exit;
		}
		if ($newSource_Attrib_ID =='') {
			$newSource_Attrib_ID = 0;
		}
		if ($Target_Attrib_ID > 0){
			$params = array($Target_Attrib_ID);
			$query = "INSERT INTO XCDE_DataMap_Attribute
                         (AttributeName, DisplayName, FieldType, FieldFormat, Required, Inactive)
						SELECT        AttributeName, DisplayName, FieldType, FieldFormat, Required, Inactive
						FROM            XCDE_DataMap_Attribute AS XCDE_DataMap_Attribute_1
						WHERE        (Attrib_ID = ?) ";
			$query .= "SELECT SCOPE_IDENTITY() AS newTarget_Attrib_ID";

			$insResult = sqlsrv_query($inputconn, $query, $params); 	
			if( $insResult === false ) {
				 die( print_r( sqlsrv_errors(), true));			
			} else {
				$newTarget_Attrib_ID = lastId($insResult);
			}	

			//echo $query.'<br>newTarget_Attrib_ID='.$newTarget_Attrib_ID;//exit;
			//echo '<br>Target_Attrib_ID='.$Target_Attrib_ID;//exit;
		}
		if ($newTarget_Attrib_ID =='') {
			$newTarget_Attrib_ID = 0;
		}

		// create base attribute
		$newAttribMappingID = 0;
			//$_SESSION['userID'], 
		$params = array($newSource_Attrib_ID, $newTarget_Attrib_ID, $newMapSegmentID, $AttribMappingID);
		$query = "INSERT INTO XCDE_DataMap_Attribute_Mapping
                         (Source_Attrib_ID, Target_Attrib_ID, MapTypeID, Source_Attrib_Condition_Code, RequestorMappingNotes, ProgrammerMappingNotes, Source_ConstantValue, MapSegmentID, MatchRelType, 
                         RelatedCIIsParent, AttributeScope, ElseSourceAttribute, ReverseCondition, FalseConditionAction, Inactive)
					SELECT    ?, ?, MapTypeID, Source_Attrib_Condition_Code, RequestorMappingNotes, ProgrammerMappingNotes, Source_ConstantValue, ?, MatchRelType, RelatedCIIsParent, AttributeScope, ElseSourceAttribute, ReverseCondition, FalseConditionAction, Inactive
					FROM            XCDE_DataMap_Attribute_Mapping AS XCDE_DataMap_Attribute_Mapping_1
					WHERE     (AttribMappingID = ?) ";
		$query .= "SELECT SCOPE_IDENTITY() AS newAttribMappingID";

		//echo $query.'<br>AttribMappingID='.$AttribMappingID;//exit;

		$insResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $insResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		} else {
			$newAttribMappingID = lastId($insResult);
		}	

		//echo $query.'<br>newAttribMappingID='.$newAttribMappingID;//exit;

 
		$params = array($newAttribMappingID, $AttribMappingID);
		$query = "INSERT INTO XCDE_DataMap_Attribute_Mapping_Concatenation
                         (AttribMappingID, Constant, ConstantValue, RelatedClass, RelatedClassName, AttributeName, ConcatOrder)
					SELECT        ?, Constant, ConstantValue, RelatedClass, RelatedClassName, AttributeName, ConcatOrder
					FROM            XCDE_DataMap_Attribute_Mapping_Concatenation AS XCDE_DataMap_Attribute_Mapping_Concatenation_1
					WHERE     (AttribMappingID = ?) ";

		//echo $query.'<br>AttribMappingID='.$AttribMappingID;//exit;

		$insResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $insResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		}

 
		$params = array($newAttribMappingID, $AttribMappingID);
		$query = "INSERT INTO XCDE_DataMap_Attribute_Mapping_List
                         (AttribMappingID, SourceValue, TargetValue)
					SELECT        ?, SourceValue, TargetValue
					FROM            XCDE_DataMap_Attribute_Mapping_List
					WHERE        (AttribMappingID = ?) AND (AttribMappingID <> 0) ";

		//echo $query.'<br>AttribMappingID='.$AttribMappingID;//exit;

		$insResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $insResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		}
		
 
		$params = array($newAttribMappingID, $AttribMappingID);
		$query = "INSERT INTO XCDE_DataMap_Attribute_Mapping_AttribValList
                         (AttribMappingID, SourceValue)
					SELECT        ?, SourceValue
				FROM            XCDE_DataMap_Attribute_Mapping_AttribValList
				WHERE        (AttribMappingID = ?) AND (AttribMappingID <> 0) ";

		//echo $query.'<br>AttribMappingID='.$AttribMappingID;//exit;

		$insResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $insResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		}
		
 
		$params = array($newAttribMappingID, $AttribMappingID);
		$query = "INSERT INTO XCDE_DataMap_Attribute_Mapping_Calculation
                         (AttribMappingID, CalculationTypeID, SumTypeClass, SumTypeAttribute)
					SELECT        ?, CalculationTypeID, SumTypeClass, SumTypeAttribute
				FROM            XCDE_DataMap_Attribute_Mapping_Calculation
				WHERE        (AttribMappingID = ?) AND (AttribMappingID <> 0) ";

		//echo $query.'<br>AttribMappingID='.$AttribMappingID;//exit;

		$insResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $insResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		}
		
 
		$params = array($newAttribMappingID, $AttribMappingID);
		$query = "INSERT INTO XCDE_DataMap_Attribute_Mapping_CI
                         (AttribMappingID, CIClassName, Source0Target1, Calculated)
					SELECT        ?, CIClassName, Source0Target1, Calculated
				FROM            XCDE_DataMap_Attribute_Mapping_CI
				WHERE        (AttribMappingID = ?) AND (AttribMappingID <> 0) ";

		//echo $query.'<br>AttribMappingID='.$AttribMappingID;//exit;

		$insResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $insResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		}

		
		if ($AttributeDerivationID > 0) {
			// add derivation record
			$newAttributeDerivationID = 0;
			$params = array($AttributeDerivationID);
			$query = "INSERT INTO XCDE_DataMap_Attribute_Mapping_Derivation
		                     (Map_MapType, IdAttribute)
						SELECT        Map_MapType, IdAttribute
						FROM            XCDE_DataMap_Attribute_Mapping_Derivation AS XCDE_DataMap_Attribute_Mapping_Derivation_1
						WHERE        (AttributeDerivationID = ?) ";
			$query .= "SELECT SCOPE_IDENTITY() AS newAttributeDerivationID";

			//echo $query.'<br>AttributeDerivationID='.$AttributeDerivationID;//exit;

			$insResult = sqlsrv_query($inputconn, $query, $params); 	
			if( $insResult === false ) {
				 die( print_r( sqlsrv_errors(), true));			
			} else {
				$newAttributeDerivationID = lastId($insResult);
			}	

			//add options
			$params = array($newAttributeDerivationID, $AttributeDerivationID);
			$query = "INSERT INTO XCDE_DataMap_Attribute_Mapping_Derivation_Option
		                         (Attribute, Condition, AttributeDerivationID)
						SELECT        Attribute, Condition, ?
						FROM            XCDE_DataMap_Attribute_Mapping_Derivation_Option AS XCDE_DataMap_Attribute_Mapping_Derivation_Option_1
						WHERE        (AttributeDerivationID = ?) ";

			//echo $query.'<br>AttributeDerivationID='.$AttributeDerivationID;//exit;

			$insResult = sqlsrv_query($inputconn, $query, $params); 	
			if( $insResult === false ) {
				 die( print_r( sqlsrv_errors(), true));			
			}
				
			//update overall attribute with new ID
			$params = array($newAttributeDerivationID, $currentUser, $newAttribMappingID);
			$query = "UPDATE       XCDE_DataMap_Attribute_Mapping
						SET          AttributeDerivationID = ?,
							      LastUpdateDate = getutcdate(), UpdatedBy = ?
						WHERE        (AttribMappingID = ?)";


			$updResult = sqlsrv_query($inputconn, $query, $params );	
			if( $updResult === false ) {
				 die( print_r( sqlsrv_errors(), true));			
			}		

		}	


        //echo 'In function test<br>AttribMappingID '.$newAttribMappingID;
        return $newAttribMappingID;
			 				
	}	


	function XCDE_CloneCIMapping($CIMappingID, $newMapSegmentID, $inputconn) {

		$currentUser = $_SESSION['userID'];
		$newCIMappingID = 0;
		$newSource_CI_ID = 0;
		$params = array($CIMappingID);
		$query = "INSERT INTO XCDE_DataMap_CI
                         (ClassName, DisplayName, MapSystemID, FQName, ParentClassName, Inactive)
					SELECT        XCDE_DataMap_CI_1.ClassName, XCDE_DataMap_CI_1.DisplayName, 
					                         XCDE_DataMap_CI_1.MapSystemID, XCDE_DataMap_CI_1.FQName, XCDE_DataMap_CI_1.ParentClassName, XCDE_DataMap_CI_1.Inactive
					FROM            XCDE_DataMap_CI_Mapping INNER JOIN
					                         XCDE_DataMap_CI AS XCDE_DataMap_CI_1 ON XCDE_DataMap_CI_Mapping.Source_CI_ID = XCDE_DataMap_CI_1.CI_ID
					WHERE        (XCDE_DataMap_CI_Mapping.CIMappingID = ?) ";
		$query .= "SELECT SCOPE_IDENTITY() AS newSource_CI_ID";

		//echo $query.'<br>newSource_CI_ID='.$newSource_CI_ID;//exit;

		$insResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $insResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		} else {
			$newSource_CI_ID = lastId($insResult);
		}	

		if ($newSource_CI_ID == '') {
			$newSource_CI_ID = 0;
		}


		$newTarget_CI_ID = 0;
		$params = array($CIMappingID);
		$query = "INSERT INTO XCDE_DataMap_CI
                         (ClassName, DisplayName, MapSystemID, FQName, ParentClassName, Inactive)
					SELECT        XCDE_DataMap_CI_1.ClassName, XCDE_DataMap_CI_1.DisplayName, 
					                         XCDE_DataMap_CI_1.MapSystemID, XCDE_DataMap_CI_1.FQName, XCDE_DataMap_CI_1.ParentClassName, XCDE_DataMap_CI_1.Inactive
					FROM            XCDE_DataMap_CI_Mapping INNER JOIN
					                         XCDE_DataMap_CI AS XCDE_DataMap_CI_1 ON XCDE_DataMap_CI_Mapping.Target_CI_ID = XCDE_DataMap_CI_1.CI_ID
					WHERE        (XCDE_DataMap_CI_Mapping.CIMappingID = ?) ";
		$query .= "SELECT SCOPE_IDENTITY() AS newTarget_CI_ID";

		//echo $query.'<br>newTarget_CI_ID='.$newTarget_CI_ID;//exit;

		$insResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $insResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		} else {
			$newTarget_CI_ID = lastId($insResult);
		}	

		if ($newTarget_CI_ID == '') {
			$newTarget_CI_ID = 0;
		}


		$newCIDerivationID = 0;
		$params = array($CIMappingID);
		$query = "INSERT INTO XCDE_DataMap_CI_Mapping_Derivation
                         (MapTypeID, IdAttribute, IdAttributeFieldTypeID)
					SELECT        XCDE_DataMap_CI_Mapping_Derivation_1.MapTypeID, XCDE_DataMap_CI_Mapping_Derivation_1.IdAttribute, XCDE_DataMap_CI_Mapping_Derivation_1.IdAttributeFieldTypeID
					FROM            XCDE_DataMap_CI_Mapping_Derivation AS XCDE_DataMap_CI_Mapping_Derivation_1 INNER JOIN
					                         XCDE_DataMap_CI_Mapping ON XCDE_DataMap_CI_Mapping_Derivation_1.CIDerivationID = XCDE_DataMap_CI_Mapping.CIDerivationID
					WHERE        (XCDE_DataMap_CI_Mapping.CIMappingID = ?) ";
		$query .= "SELECT SCOPE_IDENTITY() AS newCIDerivationID";


		$insResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $insResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		} else {
			$newCIDerivationID = lastId($insResult);
		}	

		if ($newCIDerivationID == '') {
			$newCIDerivationID = 0;
		}


		$newCIMappingID = 0;
		$params = array($newCIDerivationID, $newMapSegmentID, $newSource_CI_ID, $newTarget_CI_ID, $currentUser, $CIMappingID);
		$query = "INSERT INTO XCDE_DataMap_CI_Mapping
                         (CIDerivationID, MapID, MapSegmentID, Source_CI_ID, Target_CI_ID, Source_CI_Condition_Code, RequestorMappingNotes, ProgrammerMappingNotes, UpdatedBy, LastUpdateDate, Inactive)
					SELECT    ?, MapID, ?, ?, ?, Source_CI_Condition_Code, RequestorMappingNotes, ProgrammerMappingNotes, ?, GetUTCDate(), Inactive
					FROM            XCDE_DataMap_CI_Mapping AS XCDE_DataMap_CI_Mapping_1
					WHERE        (CIMappingID = ?) ";
		$query .= "SELECT SCOPE_IDENTITY() AS newCIMappingID";

		//echo $query.'<br>newCIMappingID='.$newCIMappingID;//exit;

		$insResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $insResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		} else {
			$newCIMappingID = lastId($insResult);
		}	

		return $newCIMappingID;

	}		


	function XCDE_CloneSegment($MapSegmentID, $newMapID, $includeRelationships, $includeConfiguration, $inputconn) {

		$currentUser = $_SESSION['userID'];
		$newMapSegmentID = 0;
		$MappingConfigurationID = 0;
		$newMappingConfigurationID = 0;
		$RelationshipSchemaID = 0;
		$newRelationshipSchemaID = 0;
		//echo 'TEST in function<br>';
		// get config and relationships IDs
		$params = array($MapSegmentID);
		$query = "SELECT        MappingConfigurationID, RelationshipSchemaID
					FROM            XCDE_DataMap_Segment
					WHERE        (MapSegmentID = ?)";


		$result = sqlsrv_query($inputconn,$query,$params);	
			
		if ($result === false ) {
			 die( print_r( sqlsrv_errors(), true));
		}

		try {	  
			$test = sqlsrv_has_rows($result);
						
			if ($test) {
				while($row = sqlsrv_fetch_array($result)) {
					$MappingConfigurationID = $row['MappingConfigurationID'];
					$RelationshipSchemaID = $row['RelationshipSchemaID'];
				}			
			} 								

		} catch (exception $e) {
			print_r($e);
		}

		sqlsrv_free_stmt($result);

		// clone map parent
		$params = array($newMapID, $currentUser, $currentUser, $MapSegmentID);
		$query = "INSERT INTO XCDE_DataMap_Segment
                         (Description, MapID, Source_MapSystemID, Target_MapSystemID, MappingConfigurationID, RelationshipSchemaID, ProcessingOrder, Version, UseCase, DateCreated, CreatedBy, LastUpdateDate, UpdatedBy)
					SELECT        '[Cloned] '+ISNULL(Description,''), ?, Source_MapSystemID, Target_MapSystemID, MappingConfigurationID, RelationshipSchemaID, ProcessingOrder, Version, UseCase, DateCreated, ?, GetUTCDate(), ?
					FROM            XCDE_DataMap_Segment AS XCDE_DataMap_Segment_1
					WHERE        (MapSegmentID = ?)";
		$query .= "SELECT SCOPE_IDENTITY() AS newMapSegmentID";

		$insResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $insResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		} else {
			$newMapSegmentID = lastId($insResult);
		}

		//echo $query.'<br>newMapSegmentID='.$newMapSegmentID;//exit;

		// clone configuration
		if ($MappingConfigurationID > 0 && $includeConfiguration > 0) {
			
			$newMappingConfigurationID = XCDE_CloneMapConfiguration($MappingConfigurationID, $inputconn); 

		}

		// clone relationships
		if ($RelationshipSchemaID > 0 && $includeRelationships > 0) {
			
			$newRelationshipSchemaID = XCDE_CloneRelationshipSchema($RelationshipSchemaID, $inputconn);

		}


		$params = array($newMappingConfigurationID, $newRelationshipSchemaID, $newMapSegmentID);
		$query = "UPDATE       XCDE_DataMap_Segment
					SET          MappingConfigurationID = ?,
								RelationshipSchemaID = ?
					WHERE        (MapSegmentID = ?)";


		$updResult = sqlsrv_query($inputconn, $query, $params );	
		if( $updResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		}

		//clone CIs
		$params = array($MapSegmentID);
		$query = "SELECT        CIMappingID
					FROM            XCDE_DataMap_CI_Mapping
					WHERE        (MapSegmentID = ?)";


		$result = sqlsrv_query($inputconn,$query,$params);	
			
		if ($result === false ) {
			 die( print_r( sqlsrv_errors(), true));
		}

		try {	  
			$test = sqlsrv_has_rows($result);
						
			if ($test) {
				while($row = sqlsrv_fetch_array($result)) {
					$CIMappingID = $row['CIMappingID'];
					XCDE_CloneCIMapping($CIMappingID, $newMapSegmentID, $inputconn);

				}			
			} 								

		} catch (exception $e) {
			print_r($e);
		}

		sqlsrv_free_stmt($result);


		//clone attributes
		$params = array($MapSegmentID);
		$query = "SELECT        AttribMappingID
					FROM            XCDE_DataMap_Attribute_Mapping
					WHERE        (MapSegmentID = ?)";


		$result = sqlsrv_query($inputconn,$query,$params);	
			
		if ($result === false ) {
			 die( print_r( sqlsrv_errors(), true));
		}

		try {	  
			$test = sqlsrv_has_rows($result);
						
			if ($test) {
				while($row = sqlsrv_fetch_array($result)) {
					$AttribMappingID = $row['AttribMappingID'];
					XCDE_CloneAttributeMapping($AttribMappingID, $newMapSegmentID, $inputconn);

				}			
			} 								

		} catch (exception $e) {
			print_r($e);
		}

		sqlsrv_free_stmt($result);


		//clone reference objects
		$params = array($MapSegmentID);
		$query = "SELECT        RefObjectID
					FROM            XCDE_DataMap_RefObject
					WHERE        (MapSegmentID = ?)";


		$result = sqlsrv_query($inputconn,$query,$params);	
			
		if ($result === false ) {
			 die( print_r( sqlsrv_errors(), true));
		}

		try {	  
			$test = sqlsrv_has_rows($result);
						
			if ($test) {
				while($row = sqlsrv_fetch_array($result)) {
					$RefObjectID = $row['RefObjectID'];

					XCDE_CloneReferenceObject($RefObjectID, $newMapSegmentID, $inputconn);

				}			
			} 								

		} catch (exception $e) {
			print_r($e);
		}

		sqlsrv_free_stmt($result);



		return $newMapSegmentID;

	}


	function XCDE_CloneMapSet($MapID, $includeRelationships, $includeConfiguration, $inputconn) {

		$currentUser = $_SESSION['userID'];
		$newMapID = 0;

		$params = array($currentUser, $currentUser, $MapID);
		$query = "INSERT INTO XCDE_DataMap
                         (MapName, MapDescription, MapStatusID, Start_MapSystemID, End_MapSystemID, 
                         	LastUpdateDate, CreatedBy, UpdatedBy)
					SELECT     RTRIM(LEFT(ISNULL(MapName, '') + ' [cloned]',50)) AS MapName,  MapDescription, 1, Start_MapSystemID, 	End_MapSystemID, GETUTCDate(), ?, ?
					FROM            XCDE_DataMap AS XCDE_DataMap_1
					WHERE        (MapID = ?)";
		$query .= "SELECT SCOPE_IDENTITY() AS newMapID";

		$insResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $insResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		} else {
			$newMapID = lastId($insResult);
		}

		if ($newMapID == '') {
			$newMapID = 0;
		}


		//clone individual segments
		$params = array($MapID);
		$query = "SELECT        MapSegmentID
					FROM            XCDE_DataMap_Segment
					WHERE        (MapID = ?)";

		$result = sqlsrv_query($inputconn,$query,$params);	
			
		if ($result === false ) {
			 die( print_r( sqlsrv_errors(), true));
		}

		try {	  
			$test = sqlsrv_has_rows($result);
						
			if ($test) {
				while($row = sqlsrv_fetch_array($result)) {
					$MapSegmentID = $row['MapSegmentID'];
					
					XCDE_CloneSegment($MapSegmentID, $newMapID, $includeRelationships, $includeConfiguration, $inputconn);

				}			
			} 								

		} catch (exception $e) {
			print_r($e);
		}

		sqlsrv_free_stmt($result);


		return $newMapID;

	}


	function XCDE_CloneMapConnectionGroup($ConnectionGroupID, $inputconn) {
		$newConnectionGroupID = 0;
		$currentUser = $_SESSION['userID'];

		$params = array($ConnectionGroupID);
		$query = "INSERT INTO XCDE_DataMap_ConnectionGroup
                         (GroupName, DisplayOrder)
					SELECT        RTRIM(LEFT(ISNULL(GroupName, '') + ' [cloned]',50)) AS GroupName, DisplayOrder+2
					FROM            XCDE_DataMap_ConnectionGroup AS XCDE_DataMap_ConnectionGroup_1
					WHERE        (ConnectionGroupID = ?)";
		$query .= "SELECT SCOPE_IDENTITY() AS newConnectionGroupID";

		$insResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $insResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		} else {
			$newConnectionGroupID = lastId($insResult);
		}

		if ($newConnectionGroupID == '') {
			$newConnectionGroupID = 0;
		}

		$params = array($newConnectionGroupID, $ConnectionGroupID);
		$query = "INSERT INTO XCDE_DataMap_Connection
                         (ConnectionGroupID, ConnectionName, EnvironmentID, Header_json, Auth_Header, Auth_Type, BasePath, relsPath, Host, Method, Port, Pwd, Type, ConnectionUser, relsHeader_json, relsHost, relsMethod, relsPort, relsPwd, 
                         relsType, relsConnectionUser, ComplexRelsConnection, isAsync)
					SELECT        ?, ConnectionName, EnvironmentID, Header_json, Auth_Header, Auth_Type, BasePath, relsPath, Host, Method, Port, Pwd, Type, ConnectionUser, relsHeader_json, relsHost, relsMethod, relsPort, relsPwd, 
					                         relsType, relsConnectionUser, ComplexRelsConnection, isAsync
					FROM            XCDE_DataMap_Connection AS XCDE_DataMap_Connection_1
					WHERE        (ConnectionGroupID = ?)";

		$insResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $insResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		} 

		return $newConnectionGroupID;

	}

	function XCDE_CloneMapConfiguration($MappingConfigurationID, $inputconn) {
		$newMappingConfigurationID = 0;
		$ConnectionGroupID = 0;
		$newConnectionGroupID = 0;

		$params = array($MappingConfigurationID);
		$query = "SELECT 	ConnectionGroupID
					FROM 	XCDE_DataMap_MappingConfiguration
					WHERE 	MappingConfigurationID = ?";


		$result = sqlsrv_query($inputconn,$query,$params);	
			
		if ($result === false ) {
			 die( print_r( sqlsrv_errors(), true));
		}

		try {	  
			$test = sqlsrv_has_rows($result);
						
			if ($test) {
				while($row = sqlsrv_fetch_array($result)) {
					$ConnectionGroupID = $row['ConnectionGroupID'];
				}			
			} 								

		} catch (exception $e) {
			print_r($e);
		}

		sqlsrv_free_stmt($result);

		if ($ConnectionGroupID > 0) {
			$newConnectionGroupID =  XCDE_CloneMapConnectionGroup($ConnectionGroupID, $inputconn);
		}


		$params = array($newConnectionGroupID, $MappingConfigurationID);
		$query = "INSERT INTO XCDE_DataMap_MappingConfiguration
                         (ConfigurationName, Details_sourceItemIdAttribute, Details_sourceItemNameAttribute, Details_sourceItemTypeAttribute, Solution, Source, DataStor, DefaultSourceInclude, DefaultSourceAttribute, Target_SysName, 
                         TargetItemIdAttribute, TargetItemNameAttribute, TargetItemTypeAttribute, Include_idAtRoot, Include_nameAtRoot, Include_otherAtRoot, MaxAttempts, RetryDelay, ConnectionGroupID, DisplayOrder, UseSeparatePath, 
                         RefObjectsEndpoint, RefObjectsTargetClassAttribute)
					SELECT        RTRIM(LEFT(ISNULL(ConfigurationName, '') + ' [cloned]', 50)) AS ConfigurationName, Details_sourceItemIdAttribute, Details_sourceItemNameAttribute, Details_sourceItemTypeAttribute, Solution, Source, DataStor, 
                         DefaultSourceInclude, DefaultSourceAttribute, Target_SysName, TargetItemIdAttribute, TargetItemNameAttribute, TargetItemTypeAttribute, Include_idAtRoot, Include_nameAtRoot, Include_otherAtRoot, MaxAttempts, RetryDelay, 
                         @Param1 AS Expr1, DisplayOrder, UseSeparatePath, RefObjectsEndpoint, RefObjectsTargetClassAttribute
					FROM            XCDE_DataMap_MappingConfiguration AS XCDE_DataMap_MappingConfiguration_1
					WHERE        (MappingConfigurationID = ?)";
		$query .= "SELECT SCOPE_IDENTITY() AS newMappingConfigurationID";

		$insResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $insResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		} else {
			$newMappingConfigurationID = lastId($insResult);
		}

		if ($newMappingConfigurationID == '') {
			$newMappingConfigurationID = 0;
		}
		

		return $newMappingConfigurationID;

	}



	function resetSchemaOrder($inputconn) {
		// this is run after a record has been saved
		
		//echo $updMapSystemID.' '.$inputconn; exit;
				  //WHERE Inactive = 0
		
		$query = "SELECT RelationshipSchemaID
				  FROM XCDE_DataMap_RelationshipSchema
				  ORDER BY DisplayOrder";
		//, $params
		$result = sqlsrv_query($inputconn, $query); 	
		if( $result === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		}	

		$newDisplayOrder = 10;
		try {	  
			$test = sqlsrv_has_rows($result);
						
			if ($test) {
				while($row = sqlsrv_fetch_array($result)) {

					$thisRelationshipSchemaID = $row['RelationshipSchemaID'];

					$query = "UPDATE   XCDE_DataMap_RelationshipSchema
								SET   DisplayOrder = ? ";
					$query .= "	WHERE  RelationshipSchemaID = ?";
					//echo $query;exit; 

					$params = array($newDisplayOrder, $thisRelationshipSchemaID);
					$updResult = sqlsrv_query($inputconn, $query, $params); 	
					if( $updResult === false ) {
						 die( print_r( sqlsrv_errors(), true));			
					}	        

			        $newDisplayOrder += 10;

				}			
			} 								

		} catch (exception $e) {
			print_r($e);
		}

		sqlsrv_free_stmt($result);


	}


	function XCDE_CloneRelationshipSchema($RelationshipSchemaID, $inputconn) {

		$currentUser = $_SESSION['userID'];


		$params = array($RelationshipSchemaID);
		$query = "INSERT INTO XCDE_DataMap_RelationshipSchema
                     (RelationshipSchemaName, Source_MapSystemID, Target_MapSystemID, DisplayOrder)
					SELECT     RTRIM(LEFT(ISNULL(RelationshipSchemaName, '') + ' [cloned]',50)) AS RelationshipSchemaName, Source_MapSystemID, Target_MapSystemID, DisplayOrder
					FROM            XCDE_DataMap_RelationshipSchema AS XCDE_DataMap_RelationshipSchema_1
					WHERE        (RelationshipSchemaID = ?)";
		$query .= "SELECT SCOPE_IDENTITY() AS newRelationshipSchemaID";

		$insResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $insResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		} else {
			$newRelationshipSchemaID = lastId($insResult);
		}

		if ($newRelationshipSchemaID == '') {
			$newRelationshipSchemaID = 0;
		}


		$params = array($newRelationshipSchemaID, $currentUser, $RelationshipSchemaID);
		$query = "INSERT INTO XCDE_DataMap_RelationshipPairs
                     (RelationshipSchemaID, SourceParentCI, SourceRelationship, SourceChildCI, TargetParentCI, TargetRelationship, TargetChildCI, UseSeparatePath, Comments, UpdatedBy, LastUpdateDate, Inactive)
					SELECT        ?, SourceParentCI, SourceRelationship, SourceChildCI, TargetParentCI, TargetRelationship, TargetChildCI, UseSeparatePath, Comments, ?, GetUTCDate(), Inactive
					FROM            XCDE_DataMap_RelationshipPairs AS XCDE_DataMap_RelationshipPairs_1
					WHERE        (RelationshipSchemaID = ?)";

		$insResult = sqlsrv_query($inputconn, $query, $params); 	
		if( $insResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		} 

		resetSchemaOrder($inputconn);

		return $newRelationshipSchemaID;

	}



	function XCDE_MarkUpdatedDate($MapSegmentID, $CIMappingID, $AttribMappingID, $RelationshipSchemaID, $MappingConfigurationID, $ConnectionGroupID, $inputconn) {
		// depending on which variable is provided, update all the upstream records
		$currentUser = $_SESSION['userID'];
		$MapID = 0;
		//echo $MapSegmentID.' | '.$CIMappingID.' | '.$AttribMappingID.' | '.$RelationshipSchemaID.' | '.$MappingConfigurationID.' | '.$ConnectionGroupID.'<br>';
		
		if ($RelationshipSchemaID > 0) {

			$params = array($currentUser, $RelationshipSchemaID);
			$query = "UPDATE       XCDE_DataMap_Segment
						SET                LastUpdateDate = getutcdate(), UpdatedBy = ?
						WHERE        (RelationshipSchemaID = ?)";

	
			$updResult = sqlsrv_query($inputconn, $query, $params );	
			if( $updResult === false ) {
				 die( print_r( sqlsrv_errors(), true));			
			}

			$params = array($currentUser, $RelationshipSchemaID);
			$query = "UPDATE       XCDE_DataMap
						SET                LastUpdateDate = getutcdate(), UpdatedBy = ?
						FROM            XCDE_DataMap_Segment INNER JOIN
                         XCDE_DataMap ON XCDE_DataMap_Segment.MapID = XCDE_DataMap.MapID
						WHERE        (XCDE_DataMap_Segment.RelationshipSchemaID = ?)";

	
			$updResult = sqlsrv_query($inputconn, $query, $params );	
			if( $updResult === false ) {
				 die( print_r( sqlsrv_errors(), true));			
			}

		} 

		if ($MappingConfigurationID > 0) {

			$params = array($currentUser, $MappingConfigurationID);
			$query = "UPDATE       XCDE_DataMap_Segment
						SET                LastUpdateDate = getutcdate(), UpdatedBy = ?
						WHERE        (MappingConfigurationID = ?)";

	
			$updResult = sqlsrv_query($inputconn, $query, $params );	
			if( $updResult === false ) {
				 die( print_r( sqlsrv_errors(), true));			
			}

			$params = array($currentUser, $MappingConfigurationID);
			$query = "UPDATE       XCDE_DataMap
						SET                LastUpdateDate = getutcdate(), UpdatedBy = ?
						FROM            XCDE_DataMap_Segment INNER JOIN
                         XCDE_DataMap ON XCDE_DataMap_Segment.MapID = XCDE_DataMap.MapID
						WHERE        (XCDE_DataMap_Segment.MappingConfigurationID = ?)";

	
			$updResult = sqlsrv_query($inputconn, $query, $params );	
			if( $updResult === false ) {
				 die( print_r( sqlsrv_errors(), true));			
			}

		} 

		if ($ConnectionGroupID > 0) {

			$params = array($currentUser, $ConnectionGroupID);
			$query = "UPDATE       XCDE_DataMap_Segment
						SET                LastUpdateDate = getutcdate(), UpdatedBy = ?
						FROM            XCDE_DataMap_Segment INNER JOIN
						                         XCDE_DataMap_MappingConfiguration ON 
						                         XCDE_DataMap_Segment.MappingConfigurationID = XCDE_DataMap_MappingConfiguration.MappingConfigurationID
						WHERE        (XCDE_DataMap_MappingConfiguration.ConnectionGroupID = ?)";

	
			$updResult = sqlsrv_query($inputconn, $query, $params );	
			if( $updResult === false ) {
				 die( print_r( sqlsrv_errors(), true));			
			}

			$params = array($currentUser, $ConnectionGroupID);
			$query = "UPDATE       XCDE_DataMap
						SET                LastUpdateDate = GETUTCDATE(), UpdatedBy = ?
						FROM            XCDE_DataMap_Segment INNER JOIN
						                         XCDE_DataMap_MappingConfiguration ON 
						                         XCDE_DataMap_Segment.MappingConfigurationID = XCDE_DataMap_MappingConfiguration.MappingConfigurationID 
						                         INNER JOIN
						                         XCDE_DataMap ON XCDE_DataMap_Segment.MapID = XCDE_DataMap.MapID
						WHERE        (XCDE_DataMap_MappingConfiguration.ConnectionGroupID = ?)";

	
			$updResult = sqlsrv_query($inputconn, $query, $params );	
			if( $updResult === false ) {
				 die( print_r( sqlsrv_errors(), true));			
			}

		} 


		if ($AttribMappingID > 0) {

			$params = array($AttribMappingID);
			$query = "SELECT 	MapSegmentID
						FROM 	XCDE_DataMap_Attribute_Mapping
						WHERE 	AttribMappingID = ?";


			$result = sqlsrv_query($inputconn,$query,$params);	
				
			if ($result === false ) {
				 die( print_r( sqlsrv_errors(), true));
			}

			try {	  
				$test = sqlsrv_has_rows($result);
							
				if ($test) {
					while($row = sqlsrv_fetch_array($result)) {
						$MapSegmentID = $row['MapSegmentID'];
					}			
				} 								

			} catch (exception $e) {
				print_r($e);
			}

			sqlsrv_free_stmt($result);


			$params = array($currentUser, $AttribMappingID);
			$query = "UPDATE       XCDE_DataMap_Attribute_Mapping
						SET                LastUpdateDate = getutcdate(), UpdatedBy = ?
						WHERE        (AttribMappingID = ?)";

	
			$updResult = sqlsrv_query($inputconn, $query, $params );	
			if( $updResult === false ) {
				 die( print_r( sqlsrv_errors(), true));			
			}

			
		}

		if ($CIMappingID > 0) {

			$params = array($CIMappingID);
			$query = "SELECT 	MapSegmentID
						FROM 	XCDE_DataMap_CI_Mapping
						WHERE 	CIMappingID = ?";


			$result = sqlsrv_query($inputconn,$query,$params);	
				
			if ($result === false ) {
				 die( print_r( sqlsrv_errors(), true));
			}

			try {	  
				$test = sqlsrv_has_rows($result);
							
				if ($test) {
					while($row = sqlsrv_fetch_array($result)) {
						$MapSegmentID = $row['MapSegmentID'];
					}			
				} 								

			} catch (exception $e) {
				print_r($e);
			}

			sqlsrv_free_stmt($result);
			

			$params = array($currentUser, $CIMappingID);
			$query = "UPDATE       XCDE_DataMap_CI_Mapping
						SET                LastUpdateDate = getutcdate(), UpdatedBy = ?
						WHERE        (CIMappingID = ?)";

	
			$updResult = sqlsrv_query($inputconn, $query, $params );	
			if( $updResult === false ) {
				 die( print_r( sqlsrv_errors(), true));			
			}
			
		}

		if ($MapSegmentID > 0) {

			$params = array($MapSegmentID);
			$query = "SELECT 	MapID
						FROM 	XCDE_DataMap_Segment
						WHERE 	MapSegmentID = ?";


			$result = sqlsrv_query($inputconn,$query,$params);	
				
			if ($result === false ) {
				 die( print_r( sqlsrv_errors(), true));
			}

			try {	  
				$test = sqlsrv_has_rows($result);
							
				if ($test) {
					while($row = sqlsrv_fetch_array($result)) {
						$MapID = $row['MapID'];
					}			
				} 								

			} catch (exception $e) {
				print_r($e);
			}

			sqlsrv_free_stmt($result);

			$params = array($currentUser, $MapSegmentID);
			$query = "UPDATE       XCDE_DataMap_Segment
						SET                LastUpdateDate = getutcdate(), UpdatedBy = ?
						WHERE        (MapSegmentID = ?)";

	
			$updResult = sqlsrv_query($inputconn, $query, $params );	
			if( $updResult === false ) {
				 die( print_r( sqlsrv_errors(), true));			
			}
			
		}

		if ($MapID > 0) {
			//echo 'MapID '.$MapID.'<br>';
			$params = array($currentUser, $MapID);
			$query = "UPDATE       XCDE_DataMap
						SET                LastUpdateDate = getutcdate(), UpdatedBy = ?
						WHERE        (MapID = ?)";

	
			$updResult = sqlsrv_query($inputconn, $query, $params );	
			if( $updResult === false ) {
				 die( print_r( sqlsrv_errors(), true));			
			}
			
		}	

		//echo 'STOP at end of function.<br>';exit;
 				
	}	


	function XCDE_ConcatArrays_init() {
		// this clears out the session arrays to get ready for the new page

		$_SESSION['XCDEAttribConcat.ConcatNo'] = array();  // array state number - change to zero if deleted during build or edit
		$_SESSION['XCDEAttribConcat.AttribConcat_ID'] = array();  // database ID , 0 = new, >0 = existing
		$_SESSION['XCDEAttribConcat.Constant'] = array(); 
		$_SESSION['XCDEAttribConcat.ConstantValue'] = array(); 
		$_SESSION['XCDEAttribConcat.RelatedClass'] = array(); 
		$_SESSION['XCDEAttribConcat.RelatedClassName'] = array(); 
		$_SESSION['XCDEAttribConcat.AttributeName'] = array(); 
		$_SESSION['XCDEAttribConcat.ConcatOrder'] = array(); 
		//echo "arrays initialized";

	}


	function resetConcatElementOrder($updAttribMappingID, $inputconn) {
		// this is run after a attrib mapping has been saved
		
		//echo $updAttribMappingID.' '.$inputconn; exit;

		$params = array($updAttribMappingID);
		$query = "SELECT AttribConcat_ID
				  FROM XCDE_DataMap_Attribute_Mapping_Concatenation
				  WHERE AttribMappingID = ?
				  ORDER BY ConcatOrder";

		$result = sqlsrv_query($inputconn, $query, $params); 	
		if( $result === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		}	

		$newConcatOrder = 10;
		try {	  
			$test = sqlsrv_has_rows($result);
						
			if ($test) {
				while($row = sqlsrv_fetch_array($result)) {

					$thisAttribConcat_ID = $row['AttribConcat_ID'];

					$query = "UPDATE   XCDE_DataMap_Attribute_Mapping_Concatenation
								SET   ConcatOrder = ? ";
					$query .= "	WHERE  AttribConcat_ID = ?";
					//echo $query;exit; 

					$params = array($newConcatOrder, $thisAttribConcat_ID);
					$updResult = sqlsrv_query($inputconn, $query, $params); 	
					if( $updResult === false ) {
						 die( print_r( sqlsrv_errors(), true));			
					}	        

			        $newConcatOrder += 10;

				}			
			} 								

		} catch (exception $e) {
			print_r($e);
		}

		sqlsrv_free_stmt($result);


	}






	//placeholder
	function DPLY_Project_LogXXX($dplyID, $dplyIntID, $StatusID, $StatusIDorig, $IntStatusID, $IntStatusIDorig, 
								$IntStateID, $IntStateIDorig, $logcomm, $inputconn) {
		
		$currentUser = $_SESSION['userID'];
		
		//check for status changes
		if ($StatusID <> $StatusIDorig) {
			// get change verbiage
			$Status = '';
			$Statusorig = '';

			$query="SELECT        StatusID, Status
					FROM            DPLY_Status
					WHERE        (StatusID = $StatusID)";
			
			$result = sqlsrv_query($inputconn,$query);	
			if ($result === false ) {
				 die( print_r( sqlsrv_errors(), true));
			}

			try {	  
				$test = sqlsrv_has_rows($result);
				if ($test) {
					while($row = sqlsrv_fetch_array($result)) {
						$Status = $row['Status'];
					}			
				} 								

			} catch (exception $e) {
				print_r($e);
			}
			sqlsrv_free_stmt($result);

			$query="SELECT        StatusID, Status
					FROM            DPLY_Status
					WHERE        (StatusID = $StatusIDorig)";
			
			$result = sqlsrv_query($inputconn,$query);	
			if ($result === false ) {
				 die( print_r( sqlsrv_errors(), true));
			}

			try {	  
				$test = sqlsrv_has_rows($result);
				if ($test) {
					while($row = sqlsrv_fetch_array($result)) {
						$Statusorig = $row['Status'];
					}			
				} 								

			} catch (exception $e) {
				print_r($e);
			}
			sqlsrv_free_stmt($result);

			$logcomm .= 'Status changed from '.$Statusorig.' to '.$Status.'. ';
		}
		
		if ($IntStatusID <> $IntStatusIDorig) {
			// get change verbiage
			
		}

		if ($IntStateID <> $IntStateIDorig) {
			// get change verbiage
			
		}

		// simple add of a history log
		$params = array($dplyID, $dplyIntID, $StatusID, $StatusIDorig,
						 $IntStatusID, $StatusIDorig, $IntStateID, $IntStateIDorig,
						 $logcomm);
		$query = "INSERT INTO DPLY_ProjectHistory
            			(DPLYProjID, DPLYIntegrationID, StatusID, StatusIDorig, 
            					IntStatusID, IntStatusIDorig, IntStateID, IntStateIDorig, 
            					LogComments, CommentDate, CommentBy)
		VALUES        (?,?,?,?,?,?,?,?,?,GetUTCDate(), 'system')";

		$insResult = sqlsrv_query($inputconn, $query, $params );	
		if( $insResult === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		}	
			 				
	}	


?>	

 