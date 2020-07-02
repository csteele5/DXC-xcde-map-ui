<?php

/**
*<!-------------------------------------------------- 
*|	 Name: Charles Steele                          |
*|	 Payroll Number: cs13514                       |
*|	 E-mail: csteele5@dxc.com                      |
*|	 Phone: 310-321-8776                           |
*| 	 Date Created: 10/31/19 					   |
*--------------------------------------------------->
*<!-------------------------------------------------------------------------
*	10/31/19 - export discoverty template for selected CIs to XLS
*---------------------------------------------------------------------------->		
*/
session_start();
ini_set('memory_limit', '512M');
ini_set('max_execution_time', 300);
include '../dbconn.inc';


if (isset($_POST['MapTemplateID'])) {
	$MapTemplateID = $_POST['MapTemplateID'];
} else if (isset($_GET['MTID'])) {
	$MapTemplateID = $_GET['MTID']; 
} else {
	$MapTemplateID = 0; 
}



$currentUser = $_SESSION['userID'];


$sheetIndex = 0;
$sheetName = '';

$MapSegmentID = 0;
$TemplatePrefix = '';

$query = "SELECT        MapTemplateID, MapSegmentID, TemplatePrefix
			FROM            XCDE_MapTemplate 
			WHERE MapTemplateID = ?";


$params = array($MapTemplateID);	

$result = sqlsrv_query($conn,$query,$params);	

if ($result === false ) {
	 die( print_r( sqlsrv_errors(), true));
}

try {	  
	$test = sqlsrv_has_rows($result);
				
	if ($test) {
		while($row = sqlsrv_fetch_array($result)) {
			$TemplatePrefix = stripslashes($row['TemplatePrefix']);
			$MapSegmentID = $row['MapSegmentID'];
		}			
	} 								

} catch (exception $e) {
	print_r($e);
}

sqlsrv_free_stmt($result);


$query = "SELECT        SourceClassName
			FROM            XCDE_MapTemplate_CI
			WHERE MapTemplateID = $MapTemplateID
			ORDER BY SourceClassName";


$TemplateCIList = array();
$ttlTemplateCI = 0; 

$result = sqlsrv_query($conn,$query);	
	
if ($result === false ) {
	 die( print_r( sqlsrv_errors(), true));
}

try {	  
	$test = sqlsrv_has_rows($result);
				
	if ($test) {
		while($row = sqlsrv_fetch_array($result)) {

	        //$TemplateCIList[] = $row;
	        array_push($TemplateCIList, $row['SourceClassName']);
	        $ttlTemplateCI ++;

		}			
	} 								

} catch (exception $e) {
	print_r($e);
}

sqlsrv_free_stmt($result);	
//echo 'MapSegmentID '.$MapSegmentID.' MapTemplateID '.$MapTemplateID;exit;

					// temp values
					// $TemplateCIList = array('webapplication','jdbcdatasource','tomcatservice','jvm','webvirtualhost','tomcatcluster','configuration_document','ip_service_endpoint','tomcat','database');
					// $ttlTemplateCI = 2;
					// $MapSegmentID = 1;



$outputFileName = $TemplatePrefix.'_Discovery_and_Mapping_Template_'.$currentUser.'.xlsx';


