<?php
	/*
<!-------------------------------------------------- 
|	 Name: Charles Steele                          |
|	 Payroll Number: cs13514                       |
|	 E-mail: csteele5@csc.com                      |
|	 Phone: 310-321-8776                           |
| 	 Date Created: 5/1/13 					   |
--------------------------------------------------->
<!-------------------------------------------------------------------------
	5/1/13  - common php functions
	7/17/13 - add temporary check to proxyPageCheck to allow any manager to navigate around
	4/2/15 - add a function to test for injection stuff
	10/5/15 - add user log item
---------------------------------------------------------------------------->
	*/

	function addUserChangeLogEntry($inputUser, $inputLog, $inputLogger, $inputconn) {
		$params = array($inputUser,$inputLog,$inputLogger);	
		$query = "INSERT INTO User_ChangeLog
                         (UserID, LogComments, DateLogged, LoggedBy)
					VALUES        (?,?,GetUTCDate(),?)";
		//echo $query .'<br>';							
		$result = sqlsrv_query($inputconn, $query, $params); 	
		if( $result === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		}
		sqlsrv_free_stmt($result);

	}

	function textVerification($inputText,$inputTextType) { 
		//text types
		//1 = 1 word, not text, to special characters (i.e shortnames)
		//2 = medium risk, multiple words with spaces and some characters - '-, (i.e. employee name)
		//3 = high risk, multiple words with spaces and some characters - '-, (i.e. description)
		//    /[^A-Za-z0-9.#\\-$]/
		// 	  /^[-a-z0-9_]*$/i

		$returnConfirm = 1; 

		$illegalWords = array("script", "print", "http");
		foreach ($illegalWords as $illWord)
		{
			$pos = strpos($inputText, $illWord);
			//echo "Applying format $illWord on $inputText...".$pos."<br>";
			if ($pos != FALSE) {
				$returnConfirm = 0; 
				//echo 'FOUND';
			}
			//exit;
		}


		// if (preg_match('/^[A-Za-z0-9\\-\\.,\' ]+$/', $inputText) == FALSE) {
		// 	//$returnConfirm = 6; 
		// 	echo ' not foundx ';
		// } else {
		// 	echo ' found ';
		// }


		if ($returnConfirm == 1) {			
		
			switch ($inputTextType) {
				case 3:
					//echo '<BR>CASE3 TEST ';
					if (preg_match('/^[A-Za-z0-9\\-\\.,\' ]+$/', $inputText) == FALSE) {
						$returnConfirm = 5; 
						//echo ' - case3 fail ';
					}

					break;
				
				
				case 2:
					//echo '<BR>CASE2 TEST ';
					if (preg_match('/^[A-Za-z0-9\\-,\' ]/', $inputText) == FALSE) {
						$returnConfirm = 10; 
						//echo ' - case2 fail ';
					}

					break;
				
				case 1:
					//echo '<BR>CASE1 TEST ';
					if (preg_match('/^[A-Za-z0-9]+$/', $inputText) == FALSE) {
						$returnConfirm = 20; 
						//echo ' - case1 fail ';
					}

					break;
				
				default:
					// this is for value , which is an error
					$returnConfirm = 0; 
					break;
			}
		}
		//echo ' will return '.$returnConfirm;exit;
		return $returnConfirm; 
	} 

	function RandomString($length) {
		$original_string = array_merge(range(0,9), range('a','z'), range('A', 'Z'));
		$original_string = implode("", $original_string);
		return substr(str_shuffle($original_string), 0, $length);
	}
						
	function convertTime($inputDate,$UTChr,$UTCmin) {
		$timeFactor = ' + ';
		$ttlMinutes = 0;
		if ($UTChr < 0 || $UTCmin < 0) {
			$timeFactor = '- ';
		}
		
		$ttlMinutes = abs($UTChr)*60+abs($UTCmin);
		
		$calcDate = strtotime($inputDate.$timeFactor.$ttlMinutes." minutes");
		$returnDate = date("Y-m-d H:i:s",$calcDate);
		
		/*echo 'function inputdate '.$inputDate.'<br>';
		echo 'function UTChr '.$UTChr.' UTCmin '.$UTCmin.'<br>';
		echo 'function time factor '.$timeFactor.' minutes '.$ttlMinutes.'<br>';
		
		echo 'function returnDate '.$returnDate.'<br>';*/
		return $returnDate;
	}

						
	function convertWOTime($inputDate,$UTChr,$UTCmin) {
		$returnDate = '';
		$timeFactor = ' + ';
		$ttlMinutes = 0;
		if ($UTChr < 0 || $UTCmin < 0) {
			$timeFactor = '- ';
		}
		
		$ttlMinutes = abs($UTChr)*60+abs($UTCmin);
		
		$calcDate = strtotime($inputDate.$timeFactor.$ttlMinutes." minutes");
		$returnDate = date("Y-m-d H:i:s",$calcDate);
		
		/*echo 'function inputdate '.$inputDate.'<br>';
		echo 'function UTChr '.$UTChr.' UTCmin '.$UTCmin.'<br>';
		echo 'function time factor '.$timeFactor.' minutes '.$ttlMinutes.'<br>';
		
		echo 'function returnDate '.$returnDate.'<br>';*/
		return $returnDate;
	}
		
	// This function accepts a date only and returns a blank or database friendly value	
	function getValidDate($tstDate) {
		$result = '';
		$formats = array("m-d-Y", "d.m.Y", "m/d/Y", "Ymd", "m/d/Y", "Y-m-d"); // and so on.....
		foreach ($formats as $format)
		{
			//echo "Applying format $format on date $tstDate...<br>";
			try {	  
				$date = DateTime::createFromFormat($format, $tstDate);
							
				if ($date) {
					//echo "<br>Date Success<br>";	
					//echo "Format: $format; " . $date->format('Y-m-d H:i:s') . "<br>";				
					$tstDateOnly = $date->format('Y-m-d') . " 00:00:00";	
					//echo "Format clean: " . $tstDateOnly . "<br>";	
					$result = $tstDateOnly;
					break;
					
				} else {
					//echo "<br>Date Failed<br>";
				}		
				
			} catch (exception $e) {
				print_r($e);
			}
		}
		return $result;
	}
		
	// This function accepts a date time and returns a blank or database friendly value	
	function getValidDateTime($tstDate) {
		$result = '';
		$formats = array("m-d-Y H:i:s", "d.m.Y H:i:s", "m/d/Y H:i:s", "Ymd H:i:s", "m/d/Y H:i:s", "Y-m-d H:i:s"); // and so on.....
		foreach ($formats as $format)
		{
			//echo "Applying format $format on date $tstDate...<br>";
			try {	  
				$date = DateTime::createFromFormat($format, $tstDate);
							
				if ($date) {
					//echo "<br>Date Success<br>";	
					//echo "Format: $format; " . $date->format('Y-m-d H:i:s') . "<br>";				
					$tstDateOnly = $date->format('Y-m-d H:i:s');	
					echo "Format clean: " . $tstDateOnly . "<br>";	
					$result = $tstDateOnly;
					break;
					
				} else {
					echo "Date Failed<br>";
				}		
				
			} catch (exception $e) {
				print_r($e);
			}
		}
		return $result;
	}

	// This function returns the ID for an insert query. Requires 'SELECT SCOPE_IDENTITY() AS LastID' be added to query 	EXAMPLE:
	// 		$query = "INSERT INTO AL_Activity
	//               (ActivityCatID, ActivitySubCatID, StatusID, Description, TaskCount, 
	//				  PriTechsname, TicketNumber, ClientID, BusUnitID, ClientSiteID, 
	//				  CustName, SupportLocation, SupportCity, SupportStateProv, SupportStateProvID, 
	//				  SupportCountry, SupportCountryID, SupportMethodID, OpenedBy)
	//			  VALUES     (?,?,?,?,?,
	//			  			  ?,?,?,?,?,
	//						  ?,?,?,?,?,
	//						  ?,?,?,?);";
	//	$query = $query."SELECT SCOPE_IDENTITY() AS LastID";	
	function lastId($queryID) {
		 sqlsrv_next_result($queryID);
		 sqlsrv_fetch($queryID);
		 return sqlsrv_get_field($queryID, 0);
	}	


	// This function calculates task time, travel time and task days		
	function activityTimeUpdate($inputAID, $inputconn) {
		// update total for activity
		$params = array($inputAID, $inputAID);	
		$query = "UPDATE AL_Activity
					SET TravelMin = ISNULL((
						SELECT     SUM(DATEDIFF (minute ,STTime ,EndTime )) AS TravelMinutes
						FROM         WorkTimeLog
						WHERE     (ActivityID = ?) AND (NOT (EndTime IS NULL)) AND (NOT (STTime IS NULL)) AND (LogType = 2)),0)
					WHERE  (ActivityID = ?)";
		//echo $query .'<br>';							
		$result = sqlsrv_query($inputconn, $query, $params); 	
		if( $result === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		}
		sqlsrv_free_stmt($result);
		
		$query = "UPDATE AL_Activity
					SET TimeOnTask = ISNULL((
					SELECT     SUM(DATEDIFF (minute ,STTime ,EndTime )) AS TaskMinutes
					FROM         WorkTimeLog
					WHERE     (ActivityID = ?) AND (NOT (EndTime IS NULL)) AND (NOT (STTime IS NULL)) AND (LogType = 1)),0)
					WHERE  (ActivityID = ?)";
		//echo $query .'<br>';								
		$result = sqlsrv_query($inputconn, $query, $params); 	
		if( $result === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		}
		sqlsrv_free_stmt($result);
		
		$query = "UPDATE AL_Activity
				  SET		TaskDays = (SELECT COUNT(DISTINCT WorkDate)
									FROM         WorkTimeLog
									WHERE     (WorkTimeLog.LogType = 1) AND (WorkTimeLog.ActivityID = AL_Activity.ActivityID))
				  WHERE	(ActivityID = ?)";
		//echo $query .'<br>';	
		$params = array($inputAID);								
		$result = sqlsrv_query($inputconn, $query, $params); 	
		if( $result === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		}
		sqlsrv_free_stmt($result);
	}

	// This function calculates or confirms a work summary record for a tech on a specific day	
	// inputDay needs to come in as a db friendly date type	
	function workSummaryUpdate($inputUID, $inputDay, $inputConfirm, $inputconn) {
		//echo 'workSummaryUpdate function test4 <br>';
		// By default we create a summary record, unless inputConfirm is 1, in which case we are simply determining whether one exists
		$createSummary = 1;
		// verify if a record exists
		if ($inputConfirm == 1) {
			$query = "SELECT WorkLogSumID 
					  FROM WorkTimeSummary
					  WHERE TechsName = ? AND WorkDate = ?";	
			//echo $query .'<br>';	
			$params = array($inputUID, $inputDay);								
			$result = sqlsrv_query($inputconn, $query, $params); 	
			if( $result === false ) {
				 die( print_r( sqlsrv_errors(), true));			
			}	
			
			try {	  
				$test = sqlsrv_has_rows($result);							
				if ($test) {
					// record is confirmed, no need to recalc everything
					$createSummary = 0;				
				}			
				
			} catch (exception $e) {
				print_r($e);
			}
			sqlsrv_free_stmt($result);
		}	
		
		if ($createSummary == 1) {
			//delete and recalculate summary record
			$query = "DELETE FROM WorkTimeSummary
					  WHERE TechsName = ? AND WorkDate = ?";	
			$params = array($inputUID, $inputDay);								
			$result = sqlsrv_query($inputconn, $query, $params); 	
			if( $result === false ) {
				 die( print_r( sqlsrv_errors(), true));			
			}	
			sqlsrv_free_stmt($result);
			
			// begin recalc steps
			// initialize 2 week expected hours
			$TC_Sa1Hrs = 0; 
			$TC_Su1Hrs = 0; 
			$TC_M1Hrs = 0; 
			$TC_Tu1Hrs = 0; 
			$TC_W1Hrs = 0; 
			$TC_Th1Hrs = 0; 
			$TC_F1Hrs = 0; 
			$TC_Sa2Hrs = 0; 
			$TC_Su2Hrs = 0; 
			$TC_M2Hrs = 0; 
			$TC_Tu2Hrs = 0; 
			$TC_W2Hrs = 0; 
			$TC_Th2Hrs = 0; 
			$TC_F2Hrs = 0; 
			$TC_WorkDayOffMins=0;
			$TC_actualTime=0;	
	
			$query="SELECT  Sa1Hrs, Su1Hrs, M1Hrs, Tu1Hrs, W1Hrs, Th1Hrs, F1Hrs, 
							Sa2Hrs, Su2Hrs, M2Hrs, Tu2Hrs, W2Hrs, Th2Hrs, F2Hrs
					FROM         UserSchedule
					WHERE     (UserID = ?)";			
		
			$params = array($inputUID);								
			$result = sqlsrv_query($inputconn, $query, $params); 	
				
			if( $result === false ) {
				 die( print_r( sqlsrv_errors(), true));
			}
		
			try {	  
				$test = sqlsrv_has_rows($result);
							
				if ($test) {
					while($row = sqlsrv_fetch_array($result)) {
						$TC_Sa1Hrs = ($row['Sa1Hrs'] ? $row['Sa1Hrs'] : 0); 
						$TC_Su1Hrs = ($row['Su1Hrs'] ? $row['Su1Hrs'] : 0);
						$TC_M1Hrs = ($row['M1Hrs'] ? $row['M1Hrs'] : 0);
						$TC_Tu1Hrs = ($row['Tu1Hrs'] ? $row['Tu1Hrs'] : 0);
						$TC_W1Hrs = ($row['W1Hrs'] ? $row['W1Hrs'] : 0);
						$TC_Th1Hrs = ($row['Th1Hrs'] ? $row['Th1Hrs'] : 0);
						$TC_F1Hrs = ($row['F1Hrs'] ? $row['F1Hrs'] : 0); 
						
						$TC_Sa2Hrs = ($row['Sa2Hrs'] ? $row['Sa2Hrs'] : 0);
						$TC_Su2Hrs = ($row['Su2Hrs'] ? $row['Su2Hrs'] : 0);
						$TC_M2Hrs = ($row['M2Hrs'] ? $row['M2Hrs'] : 0);
						$TC_Tu2Hrs = ($row['Tu2Hrs'] ? $row['Tu2Hrs'] : 0);
						$TC_W2Hrs = ($row['W2Hrs'] ? $row['W2Hrs'] : 0);
						$TC_Th2Hrs = ($row['Th2Hrs'] ? $row['Th2Hrs'] : 0);
						$TC_F2Hrs = ($row['F2Hrs'] ? $row['F2Hrs'] : 0);	
					}				
				}			
				
			} catch (exception $e) {
				print_r($e);
			}
		
			sqlsrv_free_stmt($result);

	
			// get total task/travel time for tech and target date - includes all activities and work orders	
			$query="EXEC	[dbo].[spGetLoggedTime]
					@inputUserID = '".$inputUID."',
					@inputWorkDate= '".$inputDay."'";
			//echo 	$query;	
		
			$loggedTimeresult = sqlsrv_query($inputconn, $query); //, $params
		
				
			if( $loggedTimeresult === false ) {
				 die( print_r( sqlsrv_errors(), true));
			}
		
			try {	  
				$test = sqlsrv_has_rows($loggedTimeresult);
							
				if ($test) {
					while($row = sqlsrv_fetch_array($loggedTimeresult)) {
						$TC_actualTime = ($row['LoggedTime'] ? $row['LoggedTime'] : 0); 
					}				
				}			
				
			} catch (exception $e) {
				print_r($e);
			}
		
			sqlsrv_free_stmt($loggedTimeresult);  /*	*/
			//echo '<br>TEST!e ';

/*

			$query="SELECT     SUM(DATEDIFF(n, STTime, EndTime)) AS ElapsedTime
					FROM         WorkTimeLog
					WHERE     (WorkTimeLog.TechsName = ?) AND (WorkTimeLog.WorkDate = ?)";			
		
			$params = array($inputUID, $inputDay);							
			$result = sqlsrv_query($inputconn, $query, $params); 	
				
			if( $result === false ) {
				 die( print_r( sqlsrv_errors(), true));
			}
		
			try {	  
				$test = sqlsrv_has_rows($result);
							
				if ($test) {
					while($row = sqlsrv_fetch_array($result)) {
						$TC_actualTime = ($row['ElapsedTime'] ? $row['ElapsedTime'] : 0); 
					}				
				}			
				
			} catch (exception $e) {
				print_r($e);
			}
		
			sqlsrv_free_stmt($result);
*/

	
			// get total out time for tech and target date - includes all activities and work orders
			$query="SELECT     TTODate, StartDateTime, EndDateTime,
								ISNULL(DATEDIFF(minute,StartDateTime,EndDateTime),1440) AS ElapsedTime
					FROM         TechTimeOff
					WHERE     (Deleted = 0) AND (TechSName = ?) AND (TTODate = ?)";			
		
			$params = array($inputUID, $inputDay);							
			$result = sqlsrv_query($inputconn, $query, $params); 	
				
			if( $result === false ) {
				 die( print_r( sqlsrv_errors(), true));
			}
		
			try {	  
				$test = sqlsrv_has_rows($result);
							
				if ($test) {
					while($row = sqlsrv_fetch_array($result)) {					
						$TC_WorkDayOffMins = ($row['ElapsedTime'] ? $row['ElapsedTime'] : 0); 
					}				
				}			
				
			} catch (exception $e) {
				print_r($e);
			}
		
			sqlsrv_free_stmt($result);
			//echo '<br> Time Off for Current Day '.$TC_WorkDayOffMins;
			
			
			//Calculate current pay period start date from 9/25/2004.  take difference in days from current date and divide by 14. 
			//Subtract the remainder from today's date to get the current pay period start date
			$selWorkDay = date_create($inputDay);
			
			$datetime1 = date_create('2004-09-25');
			$datetime2 = date_create($inputDay);
			//echo '<br> datetime2pre '.date_format($datetime2, 'Y-m-d');
			$interval = date_diff($datetime2, $datetime1);
			$intervalStr = $interval->format('%R%a');
			$TC_dayfactor = intval($intervalStr) % 14;
			
			// calculate the start of the pay period for the input date
			$TC_paystartdate = $datetime2;  // renamed for clarity, but it appears to be an object alias.  both values change
			date_add($TC_paystartdate, date_interval_create_from_date_string($TC_dayfactor.' days'));			

			//echo '<br> TEST5 '.$intervalStr.' TC_dayfactor '.$TC_dayfactor;
			//echo '<br> datetime1 '.date_format($datetime1, 'Y-m-d');
			//echo '<br> selWorkDay '.date_format($selWorkDay, 'Y-m-d');
			
			// now calculate the pay period day
			$interval = date_diff($TC_paystartdate, $selWorkDay);
			$intervalStr = $interval->format('%R%a');
			$TC_payPeriodDay = intval($intervalStr);
			
			//echo '<br> TC_paystartdate '.date_format($TC_paystartdate, 'Y-m-d');
			//echo '<br> TC_payPeriodDay '.$TC_payPeriodDay;
			
			// get the schedule column from a array
			$TC_payColList = array("TC_Sa1Hrs","TC_Su1Hrs","TC_M1Hrs","TC_Tu1Hrs","TC_W1Hrs","TC_Th1Hrs","TC_F1Hrs","TC_Sa2Hrs","TC_Su2Hrs","TC_M2Hrs","TC_Tu2Hrs","TC_W2Hrs","TC_Th2Hrs","TC_F2Hrs");
			$TC_schedColumn = $TC_payColList[$TC_payPeriodDay];
			//echo '<br> Current Day Column - TC_payColList '.$TC_schedColumn;
			// evaluate the expected hours/min  value for the day
			$TC_curHours = ${$TC_schedColumn};
			//echo '<br> Hours for Current Day '.$TC_curHours;
			$TC_scheduledTime = (intval($TC_curHours) * 60 > 0) ? intval($TC_curHours) * 60 : 0 ;
			//echo '<br> Min for Current Day '.$TC_scheduledTime;

			// factor in time off
			$TC_expectedTime = (intval($TC_scheduledTime-$TC_WorkDayOffMins) > 0) ? intval($TC_scheduledTime-$TC_WorkDayOffMins) : 0 ;
			//echo '<br> Expected Time for Current Day '.$TC_expectedTime;
			
			//echo '<br>inputDay '.$inputDay;
// is this needed?  $inputDay is used in other queries
			$WorkDate = strtotime($inputDay);
			//echo '<br>WorkDate '.$WorkDate;
			$insWorkDate = date("Y-m-d H:i:s",$WorkDate);
			//echo '<br>insWorkDate '.$insWorkDate;
			
			// getLastLogDate based on time logged
			date_default_timezone_set("UTC");
			$insLastLoggedDate = date('Y-m-d H:i:s');
			//echo 'insLastLoggedDate '.$insLastLoggedDate;
			$query="SELECT  ISNULL(MAX(WorkTimeLog.DateCreated),GETUTCDATE()) AS LastTimeLog
				    FROM         WorkTimeLog
				    WHERE WorkTimeLog.TechsName =  ?";			
		
			$params = array($inputUID);							
			$result = sqlsrv_query($inputconn, $query, $params); 	
				
			if( $result === false ) {
				 die( print_r( sqlsrv_errors(), true));
			}
		
			try {	  
				$test = sqlsrv_has_rows($result);
							
				if ($test) {
					while($row = sqlsrv_fetch_array($result)) {
						//$tstDate = date("Y-m-d H:i:s",$row['LastTimeLog']);
						$tstDate = $row['LastTimeLog']->format('Y-m-d H:i:s');
						if (strlen(getValidDate($tstDate)) > 0){
							$insLastLoggedDate  = $row['LastTimeLog']; 
						}
					}				
				}			
				
			} catch (exception $e) {
				print_r($e);
			}
		
			sqlsrv_free_stmt($result);
			//echo ' insLastLoggedDate2 '.$insLastLoggedDate;


			// create timelog record for travel 
			$query = "INSERT INTO WorkTimeSummary
					  (TechsName, WorkDate, ScheduledMin, ExpectedMin, LoggedMin, LastLoggedDate)
					  VALUES     (?,?,?,?,?,?)";
				
			//echo '<br>'.$query;	
			//echo 	' inputUID '.$inputUID.' inputDay '.$inputDay.' TC_scheduledTime '.$TC_scheduledTime.' TC_expectedTime '.$TC_expectedTime.' TC_actualTime '.$TC_actualTime.' insLastLoggedDate '.$insLastLoggedDate;						
			$params = array($inputUID, $inputDay, $TC_scheduledTime, $TC_expectedTime, $TC_actualTime, $insLastLoggedDate);
			$insResult = sqlsrv_query($inputconn, $query, $params); 	
			if( $insResult === false ) {
				 die( print_r( sqlsrv_errors(), true));				
			}	
			sqlsrv_free_stmt($insResult);
			
		}	
		
	}

	function get_PayPeriodDay($inputDay) {
		//Calculate current pay period start date from 9/25/2004.  take difference in days from current date and divide by 14. 
		//Subtract the remainder from today's date to get the current pay period start date
		$selWorkDay = date_create($inputDay);
		
		$datetime1 = date_create('2004-09-25');
		$datetime2 = date_create($inputDay);
		//echo '<br> datetime2pre '.date_format($datetime2, 'Y-m-d');
		$interval = date_diff($datetime2, $datetime1);
		$intervalStr = $interval->format('%R%a');
		$PP_dayfactor = intval($intervalStr) % 14;
		
		// calculate the start of the pay period for the input date
		$PP_paystartdate = $datetime2;  // renamed for clarity, but it appears to be an object alias.  both values change
		date_add($PP_paystartdate, date_interval_create_from_date_string($PP_dayfactor.' days'));			

		//echo '<br> TEST5 '.$intervalStr.' PP_dayfactor '.$PP_dayfactor;
		//echo '<br> datetime1 '.date_format($datetime1, 'Y-m-d');
		//echo '<br> selWorkDay '.date_format($selWorkDay, 'Y-m-d');
		
		// now calculate the pay period day
		$interval = date_diff($PP_paystartdate, $selWorkDay);
		$intervalStr = $interval->format('%R%a');
		$PP_payPeriodDay = intval($intervalStr);
		
		//echo '<br> PP_paystartdate '.date_format($PP_paystartdate, 'Y-m-d');
		//echo '<br> PP_payPeriodDay '.$PP_payPeriodDay;
		
		// get the schedule column from a array
		$PP_payColList = array("Sa1Hrs","Su1Hrs","M1Hrs","Tu1Hrs","W1Hrs","Th1Hrs","F1Hrs","Sa2Hrs","Su2Hrs","M2Hrs","Tu2Hrs","W2Hrs","Th2Hrs","F2Hrs");
		$PP_schedColumn = $PP_payColList[$PP_payPeriodDay];
		return $PP_schedColumn;
	
	}
	
	
	// keep for reference, but this is WAY TOO SLOW
	// invoke with this: date_diff_days('2004-09-25', '2013-09-13')
	function date_diff_days($date1, $date2) { 
		$current = $date1; 
		$datetime2 = date_create($date2); 
		$count = 0; 
		while(date_create($current) < $datetime2){ 
			$current = gmdate("Y-m-d", strtotime("+1 day", strtotime($current))); 
			$count++; 
		} 
		return $count; 
	} 

	// get activity breadcrumb and proxy user name if applicable, for any activity page
	function getActivityProxyInfo($inputAID, $inputconn) {
		global $curPageUserRights, $proxyForName, $breadCrumb, $parentpageUserID;
		// get userID and name from activity
		$query="SELECT     UsersV2.UserID, UsersV2.EmployeeName
				FROM         AL_Activity INNER JOIN
						  UsersV2 ON AL_Activity.PriTechsname = UsersV2.UserID
				WHERE     (ActivityID = $inputAID)";
		
	
		$result = sqlsrv_query($inputconn,$query);	
	
			
		if( $result === false ) {
			 die( print_r( sqlsrv_errors(), true));
		}
	
		try {	  
			$test = sqlsrv_has_rows($result);
						
			if ($test) {
				while($row = sqlsrv_fetch_array($result)) {	
					$userID = $row['UserID'];	
					$EmployeeName = $row['EmployeeName'];			
				}				
			}			
			
		} catch (exception $e) {
			print_r($e);
		}
	
		sqlsrv_free_stmt($result);
		$curPageUserRights = 1;
		$proxyForName = '';
		//echo 'test '.$userID;
		if ($userID != $_SESSION['userID']) {
			// first, set the $parentpageUserID value so that the parentPageLink.php function gives the proper option
			$parentpageUserID = $userID;
			proxyPageCheck($parentpageUserID, $inputconn);		
		} 	
		//echo ' test2 '.$parentpageUserID;
		//echo ' test2a '.$proxyForName;
	
		$breadCrumb = '<li><a href="https://twos.csc.com">TWOS</a> <span class="divider">/</span></li>
					   <li><a href="home.php">Home</a> <span class="divider">/</span></li>
					   <li><a href="l_tech.php?userID='.$userID.'">Work Log';
		if (strlen($proxyForName) > 0) {
			$breadCrumb .= 	' for '.$proxyForName;	
		}		   
		$breadCrumb .= '</a> <span class="divider">/</span></li>
					   <li class="active"><a href="#">Activity Time Log</a></li>'; 
					   
		//echo ' test3 '.$breadCrumb;
	}


	// this function is used when someone is looking at another users activities (and possibly other records)
	// it sets global variables established prior to calling this function - $proxyForName	
	function proxyPageCheck($inputUID, $inputconn) {
		global 	$curPageUserRights, $proxyForName;
		$curPageUserRights = 0; // rights to stay on the current page based on management string PLUS the current users sname
		// now, determine whether the user has the rights to be on this activity page for the user and set the proxy name
		$query = "SELECT EmpTypeID, EmployeeName, ISNULL(dbo.fnMgmtString(UserID),'TOP') AS MgmtString, MgrSname
				  FROM      UsersV2
				  WHERE     UserID = '".$inputUID."'";
	 	//echo $query;
		$proxyTechResult = sqlsrv_query($inputconn,$query); 
	
		if ( $proxyTechResult === false ) {
			 die( print_r( sqlsrv_errors(), true));
		}
	
		try {	  
			$test = sqlsrv_has_rows($proxyTechResult);
			if ($test) {
				while($row = sqlsrv_fetch_array($proxyTechResult)) {
					$singlequote = "'";
					$proxyForName = stripslashes($row['EmployeeName']);
					$curMgmtString = $row['MgmtString'];
					$singleslash = "\\";
					$testMgmtString = $curMgmtString.$singleslash;
					$testUserString = $testMgmtString.$inputUID.$singleslash;
					// test self
					if (strstr($testUserString,$_SESSION['userID'])) {
						$curPageUserRights = 1; // I am this user			
					}
					//echo 'name  '.$proxyForName;
					
					if ($curPageUserRights == 0) {
						// test all users the current user backs up
						$query = "SELECT UserID 
									FROM UsersV2 
									WHERE BackupUserID ='".$_SESSION['userID']."'
									UNION
									SELECT     TechSName AS UserID
									FROM         TechTimeOff
									WHERE     (BackupUserID = N'".$_SESSION['userID']."') AND (TTODate > DATEADD(day, - 7, getutcdate()))";
		 
						$backupResult = sqlsrv_query($inputconn,$query); 
					
						if ( $backupResult === false ) {
							 die( print_r( sqlsrv_errors(), true));
						}
						try {	  
							$test = sqlsrv_has_rows($backupResult);	
							if ($test) {
								while($row = sqlsrv_fetch_array($backupResult)) {
									//echo '<br>test '.$row['UserID'];		
									$testUserID = $singleslash.rtrim($row['UserID']).$singleslash;
									if (strstr($testUserString,$testUserID)) {
										$curPageUserRights = 1;
										//echo '<br>test user string2 '.$testUserString.' '.$curPageUserRights;
										break;
									}
								}				
							}			
							
						} catch (exception $e) {
							print_r($e);
						}
						sqlsrv_free_stmt($backupResult);
					
					}
					
				}				
			}	//else {$proxyForName = 'no rows';}		
			
		} catch (exception $e) {
			print_r($e);
		}	
		sqlsrv_free_stmt($proxyTechResult);	
		//echo 'name end function  '.$proxyForName;
		//$proxyForName = 'test';
		
		// add temporary check to allow any manager to navigate around
		if ($_SESSION['TWOSAppRights.CntlRecordMgr'] > 0) {
			$upstreamNavRights = 1;
			$curPageUserRights = 1;
		}	
		//end temp check
				
		if ($curPageUserRights == 0) {
			//echo '<br>NO CURRENT USER RIGHTS';
			header("Location: home.php?msg=1");
			exit;
		}
	}
	
	// Trims a floating point number so there are no trailing zeros. For example:
	// 1.00 -> 1
	// 1.10 -> 1.1
	function trimFloatingPoint($input) {
		$strNum = strval($input);
	
		while(substr($strNum, -1, 1) == '0')
			$strNum = substr($strNum, 0, strlen($strNum) -1);
	
		// Trailing zeros are gone. How about the trailing period?
		if(substr($strNum, -1, 1) == '.')
			$strNum = substr($strNum, 0, strlen($strNum) -1);
	
		return $strNum;
	}


	function stripallslashes($string) { 
	    while(strchr($string,'\\')) { 
	        $string = stripslashes($string); 
	    } 
	    return $string;
	} 


	function clean($string) {
		// the purpose of this is to prevent a specific SQL error
		// Array ( [0] => Array ( [0] => IMSSP [SQLSTATE] => IMSSP [1] => -40 [code] => -40 [2] => An error occurred translating string for input param 9 to UCS-2: No mapping for the Unicode character exists in the target multi-byte code page

	   //$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
	   //$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	   $string = preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
	   $string = preg_replace('/\*+/', '*', $string);
	   
	   return $string; 
	}	

?>	
 