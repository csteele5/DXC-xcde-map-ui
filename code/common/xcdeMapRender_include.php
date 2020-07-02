<?php

/**
*<!-------------------------------------------------- 
*|	 Name: Charles Steele                          |
*|	 Payroll Number: cs13514                       |
*|	 E-mail: csteele5@dxc.com                      |
*|	 Phone: 310-321-8776                           |
*| 	 Date Created: 4/19/19 					   |
*--------------------------------------------------->
*<!-------------------------------------------------------------------------
*	4/19/19 - json file render from map wizard - will not render snippets, only complete file
*---------------------------------------------------------------------------->		
*/

	
	if (!isset($MapSegmentID)) {
		$MapSegmentID = 0;
	}

	
	if (!isset($UpdateMap)) {
		$UpdateMap = 0;
	}

 	if (isset($_SESSION['userID'])) {
		$currentUser = $_SESSION['userID'];
 	} else {
 		$currentUser = '';
 	}


	$Details_sourceItemIdAttribute = '';
	$Details_sourceItemNameAttribute = '';
	$Details_sourceItemTypeAttribute = '';
	$Solution = '';
	$Source = '';
	$UseCase = '';
	$Version = '';
	// $DataStor = '';
	// $DefaultSourceInclude = '';
	$DefaultSourceAttribute = '';
	$Target_SysName = '';
	$TargetItemIdAttribute = '';
	$TargetItemNameAttribute = '';
	$TargetItemTypeAttribute = '';
	$Include_idAtRoot = false;
	$Include_nameAtRoot = false;
	$Include_otherAtRoot = '';
	$ConnectionGroupID = 0;
	$RelationshipSchemaID = 0;
	$MaxAttempts = 0;
	$RetryDelay = 0;
	$RefObjectsTargetClassAttribute = '';
    $RefObjectsEndpoint = '';

	$Target_MapSystemID = 0;

	//XCDE_DataMap_MappingConfiguration.DataStor, XCDE_DataMap_MappingConfiguration.DefaultSourceInclude, 
    // 7/15/19 - replace XCDE_DataMap_MappingConfiguration.UseCase, XCDE_DataMap_MappingConfiguration.Version, with segment level variables                     

	$query = "SELECT        XCDE_DataMap_Segment.MapSegmentID, XCDE_DataMap_Segment.Source_MapSystemID, XCDE_DataMap_Segment.Target_MapSystemID, SourceSystem.MapSystem AS Source_MapSystem, 
                         SourceSystem.idAttribute AS Source_idAttribute, TargetSystem.MapSystem AS Target_MapSystem, TargetSystem.idAttribute AS Target_idAttribute, XCDE_DataMap_Segment.MappingConfigurationID, 
                         XCDE_DataMap_Segment.RelationshipSchemaID, XCDE_DataMap_MappingConfiguration.ConfigurationName, XCDE_DataMap_MappingConfiguration.Details_sourceItemIdAttribute, 
                         XCDE_DataMap_MappingConfiguration.Details_sourceItemNameAttribute, XCDE_DataMap_MappingConfiguration.Details_sourceItemTypeAttribute, XCDE_DataMap_MappingConfiguration.Solution, 
                         XCDE_DataMap_MappingConfiguration.Source,  
                         XCDE_DataMap_MappingConfiguration.DefaultSourceAttribute, XCDE_DataMap_MappingConfiguration.Target_SysName, 
                         XCDE_DataMap_MappingConfiguration.TargetItemIdAttribute, XCDE_DataMap_MappingConfiguration.TargetItemNameAttribute, XCDE_DataMap_MappingConfiguration.TargetItemTypeAttribute, 
                         XCDE_DataMap_MappingConfiguration.Include_idAtRoot, XCDE_DataMap_MappingConfiguration.Include_nameAtRoot, XCDE_DataMap_MappingConfiguration.Include_otherAtRoot, 
                         XCDE_DataMap_MappingConfiguration.ConnectionGroupID, XCDE_DataMap_Segment.Version, XCDE_DataMap_Segment.UseCase, XCDE_DataMap_MappingConfiguration.MaxAttempts, 
                         XCDE_DataMap_MappingConfiguration.RetryDelay,
                         XCDE_DataMap_MappingConfiguration.UseSeparatePath,
                         XCDE_DataMap_MappingConfiguration.RefObjectsTargetClassAttribute,
                         XCDE_DataMap_MappingConfiguration.RefObjectsEndpoint
				FROM            XCDE_DataMap_System AS TargetSystem RIGHT OUTER JOIN
                         XCDE_DataMap_Segment INNER JOIN
                         XCDE_DataMap_MappingConfiguration ON XCDE_DataMap_Segment.MappingConfigurationID = XCDE_DataMap_MappingConfiguration.MappingConfigurationID ON 
                         TargetSystem.MapSystemID = XCDE_DataMap_Segment.Target_MapSystemID LEFT OUTER JOIN
                         XCDE_DataMap_System AS SourceSystem ON XCDE_DataMap_Segment.Source_MapSystemID = SourceSystem.MapSystemID
				WHERE        (XCDE_DataMap_Segment.MapSegmentID = $MapSegmentID) ";

	$result = sqlsrv_query($conn,$query);	
		
	if( $result === false ) {
		 die( print_r( sqlsrv_errors(), true));
	}

	try {	  
		$test = sqlsrv_has_rows($result);
					
		if ($test) {
			while($row = sqlsrv_fetch_array($result)) {
				
				$Details_sourceItemIdAttribute = $row['Details_sourceItemIdAttribute'];
				$Details_sourceItemNameAttribute = $row['Details_sourceItemNameAttribute'];
				$Details_sourceItemTypeAttribute = $row['Details_sourceItemTypeAttribute'];
				$Solution = $row['Solution'];
				$Source = $row['Source'];
				$UseCase = $row['UseCase'];
				$Version = $row['Version'];
				// $DataStor = $row['DataStor'];
				// $DefaultSourceInclude = $row['DefaultSourceInclude'];
				$DefaultSourceAttribute = $row['DefaultSourceAttribute'];
				$Target_SysName = $row['Target_SysName'];
				$TargetItemIdAttribute = $row['TargetItemIdAttribute'];
				$TargetItemNameAttribute = $row['TargetItemNameAttribute'];
				$TargetItemTypeAttribute = $row['TargetItemTypeAttribute'];
				
				if ($row['Include_idAtRoot'] == 1) {
					$Include_idAtRoot = true;
				}
				if ($row['Include_nameAtRoot'] == 1) {
					$Include_nameAtRoot = true;
				}
				
				$Include_otherAtRoot = $row['Include_otherAtRoot'];
				$ConnectionGroupID = $row['ConnectionGroupID'];
				$RelationshipSchemaID = $row['RelationshipSchemaID'];  		

				$MaxAttempts = $row['MaxAttempts'];  	
				$RetryDelay = $row['RetryDelay'];  	 
	
				$Target_MapSystemID = $row['Target_MapSystemID']; 

				$UseSeparatePath = $row['UseSeparatePath'];   

				$RefObjectsTargetClassAttribute = $row['RefObjectsTargetClassAttribute'];   
    			$RefObjectsEndpoint = $row['RefObjectsEndpoint'];   

			}			
		} 								

	} catch (exception $e) {
		print_r($e);
	}

	sqlsrv_free_stmt($result);


	// get matching root class values in scope of map
	$query = "SELECT        DISTINCT XCDE_DataMap_CI.ClassName
				FROM            XCDE_DataMap_CI_Mapping INNER JOIN
				                         XCDE_DataMap_CI ON XCDE_DataMap_CI_Mapping.Source_CI_ID = XCDE_DataMap_CI.CI_ID
				WHERE        (XCDE_DataMap_CI_Mapping.MapSegmentID = $MapSegmentID)
				ORDER BY XCDE_DataMap_CI.ClassName ";

	$matchingValueresult = sqlsrv_query($conn,$query);	
		
	if( $matchingValueresult === false ) {
		 die( print_r( sqlsrv_errors(), true));
	}


	// get environments to build connections
	$query = "SELECT        Environment, XCDEVariable, EnvironmentID
				FROM            XCDE_DataMap_Environment
				WHERE        (Inactive = 0)
				ORDER BY DisplayOrder ";

	$environmentresult = sqlsrv_query($conn,$query);	
		
	if( $environmentresult === false ) {
		 die( print_r( sqlsrv_errors(), true));
	}

	// get connection information and put in array for looping
	$query = "SELECT        ConnectionID, ConnectionName, EnvironmentID, BasePath, relsPath, Host, 
							Method, Port, Pwd, Type, ConnectionUser, Header_json, relsHeader_json, 
							relsHost, relsMethod, relsPort, relsPwd, relsType, 
                         	relsConnectionUser, ComplexRelsConnection, isAsync
				FROM            XCDE_DataMap_Connection
				WHERE        (ConnectionGroupID = $ConnectionGroupID)";


    $connectionList = array();
	$ttlConnections = 0; 
	
	$result = sqlsrv_query($conn,$query);	
		
	if ($result === false ) {
		 die( print_r( sqlsrv_errors(), true));
	}

	try {	  
		$test = sqlsrv_has_rows($result);
					
		if ($test) {
			while($row = sqlsrv_fetch_array($result)) {

		        $connectionList[] = $row;

		        $ttlConnections ++;

			}			
		} 								

	} catch (exception $e) {
		print_r($e);
	}

	sqlsrv_free_stmt($result);


	// get attribute information and put in array for looping
	$query = "SELECT        XCDE_DataMap_Attribute_Mapping.Source_Attrib_ID, XCDE_DataMap_Attribute_Mapping.Target_Attrib_ID, XCDE_DataMap_Attribute_Mapping.MapTypeID, 
                         XCDE_DataMap_Attribute_Mapping.Source_Attrib_Condition_Code, XCDE_DataMap_Attribute_Mapping.RequestorMappingNotes, XCDE_DataMap_Attribute_Mapping.ProgrammerMappingNotes, 
                         XCDE_DataMap_MapType.MapType, ISNULL(SourceAttrib.AttributeName, '<calculated '+ TargetAttrib.AttributeName + '>') AS SourceAttributeName, SourceAttrib.DisplayName AS SourceDisplayName, ISNULL(SourceAttrib.FieldType, 'string') AS SourceFieldType, 
                         TargetAttrib.AttributeName AS TargetAttributeName, TargetAttrib.DisplayName AS TargetDisplayName, ISNULL(TargetAttrib.FieldType, 'string') AS TargetFieldType, TargetAttrib.Required, TargetAttrib.FieldFormat AS TargetFieldFormat,
                         XCDE_DataMap_Attribute_Mapping.AttribMappingID, XCDE_DataMap_Attribute_Mapping.AttributeDerivationID, XCDE_DataMap_Attribute_Mapping.Source_ConstantValue,
                         XCDE_DataMap_Attribute_Mapping.MatchRelType, XCDE_DataMap_Attribute_Mapping.RelatedCIIsParent,
                         XCDE_DataMap_Attribute_Mapping.AttributeScope, XCDE_DataMap_Attribute_Mapping.ReverseCondition, XCDE_DataMap_Attribute_Mapping.ElseSourceAttribute, 
                         XCDE_DataMap_Attribute_Mapping.FalseConditionAction, XCDE_DataMap_Attribute_Mapping.Target_ReferenceCI
				FROM            XCDE_DataMap_MapType RIGHT OUTER JOIN
                         XCDE_DataMap_Attribute AS TargetAttrib RIGHT OUTER JOIN
                         XCDE_DataMap_Attribute_Mapping ON TargetAttrib.Attrib_ID = XCDE_DataMap_Attribute_Mapping.Target_Attrib_ID ON 
                         XCDE_DataMap_MapType.MapTypeID = XCDE_DataMap_Attribute_Mapping.MapTypeID LEFT OUTER JOIN
                         XCDE_DataMap_Attribute AS SourceAttrib ON XCDE_DataMap_Attribute_Mapping.Source_Attrib_ID = SourceAttrib.Attrib_ID
				WHERE        (XCDE_DataMap_Attribute_Mapping.MapSegmentID = $MapSegmentID) AND (XCDE_DataMap_Attribute_Mapping.Inactive = 0)
				ORDER BY TargetAttributeName";


    $attributeList = array();
	$ttlAttributes = 0; 
	
	$result = sqlsrv_query($conn,$query);	
		
	if ($result === false ) {
		 die( print_r( sqlsrv_errors(), true));
	}

	try {	  
		$test = sqlsrv_has_rows($result);
					
		if ($test) {
			while($row = sqlsrv_fetch_array($result)) {

		        $attributeList[] = $row;

		        $ttlAttributes ++;

			}			
		} 								

	} catch (exception $e) {
		print_r($e);
	}

	sqlsrv_free_stmt($result);

	//echo 'test1';exit;

	$query = "SELECT        XCDE_DataMap_Attribute_Mapping_CI.AttribMappingCIID, XCDE_DataMap_Attribute_Mapping_CI.CIClassName, XCDE_DataMap_Attribute_Mapping_CI.Source0Target1, 
                         XCDE_DataMap_Attribute_Mapping_CI.Calculated, XCDE_DataMap_Attribute_Mapping_CI.AttribMappingID, XCDE_DataMap_Attribute_Mapping.MapSegmentID
				FROM            XCDE_DataMap_Attribute_Mapping_CI INNER JOIN
                         XCDE_DataMap_Attribute_Mapping ON XCDE_DataMap_Attribute_Mapping_CI.AttribMappingID = XCDE_DataMap_Attribute_Mapping.AttribMappingID
				WHERE        (XCDE_DataMap_Attribute_Mapping.MapSegmentID = $MapSegmentID)
				ORDER BY XCDE_DataMap_Attribute_Mapping_CI.Source0Target1, XCDE_DataMap_Attribute_Mapping_CI.CIClassName";



    $AppliedCIList = array();
	$ttlAppliedCI = 0; 
	
	$result = sqlsrv_query($conn,$query);	
		
	if ($result === false ) {
		 die( print_r( sqlsrv_errors(), true));
	}

	try {	  
		$test = sqlsrv_has_rows($result);
					
		if ($test) {
			while($row = sqlsrv_fetch_array($result)) {

		        $AppliedCIList[] = $row;

		        $ttlAppliedCI ++;

			}			
		} 								

	} catch (exception $e) {
		print_r($e);
	}

	sqlsrv_free_stmt($result);
	//foreach ($AppliedCIList as $applCI) {
	//echo '<br>'.$applCI['CIClassName'];
	//} exit;


	$query = "SELECT        XCDE_DataMap_Attribute_Mapping_Derivation.Map_MapType, XCDE_DataMap_Attribute_Mapping_Derivation.IdAttribute, XCDE_DataMap_Attribute_Mapping_Derivation_Option.Attribute, 
                         XCDE_DataMap_Attribute_Mapping_Derivation_Option.Condition, XCDE_DataMap_Attribute_Mapping_Derivation_Option.DerivedConditionID, 
                         XCDE_DataMap_Attribute_Mapping_Derivation_Option.AttributeDerivationID, XCDE_DataMap_Attribute_Mapping.MapSegmentID, XCDE_DataMap_Attribute_Mapping.AttribMappingID
				FROM            XCDE_DataMap_Attribute_Mapping_Derivation INNER JOIN
                         XCDE_DataMap_Attribute_Mapping_Derivation_Option ON 
                         XCDE_DataMap_Attribute_Mapping_Derivation.AttributeDerivationID = XCDE_DataMap_Attribute_Mapping_Derivation_Option.AttributeDerivationID INNER JOIN
                         XCDE_DataMap_Attribute_Mapping ON XCDE_DataMap_Attribute_Mapping_Derivation.AttributeDerivationID = XCDE_DataMap_Attribute_Mapping.AttributeDerivationID
				WHERE        (XCDE_DataMap_Attribute_Mapping.MapSegmentID = $MapSegmentID)
				ORDER BY XCDE_DataMap_Attribute_Mapping_Derivation_Option.Condition, XCDE_DataMap_Attribute_Mapping_Derivation_Option.Attribute";


    $DerivedAttributeList = array();
	$ttlDerivedAttribute = 0; 
	
	$result = sqlsrv_query($conn,$query);	
		
	if ($result === false ) {
		 die( print_r( sqlsrv_errors(), true));
	}

	try {	  
		$test = sqlsrv_has_rows($result);
					
		if ($test) {
			while($row = sqlsrv_fetch_array($result)) {

		        $DerivedAttributeList[] = $row;

		        $ttlDerivedAttribute ++;

			}			
		} 								

	} catch (exception $e) {
		print_r($e);
	}

	sqlsrv_free_stmt($result);
    //echo 'debug 6<br>';//exit;	


    $query = "SELECT        XCDE_DataMap_Attribute_Mapping_Concatenation.AttribMappingID, XCDE_DataMap_Attribute_Mapping_Concatenation.AttribConcat_ID, XCDE_DataMap_Attribute_Mapping_Concatenation.Constant, 
                         XCDE_DataMap_Attribute_Mapping_Concatenation.ConstantValue, XCDE_DataMap_Attribute_Mapping_Concatenation.RelatedClass, XCDE_DataMap_Attribute_Mapping_Concatenation.RelatedClassName, 
                         XCDE_DataMap_Attribute_Mapping_Concatenation.AttributeName, XCDE_DataMap_Attribute_Mapping_Concatenation.ConcatOrder, XCDE_DataMap_Attribute_Mapping.MapSegmentID
				FROM            XCDE_DataMap_Attribute_Mapping_Concatenation INNER JOIN
                         XCDE_DataMap_Attribute_Mapping ON XCDE_DataMap_Attribute_Mapping_Concatenation.AttribMappingID = XCDE_DataMap_Attribute_Mapping.AttribMappingID
				WHERE        (XCDE_DataMap_Attribute_Mapping.MapSegmentID = $MapSegmentID)
				ORDER BY XCDE_DataMap_Attribute_Mapping_Concatenation.ConcatOrder";
		//echo $query.'<br>';		 
    $result = sqlsrv_query($conn,$query); 

	$concatValueList = array();
	$ttlConcatValue = 0; 
	
		
	if ($result === false ) {
		 die( print_r( sqlsrv_errors(), true));
	}

	try {	  
		$test = sqlsrv_has_rows($result);
					
		if ($test) {
			while($row = sqlsrv_fetch_array($result)) {

		        $concatValueList[] = $row;

		        $ttlConcatValue ++;

			}			
		} 								

	} catch (exception $e) {
		print_r($e);
	}

	sqlsrv_free_stmt($result);



    $query = "SELECT        XCDE_DataMap_Attribute_Mapping_List.SourceValue, XCDE_DataMap_Attribute_Mapping_List.TargetValue, XCDE_DataMap_Attribute_Mapping_List.AttribMappingID
				FROM            XCDE_DataMap_Attribute_Mapping_List INNER JOIN
                         XCDE_DataMap_Attribute_Mapping ON XCDE_DataMap_Attribute_Mapping_List.AttribMappingID = XCDE_DataMap_Attribute_Mapping.AttribMappingID
				WHERE        (XCDE_DataMap_Attribute_Mapping.MapSegmentID = $MapSegmentID)
				ORDER BY XCDE_DataMap_Attribute_Mapping_List.SourceValue, XCDE_DataMap_Attribute_Mapping_List.TargetValue";
				 
    $result = sqlsrv_query($conn,$query); 

	$attribListList = array();
	$ttlAttribList = 0; 
	
		
	if ($result === false ) {
		 die( print_r( sqlsrv_errors(), true));
	}

	try {	  
		$test = sqlsrv_has_rows($result);
					
		if ($test) {
			while($row = sqlsrv_fetch_array($result)) {

		        $attribListList[] = $row;

		        $ttlAttribList ++;

			}			
		} 								

	} catch (exception $e) {
		print_r($e);
	}

	sqlsrv_free_stmt($result);

	// attribute calculation
	$query = "SELECT        XCDE_DataMap_Attribute_Mapping_Calculation.AttribMappingID, XCDE_DataMap_Attribute_Mapping_Calculation.CalculationTypeID, XCDE_DataMap_Attribute_Mapping_Calculation.SumTypeClass, 
                         XCDE_DataMap_Attribute_Mapping_Calculation.SumTypeAttribute, XCDE_DataMap_Attribute_Mapping_Calculation_Type.CalculationType, XCDE_DataMap_Attribute_Mapping_Calculation_Type.codeValue
				FROM            XCDE_DataMap_Attribute_Mapping_Calculation INNER JOIN
                         XCDE_DataMap_Attribute_Mapping_Calculation_Type ON XCDE_DataMap_Attribute_Mapping_Calculation.CalculationTypeID = XCDE_DataMap_Attribute_Mapping_Calculation_Type.CalculationTypeID INNER JOIN
                         XCDE_DataMap_Attribute_Mapping ON XCDE_DataMap_Attribute_Mapping_Calculation.AttribMappingID = XCDE_DataMap_Attribute_Mapping.AttribMappingID
				WHERE        (XCDE_DataMap_Attribute_Mapping.MapSegmentID = $MapSegmentID) ";
	 
    $result = sqlsrv_query($conn,$query); 

	$attribCalcList = array();
	$ttlAttribCalc = 0; 
	
		
	if ($result === false ) {
		 die( print_r( sqlsrv_errors(), true));
	}

	try {	  
		$test = sqlsrv_has_rows($result);
					
		if ($test) {
			while($row = sqlsrv_fetch_array($result)) {

		        $attribCalcList[] = $row;

		        $ttlAttribCalc ++;

			}			
		} 								

	} catch (exception $e) {
		print_r($e);
	}

	sqlsrv_free_stmt($result);


	$query = "SELECT        XCDE_DataMap_Attribute_Mapping_AttribValList.AttribValList_ID, XCDE_DataMap_Attribute_Mapping_AttribValList.SourceValue, 
							XCDE_DataMap_Attribute_Mapping_AttribValList.AttribMappingID
				FROM            XCDE_DataMap_Attribute_Mapping_AttribValList INNER JOIN
                         XCDE_DataMap_Attribute_Mapping ON XCDE_DataMap_Attribute_Mapping_AttribValList.AttribMappingID = XCDE_DataMap_Attribute_Mapping.AttribMappingID
                WHERE 	(XCDE_DataMap_Attribute_Mapping.MapSegmentID = $MapSegmentID)";
				 
    $result = sqlsrv_query($conn,$query); 

	$attribValList = array();
	$ttlAttribValList = 0; 
		
	if ($result === false ) {
		 die( print_r( sqlsrv_errors(), true));
	}

	try {	  
		$test = sqlsrv_has_rows($result);
					
		if ($test) {
			while($row = sqlsrv_fetch_array($result)) {

		        $attribValList[] = $row;

		        $ttlAttribValList ++;

			}			
		} 								

	} catch (exception $e) {
		print_r($e);
	}

	sqlsrv_free_stmt($result);



	// relationship XCDE_DataMap_RelationshipPairs.UseSeparatePath, 
    $query = "SELECT        XCDE_DataMap_RelationshipPairs.SourceParentCI, XCDE_DataMap_RelationshipPairs.SourceRelationship, XCDE_DataMap_RelationshipPairs.SourceChildCI, XCDE_DataMap_RelationshipPairs.TargetParentCI, 
                         XCDE_DataMap_RelationshipPairs.TargetRelationship, XCDE_DataMap_RelationshipPairs.TargetChildCI, 
						 ISNULL(mappedParentCIs.CIDerivationID,0) AS TarParentDerived, 
						 ISNULL(mappedChildCIs.CIDerivationID,0) AS TarChildDerived
				FROM            XCDE_DataMap_RelationshipPairs LEFT OUTER JOIN
                             (SELECT        XCDE_DataMap_CI_Mapping_1.CIDerivationID, XCDE_DataMap_CI_1.ClassName
                               FROM            XCDE_DataMap_CI_Mapping AS XCDE_DataMap_CI_Mapping_1 INNER JOIN
                                                         XCDE_DataMap_CI AS XCDE_DataMap_CI_1 ON XCDE_DataMap_CI_Mapping_1.Target_CI_ID = XCDE_DataMap_CI_1.CI_ID
                               WHERE        (XCDE_DataMap_CI_Mapping_1.MapSegmentID = 1)) AS mappedChildCIs ON XCDE_DataMap_RelationshipPairs.TargetChildCI = mappedChildCIs.ClassName LEFT OUTER JOIN
                             (SELECT        XCDE_DataMap_CI_Mapping.CIDerivationID, XCDE_DataMap_CI.ClassName
                               FROM            XCDE_DataMap_CI_Mapping INNER JOIN
                                                         XCDE_DataMap_CI ON XCDE_DataMap_CI_Mapping.Target_CI_ID = XCDE_DataMap_CI.CI_ID
                               WHERE        (XCDE_DataMap_CI_Mapping.MapSegmentID = $MapSegmentID)) AS mappedParentCIs ON XCDE_DataMap_RelationshipPairs.TargetParentCI = mappedParentCIs.ClassName
				WHERE        (XCDE_DataMap_RelationshipPairs.RelationshipSchemaID = $RelationshipSchemaID) AND (XCDE_DataMap_RelationshipPairs.Inactive = 0) ";
				
	 
    $result = sqlsrv_query($conn,$query); 

	$relationshipList = array();
	$ttlRelationship = 0; 
	
		
	if ($result === false ) {
		 die( print_r( sqlsrv_errors(), true));
	}

	try {	  
		$test = sqlsrv_has_rows($result);
					
		if ($test) {
			while($row = sqlsrv_fetch_array($result)) {

		        $relationshipList[] = $row;

		        $ttlRelationship ++;

			}			
		} 								

	} catch (exception $e) {
		print_r($e);
	}

	sqlsrv_free_stmt($result);


	// cmdb parent child
 /*
    $query = " ";
Target_MapSystemID
	 
    $result = sqlsrv_query($conn,$query); 

	$cmdbList = array();
	$ttlCMDB = 0; 
	
		
	if ($result === false ) {
		 die( print_r( sqlsrv_errors(), true));
	}

	try {	  
		$test = sqlsrv_has_rows($result);
					
		if ($test) {
			while($row = sqlsrv_fetch_array($result)) {

		        $cmdbList[] = $row;

		        $ttlCMDB ++;

			}			
		} 								

	} catch (exception $e) {
		print_r($e);
	}

	sqlsrv_free_stmt($result);
*/

	//Get CI list with type 
	$query = "SELECT        XCDE_DataMap_CI_Mapping.MapSegmentID, XCDE_DataMap_CI_Mapping.Source_CI_ID, 
							SourceCI.ClassName AS SourceClassName, 
							XCDE_DataMap_CI_Mapping.Target_CI_ID, TargetCI.ClassName AS TargetClassName, 
                         XCDE_DataMap_CI_Mapping.CIDerivationID, XCDE_DataMap_CI_Mapping.Source_CI_Condition_Code, XCDE_DataMap_CI_Mapping_Derivation.IdAttribute, XCDE_DataMap_AttributeDataType.FieldType,
                         TargetCI.ParentClassName
			FROM            XCDE_DataMap_AttributeDataType RIGHT OUTER JOIN
                         XCDE_DataMap_CI_Mapping_Derivation ON XCDE_DataMap_AttributeDataType.FieldTypeID = XCDE_DataMap_CI_Mapping_Derivation.IdAttributeFieldTypeID RIGHT OUTER JOIN
                         XCDE_DataMap_CI_Mapping ON XCDE_DataMap_CI_Mapping_Derivation.CIDerivationID = XCDE_DataMap_CI_Mapping.CIDerivationID LEFT OUTER JOIN
                         XCDE_DataMap_CI AS TargetCI ON XCDE_DataMap_CI_Mapping.Target_CI_ID = TargetCI.CI_ID LEFT OUTER JOIN
                         XCDE_DataMap_CI AS SourceCI ON XCDE_DataMap_CI_Mapping.Source_CI_ID = SourceCI.CI_ID
				WHERE        (XCDE_DataMap_CI_Mapping.MapSegmentID = $MapSegmentID) AND (XCDE_DataMap_CI_Mapping.Inactive = 0)
				ORDER BY SourceClassName, TargetClassName";

    $CIMappingList = array();
	$ttlCIMapping = 0; 
	
	$result = sqlsrv_query($conn,$query);	
		
	if ($result === false ) {
		 die( print_r( sqlsrv_errors(), true));
	}

	try {	  
		$test = sqlsrv_has_rows($result);
					
		if ($test) {
			while($row = sqlsrv_fetch_array($result)) {

		        $CIMappingList[] = $row;

		        $ttlCIMapping ++;

			}			
		} 								

	} catch (exception $e) {
		print_r($e);
	}

	sqlsrv_free_stmt($result);
			

	//CI Mapping Derivation
	$query = "SELECT        XCDE_DataMap_CI_Mapping.MapSegmentID, XCDE_DataMap_CI_Mapping.CIDerivationID, XCDE_DataMap_CI_Mapping_Derivation.MapTypeID, XCDE_DataMap_CI_Mapping_Derivation.IdAttribute, 
                         XCDE_DataMap_CI_Mapping_Derivation.IdAttributeFieldTypeID, XCDE_DataMap_AttributeDataType.FieldType
				FROM            XCDE_DataMap_CI_Mapping_Derivation INNER JOIN
                         XCDE_DataMap_CI_Mapping ON XCDE_DataMap_CI_Mapping_Derivation.CIDerivationID = XCDE_DataMap_CI_Mapping.CIDerivationID LEFT OUTER JOIN
                         XCDE_DataMap_AttributeDataType ON XCDE_DataMap_CI_Mapping_Derivation.IdAttributeFieldTypeID = XCDE_DataMap_AttributeDataType.FieldTypeID
				WHERE        (XCDE_DataMap_CI_Mapping.MapSegmentID = $MapSegmentID)";

    $CIMappingDerivationList = array();
	$ttlCIMappingDerivation = 0; 
	
	$result = sqlsrv_query($conn,$query);	
		
	if ($result === false ) {
		 die( print_r( sqlsrv_errors(), true));
	}

	try {	  
		$test = sqlsrv_has_rows($result);
					
		if ($test) {
			while($row = sqlsrv_fetch_array($result)) {

		        $CIMappingDerivationList[] = $row;

		        $ttlCIMappingDerivation ++;

			}			
		} 								

	} catch (exception $e) {
		print_r($e);
	}

	sqlsrv_free_stmt($result);


	//CI Mapping Derivation Options
	$query = "SELECT        XCDE_DataMap_CI_Mapping.MapSegmentID, XCDE_DataMap_CI_Mapping.CIDerivationID, XCDE_DataMap_Attribute_Mapping_List.SourceValue, XCDE_DataMap_Attribute_Mapping_List.TargetValue, 
                         XCDE_DataMap_CI_Mapping.Source_CI_Condition_Code
				FROM            XCDE_DataMap_CI_Mapping INNER JOIN
                         XCDE_DataMap_Attribute_Mapping_List ON XCDE_DataMap_CI_Mapping.CIDerivationID = XCDE_DataMap_Attribute_Mapping_List.CIDerivationID
				WHERE        (XCDE_DataMap_CI_Mapping.MapSegmentID = $MapSegmentID) AND (XCDE_DataMap_CI_Mapping.CIDerivationID > 0)
				ORDER BY XCDE_DataMap_Attribute_Mapping_List.SourceValue";

    $CIMappingDerivationOptionList = array();
	$ttlCIMappingDerivationOption = 0; 
	
	$result = sqlsrv_query($conn,$query);	
		
	if ($result === false ) {
		 die( print_r( sqlsrv_errors(), true));
	}

	try {	  
		$test = sqlsrv_has_rows($result);
					
		if ($test) {
			while($row = sqlsrv_fetch_array($result)) {

		        $CIMappingDerivationOptionList[] = $row;

		        $ttlCIMappingDerivationOption ++;

			}			
		} 								

	} catch (exception $e) {
		print_r($e);
	}

	sqlsrv_free_stmt($result);




	$query = "SELECT        XCDE_DataMap_RefObject.RefObjectID, XCDE_DataMap_RefObject.TriggeringAttributeName, XCDE_DataMap_RefObject.MapMultipleAttributes, 
							XCDE_DataMap_RefObject.Required, XCDE_DataMap_RefObject.TargetCIClassName, XCDE_DataMap_RefObject.SingleTargetAttributeName, 
							XCDE_DataMap_RefObject.SingleTarget_ConstantValue, XCDE_DataMap_RefObject.TargetFieldTypeID, 
                         XCDE_DataMap_RefObject.UpdatedBy, XCDE_DataMap_RefObject.LastUpdateDate, XCDE_DataMap_AttributeDataType.FieldType, XCDE_DataMap_RefObject.Source_Attrib_Condition_Code, 
                         XCDE_DataMap_RefObject.RequestorMappingNotes, XCDE_DataMap_RefObject.ProgrammerMappingNotes, XCDE_DataMap_RefObject.Inactive
				FROM            XCDE_DataMap_RefObject LEFT OUTER JOIN
                         XCDE_DataMap_AttributeDataType ON XCDE_DataMap_RefObject.TargetFieldTypeID = XCDE_DataMap_AttributeDataType.FieldTypeID
				WHERE        (XCDE_DataMap_RefObject.MapSegmentID = ?) AND (XCDE_DataMap_RefObject.Inactive = 0)";

	$params = array($MapSegmentID);	

    $RefObjList = array();
	$ttlRefObj = 0; 

	$result = sqlsrv_query($conn,$query,$params);	
	/*	
	*/	
	if ($result === false ) {
		 die( print_r( sqlsrv_errors(), true));
	}

	try {	  
		$test = sqlsrv_has_rows($result);
					
		if ($test) {
			while($row = sqlsrv_fetch_array($result)) {

		        $RefObjList[] = $row;

		        $ttlRefObj ++;

			}			
		} 								

	} catch (exception $e) {
		print_r($e);
	}

	sqlsrv_free_stmt($result);


	$query = "SELECT        XCDE_DataMap_RefObject_CI.RefObjectID, XCDE_DataMap_RefObject_CI.CIClassName, XCDE_DataMap_RefObject.MapSegmentID
					FROM            XCDE_DataMap_RefObject_CI INNER JOIN
	                         XCDE_DataMap_RefObject ON XCDE_DataMap_RefObject_CI.RefObjectID = XCDE_DataMap_RefObject.RefObjectID
					WHERE        (XCDE_DataMap_RefObject.MapSegmentID = $MapSegmentID)
					ORDER BY XCDE_DataMap_RefObject_CI.CIClassName";


    $RefAppliedCIList = array();
	$ttlRefAppliedCI = 0; 
	
	$result = sqlsrv_query($conn,$query);	
		
	if ($result === false ) {
		 die( print_r( sqlsrv_errors(), true));
	}

	try {	  
		$test = sqlsrv_has_rows($result);
					
		if ($test) {
			while($row = sqlsrv_fetch_array($result)) {

		        $RefAppliedCIList[] = $row;

		        $ttlRefAppliedCI ++;

			}			
		} 								

	} catch (exception $e) {
		print_r($e);
	}

	sqlsrv_free_stmt($result);


	$query = "SELECT        XCDE_DataMap_RefObject_Attribute_Mapping.RefObjectID, XCDE_DataMap_RefObject_Attribute_Mapping.Source_Attribute, 
							XCDE_DataMap_RefObject_Attribute_Mapping.Target_Attribute, XCDE_DataMap_RefObject_Attribute_Mapping.Target_ConstantValue, XCDE_DataMap_RefObject_Attribute_Mapping.ExistsInRef
				FROM            XCDE_DataMap_RefObject_Attribute_Mapping INNER JOIN
                         XCDE_DataMap_RefObject ON XCDE_DataMap_RefObject_Attribute_Mapping.RefObjectID = XCDE_DataMap_RefObject.RefObjectID
				WHERE        (XCDE_DataMap_RefObject.MapSegmentID = $MapSegmentID)
				ORDER BY XCDE_DataMap_RefObject_Attribute_Mapping.Source_Attribute, XCDE_DataMap_RefObject_Attribute_Mapping.Target_Attribute";			
				 
    $result = sqlsrv_query($conn,$query); 

	$RefAttribList = array();
	$ttlRefAttribList = 0; 
	
		
	if ($result === false ) {
		 die( print_r( sqlsrv_errors(), true));
	}

	try {	  
		$test = sqlsrv_has_rows($result);
					
		if ($test) {
			while($row = sqlsrv_fetch_array($result)) {

		        $RefAttribList[] = $row;

		        $ttlRefAttribList ++;

			}			
		} 								

	} catch (exception $e) {
		print_r($e);
	}

	sqlsrv_free_stmt($result);







	$data = array();

	$data  += [ "active" => true ];

	if ($UpdateMap != 1) {
		$data  += [ "_request" => "createMap" ];
	} else {
		$data  += [ "_request" => "updateMap" ];
		$data  += [ "updatedBy" =>  $currentUser];
	}
	
	$data  += [ "createdBy" => "XCDEMapUI" ];

	//array_push($data, getChildElement_array($ElementID));


