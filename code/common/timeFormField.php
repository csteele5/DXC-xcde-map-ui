
<?php
	// Expected values for input fields
	//$inputTimeValue = '15:47';
	//$inputTimeValue = '';
	//$inputDefStartTime = '08:00';
	//$inputTimeValName = 'TravelTime';
	//$inputTimeInterval = 6 or 15;  Optional
	//$inputTimeStyle = 6 or 15;  Optional

?>
<?php
	if (!isset($inputTimeInterval)) {
		$inputTimeInterval = 6;
	}
	if (!isset($inputTimeStyle)) {
		$inputTimeStyle = 'input-small setFormDirty ';
	}
	$curTimeValue = $inputTimeValue; 
	$defStartTime = $inputDefStartTime;					
	if ($curTimeValue) {$defStartTime = $curTimeValue;}
	//echo 'curTimeValue '.$curTimeValue.'<br>';
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
				//else {echo($defStartTime.' is not between '.$lastTime.' and '.$instTimeKey.'<br>');}
			}
			$lastTime = $instTimeKey;
		}
	
?>

<select id="<?php echo $inputTimeValName ?>" name="<?php echo $inputTimeValName ?>" class="<?php echo $inputTimeStyle ?> timeOfDaySelect">	
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
