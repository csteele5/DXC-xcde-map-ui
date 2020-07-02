
<?php
	// Expected values for input fields
	//$inputDateValue = '2013-05-03';
	//$inputPrevDays = 30;
	//$inputFollDays = 30;
	//$inputDateValName = 'TravelDate';
	//$inputElementStyle = '';
	//$inputDateDispFormat = '';
	if (!isset($inputDateValue)) {$inputDateValue = '';}
	if (!isset($inputPrevDays)) {$inputPrevDays = 30;}
	if (!isset($inputFollDays)) {$inputFollDays = 30;}
	if (!isset($inputDateValName)) {$inputDateValName = '';}
	if (!isset($inputElementStyle)) {$inputElementStyle = ' setFormDirty';}
	if (!isset($inputDateDispFormat)) {$inputDateDispFormat = '';}

?>

<?php
	// begin configuration of date values for populating select box				
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

	$disptodayDate = substr($todayDate, 0, 10);

	//$disptodayDate = date('Y-m-d');
	// default span of days for dropdown
	$prevDays = $inputPrevDays;
	$follDays = $inputFollDays;
	$totalDays = $prevDays + $follDays;
	//echo 'totalDays '.$totalDays.'<br>';
	//echo 'disptodayDate '.$disptodayDate.'<br>';
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

	$elementStyle = 'input-large';
	// optional style param
	if (strlen($inputElementStyle) > 0) {
		$elementStyle = $inputElementStyle;
	}

	$dateDispFormat = 'l Y-m-d';
	// optional style param
	if (strlen($inputDateDispFormat) > 0) {
		$dateDispFormat = $inputDateDispFormat;
	}
			
?>				

<select id="<?php echo $inputDateValName ?>" name="<?php echo $inputDateValName ?>" class="<?php echo $elementStyle ?>">	
	<?php
		$testKeyDate = $disptodayDate;
		if ($curDateValue) {
			$testKeyDate = $curDateValue;
		}
		for ($i=0; $i<$totalDays; $i++)
		   {
				$mod_date = strtotime($startDate."+ $i days");
				$keyDate =  date("Y-m-d",$mod_date);
				$dispDate =  date("$dateDispFormat",$mod_date);
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