// $Solution = '';
// $Source = '';
// $UseCase = '';
// $Version = '';
// $DataStor = '';
// $DefaultSourceInclude = '';
// $DefaultSourceAttribute = '';
// $Target_SysName = '';
// $TargetItemIdAttribute = '';
// $TargetItemNameAttribute = '';
// $TargetItemTypeAttribute = '';
// $Include_idAtRoot = '';
// $Include_nameAtRoot = '';
// $Include_otherAtRoot = '';
// $ConnectionGroupID = 0;
	$detailArray = array();
	$detailArray  += [ "sourceItemIdAttribute" => $Details_sourceItemIdAttribute ];
	$detailArray  += [ "sourceItemNameAttribute" => $Details_sourceItemNameAttribute ];
	$detailArray  += [ "sourceItemTypeAttribute" => $Details_sourceItemTypeAttribute ];

	$data  += [ "details" => $detailArray ];


	$mapContentArray = array();
	foreach ($attributeList as $attr) {

		$mapObjectArray = array();
		$AttribMappingID = $attr['AttribMappingID'];
		$MapTypeID = $attr['MapTypeID'];
		$MapType = strtolower($attr['MapType']);
		$TargetFieldType = strtolower($attr['TargetFieldType']);
		$TargetFieldFormat = $attr['TargetFieldFormat'];
		if ($TargetFieldFormat == '0') {
			$TargetFieldFormat = '';
		} 
		//else {
		//echo $TargetFieldType.' | '.$TargetFieldFormat.'<br>';
		//}
		$TargetAttributeName = trim($attr['TargetAttributeName']);
		$Target_ReferenceCI = trim($attr['Target_ReferenceCI']);
		if ($Target_ReferenceCI == '0') {
			$Target_ReferenceCI = '';
		}
		$SourceAttributeName = trim($attr['SourceAttributeName']);  // default value.  can be overwritten in multiple places
		$Required = $attr['Required'];
		$MatchRelType = $attr['MatchRelType'];
		$RelatedCIIsParent = $attr['RelatedCIIsParent'];

		$AttributeScope = $attr['AttributeScope'];
		$ReverseCondition = $attr['ReverseCondition'];
		$ElseSourceAttribute = $attr['ElseSourceAttribute'];
		$FalseConditionAction = $attr['FalseConditionAction'];

		$Source_ConstantValue = $attr['Source_ConstantValue'];

		//$mapObjectArray  += [ "(DEBUG) MapType" => $attr['MapType'].'('.$MapTypeID.')'];
		$mapObjectArray  += [ "datatype" => strtolower($attr['SourceFieldType'])];
		$mapObjectArray  += [ "elementType" => 'attribute'];


		
		$mapObjectTargetsArray = array();
		$mapObjectTargetsContentArray = array();

		$ConditionalElement = 0;


//AttributeScope, ReverseCondition, ElseSourceAttribute, FalseConditionAction
//$attribValList $ttlAttribValList	AttribMappingID SourceValue
		$attribValueArray = array();
		foreach ($attribValList as $attrVal) {
                if ($attrVal['AttribMappingID'] == $AttribMappingID) {
					array_push($attribValueArray, $attrVal['SourceValue']);
					//$mapObjectTargetsContentArray  += [ "(DEBUG) FOUND Attribute Value - ".$attrVal['SourceValue'] => '('.$attrVal['SourceValue'].') + '.$AttribMappingID ];
				}
				//echo '<br>'.$applCI['CIClassName'].'|'.$applCI['AttribMappingID'].'|'.$applCI['Source0Target1'];

			}

		if (!empty($attribValueArray)) {
			$ConditionalElement = 1;
		}

		// get applied CIs for this map type and put in a temporary array
        //$mapObjectTargetsContentArray  += [ "(DEBUG) PRE CHECK Source CI from ttl:".$ttlAppliedCI => 'ID'.$AttribMappingID];
		$appliedCIArray = array();
		foreach ($AppliedCIList as $applCI) {
                //echo $AttribMappingID.'|'.$applCI['AttribMappingID'].'|'.$applCI['Source0Target1'].'  '.$applCI['CIClassName'].'<br>';
                //$mapObjectTargetsContentArray  += [ "(DEBUG) Check Source CI from ttl:".$ttlAppliedCI => 'ID'.$AttribMappingID];
                //$mapObjectTargetsContentArray  += [ "(DEBUG) Check Source CI from ttl:".$ttlAppliedCI => 'Class'.'('.$applCI['CIClassName'].') + '.$AttribMappingID ];
                if ($applCI['AttribMappingID'] == $AttribMappingID && $applCI['Source0Target1'] == 0) {
					//echo '<br>'.'ADD! ';echo $applCI['CIClassName'].'|'.$applCI['AttribMappingID'].'|'.$applCI['Source0Target1'];
					//$appliedCIArray += $applCI['CIClassName'];
					array_push($appliedCIArray, trim($applCI['CIClassName']));
					//$mapObjectTargetsContentArray  += [ "(DEBUG) FOUND Applied Source CI- ".$applCI['AttribMappingCIID'] => 'Class'.'('.$applCI['CIClassName'].') + '.$AttribMappingID ];
				}
				//echo '<br>'.$applCI['CIClassName'].'|'.$applCI['AttribMappingID'].'|'.$applCI['Source0Target1'];

			}

		if (!empty($appliedCIArray)) {
			$ConditionalElement = 1;
		}
			if ($AttribMappingID == 7 && 1==2) {
				echo '<br>+++++++++++++++++++++++++++++++++++++++++++++++++<br>';
				// foreach ($AppliedCIList as $applCI) {
				// 	echo $applCI['AttribMappingID'].'  '.$applCI['CIClassName'].'<br>';
				// }
				var_dump($appliedCIArray);
				//var_dump($mapObjectTargetsContentArray);
				exit;
			}

		// go calculate this based on type
		switch ($MapTypeID) {
		    case 6:
				$mapObjectTargetsContentArray  += [ "(DEBUG) MapType SWITCH" => 'DERIVED'.'('.$MapTypeID.')'];

				$mapObjectArray  += [ "name" => $SourceAttributeName];
		        break;
		    case 5:
				//$mapObjectTargetsContentArray  += [ "(DEBUG) MapType SWITCH" => 'CONSTANT'.'('.$MapTypeID.') NOT CURRENTLY OFFERED'];
				
				$mapObjectTargetValuesArray = array();
				$mapObjectTargetValuesContentArray = array();
				$mapObjectTargetValuesContentArray  += [ "type" => "constant"];
				
				//if constant value is a boolean, translate that value for the map
				$mapObjectTargetValuesContentArray  += [ "value" => $Source_ConstantValue == "true" ? true: ($Source_ConstantValue == "false" ? false: $Source_ConstantValue)];

				//$thisValue == "true" ? true: $thisValue == "false" ? false: $thisValue
				array_push($mapObjectTargetValuesArray, $mapObjectTargetValuesContentArray);

				if ($ConditionalElement == 0) {  // if this is false, it is moved down to the conditional statement
					$mapObjectTargetsContentArray  += [ "values" => $mapObjectTargetValuesArray];
				}

				$SourceAttributeName = "< constant value >";

				$mapObjectArray  += [ "name" => $SourceAttributeName];


				$MapType = 'concatenation';

		        break;
		    case 4:
				//$mapObjectTargetsContentArray  += [ "(DEBUG) MapType SWITCH" => 'CONCATENATED'.'('.$MapTypeID.')'];
		        
				$ttlThisConcatValue = 0;
				$dispConcatVal = '';
				$dispValueType = '';
				$dispValue = '';

		
				$mapObjectTargetValuesArray = array();
				foreach ($concatValueList as $ConcatVal) {
					$thisValue = '';
					$thisType = '';
					if ($ConcatVal['AttribMappingID'] == $AttribMappingID) {
						$AttribConcat_ID = $ConcatVal['AttribConcat_ID'];
						$Constant = $ConcatVal['Constant'];
						if (!$ConcatVal['ConstantValue'] == 0) {$ConstantValue = stripslashes($ConcatVal['ConstantValue']);} else {$ConstantValue ='';}
						$RelatedClass = $ConcatVal['RelatedClass'];
						if (!$ConcatVal['RelatedClassName'] == 0) {$RelatedClassName = trim(stripslashes($ConcatVal['RelatedClassName']));} else {$RelatedClassName ='';}
						if (!$ConcatVal['AttributeName'] == 0) {$AttributeName = trim(stripslashes($ConcatVal['AttributeName']));} else {$AttributeName ='';}
						$ConcatOrder = $ConcatVal['ConcatOrder'];
						

						$ttlThisConcatValue++;
		

						if ($dispConcatVal != '') {
							$dispConcatVal .= '+';
						}

						if ($Constant == 1) {
							//$dispConcatVal .= '<b>Constant</b>: ';
							//$dispValueType = 'Constant';
							$thisType = 'constant';
							if ($ConstantValue === '') {
								
								$dispConcatVal .= '<i>(blank)</i>';
								$dispValue .= '(blank)';
								$thisValue = '(blank)';
							} else {
								$dispConcatVal .= '<i>"'.$ConstantValue.'"</i>';
								$dispValue .= $ConstantValue;
								$thisValue = $ConstantValue;
							}
						} else {
							$thisType = 'variable';
							if ($RelatedClass == 1) {
								//$dispConcatVal .= '<b>Related Class Attribute</b>: ';
											
								$mapObjectTargetsContentArray  += [ "(DEBUG) Concat" => 'Related Class Attribute - Not a legal map option.'];
								/*
								if ($RelatedClassName == '') {
									$dispConcatVal .= '<i>(blank class)</i>==>>';
									$dispValue .= '(blank class)>>';
								} else {
									$dispConcatVal .= '<i>'.$RelatedClassName.'</i>==>>';
									$dispValue .= $RelatedClassName.'>>';
								}
								*/
							} else {
								//$dispConcatVal .= '<b>Class Attribute</b>: ';
								//$dispValueType = 'Class Attribute';
							}
							if ($AttributeName == '') {
								$dispValue .= '(blank)';
								$thisValue .= '(blank)';
							} else {
								$dispValue .= $AttributeName;
								$thisValue .= $AttributeName;
							}
								

						}
						$mapObjectTargetValuesContentArray = array();
						$mapObjectTargetValuesContentArray  += [ "type" => $thisType];
						//$mapObjectTargetValuesContentArray  += [ "valueorig" => $thisValue];
						//if constant value is a boolean, translate that value for the map
						$mapObjectTargetValuesContentArray  += [ "value" => $thisValue == "true" ? true: ($thisValue == "false" ? false: $thisValue)];

						//$thisValue == "true" ? true: $thisValue == "false" ? false: $thisValue
						array_push($mapObjectTargetValuesArray, $mapObjectTargetValuesContentArray);

					}
				}

				if ($ConditionalElement == 0) {  // if this is false, it is moved down to the conditional statement
					$mapObjectTargetsContentArray  += [ "values" => $mapObjectTargetValuesArray];
				}

				if ($ttlThisConcatValue > 0) {
					//$SourceAttributeName = $dispValue;
					$SourceAttributeName = "concatenated values";  // to match values in nexted conditional concatenation
				}							

				//$mapObjectTargetsContentArray  += [ "(DEBUG) Concat" => $SourceAttributeName];

				$mapObjectArray  += [ "name" => '< '.$SourceAttributeName.' >'];


		        break;
		    case 3:
				//$mapObjectTargetsContentArray  += [ "(DEBUG) MapType SWITCH" => 'LIST'.'('.$MapTypeID.')'];
	  			$choiceListOptionArray = array();
				$ttlThisAttribList = 0;
				foreach ($attribListList as $attrList) {
	  				$choiceListOptionContentArray = array();

					if (!$attrList['SourceValue'] == 0) {$SourceValue = stripslashes($attrList['SourceValue']);} else {$SourceValue ='';}
					if (!$attrList['TargetValue'] == 0) {$TargetValue = stripslashes($attrList['TargetValue']);} else {$TargetValue ='';}

					if ($attrList['AttribMappingID'] == $AttribMappingID) {

	  					//$mapObjectTargetsContentArray  += [ "(DEBUG) MapType ListFound".$ttlThisAttribList => 'DERIVED found'.'('.$MapTypeID.')'];
						

						$choiceListOptionContentArray  += [ "sourceValue" => $SourceValue];
						$choiceListOptionContentArray  += [ "targetValue" => $TargetValue];


						array_push($choiceListOptionArray, $choiceListOptionContentArray);

						$ttlThisAttribList++;

					}

				}

				if ($ConditionalElement == 0) {  // if this is false, it is moved down to the conditional statement
					$mapObjectTargetsContentArray  += [ "options" => $choiceListOptionArray];
				}

				$mapObjectArray  += [ "name" => $SourceAttributeName];
		        break;
		    case 2:
				//$mapObjectTargetsContentArray  += [ "(DEBUG) MapType SWITCH" => 'CALCULATED'.'('.$MapTypeID.')'];

				$formulaVal = '';
				$relClassKey = 'matchChildItemType';
				if ($RelatedCIIsParent == 1) {
					$relClassKey = 'matchParentItemType';
				}
				$relClassValue = '';
				$relType = $MatchRelType;

				foreach ($attribCalcList as $attrCalc) {  
	  				
	  				if ($attrCalc['AttribMappingID'] == $AttribMappingID) {

						$formulaVal = $attrCalc['codeValue'];
						$relClassValue = $attrCalc['SumTypeClass'];

					}

				}


				if ($ConditionalElement == 0) {  // if this is false, it is moved down to the conditional statement
					$mapObjectTargetsContentArray  += [ "context" => "relationship"];
					$mapObjectTargetsContentArray  += [ "formula" => $formulaVal];
					//$mapObjectTargetsContentArray  += [ "mapType2" => $MapType];
					$mapObjectTargetsContentArray  += [ $relClassKey => $relClassValue];
					$mapObjectTargetsContentArray  += [ "matchRelType" => $MatchRelType];
					//$mapObjectTargetsContentArray  += [ "name" => $TargetAttributeName];
					//$mapObjectTargetsContentArray  += [ "required" => $Required];
					//$mapObjectTargetsContentArray  += [ "solution" => $Solution];
					
				}
				$MapType = 'calculation';

				$mapObjectArray  += [ "name" => $SourceAttributeName];



		        break;
		    default:
				//$mapObjectArray  += [ "(DEBUG) MapType SWITCH" => 'DIRECT'.'('.$MapTypeID.')'];
				$mapObjectArray  += [ "name" => $SourceAttributeName];


		}
		//$mapObjectTargetsContentArray  += [ "(DEBUG) MapType SWITCH" => 'IS this really direct or conditional. Ttl derived: '.$ttlDerivedAttribute.' AttribMappingID: '.'('.$AttribMappingID.')'];

		$ttlThisDerivedAttribute = 0;

			/*
			//UPDATE UI FOR THIS!!!!!  MatchRelType RelatedCIIsParent
			$relatedCIrelationship = 'composition'; // missing parameter from UI.  
			$relatedCIParent = 1;  // missing parameter from UI.  Is this related CI a parent or child of the base CI
			// for the start, we'll say it is a parent.  add it to the UI later
		*/
			$relationMapName = '';
			$derivedRelationOptionArray = array();
			foreach ($DerivedAttributeList as $DAttr) {  
				$derivedRelationOptionContentArray = array();
				//$mapObjectTargetsContentArray  += [ "(DEBUG) MapType SWITCH".$ttlThisDerivedAttribute => 'DERIVED Test'.'('.$MapTypeID.')'];
			if ($DAttr['AttribMappingID'] == $AttribMappingID) {

					//$mapObjectTargetsContentArray  += [ "(DEBUG) MapType SWITCHFound".$ttlThisDerivedAttribute => 'DERIVED found'.'('.$MapTypeID.')'];
				

				$derivedRelationOptionContentArray  += [ "attribute" => $DAttr['Attribute']];
				$derivedRelationOptionContentArray  += [ "condition" => $DAttr['Condition']];

				// set this name one time using the first attribute name encountered.  Test to see if relevant
				if ($relationMapName == '') {
					$relationMapName = $DAttr['Attribute'];
				}

				array_push($derivedRelationOptionArray, $derivedRelationOptionContentArray);

				$ttlThisDerivedAttribute++;

			}

		}


		if ($ttlThisDerivedAttribute > 0) {
			//$mapObjectTargetsContentArray  += [ "(DEBUG) MapType SWITCH Confirmed" => 'DERIVED'.'('.$MapTypeID.')'];
			// now get the relationship type for the derived attribute
			// id attribute -= $Details_sourceItemIdAttribute
			// loop through the relationship and find the relationship type for this derivation
			// this relationship type can be added to the UI

			$derivedArray = array();
			$derivedRelationArray = array();
			$derivedRelationMapArray = array();
			$derivedRelationMapArray  += [ "mapType" => 'direct']; 
			/*
			if ($Target_ReferenceCI == '') {
				$derivedRelationMapArray  += [ "mapType" => 'direct']; // other options available?
			} else {
				$derivedRelationMapArray  += [ "mapType" => 'refObject']; 
				$derivedRelationMapArray  += [ "object" => $Target_ReferenceCI]; 
			}
			*/
			//$derivedRelationMapArray  += [ "name" => $relationMapName];  // may not be needed

			$derivedRelationArray  += [ "current" => $RelatedCIIsParent == 1 ? 'child': 'parent'];
			$derivedRelationArray  += [ "idAttribute" => $Details_sourceItemTypeAttribute];
			$derivedRelationArray  += [ "map" => $derivedRelationMapArray];

			$derivedRelationArray  += [ "options" => $derivedRelationOptionArray];
			$derivedRelationArray  += [ "type" => $MatchRelType];


			// $derivedArray  += [ "mapType" => 'derived'];
			// $derivedArray  += [ "relation" => $derivedRelationArray];

			// are there conditional CIs?
			// if (!empty($appliedCIArray)) {
			// 	//$mapObjectTargetsContentArray  += [ "(DEBUG) AppliedCIs Found" => 'Test'.'('.$MapTypeID.')'];
				
			// 	$conditionArray = array();
			// 	$conditionElseArray = array();
			// 	$conditionInArray = array();
			// 	$conditionThenArray = array();

			// 	$conditionElseArray  += [ "mapType" => 'skip'];
			// 	// foreach ($appliedCIArray as $applCI) {

			// 	// 	}
			// 	$conditionArray  += [ "else" => $conditionElseArray];
			// 	$conditionArray  += [ "if" => $Details_sourceItemTypeAttribute];
			// 	$conditionArray  += [ "in" => $appliedCIArray];
				
			// 	$conditionThenArray  += [ "mapType" => 'derived'];
			// 	$conditionThenArray  += [ "relation" => $derivedRelationArray];

			// 	$conditionArray  += [ "then" => $conditionThenArray];
			// 	$mapObjectTargetsContentArray  += [ "condition" => $conditionArray];

			// 	//override maptype
			// 	$MapType = 'conditional';


			// } else {
			// 	//$mapObjectTargetsContentArray  += [ "(DEBUG) AppliedCIs Not Found" => 'Test'.'('.$MapTypeID.')'];
			// 	$mapObjectTargetsContentArray  += [ "mapType" => 'derived'];
			// 	$mapObjectTargetsContentArray  += [ "relation" => $derivedRelationArray];

			// }
			if (empty($appliedCIArray)) {
				//$mapObjectTargetsContentArray  += [ "(DEBUG) AppliedCIs Not Found" => 'Test'.'('.$MapTypeID.')'];
				$mapObjectTargetsContentArray  += [ "mapType" => 'derived'];
				$mapObjectTargetsContentArray  += [ "relation" => $derivedRelationArray];

			}

		}

		$ifAttributeName = '';
		$inArray = array();
		if (!empty($appliedCIArray)) {
			$ifAttributeName = $Details_sourceItemTypeAttribute;
			$inArray = $appliedCIArray;
		} else if (!empty($attribValueArray)){
			$ifAttributeName = $SourceAttributeName;
			$inArray = $attribValueArray;
		}

		if (!empty($inArray)) {
			//$mapObjectTargetsContentArray  += [ "(DEBUG) AppliedCIs Found" => 'Test'.'('.$MapTypeID.')'];
			
			$conditionArray = array();
			$preConditionElseArray = array();
			$conditionElseArray = array();
			$conditionInArray = array();
			$preConditionThenArray = array();
			$conditionThenArray = array();

			if ($FalseConditionAction == 1) {
				$preConditionElseArray  += [ "attribute" => $ElseSourceAttribute];
				$preConditionElseArray  += [ "datatype" => 'string'];
				//$preConditionElseArray  += [ "mapType" => 'direct'];
				if ($Target_ReferenceCI == '') {
					//$preConditionElseArray  += [ "mapType" => 'direct']; // other options available?
					switch ($MapTypeID) {
				    	case 5:
				    	case 4:
				    		$preConditionElseArray  += [ "mapType" => 'concatenation'];
				    	break;
				    	case 3:
				    		$preConditionElseArray  += [ "mapType" => 'list'];
				    	break;
				    	case 2:
				    		$preConditionElseArray  += [ "mapType" => 'calculation'];
				    	break;
				    	default:
				    		$preConditionElseArray  += [ "mapType" => 'direct'];
				    	}	
				} else {
					$preConditionElseArray  += [ "mapType" => 'refObject']; 
					$preConditionElseArray  += [ "object" => $Target_ReferenceCI]; 
				}
				$preConditionElseArray  += [ "name" => $ElseSourceAttribute];
				$preConditionElseArray  += [ "required" => false];

			} else {
				$preConditionElseArray  += [ "mapType" => 'skip'];
			}
			if (!empty($appliedCIArray)) {
				$preConditionThenArray  += [ "datatype" => $TargetFieldType];
				if ($TargetFieldFormat != '') {
					$preConditionThenArray  += [ "dataFormat" => $TargetFieldFormat];
				}
				//$preConditionThenArray  += [ "mapType" => $MapType];
				if ($Target_ReferenceCI == '') {
					//$preConditionThenArray  += [ "mapType" => 'direct']; // other options available?
					switch ($MapTypeID) {
				    	case 5:
				    	case 4:
				    		$preConditionThenArray  += [ "mapType" => 'concatenation'];
				    	break;
				    	case 3:
				    		$preConditionThenArray  += [ "mapType" => 'list'];
				    	break;
				    	case 2:
				    		$preConditionThenArray  += [ "mapType" => 'calculation'];
				    	break;
				    	default:
				    		$preConditionThenArray  += [ "mapType" => 'direct'];
				    	}	
				} else {
					$preConditionThenArray  += [ "mapType" => 'refObject']; 
					$preConditionThenArray  += [ "object" => $Target_ReferenceCI]; 
				}
				$preConditionThenArray  += [ "endpoint" => "attributes"];
				//$preConditionThenArray  += [ "name" => $SourceAttributeName];

				// determine pair here based on type
				switch ($MapTypeID) {
				    case 5: //constant
						$preConditionThenArray  += [ "name" => $TargetAttributeName];

						$preConditionThenArray  += [ "values" => $mapObjectTargetValuesArray];


/*
				$mapObjectTargetValuesArray = array();
				$mapObjectTargetValuesContentArray = array();
				$mapObjectTargetValuesContentArray  += [ "type" => "constant"];
				
				//if constant value is a boolean, translate that value for the map
				$mapObjectTargetValuesContentArray  += [ "value" => $Source_ConstantValue == "true" ? true: ($Source_ConstantValue == "false" ? false: $Source_ConstantValue)];

				//$thisValue == "true" ? true: $thisValue == "false" ? false: $thisValue
				array_push($mapObjectTargetValuesArray, $mapObjectTargetValuesContentArray);

				if ($ConditionalElement == 0) {  // if this is false, it is moved down to the conditional statement
					$mapObjectTargetsContentArray  += [ "values" => $mapObjectTargetValuesArray];
				}
*/





				    
				        break;
				    case 4: //concatenated
						$preConditionThenArray  += [ "name" => $TargetAttributeName];
						$preConditionThenArray  += [ "values" => $mapObjectTargetValuesArray];
				    
				        break;
				    case 3: // list
				    	$preConditionThenArray  += [ "name" => $SourceAttributeName];
				    	$preConditionThenArray  += [ "options" => $choiceListOptionArray];

				        break;
				    case 2: // calculated
				    	$preConditionThenArray  += [ "name" => $SourceAttributeName];
						$preConditionThenArray  += [ "context" => "relationship"];
						$preConditionThenArray  += [ "formula" => $formulaVal];
						$preConditionThenArray  += [ $relClassKey => $relClassValue];
						$preConditionThenArray  += [ "matchRelType" => $MatchRelType];

			    		break;
			    	default:  // direct
				    	$preConditionThenArray  += [ "name" => $SourceAttributeName];

			    }	

			} else if (!empty($attribValueArray)){
				$preConditionThenArray  += [ "mapType" => 'direct'];
				/*
				if ($Target_ReferenceCI == '') {
					$preConditionThenArray  += [ "mapType" => 'direct']; // other options available?
				} else {
					$preConditionThenArray  += [ "mapType" => 'refObject']; 
					$preConditionThenArray  += [ "object" => $Target_ReferenceCI]; 
				}
				*/
				$preConditionThenArray  += [ "name" => $SourceAttributeName];
			}

			//$preConditionThenArray  += [ "mapType" => 'derived'];
			if ($ttlThisDerivedAttribute > 0) {
				$preConditionThenArray  += [ "relation" => $derivedRelationArray];
			}
			// foreach ($appliedCIArray as $applCI) {

			// 	}



			if ($ReverseCondition == 1) {
				$conditionElseArray = $preConditionThenArray;
				$conditionThenArray = $preConditionElseArray;
			} else {
				$conditionElseArray = $preConditionElseArray;
				$conditionThenArray = $preConditionThenArray;	
			}


			$conditionArray  += [ "else" => $conditionElseArray];
			$conditionArray  += [ "if" => $ifAttributeName];
			$conditionArray  += [ "in" => $inArray];

			$conditionArray  += [ "then" => $conditionThenArray];
			$mapObjectTargetsContentArray  += [ "condition" => $conditionArray];

			//override maptype
			$MapType = 'conditional';

			// override any previously set value for name
			$newSourceName = '';
			if ($MapTypeID == 4) {
				$newSourceName  = '<concatenated values> 1106';
			} else if ($SourceAttributeName != '') {
				$newSourceName  = '<calculated '.$SourceAttributeName.'>';
			} else {
				$newSourceName  = '<calculated>';
			}
			// only apply if there aren't already calculation indicators in name
			if (strpos($SourceAttributeName,'<') != 0) {
				$mapObjectArray['name'] = $newSourceName;
			}


		}
		
		$mapObjectTargetsContentArray  += [ "datatype" => $TargetFieldType];
		//echo $TargetFieldType.'==>';
		if ($TargetFieldFormat != '') {
			$mapObjectTargetsContentArray  += [ "dataFormat" => $TargetFieldFormat];
			//echo $TargetFieldFormat;
		}
		//echo '<br>';
		$mapObjectTargetsContentArray  += [ "endpoint" => 'attributes'];
		if (!empty($inArray)) {
			$mapObjectTargetsContentArray  += [ "mapType" => $MapType];
		} else {
			if ($Target_ReferenceCI == '') {
				$mapObjectTargetsContentArray  += [ "mapType" =>  $MapType]; 
			} else {
				$mapObjectTargetsContentArray  += [ "mapType" => 'refObject']; 
				$mapObjectTargetsContentArray  += [ "object" => $Target_ReferenceCI]; 
			}
		}
		$mapObjectTargetsContentArray  += [ "name" => $TargetAttributeName];
		
		$mapObjectTargetsContentArray  += [ "required" => $Required > 0 ? true: false ];

		$mapObjectTargetsContentArray  += [ "solution" => $Target_SysName];


		array_push($mapObjectTargetsArray, $mapObjectTargetsContentArray);
		$mapObjectArray  += [ "targets" => $mapObjectTargetsArray];
		array_push($mapContentArray, $mapObjectArray);
	}

