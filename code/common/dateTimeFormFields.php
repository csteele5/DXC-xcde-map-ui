
<?php
	// Expected values for input fields
	//$inputDateValue = '2013-05-03';
	//$inputTimeValue = '15:47';
	//$inputDefStartTime = '08:00';
	//$inputPrevDays = 30;
	//$inputFollDays = 30;
	//$inputDateValName = 'TravelDate';
	//$inputTimeValName = 'TravelTime';
	//$inputTimeInterval = 6 or 15;  Optional

?>

<?php
	if (!isset($inputTimeInterval)) {
		$inputTimeInterval = 6;
	}
	if (!isset($inputDateStyle)) {
		$inputDateStyle = 'input-large setFormDirty';
	}
	if (!isset($inputDateFormat)) {
		$inputDateFormat = 'l Y-m-d';
	}
	if (!isset($inputTimeStyle)) {
		$inputTimeStyle = 'input-small stackedFormElement-tablet setFormDirty';
	}
	// begin conviguration of date values for populating select box				
	// set the current date value, if exist
	$curDateValue = $inputDateValue;
	// what is today's current date
	//$todayDate = date(DATE_W3C);
	
	date_default_timezone_set("UTC");
	$todayDateUTC = date('Y-m-d H:i:s');
	//echo 'today full UTC date: '.$todayDateUTC.'<br>';
	$hrFactor = $_SESSION['UserParams.UTCOffsetH']*-1;
	$minFactor = $_SESSION['UserParams.UTCOffsetM']*-1;
	$todayDate = convertTime($todayDateUTC,$hrFactor,$minFactor);
	//echo 'today  date: '.$todayDate.'<br>';

	$disptodayDate = substr($todayDate, 0, 10);
	//$disptodayDate = date('Y-m-d');
	// default span of days for dropdown
	$prevDays = $inputPrevDays;
	$follDays = $inputFollDays;
	$totalDays = $prevDays + $follDays;
	// set the default start date based on the default previous days value.  
	// may be overwritten if existing value is outside the range (if applicable)
	$mod_date = strtotime($disptodayDate."- $prevDays days");
	$startDate =  date("Y-m-d",$mod_date);
	//echo 'Start Date '.$startDate.'<br>';
	//echo 'Cur Date '.$curDateValue.'<br>';
	// if there is an existing date, make sure it is included in the dropdown by checking 
	// for the need to expand
	if (strlen($curDateValue ) > 0) {
		// convert the dates for comparison
		$datenow = date_create($disptodayDate);
		$datecheck = date_create($curDateValue);
		
		//$curTimeValue = "10:30"; 
		//$curTimeValue = date("HH:mm",$datecheck);

		// get the difference between the dates and convert to a string for the operations				
		$interval = date_diff($datenow, $datecheck);
		$intervalStr = $interval->format('%R%a');
		
		//echo 'intervalStr '.$intervalStr.'<br>';
		if ($intervalStr < $prevDays*-1) {
			$prevDays = ($intervalStr*-1)+1;
			$startDate = $curDateValue;
		}				
		if ($intervalStr > $follDays) {
			$follDays = $intervalStr+5;
		}			
		/*if ($interval > $follDays) {
			$follDays = $interval;
		}*/
		// re-establish the total days value
		$totalDays = $follDays + $prevDays;
	}
	//echo 'NEW Start Date '.$startDate.'<br>';
			
?>				

<select id="<?php echo $inputDateValName ?>" name="<?php echo $inputDateValName ?>" class="<?php echo $inputDateStyle ?> ">	
	<?php
		$testKeyDate = $disptodayDate;
		if ($curDateValue) {
			$testKeyDate = $curDateValue;
		}
		for ($i=0; $i<$totalDays; $i++)
		   {
				$mod_date = strtotime($startDate."+ $i days");
				$keyDate =  date("Y-m-d",$mod_date);
				$dispDate =  date("$inputDateFormat",$mod_date);
				echo('<option value="'.$keyDate.'"');
				if ($testKeyDate==$keyDate) {echo(' selected');}
				echo('>');
				if ($disptodayDate==$keyDate) {
					echo('**** Today ****');
				} else {
					echo($dispDate);
				}									
				echo("</option>\n\t\t");
		   }
	?>
</select>
<?php
	$curTimeValue = $inputTimeValue; 
	$defStartTime = $inputDefStartTime;					
	if ($curTimeValue) {$defStartTime = $curTimeValue;}
		
	$lastTime = '00:00';
	if ($inputTimeInterval == 6) {
		$min=array("00","06","12","18","24","30","36","42","48","54");	
	} else {
		$min=array("00","15","30","45");
	}				
	for($i=0;$i<24;$i++)
	  foreach ($min as $v) {
			$instTime = "$i:$v";
			//$instTime = date("g:i a", strtotime("$i:$v"));
			$instTimeKey = sprintf("%02d", $i).":$v"; 
			if ($defStartTime==$instTimeKey) {
				//echo($instTimeKey.' ');
				//echo(' selected<br>');
				//break;
			} else {
				if ($defStartTime > $lastTime && $defStartTime < $instTimeKey) {
					//echo($defStartTime.' is between '.$lastTime.' and '.$instTimeKey.'<br>');
					$defStartTime = $instTimeKey;
					//echo($defStartTime.' changed and selected<br>');	
					//break;								
				}
			}
			$lastTime = $instTime;
		}
	
?>

<select id="<?php echo $inputTimeValName ?>" name="<?php echo $inputTimeValName ?>" class="<?php echo $inputTimeStyle ?>  ">	
	<?php

		if ($inputTimeInterval == 6) {
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
				if ($defStartTime==$instTimeKey) {echo(' selected');}
				echo('>'.$instTime.'</option>\n\t\t');
			}
	?>
</select>