// get all source CIs for matching against template CI list

	$query = "SELECT     XCDE_DataMap_CI_Mapping.CIMappingID, XCDE_DataMap_CI_Mapping.MapID, XCDE_DataMap_CI_Mapping.Source_CI_ID, XCDE_DataMap_CI_Mapping.Target_CI_ID, 
                         XCDE_DataMap_CI_Mapping.Source_CI_Condition_Code, XCDE_DataMap_CI_Mapping.RequestorMappingNotes, XCDE_DataMap_CI_Mapping.ProgrammerMappingNotes, 
                         SourceCI.ClassName AS SourceClassName, SourceCI.DisplayName AS SourceDisplayName, TargetCI.ClassName AS TargetClassName, TargetCI.DisplayName AS TargetDisplayName, 
                         XCDE_DataMap_CI_Mapping_Derivation.MapTypeID AS Derivation_MapTypeID, XCDE_DataMap_CI_Mapping_Derivation.IdAttribute AS Derivation_IdAttribute, XCDE_DataMap_MapType.MapType AS Derivation_MapType, 
                         XCDE_DataMap_MapType.CodeValue AS Derivation_MapTypeCodeValue, XCDE_DataMap_CI_Mapping.CIDerivationID,
                         dbo.fnXCDERelCount(SourceCI.ClassName,XCDE_DataMap_CI_Mapping.MapSegmentID,1) AS SourceAsParentRelCnt, dbo.fnXCDERelCount(SourceCI.ClassName,XCDE_DataMap_CI_Mapping.MapSegmentID,0) AS SourceAsChildRelCnt, XCDE_DataMap_CI_Mapping.Inactive
				FROM            XCDE_DataMap_MapType RIGHT OUTER JOIN
                         XCDE_DataMap_CI_Mapping_Derivation ON XCDE_DataMap_MapType.MapTypeID = XCDE_DataMap_CI_Mapping_Derivation.MapTypeID RIGHT OUTER JOIN
                         XCDE_DataMap_CI_Mapping ON XCDE_DataMap_CI_Mapping_Derivation.CIDerivationID = XCDE_DataMap_CI_Mapping.CIDerivationID LEFT OUTER JOIN
                         XCDE_DataMap_CI AS TargetCI ON XCDE_DataMap_CI_Mapping.Target_CI_ID = TargetCI.CI_ID LEFT OUTER JOIN
                         XCDE_DataMap_CI AS SourceCI ON XCDE_DataMap_CI_Mapping.Source_CI_ID = SourceCI.CI_ID
				WHERE        (XCDE_DataMap_CI_Mapping.MapSegmentID = $MapSegmentID) ";

	//echo $query; exit;	

    $CIList = array();
	$ttlCI = 0; 
	
	$result = sqlsrv_query($conn,$query);	
		
	if ($result === false ) {
		 die( print_r( sqlsrv_errors(), true));
	}

	try {	  
		$test = sqlsrv_has_rows($result);
					
		if ($test) {
			while($row = sqlsrv_fetch_array($result)) {

		        $CIList[] = $row;

		        $ttlCI ++;

			}			
		} 								

	} catch (exception $e) {
		print_r($e);
	}

	sqlsrv_free_stmt($result);



	$query = "SELECT        AttribList_ID, CIDerivationID, SourceValue, TargetValue
				FROM            XCDE_DataMap_Attribute_Mapping_List
				WHERE        (CIDerivationID > 0)";


    $CIDerivationList = array();
	$ttlCIDerivation = 0; 
	
	$result = sqlsrv_query($conn,$query);	
		
	if ($result === false ) {
		 die( print_r( sqlsrv_errors(), true));
	}

	try {	  
		$test = sqlsrv_has_rows($result);
					
		if ($test) {
			while($row = sqlsrv_fetch_array($result)) {

		        $CIDerivationList[] = $row;

		        $ttlCIDerivation ++;

			}			
		} 								

	} catch (exception $e) {
		print_r($e);
	}

	sqlsrv_free_stmt($result);	


