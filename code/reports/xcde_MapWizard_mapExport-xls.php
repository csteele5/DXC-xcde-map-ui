<?php

/**
*<!-------------------------------------------------- 
*|	 Name: Charles Steele                          |
*|	 Payroll Number: cs13514                       |
*|	 E-mail: csteele5@dxc.com                      |
*|	 Phone: 310-321-8776                           |
*| 	 Date Created: 9/26/19 					   |
*--------------------------------------------------->
*<!-------------------------------------------------------------------------
*	9/26/19 - export CIs, attributes, relationships to XLS
*---------------------------------------------------------------------------->		
*/
	session_start();
	ini_set('memory_limit', '512M');
	ini_set('max_execution_time', 300);
	include '../dbconn.inc';

	
	if (isset($_POST['MapSegmentID'])) {
		$MapSegmentID = $_POST['MapSegmentID'];
	} else if (isset($_GET['MSID'])) {
		$MapSegmentID = $_GET['MSID']; 
	} else {
		$MapSegmentID = 0; 
	}
	

	$currentUser = $_SESSION['userID'];

	$outputFileName = 'xcde_MapExport_'.$currentUser.'.xlsx';

	//include '../common/xcdeCIbySegment_include.php';
	
	//echo 'TEST1'; //exit;
	$query = '';	


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
							 ->setTitle("Office XLS XCDE Map")
							 ->setSubject("Office XLS XCDE Map Document")
							 ->setDescription("XCDE Map Document for Office xls, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("XCDE Map result file");



// set up columns
$colNum = 0;

$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($colNum, 1, 'Source Display Name');
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('60');
$colNum++; 
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('30');
$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($colNum, 1, 'Source Technical Name');
$colNum++; 
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('40');
$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($colNum, 1, 'Condition');
$colNum++; 
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('60');
$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($colNum, 1, 'Target Display Name');
$colNum++;
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('30'); 
$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($colNum, 1, 'Target Technical Name');
$colNum++; 
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('100');
$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($colNum, 1, 'Comments');
$colNum++; 
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('30');
$objPHPExcel->setActiveSheetIndex(0)->setCellValueByColumnAndRow($colNum, 1, 'Status');
$colNum++; 

$objPHPExcel->getActiveSheet()
    ->getStyle('A1:G1')
    ->getFill()
    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('ffb3b3');

$sheetRowNum = 2;
if ($ttlCI > 0) {
	
	foreach ($CIList as $row) {

			$colNum = 0;

				$displayValue = '';
				if (strlen($row['SourceDisplayName']) > 0 && $row['SourceDisplayName'] != '0') {
					$displayValue = stripslashes($row['SourceDisplayName']);									
				}
		    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
    		$colNum++; 
				$displayValue = '';
				if (strlen($row['SourceClassName']) > 0 && $row['SourceClassName'] != '0') {
					$displayValue = stripslashes($row['SourceClassName']);									
				}
		    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
    		$colNum++; 
				
				$displayValue = '';
    			$CIDerivationID = $row['CIDerivationID'];
				$Derivation_MapTypeID = $row['Derivation_MapTypeID'];
				if (!$row['Derivation_IdAttribute'] == 0) {$Derivation_IdAttribute = stripslashes($row['Derivation_IdAttribute']);} else {$Derivation_IdAttribute ='';}
				if (!$row['Derivation_MapType'] == 0) {$Derivation_MapType = stripslashes($row['Derivation_MapType']);} else {$Derivation_MapType ='';}
				if (!$row['Derivation_MapTypeCodeValue'] == 0) {$Derivation_MapTypeCodeValue = stripslashes($row['Derivation_MapTypeCodeValue']);} else {$Derivation_MapTypeCodeValue ='';}

				if ($Derivation_MapTypeID > 0) {
					$subDisplaySource = '';
					if ($Derivation_MapTypeID == 3) {
						$subDisplaySource = 'When '.$Derivation_IdAttribute.' is: ';
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

					$displayValue = $subDisplaySource;
				}


				// if (strlen($row['SourceClassName']) > 0 && $row['SourceClassName'] != '0') {
				// 	$displayValue = stripslashes($row['SourceClassName']);									
				// }
		    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
    		$colNum++; 
				$displayValue = '';
				if (strlen($row['TargetDisplayName']) > 0 && $row['TargetDisplayName'] != '0') {
					$displayValue = stripslashes($row['TargetDisplayName']);									
				}
		    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
    		$colNum++; 
				$displayValue = '';
				if (strlen($row['TargetClassName']) > 0 && $row['TargetClassName'] != '0') {
					$displayValue = stripslashes($row['TargetClassName']);									
				}
		    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
    		$colNum++; 
				$displayValue = '';
				if (strlen($row['RequestorMappingNotes']) > 0 && $row['RequestorMappingNotes'] != '0') {
					$displayValue = 'Requestor Notes: '.stripslashes($row['RequestorMappingNotes']);									
				}
				if (strlen($row['ProgrammerMappingNotes']) > 0 && $row['ProgrammerMappingNotes'] != '0') {
					if (strlen($displayValue != '')) {
						$displayValue .= ' ';	
					}
					$displayValue .= 'Programmer Notes: ';	
					$displayValue .= stripslashes($row['ProgrammerMappingNotes']);									
				}
		    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
    		$colNum++; 
				$displayValue = 'Active';
				if ($row['Inactive'] == 1) {
					$displayValue = 'Inactive';									
				}
		    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
    		$colNum++; 

			$sheetRowNum ++;

		}     

				  
} else {
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $sheetRowNum, 'No records returned.');
}



// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('CIMapping');


// Create a new worksheet, after the default sheet
$objPHPExcel->createSheet();
//echo 'TEST2'; exit;

/*
include '../common/xcdeAttribbySegment_include.php';
*/
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

$colNum = 0;
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow($colNum, 1, 'Source CI');
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('50');
$colNum++; 
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('60');
$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow($colNum, 1, 'Source Display Name');
$colNum++; 
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('30');
$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow($colNum, 1, 'Source Technical Name');
$colNum++; 
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('40');
$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow($colNum, 1, 'Attrib Condition');
$colNum++; 
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('60');
$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow($colNum, 1, 'Target Display Name');
$colNum++; 
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('30');
$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow($colNum, 1, 'Target Technical Name');
$colNum++; 
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('15');
$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow($colNum, 1, 'MapType');
$colNum++; 
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('15');
$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow($colNum, 1, 'FieldType');
$colNum++; 
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('100');
$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow($colNum, 1, 'Comments');
$colNum++; 
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('30');
$objPHPExcel->setActiveSheetIndex(1)->setCellValueByColumnAndRow($colNum, 1, 'Status');
$colNum++; 


$objPHPExcel->getActiveSheet()
    ->getStyle('A1:J1')
    ->getFill()
    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('FF808080');


$sheetRowNum = 2;

if ($ttlMap > 0) {
	
	//create the unsorted array of attributes
	foreach ($MapList as $row) {
		$colNum = 0;
	
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


		if (!$row['Source_Attrib_Condition_Code'] == 0) {$Source_Attrib_Condition_Code = stripslashes($row['Source_Attrib_Condition_Code']);} else {$Source_Attrib_Condition_Code ='';}
		if (!$row['RequestorMappingNotes'] == 0) {$RequestorMappingNotes = stripslashes($row['RequestorMappingNotes']);} else {$RequestorMappingNotes ='';}
		if (!$row['ProgrammerMappingNotes'] == 0) {$ProgrammerMappingNotes = stripslashes($row['ProgrammerMappingNotes']);} else {$ProgrammerMappingNotes ='';}

		//if (!$row['Source_CIName'] == 0) {$Source_CIName = stripslashes($row['Source_CIName']);} else {$Source_CIName ='';}
		if (!$row['SourceAttributeName'] == 0) {$SourceAttributeName = stripslashes($row['SourceAttributeName']);} else {$SourceAttributeName ='';}
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
						$displaySourceCIList .= $AppCI['CIClassName']." ";
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
		/*
		if ($SourceFieldType != '') {
			if ($displaySource != '') {
				$displaySource .= "\r";
			}
			$displaySource .= ''.$SourceFieldType.'';
		}	
		*/


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
				$displayAttributeCondition .= " ";
			}
			$displayAttributeCondition .= "When value ";
			if ($ReverseCondition == 1) {
				$displayAttributeCondition .= 'NOT ';
			}
			$displayAttributeCondition .= "equal to:  ";
			$displayAttributeCondition .= $dispSourceValList;

		}
	

		//FalseConditionAction ElseSourceAttribute
		if ($FalseConditionAction == 1 && $ElseSourceAttribute != '') {
			if ($displayAttributeCondition != '') {
				$displayAttributeCondition .= "  ";
			}
			$displayAttributeCondition .= "Otherwise, use:  ".$ElseSourceAttribute;
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
				$displayTargetFieldType .= "  ";
			}
			$displayTargetFieldType .= $TargetFieldFormat;
		}


		$displayMapType = '';

		if ($MapType != '') {
			$displayMapType = $MapType;
		}
		if ($Target_ReferenceCI != '') {
			if ($displayMapType != '') {
				$displayMapType .= "  ";
			}
			$displayMapType .= "Apply to RefObj:  ";
			$displayMapType .= $Target_ReferenceCI;
		}

		/*
		if ($TargetFieldType != '') {
			if ($displayTarget != '') {
				$displayTarget .= "\r";
			}
			$displayTarget .= ''.$TargetFieldType.'';
		}
		*/
		// if ($Target_CIName != '') {
		// 	if ($displayTarget != '') {
		// 		$displayTarget .= '<br>';
		// 	}
		// 	$displayTarget .= 'CI: <i>'.$Target_CIName.'</i>';
		// }


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
		if ($Source_Attrib_Condition_Code != '') {
			if ($displayComments != '') {
				$displayComments .= "\r";
			}
			$displayComments .= 'Pseudo Code: '.$Source_Attrib_Condition_Code;
		}
		/*
		if ($displayComments == '') {
			$displayComments = '(no comments)';
		}
		*/
		// if ($MapTypeID != 1) {
		// 	$displaySource = '<i>(calculated)</i>';
		// } else 
		if ($displaySource == '') {
			$displaySource = '(not mapped)';
			$displaySource = '(to be populated)';
		}
		if ($displayTarget == '') {
			$displayTarget = '(not mapped)';
		}



			$displayValue = '';
			if (strlen($displaySourceCIList) > 0 && $displaySourceCIList != '0') {
				$displayValue = stripslashes($displaySourceCIList);									
			}
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
		$colNum++; 
			$displayValue = '';
			if (strlen($displaySourceDisplay) > 0 && $displaySourceDisplay != '0') {
				$displayValue = stripslashes($displaySourceDisplay);									
			}
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
		$colNum++; 
			$displayValue = '';
			if (strlen($displaySource) > 0 && $displaySource != '0') {
				$displayValue = stripslashes($displaySource);									
			}
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
		$colNum++; 
			$displayValue = '';
			if (strlen($displayAttributeCondition) > 0 && $displayAttributeCondition != '0') {
				$displayValue = stripslashes($displayAttributeCondition);									
			}
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
		$colNum++; 
			$displayValue = '';
			if (strlen($displayTarget) > 0 && $displayTarget != '0') {
				$displayValue = stripslashes($displayTarget);									
			}
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
		$colNum++; 
			$displayValue = '';
			if (strlen($displayTarget) > 0 && $displayTarget != '0') {
				$displayValue = stripslashes($displayTarget);									
			}
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
		$colNum++; 
			$displayValue = '';
			if (strlen($displayMapType) > 0 && $displayMapType != '0') {
				$displayValue = stripslashes($displayMapType);									
			}
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
		$colNum++; 
			$displayValue = '';
			if (strlen($displayTargetFieldType) > 0 && $displayTargetFieldType != '0') {
				$displayValue = stripslashes($displayTargetFieldType);									
			}
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
		$colNum++; 
			$displayValue = '';
			if (strlen($displayComments) > 0 && $displayComments != '0') {
				$displayValue = stripslashes($displayComments);									
			}
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
		$colNum++; 
			$displayValue = 'Active';
			if ($row['Inactive'] == 1) {
				$displayValue = 'Inactive';									
			}
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
		$colNum++; 

		$sheetRowNum ++;





	}													  
} else {
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $sheetRowNum, 'No records returned.');
}




// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('AttribMapping');


// Create a new worksheet, after the default sheet
$objPHPExcel->createSheet();


$query = "SELECT        XCDE_DataMap_RelationshipPairs.RecordID, XCDE_DataMap_RelationshipPairs.RelationshipSchemaID, 				
						XCDE_DataMap_RelationshipPairs.SourceParentCI, XCDE_DataMap_RelationshipPairs.SourceRelationship, 
                         XCDE_DataMap_RelationshipPairs.SourceChildCI, XCDE_DataMap_RelationshipPairs.TargetParentCI, XCDE_DataMap_RelationshipPairs.TargetRelationship, XCDE_DataMap_RelationshipPairs.TargetChildCI, 
                         XCDE_DataMap_RelationshipPairs.UseSeparatePath, XCDE_DataMap_RelationshipPairs.Comments, XCDE_DataMap_Segment.MapSegmentID,
                         XCDE_DataMap_RelationshipPairs.Inactive
			FROM            XCDE_DataMap_RelationshipPairs INNER JOIN
                         XCDE_DataMap_Segment ON XCDE_DataMap_RelationshipPairs.RelationshipSchemaID = XCDE_DataMap_Segment.RelationshipSchemaID
			WHERE        (XCDE_DataMap_Segment.MapSegmentID = $MapSegmentID)";


 
