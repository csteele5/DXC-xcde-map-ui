
<?php
	// Expected values for input fields


	// input values
	//inputDivID = ID of the parent div so the clear function works
	//inputDateValue = UTC Date Time
	//$inputTimeInterval = 1,6, or other - for display of actual hrs min
	//$_SESSION['UserParams.UTCOffsetH'] = int
	//$_SESSION['UserParams.UTCOffsetM'] = int
	//$inputDisabled = Yes|No; will the input be disabled
	//inputSetDefaultValue = Yes|No; will the input have a default value if none is set
	//$inputDateValName = 'TravelDate';
	//$inputTimeValName = 'TravelTime';
	//$inputTimeStyle = 'input-small stackedFormElement-tablet setFormDirty'
	//$inputDateFormat = 'Y-m-d';

	if (!isset($inputDivID)) {
		$inputDivID = '';
	}

	if (!isset($inputTimeInterval)) {
		$inputTimeInterval = 6;
	}
	if (!isset($inputDateStyle)) {
		$inputDateStyle = 'input-small setFormDirty';
	}
	if (!isset($inputDateFormat)) {
		$inputDateFormat = 'Y-m-d';
	}
	if (!isset($inputTimeStyle)) {
		$inputTimeStyle = 'input-small stackedFormElement-tablet setFormDirty';
	}
	
	if (!isset($inputDisabled)) {
		$inputDisabled = "No";
	} 
	
	if (!isset($inputSetDefaultValue)) {
		$inputSetDefaultValue = "No";
	} 
	
	if (!isset($inputSuppressTime)) {
		$inputSuppressTime = "No";
	} 


?>
<div id="<?php echo $inputDivID; ?>" class="dateTimeCalFormFieldsDiv">
<?php
				
	// set the current date value, if exist
	$curDateValue = $inputDateValue;
	//echo 'Input date/time: '.$curDateValue.'<br>';
	// what is today's current date
	//$todayDate = date(DATE_W3C);
	date_default_timezone_set("UTC");
	$todayDateUTC = date('Y-m-d H:i:s');
	//$todayDateUTCText = $todayDateUTC->format('Y-m-d H:i:s');
	$hrFactor = $_SESSION['UserParams.UTCOffsetH']*-1;
	$minFactor = $_SESSION['UserParams.UTCOffsetM']*-1;
	
	//echo 'Today UTC Date = '.$todayDateUTC.' hr: '.$hrFactor.' min: '.$minFactor.' <br>';

	try {
	  if ($curDateValue == '' && $inputSetDefaultValue == "Yes") {
			$curDateValue = convertWOTime($todayDateUTC,$hrFactor,$minFactor);
		} else if ($curDateValue != '') {
			$curDateValue = convertWOTime($curDateValue,$hrFactor,$minFactor);
		}
		//echo 'select date/time: '.$curDateValue.'<br>';
	  //If the exception is thrown, this text will not be shown
	 // echo 'If you see this, no date error';
	}

	//catch exception
	catch(Exception $e) {
	  echo 'Date Error Message from dateTimeCalFormFieldsTZConverted: ' .$e->getMessage();
	}
	/*
		if ($curDateValue == '' && $inputSetDefaultValue == "Yes") {
			$curDateValue = convertWOTime($todayDateUTC,$hrFactor,$minFactor);
		} else if ($curDateValue != '') {
			$curDateValue = convertWOTime($curDateValue,$hrFactor,$minFactor);
		}
		
		if ($curDateValue) {
			$curDisplayDateValue = $curDateValue->format('Y-m-d H:i:s');
			echo 'select date/time: '.$curDisplayDateValue.'<br>';

		} else {
			echo ' NOT A DATE';
		}
	*/
			
?>				


<?php 

	if ($curDateValue != '') { 
		$dispDate = substr($curDateValue, 0, 10);
	} else {
		$dispDate = '';
	}
?>
<input type="text" class="<?php echo $inputDateStyle ?> datepicker" id="<?php echo $inputDateValName ?>" name="<?php echo $inputDateValName ?>" placeholder="input date" 
	value="<?php echo $dispDate ?>" data-date-format="yyyy-mm-dd"  data-date-viewmode="days" 
	<?php if ($inputDisabled == 'Yes') {echo ' disabled';} ?>>	


<?php
	if ($curDateValue != '') { 
		$dispTime = substr($curDateValue, 11, 5);
	} else {
		$dispTime = '';
	}
	//echo 'select time: '.$dispTime.'<br>';

	//$curTimeValue = $inputTimeValue; 
	$defStartTime = $inputDefStartTime;					
	//if ($curTimeValue) {$defStartTime = $curTimeValue;}
	if ($dispTime == '' && $inputSetDefaultValue == "Yes") {
		$dispTime = $defStartTime;
	}
	//$curTimeValue = $dispTime; 
		
	/*
		*/
	$lastTime = '00:00';
	if ($inputTimeInterval == 1) {
			$min=array("00","01","02","03","04","05","06","07","08","09",
						"10","11","12","13","14","15","16","17","18","19",
						"20","21","22","23","24","25","26","27","28","29",
						"30","31","32","33","34","35","36","37","38","39",
						"40","41","42","43","44","45","46","47","48","49",
						"50","51","52","53","54","55","56","57","58","59");	
	} else if ($inputTimeInterval == 6) {
		$min=array("00","06","12","18","24","30","36","42","48","54");	
	} else {
		$min=array("00","15","30","45");
	}				
	for($i=0;$i<24;$i++)
	  foreach ($min as $v) {
			$instTime = "$i:$v";
			//$instTime = date("g:i a", strtotime("$i:$v"));
			$instTimeKey = sprintf("%02d", $i).":$v"; 
			if ($dispTime==$instTimeKey) {
				//echo($instTimeKey.' ');
				//echo(' selected<br>');
				//break;
			} else {
				if ($dispTime > $lastTime && $dispTime < $instTimeKey) {
					//echo($defStartTime.' is between '.$lastTime.' and '.$instTimeKey.'<br>');
					$dispTime = $instTimeKey;
					//echo($defStartTime.' changed and selected<br>');	
					//break;								
				}
			}
			$lastTime = $instTime;
		}
	
if ($inputSuppressTime == 'No') {

?>

	<select id="<?php echo $inputTimeValName ?>" name="<?php echo $inputTimeValName ?>" class="<?php echo $inputTimeStyle ?>  "
	<?php if ($inputDisabled == 'Yes') {echo ' disabled';} ?>>	
		<?php

			if ($inputTimeInterval == 1) {
				$min=array("00","01","02","03","04","05","06","07","08","09",
							"10","11","12","13","14","15","16","17","18","19",
							"20","21","22","23","24","25","26","27","28","29",
							"30","31","32","33","34","35","36","37","38","39",
							"40","41","42","43","44","45","46","47","48","49",
							"50","51","52","53","54","55","56","57","58","59");	
			} else if ($inputTimeInterval == 6) {
				$min=array("00","06","12","18","24","30","36","42","48","54");	
			} else {
				$min=array("00","15","30","45");
			}						
			for($i=0;$i<24;$i++)
			  foreach ($min as $v) {
					$instTime = "$i:$v";
					$instTime = date("g:i a", strtotime("$i:$v"));
					$instTimeKey = sprintf("%02d", $i).":$v"; 
					echo('<option value="'.$instTimeKey.'"');
					if ($dispTime==$instTimeKey) {echo(' selected');}
					echo('>'.$instTime.'</option>\n\t\t');
				}
		?>
	</select>

<?php
}

?>

<p class="help-inline clearDateFields addPointer"> <i class="icon-remove-sign"></i></p>
</div>