// get all attributes for the map segment 

	$query = "SELECT        XCDE_DataMap_Attribute_Mapping.Source_Attrib_ID, XCDE_DataMap_Attribute_Mapping.Target_Attrib_ID, XCDE_DataMap_Attribute_Mapping.MapTypeID, 
	                     XCDE_DataMap_Attribute_Mapping.Source_Attrib_Condition_Code, XCDE_DataMap_Attribute_Mapping.RequestorMappingNotes, XCDE_DataMap_Attribute_Mapping.ProgrammerMappingNotes, 
	                     XCDE_DataMap_MapType.MapType, SourceAttrib.AttributeName AS SourceAttributeName, SourceAttrib.DisplayName AS SourceDisplayName, SourceAttrib.FieldType AS SourceFieldType, 
	                     TargetAttrib.AttributeName AS TargetAttributeName, TargetAttrib.DisplayName AS TargetDisplayName, TargetAttrib.FieldType AS TargetFieldType, TargetAttrib.Required, 
	                     XCDE_DataMap_Attribute_Mapping.AttribMappingID, XCDE_DataMap_Attribute_Mapping.AttributeDerivationID, XCDE_DataMap_Attribute_Mapping.Source_ConstantValue,
	                     XCDE_DataMap_Attribute_Mapping.MatchRelType, XCDE_DataMap_Attribute_Mapping.RelatedCIIsParent,
	                     XCDE_DataMap_Attribute_Mapping.AttributeScope, XCDE_DataMap_Attribute_Mapping.ElseSourceAttribute, XCDE_DataMap_Attribute_Mapping.ReverseCondition,
	                     XCDE_DataMap_Attribute_Mapping.FalseConditionAction, XCDE_DataMap_Attribute_Mapping.Target_ReferenceCI,
	                         TargetAttrib.FieldFormat AS TargetFieldFormat, XCDE_DataMap_Attribute_Mapping.Inactive
				FROM            XCDE_DataMap_MapType RIGHT OUTER JOIN
	                     XCDE_DataMap_Attribute AS TargetAttrib RIGHT OUTER JOIN
	                     XCDE_DataMap_Attribute_Mapping ON TargetAttrib.Attrib_ID = XCDE_DataMap_Attribute_Mapping.Target_Attrib_ID ON 
	                     XCDE_DataMap_MapType.MapTypeID = XCDE_DataMap_Attribute_Mapping.MapTypeID LEFT OUTER JOIN
	                     XCDE_DataMap_Attribute AS SourceAttrib ON XCDE_DataMap_Attribute_Mapping.Source_Attrib_ID = SourceAttrib.Attrib_ID
				WHERE        (XCDE_DataMap_Attribute_Mapping.MapSegmentID = ?)
				ORDER BY TargetAttributeName";

	$params = array($MapSegmentID);	


	$MapList = array();
	$ttlMap = 0; 

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

		        $MapList[] = $row;

		        $ttlMap ++;

			}			
		} 								

	} catch (exception $e) {
		print_r($e);
	}

	sqlsrv_free_stmt($result);



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



	$query = "SELECT        XCDE_DataMap_Attribute_Mapping_AttribValList.AttribValList_ID, XCDE_DataMap_Attribute_Mapping_AttribValList.					AttribMappingID, XCDE_DataMap_Attribute_Mapping_AttribValList.SourceValue
				FROM            XCDE_DataMap_Attribute_Mapping_AttribValList INNER JOIN
	                     XCDE_DataMap_Attribute_Mapping ON XCDE_DataMap_Attribute_Mapping_AttribValList.AttribMappingID = XCDE_DataMap_Attribute_Mapping.AttribMappingID
				WHERE        (XCDE_DataMap_Attribute_Mapping.MapSegmentID = $MapSegmentID)
				ORDER BY XCDE_DataMap_Attribute_Mapping_AttribValList.SourceValue";
				 
	$result = sqlsrv_query($conn,$query); 

	$attribValListList = array();
	$ttlAttribValList = 0; 

		
	if ($result === false ) {
		 die( print_r( sqlsrv_errors(), true));
	}

	try {	  
		$test = sqlsrv_has_rows($result);
					
		if ($test) {
			while($row = sqlsrv_fetch_array($result)) {

		        $attribValListList[] = $row;

		        $ttlAttribValList ++;

			}			
		} 								

	} catch (exception $e) {
		print_r($e);
	}

	sqlsrv_free_stmt($result);


/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2014 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.8.0, 2014-03-02
 */

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Charles Steele")
							 ->setLastModifiedBy("Charles Steele")
							 ->setTitle("Office XLS Discovery template")
							 ->setSubject("Office XLS Discovery template Document")
							 ->setDescription("Discovery template Document for Office xls, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Discovery template result file");