$RelResult = sqlsrv_query($conn,$query); 

if ($RelResult === false ) {
	 die( print_r( sqlsrv_errors(), true));
}


// set up columns
$colNum = 0;

$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
$objPHPExcel->setActiveSheetIndex(2)->setCellValueByColumnAndRow($colNum, 1, 'Source Parent CI');
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('50');
$colNum++; 
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('30');
$objPHPExcel->setActiveSheetIndex(2)->setCellValueByColumnAndRow($colNum, 1, 'Source Relationship');
$colNum++; 
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('50');
$objPHPExcel->setActiveSheetIndex(2)->setCellValueByColumnAndRow($colNum, 1, 'Source Child CI');
$colNum++; 
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('50');
$objPHPExcel->setActiveSheetIndex(2)->setCellValueByColumnAndRow($colNum, 1, 'Target Parent CI');
$colNum++;
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('30'); 
$objPHPExcel->setActiveSheetIndex(2)->setCellValueByColumnAndRow($colNum, 1, 'Target Relationship');
$colNum++; 
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('50');
$objPHPExcel->setActiveSheetIndex(2)->setCellValueByColumnAndRow($colNum, 1, 'Target Child CI');
$colNum++;
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('10');
$objPHPExcel->setActiveSheetIndex(2)->setCellValueByColumnAndRow($colNum, 1, 'CMS Path');
$colNum++;
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('100');
$objPHPExcel->setActiveSheetIndex(2)->setCellValueByColumnAndRow($colNum, 1, 'Comments');
$colNum++; 
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('30');
$objPHPExcel->setActiveSheetIndex(2)->setCellValueByColumnAndRow($colNum, 1, 'Status');
$colNum++; 