// now build the ci class mapping into the appropriate attribute
if ($ttlCIMappingDerivation == 0) {
	// simple list map type
	$mapObjectArray = array();
	$mapObjectArray  += [ "datatype" => 'string'];
	$mapObjectArray  += [ "elementType" => 'attribute'];
	$mapObjectArray  += [ "name" => $Details_sourceItemTypeAttribute];
		
	$mapObjectTargetsArray = array();
	$mapObjectTargetsContentArray = array();
	//$mapObjectTargetsContentArray  += [ "(DEBUG) root class-No Derivation" => 'LIST'];
	$mapObjectTargetsContentArray  += [ "datatype" => 'string'];
	$mapObjectTargetsContentArray  += [ "endpoint" => 'attributes'];
	$mapObjectTargetsContentArray  += [ "mapType" => 'list'];
	$mapObjectTargetsContentArray  += [ "name" => $TargetItemTypeAttribute];

	$choiceListOptionArray = array();

	foreach ($CIMappingList as $CIMap) {
		$choiceListOptionContentArray = array();

		if (!$CIMap['SourceClassName'] == 0) {$SourceClassName = stripslashes($CIMap['SourceClassName']);} else {$SourceClassName ='';}
		if (!$CIMap['TargetClassName'] == 0) {$TargetClassName = stripslashes($CIMap['TargetClassName']);} else {$TargetClassName ='';}

		$choiceListOptionContentArray  += [ "sourceValue" => $SourceClassName];
		$choiceListOptionContentArray  += [ "targetValue" => $TargetClassName];

		//$CIMappingList $ttlCIMapping Source_CI_ID, SourceClassName Target_CI_ID TargetClassName CIDerivationID Source_CI_Condition_Code
		array_push($choiceListOptionArray, $choiceListOptionContentArray);
	}
		
	$mapObjectTargetsContentArray  += [ "options" => $choiceListOptionArray];

	array_push($mapObjectTargetsArray, $mapObjectTargetsContentArray);
	$mapObjectArray  += [ "targets" => $mapObjectTargetsArray];
	array_push($mapContentArray, $mapObjectArray);

} else {
	// conditional map types for straight list and attribute derivation
		
	// first, create the straight list, excluding classes with derived attribute mapping
	$mapObjectArray = array();
	$mapObjectArray  += [ "datatype" => 'string'];
	$mapObjectArray  += [ "elementType" => 'attribute'];
	$mapObjectArray  += [ "name" => $Details_sourceItemTypeAttribute];

	$mapObjectTargetsArray = array();
	$mapObjectTargetsContentArray = array();

	$derivedCIArray = array();
	$lastCI = '';
	foreach ($CIMappingList as $CIMap) {
		if ($CIMap['CIDerivationID'] > 0) {
			if (!$CIMap['SourceClassName'] == 0) {$SourceClassName = stripslashes($CIMap['SourceClassName']);} else {$SourceClassName ='';}
			if ($lastCI != $SourceClassName) {
				array_push($derivedCIArray, $SourceClassName);
				$lastCI = $SourceClassName;
			}
		}
	}




//AttributeScope, ReverseCondition, ElseSourceAttribute, FalseConditionAction
//$attribValListList $ttlAttribValList	AttribMappingID SourceValue
/*
			$preConditionElseArray = array();
			$preConditionThenArray = array();
			if ($ReverseCondition == 1) {
				$conditionElseArray = $preConditionThenArray;
				$conditionThenArray = $preConditionElseArray;
			} else {
				$conditionElseArray = $preConditionElseArray;
				$conditionThenArray = $preConditionThenArray;	
			}
*/


	$conditionArray = array();
	$conditionElseArray = array();
	$conditionInArray = array();
	$conditionThenArray = array();

	$conditionThenArray  += [ "mapType" => 'skip'];
	// foreach ($appliedCIArray as $applCI) {

	// 	}
	$choiceListOptionArray = array();

	foreach ($CIMappingList as $CIMap) {

		if ($CIMap['CIDerivationID'] == 0) {
			$choiceListOptionContentArray = array();
			if (!$CIMap['SourceClassName'] == 0) {$SourceClassName = stripslashes($CIMap['SourceClassName']);} else {$SourceClassName ='';}
			if (!$CIMap['TargetClassName'] == 0) {$TargetClassName = stripslashes($CIMap['TargetClassName']);} else {$TargetClassName ='';}

			$choiceListOptionContentArray  += [ "sourceValue" => $SourceClassName];
			$choiceListOptionContentArray  += [ "targetValue" => $TargetClassName];

			array_push($choiceListOptionArray, $choiceListOptionContentArray);
		}
	}

	$conditionElseArray  += [ "mapType" => 'list'];
	$conditionElseArray  += [ "options" => $choiceListOptionArray];

	$conditionArray  += [ "else" => $conditionElseArray];
	$conditionArray  += [ "if" => $Details_sourceItemTypeAttribute];
	$conditionArray  += [ "in" => $derivedCIArray];
	

	$conditionArray  += [ "then" => $conditionThenArray];
	$mapObjectTargetsContentArray  += [ "condition" => $conditionArray];


	$mapObjectTargetsContentArray  += [ "datatype" => "string"];
	$mapObjectTargetsContentArray  += [ "endpoint" => 'attributes'];
	$mapObjectTargetsContentArray  += [ "mapType" => "conditional"];
	$mapObjectTargetsContentArray  += [ "name" => $TargetItemTypeAttribute];
	
	$mapObjectTargetsContentArray  += [ "required" => false ]; //9/23/19 - change this to false to allow for multiple conditional mappings

	$mapObjectTargetsContentArray  += [ "solution" => $Target_SysName];



	array_push($mapObjectTargetsArray, $mapObjectTargetsContentArray);
	$mapObjectArray  += [ "targets" => $mapObjectTargetsArray];
	array_push($mapContentArray, $mapObjectArray);

	$CIMappingDerivationIdAttribute = '';
	// now loop through the CIs with attribute derivations
	foreach ($CIMappingList as $CIMap) {

		if ($CIMap['CIDerivationID'] > 0) {
			if (!$CIMap['SourceClassName'] == 0) {$SourceClassName = stripslashes($CIMap['SourceClassName']);} else {$SourceClassName ='';}
			if (!$CIMap['TargetClassName'] == 0) {$TargetClassName = stripslashes($CIMap['TargetClassName']);} else {$TargetClassName ='';}
			// only use for derivation
			if (!$CIMap['IdAttribute'] == 0) {$CIMappingDerivationIdAttribute = stripslashes($CIMap['IdAttribute']);} else {$CIMappingDerivationIdAttribute ='';}

			// $choiceListOptionContentArray = array();
			// $choiceListOptionContentArray  += [ "sourceValue" => $SourceClassName];
			// $choiceListOptionContentArray  += [ "targetValue" => $TargetClassName];

			// array_push($choiceListOptionArray, $choiceListOptionContentArray);
			$mapObjectArray = array();
			$mapObjectArray  += [ "datatype" => 'string'];
			$mapObjectArray  += [ "elementType" => 'attribute'];
			$mapObjectArray  += [ "name" => $Details_sourceItemTypeAttribute];

			$mapObjectTargetsArray = array();
			$mapObjectTargetsContentArray = array();

			$derivedCIArray = array();
			array_push($derivedCIArray, $SourceClassName);
			

			$conditionArray = array();
			$conditionElseArray = array();
			$conditionInArray = array();
			$conditionThenArray = array();

			$conditionElseArray  += [ "mapType" => 'skip'];
			// foreach ($appliedCIArray as $applCI) {

			// 	}
			$choiceListOptionArray = array();

			foreach ($CIMappingDerivationOptionList as $CIMapDer) {

				if (!$CIMapDer['SourceValue'] == 0) {$SourceValue = stripslashes($CIMapDer['SourceValue']);} else {$SourceValue ='';}
				if (!$CIMapDer['TargetValue'] == 0) {$TargetValue = stripslashes($CIMapDer['TargetValue']);} else {$TargetValue ='';}

				if ($CIMap['CIDerivationID'] == $CIMapDer['CIDerivationID'] && $SourceValue != '' && $TargetValue != '') {
					$choiceListOptionContentArray = array();
					$choiceListOptionContentArray  += [ "sourceValue" => $SourceValue];
					$choiceListOptionContentArray  += [ "targetValue" => $TargetValue];

					array_push($choiceListOptionArray, $choiceListOptionContentArray);
				}
			}

			$conditionThenArray  += [ "idAttribute" => $CIMappingDerivationIdAttribute];
			$conditionThenArray  += [ "mapType" => 'list'];

			$conditionThenArray  += [ "options" => $choiceListOptionArray];

			$conditionArray  += [ "else" => $conditionElseArray];
			$conditionArray  += [ "if" => $Details_sourceItemTypeAttribute];
			$conditionArray  += [ "in" => $derivedCIArray];
			

			$conditionArray  += [ "then" => $conditionThenArray];
			$mapObjectTargetsContentArray  += [ "condition" => $conditionArray];


			$mapObjectTargetsContentArray  += [ "datatype" => "string"];
			$mapObjectTargetsContentArray  += [ "endpoint" => 'attributes'];
			$mapObjectTargetsContentArray  += [ "mapType" => "conditional"];
			$mapObjectTargetsContentArray  += [ "name" => $TargetItemTypeAttribute];
			
			$mapObjectTargetsContentArray  += [ "required" => false ]; //9/23/19 - change this to false to allow for multiple conditional mappings

			$mapObjectTargetsContentArray  += [ "solution" => $Target_SysName];



			array_push($mapObjectTargetsArray, $mapObjectTargetsContentArray);
			$mapObjectArray  += [ "targets" => $mapObjectTargetsArray];
			array_push($mapContentArray, $mapObjectArray);

		}
	}

}	