if ($ttlTemplateCI > 0) {
	
	foreach ($TemplateCIList as $templateCI) {

		if ($sheetIndex > 0) {
			// Create a new worksheet, after the default sheet
			$objPHPExcel->createSheet();
		}

		// set up columns
		$colNum = 0;

		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('10');
		$objPHPExcel->setActiveSheetIndex($sheetIndex)->setCellValueByColumnAndRow($colNum, 1, 'Required for Monitoring');
		$colNum++; 
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('10');
		$objPHPExcel->setActiveSheetIndex($sheetIndex)->setCellValueByColumnAndRow($colNum, 1, 'Required for Operations');
		$colNum++; 
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('20');
		$objPHPExcel->setActiveSheetIndex($sheetIndex)->setCellValueByColumnAndRow($colNum, 1, 'Technical Name');
		$colNum++; 
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('20');
		$objPHPExcel->setActiveSheetIndex($sheetIndex)->setCellValueByColumnAndRow($colNum, 1, 'Technical Type');
		$colNum++;
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('30'); 
		$objPHPExcel->setActiveSheetIndex($sheetIndex)->setCellValueByColumnAndRow($colNum, 1, 'Name');
		$colNum++; 
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('10');
		$objPHPExcel->setActiveSheetIndex($sheetIndex)->setCellValueByColumnAndRow($colNum, 1, 'Type');
		$colNum++; 
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('50');
		$objPHPExcel->setActiveSheetIndex($sheetIndex)->setCellValueByColumnAndRow($colNum, 1, 'Value');
		$colNum++; 
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('30'); 
		$objPHPExcel->setActiveSheetIndex($sheetIndex)->setCellValueByColumnAndRow($colNum, 1, 'ServiceNow Technical Name');
		$colNum++; 
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('10');
		$objPHPExcel->setActiveSheetIndex($sheetIndex)->setCellValueByColumnAndRow($colNum, 1, 'In ServiceNow?');
		$colNum++; 
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('50');
		$objPHPExcel->setActiveSheetIndex($sheetIndex)->setCellValueByColumnAndRow($colNum, 1, 'Comments');
		$colNum++;
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('50');
		$objPHPExcel->setActiveSheetIndex($sheetIndex)->setCellValueByColumnAndRow($colNum, 1, 'Mapping Comments');
		$colNum++; 
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
		$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('50');
		$objPHPExcel->setActiveSheetIndex($sheetIndex)->setCellValueByColumnAndRow($colNum, 1, 'Validation Comments');
		$colNum++; 


		$objPHPExcel->getActiveSheet()
		    ->getStyle('A1:L1')
		    ->getFill()
		    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
		    ->getStartColor()
		    ->setARGB('ffb3b3');


		$sheetRowNum = 2;

		$query = '';	

		$params = array($templateCI);

		$query = "SELECT        RecordID, CIClass, DisplayLabel, AttributeName, AttributeDisplayLabel, AttributeType, AttributeDescription, LastUpdated, 
								RequiredforMonitoring, RequiredforOperations, Type, Value, ServiceNowTechnicalName, InServiceNow
					FROM            XCDE_UCMDB_class_attributes
					WHERE        (CIClass = ?) AND (ExcludeFromTemplate <> 1)
					ORDER BY AttributeName ";

		//echo '<br>'.$query; //exit;	

	    $CIAttributeList = array();
		$ttlCIAttribute = 0; 
		
		$result = sqlsrv_query($conn,$query,$params);	
			
		if ($result === false ) {
			 die( print_r( sqlsrv_errors(), true));
		}

		try {	  
			$test = sqlsrv_has_rows($result);
						
			if ($test) {
				while($row = sqlsrv_fetch_array($result)) {

			        $CIAttributeList[] = $row;

			        $ttlCIAttribute ++;

				}			
			} 								

		} catch (exception $e) {
			print_r($e);
		}

		sqlsrv_free_stmt($result);


		$SheetTitle = $templateCI;
		$attribCount = 0;

		foreach ($CIAttributeList as $CIAttribute) {
			if ($attribCount == 0) {
				$SheetTitle = $CIAttribute['DisplayLabel'];
			}
			$colNum = 0;

			$ValA = stripslashes($CIAttribute['RequiredforMonitoring']);
			$ValB = stripslashes($CIAttribute['RequiredforOperations']);
			$ValC = stripslashes($CIAttribute['AttributeName']);
			$ValD = stripslashes($CIAttribute['AttributeType']);
			$ValE = stripslashes($CIAttribute['AttributeDisplayLabel']);
			$ValF = stripslashes($CIAttribute['Type']);
			$ValG = stripslashes($CIAttribute['Value']);
			//$ValH = $CIAttribute['ServiceNowTechnicalName']; // temp value until map comparison takes place
			$ValH = '';
			//$ValI = stripslashes($CIAttribute['InServiceNow']); // temp value until map comparison takes place - new field must be added to UI
			$ValI = '';
			$ValJ = stripslashes($CIAttribute['AttributeDescription']);
			$ValK = '';
			$ValL = '';

			// figure out what the root class should be mapped to for the source CI
			$SourceCIValue = '';
			$TargetCIValue = ''; // possibly a list if more than one
			$MappingNotes = '';  // for use if there are conditional considerations
			if ($ValC == 'root_class' && $ValG != '') {
				$SourceCIValue = $ValG;
				$matchingVal = 0;
				foreach ($CIList as $row) {
					
					$testValue = '';
					if (!$row['SourceClassName'] == 0) {$testValue = stripslashes($row['SourceClassName']);} else {$testValue ='';}
					if (!$row['TargetClassName'] == 0) {$TargetClassName = stripslashes($row['TargetClassName']);} else {$TargetClassName ='';}
					if (!$row['Derivation_IdAttribute'] == 0) {$Derivation_IdAttribute = stripslashes($row['Derivation_IdAttribute']);} else {$Derivation_IdAttribute ='';}
					$Derivation_MapTypeID = $row['Derivation_MapTypeID'];
					$CIDerivationID = $row['CIDerivationID'];

					if ($testValue == $SourceCIValue) {
						if ($matchingVal > 0) {
							$TargetCIValue .= ' ';
						}
						$TargetCIValue .= $TargetClassName;
						$matchingVal++;

						if ($Derivation_MapTypeID > 0) {
							$subDisplaySource = '';
							if ($Derivation_MapTypeID == 3) {
								$subDisplaySource = $TargetClassName.', when '.$Derivation_IdAttribute.' is: ';
								$itemSeparator = '';

								// loop through options and add applicable to display
								foreach ($CIDerivationList as $derivationrow) {

									$testCIDerivationID = $derivationrow['CIDerivationID'];

									if (!$derivationrow['SourceValue'] == 0) {$testSourceValue = stripslashes($derivationrow['SourceValue']);} else {$testSourceValue ='';}
									if (!$derivationrow['TargetValue'] == 0) {$testTargetValue = stripslashes($derivationrow['TargetValue']);} else {$testTargetValue ='';}
									
									if ($testCIDerivationID == $CIDerivationID && $testTargetValue == $row['TargetClassName']) {
										$subDisplaySource .= $itemSeparator.''.$testSourceValue.'';
										$itemSeparator = ' | ';

									}
								}

							
							}
							if ($MappingNotes != '') {
								$MappingNotes .= ' ';
							}
							$MappingNotes .= $subDisplaySource;
						}


					}


				}

				if ($TargetCIValue != '') {
					$ValH = $TargetCIValue;
				}else {
					$ValH = 'CI NOT MAPPED!';
				}
				if ($MappingNotes != '') {
					$ValK = $MappingNotes;
				}
			}

			// now figure out what the service now value will be, if this is not the root class (not already mapped)
			if ($ValH == '') {
				$testSourceCI = stripslashes($CIAttribute['CIClass']);
				$testSourceAttrib = $ValC;
				//$ValH = 'test for match with '.$ValC;
				// get straight up mapping
				// get concatenated mapping and look for match to source
				// get conditional mapping and look for match to source
				 //start with source attribute and common
				if ($ttlMap > 0) {
					$mapMatch = 0;
					//$ValH = 'test '.$ttlMap.' for match with '.$ValC;
					//create the unsorted array of attributes
					foreach ($MapList as $row) {
					
						$AttribMappingID = $row['AttribMappingID'];


						$Source_Attrib_ID = $row['Source_Attrib_ID'];
						$Target_Attrib_ID = $row['Target_Attrib_ID'];
						$MapTypeID = $row['MapTypeID'];
						
						$MapType = $row['MapType'];
						$SourceFieldType = $row['SourceFieldType'];
						$TargetFieldType = $row['TargetFieldType'];
						$Required = $row['Required'];
						$ReverseCondition = $row['ReverseCondition'];
						$FalseConditionAction = $row['FalseConditionAction'];
						if (!$row['ElseSourceAttribute'] == 0) {
							$ElseSourceAttribute = stripslashes($row['ElseSourceAttribute']);
						} else {
							$ElseSourceAttribute ='';
						}
						$displayAttributeCondition ='';
						if (!$row['TargetFieldFormat'] == 0) {$TargetFieldFormat = stripslashes($row['TargetFieldFormat']);} else {$TargetFieldFormat ='';}
						if (!$row['Target_ReferenceCI'] == 0) {$Target_ReferenceCI = stripslashes($row['Target_ReferenceCI']);} else {$Target_ReferenceCI ='';}

						//if (!$row['RequestorMappingNotes'] == 0) {$RequestorMappingNotes = stripslashes($row['RequestorMappingNotes']);} else {$RequestorMappingNotes ='';}
						//if (!$row['ProgrammerMappingNotes'] == 0) {$ProgrammerMappingNotes = stripslashes($row['ProgrammerMappingNotes']);} else {$ProgrammerMappingNotes ='';}

						//if (!$row['Source_CIName'] == 0) {$Source_CIName = stripslashes($row['Source_CIName']);} else {$Source_CIName ='';}
						if (!$row['SourceAttributeName'] == 0) {
							$SourceAttributeName = stripslashes($row['SourceAttributeName']);
							$SimpleSourceAttributeName = stripslashes($row['SourceAttributeName']);
						} else {
							$SourceAttributeName ='';
							$SimpleSourceAttributeName ='';
						}
						if (!$row['SourceDisplayName'] == 0) {$SourceDisplayName = stripslashes($row['SourceDisplayName']);} else {$SourceDisplayName ='';}
						if (!$row['SourceFieldType'] == 0) {$SourceFieldType = stripslashes($row['SourceFieldType']);} else {$SourceFieldType ='';}

						//if (!$row['Target_CIName'] == 0) {$Target_CIName = stripslashes($row['Target_CIName']);} else {$Target_CIName ='';}
						if (!$row['TargetAttributeName'] == 0) {$TargetAttributeName = stripslashes($row['TargetAttributeName']);} else {$TargetAttributeName ='';}
						if (!$row['TargetDisplayName'] == 0) {$TargetDisplayName = stripslashes($row['TargetDisplayName']);} else {$TargetDisplayName ='';}
						if (!$row['TargetFieldType'] == 0) {$TargetFieldType = stripslashes($row['TargetFieldType']);} else {$TargetFieldType ='';}

						if (!$row['Source_ConstantValue'] == 0) {$Source_ConstantValue = stripslashes($row['Source_ConstantValue']);} else {$Source_ConstantValue ='';}
							
						//echo '<br>'.$TargetAttributeName.' '.$TargetDisplayName.' '.$TargetFieldType.' ';


						//Check for Derived Attribute for this mapping
						$ttlThisDerivedAttribute = 0;
							foreach ($DerivedAttributeList as $DerAttr) {

								if ($DerAttr['AttribMappingID'] == $AttribMappingID) {
									$SourceAttributeName .= $DerAttr['Condition'].'==>>'.$DerAttr['Attribute']."\r";
									$ttlThisDerivedAttribute++;
								}

							}
						if ($ttlThisDerivedAttribute > 0) {
							$SourceAttributeName = "From Related CI\r".$SourceAttributeName;
						}


						if ($MapTypeID == 5) {
							$SourceAttributeName = '"'.$Source_ConstantValue.'"';
						} else if ($MapTypeID == 4) {
							$ttlThisConcatValue = 0;
							$dispConcatVal = '';
							$dispValueType = '';
							$dispValue = '';
							foreach ($concatValueList as $ConcatVal) {

								if ($ConcatVal['AttribMappingID'] == $AttribMappingID) {
									$AttribConcat_ID = $ConcatVal['AttribConcat_ID'];
									$Constant = $ConcatVal['Constant'];
									if (!$ConcatVal['ConstantValue'] == 0) {$ConstantValue = stripslashes($ConcatVal['ConstantValue']);} else {$ConstantValue ='';}
									$RelatedClass = $ConcatVal['RelatedClass'];
									if (!$ConcatVal['RelatedClassName'] == 0) {$RelatedClassName = stripslashes($ConcatVal['RelatedClassName']);} else {$RelatedClassName ='';}
									if (!$ConcatVal['AttributeName'] == 0) {$AttributeName = stripslashes($ConcatVal['AttributeName']);} else {$AttributeName ='';}
									$ConcatOrder = $ConcatVal['ConcatOrder'];
									

									$ttlThisConcatValue++;
					

									if ($dispConcatVal != '') {
										$dispConcatVal .= '+';
									}

									if ($Constant == 1) {
										//$dispConcatVal .= '<b>Constant</b>: ';
										//$dispValueType = 'Constant';
										if ($ConstantValue == '') {
											
											$dispConcatVal .= '(blank)';
											$dispValue = '(blank)';
										} else {
											$dispConcatVal .= '"'.$ConstantValue.'"';
											$dispValue = $ConstantValue;
										}
									} else {
										if ($RelatedClass == 1) {
											//$dispConcatVal .= '<b>Related Class Attribute</b>: ';
											//$dispValueType = 'Related Class Attribute';
											if ($RelatedClassName == '') {
												$dispConcatVal .= '(blank class)==>>';
												$dispValue = '(blank class) >> ';
											} else {
												$dispConcatVal .= ''.$RelatedClassName.'==>>';
												$dispValue = $RelatedClassName.' >> ';
											}
										} else {
											//$dispConcatVal .= '<b>Class Attribute</b>: ';
											//$dispValueType = 'Class Attribute';
										}
										if ($AttributeName == '') {
											$dispConcatVal .= '(blank attribute)';
											$dispValue .= '(blank)';
										} else {
											$dispConcatVal .= ''.$AttributeName.'';
											$dispValue .= $AttributeName;
										}
											

									}
								}
							}

							if ($ttlThisConcatValue > 0) {
								//$SourceAttributeName = '<i>Concatenated</i><br>'.$dispConcatVal;
								$SourceAttributeName = $dispConcatVal;
							}
					

						} else if ($MapTypeID == 2) {
							$SourceAttributeName = '(see calculation)';
						}


						$ttlSourceCIs = 0;

						$displaySourceCIList = "";
						$displayTargetCIList = "";
						
							foreach ($AppliedCIList as $AppCI) {
								$appliedCIList = 'appliedCI ';
								if ($AppCI['Source0Target1'] == 0 && $AppCI['AttribMappingID'] == $AttribMappingID) {
									if ($AppCI['Calculated'] == 0) {
										$displaySourceCIList .= $AppCI['CIClassName']."\r";
										//$displaySourceCIList .= $AppCI['CIClassName'].'<br>';
									} else {
										$displaySourceCIList .= "<calculated>\r";
									}
									$ttlSourceCIs++;
								}

							}

						if ($ttlSourceCIs == 0) {
							$displaySourceCIList = "Common Attribute";
						}

						if ($ttlSourceCIs > 0 && $ReverseCondition == 1) {
							$displaySourceCIList = "WHEN NOT:\r".$displaySourceCIList;
						}

							
						$displaySource = '';
						$displaySourceDisplay = '';
						if ($SourceAttributeName != '') {
							$displaySource = ''.$SourceAttributeName.'';
						}
						if ($SourceDisplayName != '') {
							if ($displaySource != '') {
								//$displaySource .= "\r";
							}
							//$displaySource .= $SourceDisplayName;
							$displaySourceDisplay .= $SourceDisplayName;
						}


						// get the attribute conditions					
						$dispSourceValList = '';
						if ($ttlAttribValList > 0) {
							
							foreach ($attribValListList as $AVRow) {
								$testAttribMappingID = $AVRow['AttribMappingID'];
								
								if ($AttribMappingID == $testAttribMappingID && $testAttribMappingID != 0) {
								
									if (!$AVRow['SourceValue'] == 0) {
											$SourceValue = stripslashes($AVRow['SourceValue']);
									} else {
										$SourceValue ='';
									}
									

									$dispSourceVal = '';

									if ($SourceValue == '') {
										$dispSourceVal = '(blank)';
									} else {
										$dispSourceVal = $SourceValue;
									}

									if ($dispSourceValList != '') {
										$dispSourceValList .= "\r ";
									}
									$dispSourceValList .= $dispSourceVal;
								}   

							}   
													  
						} 
								


						if ($dispSourceValList != '') {
							if ($displayAttributeCondition != '') {
								$displayAttributeCondition .= "\r";
							}
							$displayAttributeCondition .= "When value ";
							if ($ReverseCondition == 1) {
								$displayAttributeCondition .= 'NOT ';
							}
							$displayAttributeCondition .= "equal to:\r ";
							$displayAttributeCondition .= $dispSourceValList;

						}
					

						//FalseConditionAction ElseSourceAttribute
						if ($FalseConditionAction == 1 && $ElseSourceAttribute != '') {
							if ($displayAttributeCondition != '') {
								$displayAttributeCondition .= "\r ";
							}
							$displayAttributeCondition .= "Otherwise, use:\r ".$ElseSourceAttribute;
						}
						

						$displayTarget = '';
						$displayTargetDisplay = '';

						if ($TargetAttributeName != '') {
							$displayTarget = ''.$TargetAttributeName.'';
						}
						if ($TargetDisplayName != '') {
							if ($displayTarget != '') {
								//$displayTarget .= "\r";
							}
							$displayTargetDisplay .= $TargetDisplayName;
						}
						
						$displayTargetFieldType = '';
						if ($TargetFieldType != '') {
							$displayTargetFieldType = $TargetFieldType;
						}
						if ($TargetFieldFormat != '') {
							if ($displayTargetFieldType != '') {
								$displayTargetFieldType .= "\r ";
							}
							$displayTargetFieldType .= $TargetFieldFormat;
						}


						$displayMapType = '';

						if ($MapType != '') {
							$displayMapType = $MapType;
						}
						if ($Target_ReferenceCI != '') {
							if ($displayMapType != '') {
								$displayMapType .= "\r ";
							}
							$displayMapType .= "Apply to RefObj:\r ";
							$displayMapType .= $Target_ReferenceCI;
						}

						if ($testSourceAttrib == $SimpleSourceAttributeName && $ttlSourceCIs == 0) {
							//$mapMatch == 1;
							$ValH = $TargetAttributeName;
							$ValI = 'yes';  //temporary until validated in target field is added to mapping UI
						}

						/*
						$displayComments = '';
						if ($RequestorMappingNotes != '') {
							$displayComments = 'Requestor Notes: '.$RequestorMappingNotes;
						}
						if ($ProgrammerMappingNotes != '') {
							if ($displayComments != '') {
								$displayComments .= "\r";
							}
							$displayComments .= 'Programmer Notes: '.$ProgrammerMappingNotes;
						}
						*/

						if ($mapMatch == 1) {
							break;
						}


					}													  
				}



			} // end find target value




				$displayValue = '';
				if (strlen($ValA) > 0 && $ValA != '0') {
					$displayValue = stripslashes($ValA);									
				}
		    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
			$colNum++;
				$displayValue = '';
				if (strlen($ValB) > 0 && $ValB != '0') {
					$displayValue = stripslashes($ValB);									
				}
		    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
			$colNum++;
				$displayValue = '';
				if (strlen($ValC) > 0 && $ValC != '0') {
					$displayValue = stripslashes($ValC);									
				}
		    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
			$colNum++;
				$displayValue = '';
				if (strlen($ValD) > 0 && $ValD != '0') {
					$displayValue = stripslashes($ValD);									
				}
		    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
			$colNum++;
				$displayValue = '';
				if (strlen($ValE) > 0 && $ValE != '0') {
					$displayValue = stripslashes($ValE);									
				}
		    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
			$colNum++;
				$displayValue = '';
				if (strlen($ValF) > 0 && $ValF != '0') {
					$displayValue = stripslashes($ValF);									
				}
		    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
			$colNum++;
				$displayValue = '';
				if (strlen($ValG) > 0 && $ValG != '0') {
					$displayValue = stripslashes($ValG);									
				}
		    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
			$colNum++;
				$displayValue = '';
				if (strlen($ValH) > 0 && $ValH != '0') {
					$displayValue = stripslashes($ValH);									
				}
		    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
			$colNum++;
				$displayValue = '';
				if (strlen($ValI) > 0 && $ValI != '0') {
					$displayValue = stripslashes($ValI);									
				}
		    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
			$colNum++;
				$displayValue = '';
				if (strlen($ValJ) > 0 && $ValJ != '0') {
					$displayValue = stripslashes($ValJ);									
				}
		    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
			$colNum++;
				$displayValue = '';
				if (strlen($ValK) > 0 && $ValK != '0') {
					$displayValue = stripslashes($ValK);									
				}
		    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
			$colNum++;
				$displayValue = '';
				if (strlen($ValL) > 0 && $ValL != '0') {
					$displayValue = stripslashes($ValL);									
				}
		    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
			$colNum++;

																				
			$attribCount++;
			$sheetRowNum++;
		}
		
		if ($sheetRowNum == 2) {
		   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $sheetRowNum, 'No records returned.');
		}


		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle($SheetTitle);

		$sheetIndex++;
	}
}
//echo 'test11';exit;

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$outputFileName.'"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;



?>