$objPHPExcel->getActiveSheet()
    ->getStyle('A1:I1')
    ->getFill()
    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('FF808080');


$sheetRowNum = 2;

try {     
	$test = sqlsrv_has_rows($RelResult);	
} catch (exception $e) {
	print_r($e);
}
							
if ($test) {
	while($row = sqlsrv_fetch_array($RelResult)) {
	
		$colNum = 0;
			$displayValue = '';
			if (strlen($row['SourceParentCI']) > 0 && $row['SourceParentCI'] != '0') {
				$displayValue = stripslashes($row['SourceParentCI']);									
			}
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
		$colNum++; 
			$displayValue = '';
			if (strlen($row['SourceRelationship']) > 0 && $row['SourceRelationship'] != '0') {
				$displayValue = stripslashes($row['SourceRelationship']);									
			}
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
		$colNum++; 	
			$displayValue = '';
			if (strlen($row['SourceChildCI']) > 0 && $row['SourceChildCI'] != '0') {
				$displayValue = stripslashes($row['SourceChildCI']);									
			}
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
		$colNum++; 	
			$displayValue = '';
			if (strlen($row['TargetParentCI']) > 0 && $row['TargetParentCI'] != '0') {
				$displayValue = stripslashes($row['TargetParentCI']);									
			}
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
		$colNum++; 
			$displayValue = '';
			if (strlen($row['TargetRelationship']) > 0 && $row['TargetRelationship'] != '0') {
				$displayValue = stripslashes($row['TargetRelationship']);									
			}
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
		$colNum++; 	
			$displayValue = '';
			if (strlen($row['TargetChildCI']) > 0 && $row['TargetChildCI'] != '0') {
				$displayValue = stripslashes($row['TargetChildCI']);									
			}
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
		$colNum++; 	

		if ($row['UseSeparatePath'] == 1) {
			$displayUseSeparatePath = "<i>CMS Path</i>";
			$UseSeparatePath = 1;
		} else {
			$displayUseSeparatePath = "";
			$UseSeparatePath = 0;
		}
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayUseSeparatePath);
		$colNum++; 
			$displayValue = '';
			if (strlen($row['Comments']) > 0 && $row['Comments'] != '0') {
				$displayValue = stripslashes($row['Comments']);									
			}
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
		$colNum++; 	
			$displayValue = 'Active';
			if ($row['Inactive'] == 1) {
				$displayValue = 'Inactive';									
			}
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
		$colNum++; 


		$sheetRowNum ++;
				
	}   
							  
} else {
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $sheetRowNum, 'No records returned.');
}

