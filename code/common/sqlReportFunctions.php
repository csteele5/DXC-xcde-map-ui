<?php
	/*
<!-------------------------------------------------- 
|	 Name: Charles Steele                          |
|	 Payroll Number: cs13514                       |
|	 E-mail: csteele5@csc.com                      |
|	 Phone: 310-321-8776                           |
| 	 Date Created: 1/5/15 					   |
--------------------------------------------------->
<!-------------------------------------------------------------------------
	1/5/15  - general report functions
---------------------------------------------------------------------------->
	*/


	function createEmailReport($inputRptNo, $inputconn) {

		$userID = $_SESSION['userID'];

		$params = array($userID);

		// setting the ToEmail to 'calculate' causes the stored procedure to get either the standard or preferred email address for the user

		if ($inputRptNo == 9) {
			// REPORT #9 - Groups with assignments
			$query = "INSERT INTO ReportRequest
		                      (ReportID, ClientID, ToEmail, Requestor, DateRequested, PickedUp)
							VALUES     (9, 0, N'calculate', ?, getutcdate(), 0)";

			$insResults = sqlsrv_query($inputconn, $query, $params); 
					
			if( $insResults === false ) {
				 die( print_r( sqlsrv_errors(), true));			
			} 

		}

	}

	function emailETLNameMatchRequest($inputFromUserID,$inputAccount,$inputDataSource,$inputAssignee,$inputTechSname,$inputPrevTechSname,$inputconn,$inputconnETL) {

		// NOTIFY ADMIN HERE
		/*--------- The next few steps call the stored procedure. ---------*/
		
		/* Define the Transact-SQL query. Use question marks (?) in place of
		 the parameters to be passed to the stored procedure */
		$tsql_callSP = "{call spETLMappingRequestNotification( ?,?,?,?,?,? )}";
		
		/* Define the parameter array. By default, the first parameter is an
		INPUT parameter. The second parameter is specified as an OUTPUT
		parameter. Initializing $salesYTD to 0.0 sets the returned PHPTYPE to
		float. To ensure data type integrity, output parameters should be
		initialized before calling the stored procedure, or the desired
		PHPTYPE should be specified in the $params array.*/
		/*EXAMPLE
		$lastName = "Blythe";
		$salesYTD = 0.0;
		$params = array( 
						 array($lastName, SQLSRV_PARAM_IN),
						 array($salesYTD, SQLSRV_PARAM_OUT)
					   );*/
		   
		//array($requestMsg, SQLSRV_PARAM_IN),

		$params = array( 
						 array($inputFromUserID, SQLSRV_PARAM_IN),
						 array($inputAccount, SQLSRV_PARAM_IN),
						 array($inputDataSource, SQLSRV_PARAM_IN),
						 array($inputAssignee, SQLSRV_PARAM_IN),
						 array($inputTechSname, SQLSRV_PARAM_IN),
						 array($inputPrevTechSname, SQLSRV_PARAM_IN)
					   );
								   
		/* Execute the query. */
		$stmt1 = sqlsrv_query( $inputconn, $tsql_callSP, $params);
		
		/*
		if( $stmt1 === false )
		{
			 echo "Error in executing stored proc spETLMappingRequestNotification.<br>";
			 echo "UserID ".$inputFromUserID."<br>";
			 echo "Account ".$inputAccount."<br>";
			 echo "DataSource ".$inputDataSource."<br>";
			 echo "Assignee ".$inputAssignee."<br>";
			 echo "TechSname ".$inputTechSname."<br>";
			 echo "PrevTechSname ".$inputPrevTechSname."<br>";
			 die( print_r( sqlsrv_errors(), true));
		}
		*/
		
		/* Display the value of the output parameter $salesYTD. */
		//echo '<br>resetEmail '.$resetEmail;
		
		/*Free the statement and connection resources. */
		sqlsrv_free_stmt($stmt1);
		/*--------- END call to the stored procedure. ---------*/


		// END ADMIN NOTIFY


		// Log request in the ETL database
		$params = array($inputAccount, $inputDataSource, $inputAssignee, $inputTechSname, $inputPrevTechSname, $inputFromUserID);

		$query = "INSERT INTO [ETL].[dbo].[AssigneeNameRequest]
					(Account, DataSource, AssigneeName, NewSName, OldSName, DateRequested, RequestedBy)
					VALUES        (?, ?, ?, ?, ?, getutcdate(), ?)";

		$insResults = sqlsrv_query($inputconnETL, $query, $params); 
				
		if( $insResults === false ) {
			 die( print_r( sqlsrv_errors(), true));			
		} 


	}


?>