// Reference Object section

if ($ttlRefObj > 0) {
	
	foreach ($RefObjList as $RefObj) { 

		$mapObjectArray = array();
		$RefObjectID = $RefObj['RefObjectID'];
		$TriggeringAttributeName = $RefObj['TriggeringAttributeName'];
		$MapMultipleAttributes = $RefObj['MapMultipleAttributes'];
		$Required = $RefObj['Required'];
		$TargetCIClassName = $RefObj['TargetCIClassName'];
		$SingleTargetAttributeName = $RefObj['SingleTargetAttributeName'];
		//$SingleTarget_ConstantValue = $RefObj['SingleTarget_ConstantValue'];
		$FieldType = $RefObj['FieldType'];

		$mapObjectArray  += [ "datatype" => $FieldType];
		$mapObjectArray  += [ "elementType" => 'attribute'];
		$mapObjectArray  += [ "name" => $TriggeringAttributeName];

		$mapObjectTargetsArray = array();
		$mapObjectTargetsContentArray = array();

		$appliedCIArray = array();
		$lastCI = '';
		foreach ($RefAppliedCIList as $CIMap) {
			if ($CIMap['RefObjectID'] == $RefObjectID) {
				if (!$CIMap['CIClassName'] == 0) {$CIClassName = stripslashes($CIMap['CIClassName']);} else {$CIClassName ='';}
				if ($lastCI != $CIClassName) {
					array_push($appliedCIArray, $CIClassName);
					$lastCI = $CIClassName;
				}
			}
		}

		$conditionArray = array();
		$conditionElseArray = array();
		$conditionInArray = array();
		$conditionThenArray = array();

		$conditionElseArray  += [ "mapType" => 'skip'];
		// foreach ($appliedCIArray as $applCI) {

		// 	}
		$choiceListOptionArray = array();
		//RefAttribList ttlRefAttribList RefObjectID, Source_Attribute, Target_Attribute, Target_ConstantValue, ExistsInRef
		foreach ($RefAttribList as $TarAttrib) {

			if (!$TarAttrib['Source_Attribute'] == 0) {$Source_Attribute = stripslashes($TarAttrib['Source_Attribute']);} else {$Source_Attribute ='';}
			if (!$TarAttrib['Target_Attribute'] == 0) {$Target_Attribute = stripslashes($TarAttrib['Target_Attribute']);} else {$Target_Attribute ='';}
			if (!$TarAttrib['Target_ConstantValue'] == 0) {$Target_ConstantValue = stripslashes($TarAttrib['Target_ConstantValue']);} else {$Target_ConstantValue ='';}

			$ExistsInRef = $TarAttrib['ExistsInRef'];
			if ($RefObjectID == $TarAttrib['RefObjectID']) {

				if ($Source_Attribute != '' && $Target_Attribute != '') {
					$choiceListOptionContentArray = array();
					$choiceListOptionContentArray  += [ "datatype" => $FieldType];
					$choiceListOptionContentArray  += [ "existingInRef" => $ExistsInRef > 0 ? true: false ];
					$choiceListOptionContentArray  += [ "sourceName" => $Source_Attribute];
					$choiceListOptionContentArray  += [ "targetName" => $Target_Attribute];

					array_push($choiceListOptionArray, $choiceListOptionContentArray);
				} else if ($Target_Attribute != '' && $Target_ConstantValue != '') {
					$choiceListOptionContentArray = array();
					$choiceListOptionContentArray  += [ "datatype" => $FieldType];
					$choiceListOptionContentArray  += [ "existingInRef" => $ExistsInRef > 0 ? true: false ];
					$choiceListOptionContentArray  += [ "targetName" => $Target_Attribute];
					$choiceListOptionContentArray  += [ "targetValue" => $Target_ConstantValue];

					array_push($choiceListOptionArray, $choiceListOptionContentArray);
				}

			} 
		}

		//$conditionThenArray  += [ "idAttribute" => $CIMappingDerivationIdAttribute];
		$conditionThenArray  += [ "datatype" => $FieldType];
		$conditionThenArray  += [ "endpoint" => $RefObjectsEndpoint];
		$conditionThenArray  += [ "mapType" => 'refObject'];
		$conditionThenArray  += [ "object" => $TargetCIClassName];
		$conditionThenArray  += [ "classAttribute" => $RefObjectsTargetClassAttribute];


		if ($MapMultipleAttributes == 1) {
			$conditionThenArray  += [ "attributes" => $choiceListOptionArray];
		} else {
			$conditionThenArray  += [ "name" => $SingleTargetAttributeName];
		}
		
		$conditionThenArray  += [ "required" => false ]; 
		$conditionThenArray  += [ "solution" => $Target_SysName];

		$conditionArray  += [ "else" => $conditionElseArray];
		$conditionArray  += [ "if" => $Details_sourceItemTypeAttribute];
		$conditionArray  += [ "in" => $appliedCIArray];
		

		$conditionArray  += [ "then" => $conditionThenArray];
		$mapObjectTargetsContentArray  += [ "condition" => $conditionArray];


		$mapObjectTargetsContentArray  += [ "datatype" => "string"];
		$mapObjectTargetsContentArray  += [ "elementType" => 'attributes'];
		$mapObjectTargetsContentArray  += [ "mapType" => "conditional"];
		$mapObjectTargetsContentArray  += [ "name" => $TargetItemTypeAttribute];
		
		$mapObjectTargetsContentArray  += [ "required" => false ]; //9/23/19 - change this to false to allow for multiple conditional mappings

		$mapObjectTargetsContentArray  += [ "solution" => $Target_SysName];



		array_push($mapObjectTargetsArray, $mapObjectTargetsContentArray);
		$mapObjectArray  += [ "targets" => $mapObjectTargetsArray];



		array_push($mapContentArray, $mapObjectArray);
	}

/*
$mapObjectArray = array();
	$mapObjectArray  += [ "datatype" => 'string'];
	$mapObjectArray  += [ "elementType" => 'attribute'];
	$mapObjectArray  += [ "name" => $Details_sourceItemTypeAttribute];

	$mapObjectTargetsArray = array();
	$mapObjectTargetsContentArray = array();

	$derivedCIArray = array();
	$lastCI = '';
	foreach ($CIMappingList as $CIMap) {
		if ($CIMap['CIDerivationID'] > 0) {
			if (!$CIMap['SourceClassName'] == 0) {$SourceClassName = stripslashes($CIMap['SourceClassName']);} else {$SourceClassName ='';}
			if ($lastCI != $SourceClassName) {
				array_push($derivedCIArray, $SourceClassName);
				$lastCI = $SourceClassName;
			}
		}
	}
*/

}