sqlsrv_free_stmt($RelResult);


// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Relationships');


// Create a new worksheet, after the default sheet
$objPHPExcel->createSheet();

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




// set up columns
$colNum = 0;

$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
$objPHPExcel->setActiveSheetIndex(3)->setCellValueByColumnAndRow($colNum, 1, 'Source CI');
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('40');
$colNum++; 
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('30');
$objPHPExcel->setActiveSheetIndex(3)->setCellValueByColumnAndRow($colNum, 1, 'Triggering Attribute');
$colNum++; 
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('30');
$objPHPExcel->setActiveSheetIndex(3)->setCellValueByColumnAndRow($colNum, 1, 'Target CI');
$colNum++; 
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('30');
$objPHPExcel->setActiveSheetIndex(3)->setCellValueByColumnAndRow($colNum, 1, 'Target Attributes');
$colNum++;
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('100');
$objPHPExcel->setActiveSheetIndex(3)->setCellValueByColumnAndRow($colNum, 1, 'Comments');
$colNum++; 
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setAutoSize(false);
$objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($colNum)->setWidth('30');
$objPHPExcel->setActiveSheetIndex(3)->setCellValueByColumnAndRow($colNum, 1, 'Status');
$colNum++; 

$objPHPExcel->getActiveSheet()
    ->getStyle('A1:F1')
    ->getFill()
    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('FF808080');