foreach ($relationshipList as $rel) {

		$mapObjectArray = array();
		$SourceParentCI = $rel['SourceParentCI'];
		$SourceRelationship = $rel['SourceRelationship'];
		$SourceChildCI = $rel['SourceChildCI'];
		$TargetParentCI = $rel['TargetParentCI'];
		$TargetRelationship = $rel['TargetRelationship'];
		$TargetChildCI = $rel['TargetChildCI'];
		//$UseSeparatePath = $rel['UseSeparatePath'];  // moved to configuation level for the entire map

		$TarParentDerived = $rel['TarParentDerived'];
		$TarChildDerived = $rel['TarChildDerived'];


		$mapObjectArray  += [ "childItemType" => $SourceChildCI];
		$mapObjectArray  += [ "elementType" => 'relationship'];
		$mapObjectArray  += [ "parentItemType" => $SourceParentCI];
		$mapObjectArray  += [ "sourceRelationshipType" => $SourceRelationship];

		$mapObjectTargetsArray = array();
		$mapObjectTargetsContentArray = array();
		$mapObjectEndPointArray = array();
		$mapObjectEndPointContentArray = array();
		$mapObjectEndPointIncludeArray = array();
		$mapObjectEndPointIncludeContentArray = array();

//$relationshipList SourceParentCI, SourceRelationship, SourceChildCI, TargetParentCI, TargetRelationship, TargetChildCI
		$mapObjectTargetsContentArray  += [ "childIdKey" => $TargetItemIdAttribute];

		$mapObjectEndPointIncludeContentArray  += [ "child" => true];
		$mapObjectEndPointIncludeContentArray  += [ "parent" => false];
		$mapObjectEndPointContentArray  += [ "include" => $mapObjectEndPointIncludeContentArray];

		$mapObjectEndPointContentArray  += [ "key" => "relationships"];
		$mapObjectEndPointContentArray  += [ "keyType" => "array"];
		$mapObjectEndPointContentArray  += [ "location" => "insideItems"];
		$mapObjectEndPointContentArray  += [ "type" => "parent"];
		//array_push($mapObjectEndPointContentArray, $mapObjectEndPointContentArray);

		$mapObjectTargetsContentArray  += [ "endpoint" => $mapObjectEndPointContentArray];

		//array_push($mapObjectTargetsContentArray, $mapObjectEndPointArray);

		$mapObjectTargetsContentArray  += [ "parentIdKey" => $TargetItemIdAttribute];
		$mapObjectTargetsContentArray  += [ "relationshipType" => $TargetRelationship];
		$mapObjectTargetsContentArray  += [ "solution" => $Target_SysName];
		$mapObjectTargetsContentArray  += [ "typeKey" => "type"];
		


		array_push($mapObjectTargetsArray, $mapObjectTargetsContentArray); // this may be in a loop if necessary


		$mapObjectArray  += [ "targets" => $mapObjectTargetsArray];

		//$mapObjectArray  += [ "UseSeparatePath" => $UseSeparatePath];
		//$mapObjectArray  += [ "Source" => $Source];

		array_push($mapContentArray, $mapObjectArray);

		if ($UseSeparatePath == 1 || $Source = 'udi-ucmdb') {
			// this is the CMS relationship mapping
			$mapObjectArray = array();
			// $SourceParentCI = $rel['SourceParentCI'];
			// $SourceRelationship = $rel['SourceRelationship'];
			// $SourceChildCI = $rel['SourceChildCI'];
			// $TargetParentCI = $rel['TargetParentCI'];
			// $TargetRelationship = $rel['TargetRelationship'];
			// $TargetChildCI = $rel['TargetChildCI'];
			// $UseSeparatePath = $rel['UseSeparatePath'];
			
			//$mapObjectArray  += [ "preTargetParentCI" => $TargetParentCI];
			//$mapObjectArray  += [ "preTargetChildCI" => $TargetChildCI];
			//$mapObjectArray  += [ "TarParentDerived" => $TarParentDerived];
			//$mapObjectArray  += [ "TarChildDerived" => $TarChildDerived];

			$parentLoopCnt = 0;
			if ($TarParentDerived > 0) {
				foreach ($CIMappingList as $CIMap) {
					if ($CIMap['TargetClassName'] == $TargetParentCI && $CIMap['ParentClassName'] != '') {
						$TargetParentCI = $CIMap['ParentClassName'];
						break;
					}
					$parentLoopCnt ++;
				}
			}

			$childLoopCnt = 0;
			if ($TarChildDerived > 0) {
				foreach ($CIMappingList as $CIMap) {
					if ($CIMap['TargetClassName'] == $TargetChildCI && $CIMap['ParentClassName'] != '') {
						$TargetChildCI = $CIMap['ParentClassName'];
						break;
					}
					$childLoopCnt ++;
				}
			}
			//$mapObjectArray  += [ "parentLoopCnt" => $parentLoopCnt];
			//$mapObjectArray  += [ "postTargetParentCI" => $TargetParentCI];
			//$mapObjectArray  += [ "childLoopCnt" => $childLoopCnt];
			//$mapObjectArray  += [ "postTargetChildCI" => $TargetChildCI];

			//$mapObjectArray  += [ "UseSeparatePath" => $UseSeparatePath];
			//$mapObjectArray  += [ "Source" => $Source];

			$mapObjectArray  += [ "childItemType" => $SourceChildCI];
			$mapObjectArray  += [ "elementType" => 'noTopRelationship'];
			$mapObjectArray  += [ "parentItemType" => $SourceParentCI];
			$mapObjectArray  += [ "relationshipType" => $SourceRelationship];

			$mapObjectTargetsArray = array();
			$mapObjectTargetsContentArray = array();
			$mapObjectEndPointArray = array();
			$mapObjectEndPointContentArray = array();
			$mapObjectEndPointIncludeArray = array();
			$mapObjectEndPointIncludeContentArray = array();

			$mapObjectTargetsContentArray  += [ "childIdKey" => 'child_correlation_id'];
			$mapObjectTargetsContentArray  += [ "childType" => $TargetChildCI];
			$mapObjectTargetsContentArray  += [ "childTypeKey" => 'childsysclassname'];

			$mapObjectEndPointIncludeContentArray  += [ "child" => true];
			$mapObjectEndPointIncludeContentArray  += [ "parent" => true];
			$mapObjectEndPointContentArray  += [ "include" => $mapObjectEndPointIncludeContentArray];

			$mapObjectEndPointContentArray  += [ "key" => "relationships"];
			$mapObjectEndPointContentArray  += [ "keyType" => "array"];
			$mapObjectEndPointContentArray  += [ "location" => "outsideItems"];
			$mapObjectEndPointContentArray  += [ "type" => "parent"];
			//array_push($mapObjectEndPointContentArray, $mapObjectEndPointContentArray);

			$mapObjectTargetsContentArray  += [ "endpoint" => $mapObjectEndPointContentArray];

			//array_push($mapObjectTargetsContentArray, $mapObjectEndPointArray);

			$mapObjectTargetsContentArray  += [ "parentIdKey" => "parent_correlation_id"];
			$mapObjectTargetsContentArray  += [ "parentType" => $TargetParentCI];
			$mapObjectTargetsContentArray  += [ "parentTypeKey" => "parentsysclassname"];
			$mapObjectTargetsContentArray  += [ "relationshipType" => $TargetRelationship];
			$mapObjectTargetsContentArray  += [ "solution" => $Target_SysName];
			$mapObjectTargetsContentArray  += [ "typeKey" => "relationshipname"];
			

			array_push($mapObjectTargetsArray, $mapObjectTargetsContentArray); // this may be in a loop if necessary


			$mapObjectArray  += [ "targets" => $mapObjectTargetsArray];

			array_push($mapContentArray, $mapObjectArray);


		} 

	}



	$data  += [ "map" => $mapContentArray ];
	$data  += [ "solution" => $Solution ];
	$data  += [ "source" => $Source ];

	$targetDetailsArray = array();
	$targetSystemArray = array();
	$targetSystemContentArray = array();
	$targetConnectionArray = array();
	$targetConnectionContentArray = array();
	$targetFilterArray = array();
	$targetFilterContentArray = array();
	$targetFilterMatchingValuesArray = array();
	$targetIncludeArray = array();