$sheetRowNum = 2;


						
if ($ttlRefObj > 0) {


	
	foreach ($RefObjList as $row) { 		
		$RefObjectID = $row['RefObjectID'];
		
		$MapMultipleAttributes = $row['MapMultipleAttributes'];
		$Required = $row['Required'];
		if (!$row['TargetCIClassName'] == 0) {$TargetCIClassName = stripslashes($row['TargetCIClassName']);} else {$TargetCIClassName ='';}
		if (!$row['SingleTargetAttributeName'] == 0) {$SingleTargetAttributeName = stripslashes($row['SingleTargetAttributeName']);} else {$SingleTargetAttributeName ='';}
		if (!$row['SingleTarget_ConstantValue'] == 0) {$SingleTarget_ConstantValue = stripslashes($row['SingleTarget_ConstantValue']);} else {$SingleTarget_ConstantValue ='';}
		$TargetFieldTypeID = $row['TargetFieldTypeID'];
		if (!$row['Source_Attrib_Condition_Code'] == 0) {$Source_Attrib_Condition_Code = stripslashes($row['Source_Attrib_Condition_Code']);} else {$Source_Attrib_Condition_Code ='';}
		if (!$row['RequestorMappingNotes'] == 0) {$RequestorMappingNotes = stripslashes($row['RequestorMappingNotes']);} else {$RequestorMappingNotes ='';}
		if (!$row['ProgrammerMappingNotes'] == 0) {$ProgrammerMappingNotes = stripslashes($row['ProgrammerMappingNotes']);} else {$ProgrammerMappingNotes ='';}


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
		if ($Source_Attrib_Condition_Code != '') {
			if ($displayComments != '') {
				$displayComments .= "\r";
			}
			$displayComments .= 'Pseudo Code: '.$Source_Attrib_Condition_Code;
		}

		$UpdatedBy = $row['UpdatedBy'];
		$FieldType = $row['FieldType'];

		if ($row['Inactive'] == 1) {
			$displayInactive = "<i><b>Inactive</b></i>";
			$Inactive = 1;
		} else {
			$displayInactive = "";
			$Inactive = 0;
		}


		$ttlSourceCIs = 0;
		$displaySourceCIList = "";
		
			foreach ($RefAppliedCIList as $AppCI) {
				
				if ($AppCI['RefObjectID'] == $RefObjectID) {
					$displaySourceCIList .= $AppCI['CIClassName']." ";
					
					$ttlSourceCIs++;
				}

			}

		$displayTargetAttrib = '';
		if ($MapMultipleAttributes == 1) {
			$ttlMultipleAttrib = 0;
  			foreach ($RefAttribList as $Attr) {

				if ($Attr['RefObjectID'] == $RefObjectID) {
					//$SourceAttributeName .= $DerAttr['Condition'].'==>>'.$DerAttr['Attribute'].'<br>';
					if (!$Attr['Source_Attribute'] == 0) {$Source_Attribute = stripslashes($Attr['Source_Attribute']);} else {$Source_Attribute ='';}
					if (!$Attr['Target_Attribute'] == 0) {$Target_Attribute = stripslashes($Attr['Target_Attribute']);} else {$Target_Attribute ='';}
					if (!$Attr['Target_ConstantValue'] == 0) {$Target_ConstantValue = stripslashes($Attr['Target_ConstantValue']);} else {$Target_ConstantValue ='';}
					
					$ExistsInRef = $Attr['ExistsInRef'];

					if ($displayTargetAttrib != '') {
						$displayTargetAttrib .= ' ';
					}
					if ($Source_Attribute != '') {
						$displayTargetAttrib .= $Source_Attribute.'==>';
					}
					if ($Target_Attribute != '') {
						$displayTargetAttrib .= $Target_Attribute;
					}
					if ($Target_ConstantValue != '') {
						if ($displayTargetAttrib != '') {
							$displayTargetAttrib .= '=';
						}
						$displayTargetAttrib .= $Target_ConstantValue;
					}

					$ttlMultipleAttrib++;
				}

			}
			//$displayTargetAttrib = 'test '.$ttlMultipleAttrib;

		} else {
			//SingleTargetAttributeName SingleTarget_ConstantValue
			$displayTargetAttrib = $SingleTargetAttributeName;
			/*
			if ($SingleTarget_ConstantValue != '') {
				if ($displayTargetAttrib != '') {
					$displayTargetAttrib .= '=';
				}
				$displayTargetAttrib .= $SingleTarget_ConstantValue;
			}
			*/

		}	

		$colNum = 0;
			$displayValue = '';
			if (strlen($displaySourceCIList) > 0 && $displaySourceCIList != '0') {
				$displayValue = stripslashes($displaySourceCIList);									
			}
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
		$colNum++;
			$displayValue = '';
			if (strlen($row['TriggeringAttributeName']) > 0 && $row['TriggeringAttributeName'] != '0') {
				$displayValue = stripslashes($row['TriggeringAttributeName']);									
			}
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
		$colNum++; 
			$displayValue = '';
			if (strlen($row['TargetCIClassName']) > 0 && $row['TargetCIClassName'] != '0') {
				$displayValue = stripslashes($row['TargetCIClassName']);									
			}
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
		$colNum++; 
			$displayValue = '';
			if (strlen($displayTargetAttrib) > 0 && $displayTargetAttrib != '0') {
				$displayValue = stripslashes($displayTargetAttrib);									
			}
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
		$colNum++;
			

	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayComments);
		$colNum++; 	
			$displayValue = 'Active';
			if ($row['Inactive'] == 1) {
				$displayValue = 'Inactive';									
			}
	    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colNum, $sheetRowNum, $displayValue);
		$colNum++; 


		$sheetRowNum ++;
				
	}   


							  
} else {
	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $sheetRowNum, 'No records returned.');
}



// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Reference Objects');



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

	$params = array($MapSegmentID);

	$query = "UPDATE    XCDE_DataMap_Segment 
			 	SET       LastExcelExportDate = GetUTCDate() ";
	$query .= "	 WHERE 	   MapSegmentID = ? ";

	//echo $query;//exit;	

	$updResult = sqlsrv_query($conn, $query, $params); 	
	if( $updResult === false ) {
		 die( print_r( sqlsrv_errors(), true));			
	}	

exit;



?>