//environmentresult  Environment, XCDEVariable, EnvironmentID
	try {     
		$test = sqlsrv_has_rows($environmentresult);
		
		if ($test) {
			while($row = sqlsrv_fetch_array($environmentresult)) {
				$EnvironmentID = $row['EnvironmentID'];
				
				$targetConnectionEnvContent = array();

   							//$connectionList  ConnectionID, ConnectionName, EnvironmentID, BasePath, Host, 
							//Method, Port, Pwd, Type, ConnectionUse, Header_json
							//relsHeader_json, relsHost, relsMethod, relsPort, relsPwd, relsType, 
                         	//relsConnectionUser, ComplexRelsConnection

				foreach ($connectionList as $connDetails) {

						if ($connDetails['EnvironmentID'] == $EnvironmentID) {
							// how are relationships handled? 0=no 1=simple rels path 2=complex
							$ComplexRelsConnection = $connDetails['ComplexRelsConnection'];
							$isAsync = $connDetails['isAsync'];
							if ($ComplexRelsConnection == 2) {
								//full breakout of items vs relationship connection
								$targetConnectionEnvContent_Items = array();
								$targetConnectionEnvContent_Rels = array();

								//items first
								$targetConnectionEnvContent_Items  += [ "basePath" => stripslashes($connDetails['BasePath'])];
								
								$headerArray = array();
								$headerContentArray = array();

								$json = json_decode(trim(stripslashes($connDetails['Header_json'])), true);
								foreach ($json as $key => $value) {
									$headerContentArray  += [ $key => $value];
								}
								$targetConnectionEnvContent_Items  += [ "headers" => $headerContentArray];

								$targetConnectionEnvContent_Items  += [ "host" => trim($connDetails['Host'])];
								$targetConnectionEnvContent_Items  += [ "method" => trim($connDetails['Method'])];
								$targetConnectionEnvContent_Items  += [ "port" => trim($connDetails['Port'])];
								$targetConnectionEnvContent_Items  += [ "pwd" => trim($connDetails['Pwd'])];
								$targetConnectionEnvContent_Items  += [ "type" => trim($connDetails['Type'])];
								$targetConnectionEnvContent_Items  += [ "user" => trim($connDetails['ConnectionUser'])];


								//relationships
								$targetConnectionEnvContent_Rels  += [ "basePath" => stripslashes($connDetails['relsPath'])];
								
								$headerArray = array();
								$headerContentArray = array();

								$json = json_decode(trim(stripslashes($connDetails['relsHeader_json'])), true);
								foreach ($json as $key => $value) {
									$headerContentArray  += [ $key => $value];
								}
								$targetConnectionEnvContent_Rels  += [ "headers" => $headerContentArray];

								$targetConnectionEnvContent_Rels  += [ "host" => trim($connDetails['relsHost'])];
								$targetConnectionEnvContent_Rels  += [ "method" => trim($connDetails['relsMethod'])];
								$targetConnectionEnvContent_Rels  += [ "port" => trim($connDetails['relsPort'])];
								$targetConnectionEnvContent_Rels  += [ "pwd" => trim($connDetails['relsPwd'])];
								$targetConnectionEnvContent_Rels  += [ "type" => trim($connDetails['relsType'])];
								$targetConnectionEnvContent_Rels  += [ "user" => trim($connDetails['relsConnectionUser'])];

								//combine results
								if ($isAsync == 1) {
									$targetConnectionEnvContent  += [ "isAsync" => true];
								}
								$targetConnectionEnvContent  += [ "items" => $targetConnectionEnvContent_Items];
								$targetConnectionEnvContent  += [ "relationships" => $targetConnectionEnvContent_Rels];



							} else {
								// simple connection with possible relspath value
								if ($isAsync == 1) {
									$targetConnectionEnvContent  += [ "isAsync" => true];
								}
								$targetConnectionEnvContent  += [ "basePath" => stripslashes($connDetails['BasePath'])];
								if ($ComplexRelsConnection = 1 && $connDetails['relsPath'] !='' && $connDetails['relsPath'] !='0') {
									$targetConnectionEnvContent  += [ "relsPath" => stripslashes($connDetails['relsPath'])];
								}
								
								$headerArray = array();
								$headerContentArray = array();

								$json = json_decode(trim(stripslashes($connDetails['Header_json'])), true);
								//echo $json. '<br />';
								foreach ($json as $key => $value) {
								    
									//array_push($headerArray, $connDetails['Header_json']);
									$headerContentArray  += [ $key => $value];

								    // if (!is_array($value)) {
								    //     echo $key . '=>' . $value . '<br />';

								    // } else {
								    //     foreach ($value as $key => $val) {
								    //         echo $key . '=>' . $val . '<br />';
								    //     }
								    // }
								}



								//array_push($headerArray, $connDetails['Header_json']);
								//json_encode($connDetails['Header_json'])

								// 5/15/19 THIS DID WORK but give double square brackets
								//array_push($headerArray, $headerContentArray);
								//$targetConnectionEnvContent  += [ "headers" => $headerArray];
								$targetConnectionEnvContent  += [ "headers" => $headerContentArray];

								//$targetConnectionEnvContent  += [ "headers" => $connDetails['Header_json']];

								$targetConnectionEnvContent  += [ "host" => trim($connDetails['Host'])];
								$targetConnectionEnvContent  += [ "method" => trim($connDetails['Method'])];
								$targetConnectionEnvContent  += [ "port" => trim($connDetails['Port'])];
								$targetConnectionEnvContent  += [ "pwd" => trim($connDetails['Pwd'])];
								$targetConnectionEnvContent  += [ "type" => trim($connDetails['Type'])];
								$targetConnectionEnvContent  += [ "user" => trim($connDetails['ConnectionUser'])];


							}

						} // end simple connection

					}

				$targetConnectionContentArray += [ $row['XCDEVariable'] => $targetConnectionEnvContent ];


			}                                
		}
		
		
	} catch (exception $e) {
		print_r($e);
	}

	sqlsrv_free_stmt($environmentresult);


	$targetConnectionArray  += [ "connection" => $targetConnectionContentArray ];
	

	$targetFilterContentArray += [ "attribute" => $DefaultSourceAttribute ];
	$targetFilterContentArray += [ "condition" => "include" ];  // look for attribute to control this
	$targetFilterMatchingValuesContentArray = array();
	try {     
		$test = sqlsrv_has_rows($matchingValueresult);
		
		if ($test) {
			while($row = sqlsrv_fetch_array($matchingValueresult)) {

				//$targetFilterMatchingValuesContentArray += $row['ClassName'];
				array_push($targetFilterMatchingValuesContentArray, $row['ClassName']);

			}                                
		}
		
		
	} catch (exception $e) {
		print_r($e);
	}

	sqlsrv_free_stmt($matchingValueresult);


	$targetFilterContentArray  += [ "matchingValues" => $targetFilterMatchingValuesContentArray ];


	$targetFilterArray  += [ "filter" => $targetFilterContentArray ];


	$targetIncludeContentArray = array(); 
	$targetIncludeContentArray  += [ "idAtRoot" => $Include_idAtRoot ];
	$targetIncludeContentArray  += [ "nameAtRoot" => $Include_nameAtRoot ];
	$targetIncludeContentArray  += [ "otherAtRoot" => $Include_otherAtRoot ];

	$targetIncludeArray  += [ "include" => $targetIncludeContentArray ];

	$targetSystemContentArray  += $targetConnectionArray;
	$targetSystemContentArray  += $targetFilterArray;
	$targetSystemContentArray  += $targetIncludeArray;
	$targetSystemContentArray  += [ "targetItemIdAttribute" => $TargetItemIdAttribute ];
	$targetSystemContentArray  += [ "targetItemNameAttribute" => $TargetItemNameAttribute ];
	$targetSystemContentArray  += [ "targetItemTypeAttribute" => $TargetItemTypeAttribute ];

	$targetSystemContentArray  += [ "maxAttempts" => $MaxAttempts ];
	$targetSystemContentArray  += [ "retryDelay" => $RetryDelay ];




	$targetSystemArray  += [ $Target_SysName => $targetSystemContentArray ];


	$targetDetailsArray += $targetSystemArray;

	$data  += [ "targetDetails" => $targetDetailsArray ];

	$data  += [ "usecase" => $UseCase ];

	$data  += [ "version" => $Version ];


	//echo 'begin build ';
	/*
	$ElementName = '';
	$ElementDescription = '';
	$ParentElementID = 0;
	foreach ($ElementList as $Element) {
		//this is the top level section
			$ElementID = $Element['ElementID'];
			$ParentElementID = $Element['ParentElementID'];

			if ($ParentElementID == $RootElementID) {
				//build parent section
				//echo 'Element '.$ElementID.'\r\n';
				//array_push($data, getChildElement_array($ElementID));



			}


		}


	if ($RootElementTypeID > 2) {
		$nestData = $data;
		//array_push($data, array());
		$data = array();
		array_push($data, $nestData);
		//echo 'xxxxxxxyy';
	}
	*/
	//echo 'asdf '.$RootElementTypeID;

//exit;

?>

