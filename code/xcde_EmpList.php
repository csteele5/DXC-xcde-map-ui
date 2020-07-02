<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->

<!-------------------------------------------------- 
|	 Name: Charles Steele                          |
|	 Payroll Number: cs13514                       |
|	 E-mail: csteele5@csc.com                      |
|	 Phone: 310-321-8776                           |
| 	 Date Created: 6/4/18 					   |
--------------------------------------------------->
<!-------------------------------------------------------------------------
	6/4/18  - module users list
---------------------------------------------------------------------------->		
<?php
	session_start();

	/* security include */
	include 'dbconn.inc';
	include 'security.inc';
	include 'common/mainFunctions.php';


	$currentUser = strtolower($_SESSION['userID']);
	
	
	if (isset($_GET['msg'])) {
		$msg = $_GET['msg'];
	} else if (isset($_POST['msg'])) {
		$msg = $_POST['msg'];
	} 
	if (!isset($msg)) {
		$msg = 0;
	}


	if (isset($_POST['selUserID'])) {
		$selUserID = $_POST['selUserID'];
		$_SESSION['xcdeEmpList_selUserID'] = $_POST['selUserID'];
	} else if (isset($_GET['selUserID'])) {
		$selUserID = $_GET['selUserID']; 
		$_SESSION['xcdeEmpList_selUserID'] = $_GET['selUserID'];
	}
	if (!isset($selUserID) && isset($_SESSION['xcdeEmpList_selUserID'])) {
		$selUserID = $_SESSION['xcdeEmpList_selUserID'];
	} else if (!isset($selUserID)) {
		$selUserID = $_SESSION['userID'];
		$_SESSION['xcdeEmpList_selUserID'] = $_SESSION['userID'];
	}

	$selEmployeeName = "";

    $query = "SELECT EmployeeName
			  FROM      UsersV2
			  WHERE     UserID = '".$selUserID."' AND NOT UserID IS NULL";
 
    $curUserResult = sqlsrv_query($conn,$query); 

    if ( $curUserResult === false ) {
		 die( print_r( sqlsrv_errors(), true));
    }

	try {	  
		$test = sqlsrv_has_rows($curUserResult);
					
		if ($test) {
			while($row = sqlsrv_fetch_array($curUserResult)) {
				$selEmployeeName = stripslashes($row['EmployeeName']);					
			}				
		}			
		
	} catch (exception $e) {
		print_r($e);
	}

	sqlsrv_free_stmt($curUserResult);
	

	if (isset($_POST['toggleView'])) {
		$toggleView = $_POST['toggleView'];
		$_SESSION['xcdeEmpList_ToggleView'] = $_POST['toggleView'];
	} else if (isset($_GET['TV'])) {
		$toggleView = $_GET['TV'];
		$_SESSION['xcdeEmpList_ToggleView'] = $_GET['TV'];
	}
	if (!isset($toggleView) && isset($_SESSION['xcdeEmpList_ToggleView'])) {
		$toggleView = $_SESSION['xcdeEmpList_ToggleView'];
	} else if (!isset($toggleView)) {
		$toggleView = 0;
		$_SESSION['xcdeEmpList_ToggleView'] = 0;
	}
	/* if this form is opened for first time, determine the appropriate view */
	if (!isset($_SESSION['xcdeEmpList_ToggleView']) && !isset($_GET['TV'])) {
		//$toggleView = $toggleViewCalc;
		$_SESSION['xcdeEmpList_ToggleView'] = $toggleView;
	}	
	if (!isset($_SESSION['xcdeEmpList_ToggleView'] )) {	
		$_SESSION['xcdeEmpList_ToggleView'] = $toggleView;
	}

	if (isset($_POST['xcdeViewAccess'])) {
		$xcdeViewAccess = $_POST['xcdeViewAccess'];
		$_SESSION['xcdeEmpList_xcdeViewAccess'] = $_POST['xcdeViewAccess'];
	} else if (isset($_GET['xcdeViewAccess'])) {
		$xcdeViewAccess = $_GET['xcdeViewAccess']; 
		$_SESSION['xcdeEmpList_xcdeViewAccess'] = $_GET['xcdeViewAccess'];
	}
	if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($xcdeViewAccess)) {
		$xcdeViewAccess = 0;
		$_SESSION['xcdeEmpList_xcdeViewAccess'] = 0;
	}

	if (!isset($xcdeViewAccess) && isset($_SESSION['xcdeEmpList_xcdeViewAccess'])) {
		$xcdeViewAccess = $_SESSION['xcdeEmpList_xcdeViewAccess'];
	} else if (!isset($xcdeViewAccess)) {
		$xcdeViewAccess = 0;
		$_SESSION['xcdeEmpList_xcdeViewAccess'] = $xcdeViewAccess;
	}
	if (!isset($_SESSION['xcdeEmpList_xcdeViewAccess'] )) {	
		$_SESSION['xcdeEmpList_xcdeViewAccess'] = $xcdeViewAccess;
	}



	if (isset($_POST['xcdeEditAccess'])) {
		$xcdeEditAccess = $_POST['xcdeEditAccess'];
		$_SESSION['xcdeEmpList_xcdeEditAccess'] = $_POST['xcdeEditAccess'];
	} else if (isset($_GET['xcdeEditAccess'])) {
		$xcdeEditAccess = $_GET['xcdeEditAccess']; 
		$_SESSION['xcdeEmpList_xcdeEditAccess'] = $_GET['xcdeEditAccess'];
	}
	if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($xcdeEditAccess)) {
		$xcdeEditAccess = 0;
		$_SESSION['xcdeEmpList_xcdeEditAccess'] = 0;
	}

	if (!isset($xcdeEditAccess) && isset($_SESSION['xcdeEmpList_xcdeEditAccess'])) {
		$xcdeEditAccess = $_SESSION['xcdeEmpList_xcdeEditAccess'];
	} else if (!isset($xcdeEditAccess)) {
		$xcdeEditAccess = 0;
		$_SESSION['xcdeEmpList_xcdeEditAccess'] = $xcdeEditAccess;
	}
	if (!isset($_SESSION['xcdeEmpList_xcdeEditAccess'] )) {	
		$_SESSION['xcdeEmpList_xcdeEditAccess'] = $xcdeEditAccess;
	}



	if (isset($_POST['xcdeAdminAccess'])) {
		$xcdeAdminAccess = $_POST['xcdeAdminAccess'];
		$_SESSION['xcdeEmpList_xcdeAdminAccess'] = $_POST['xcdeAdminAccess'];
	} else if (isset($_GET['xcdeAdminAccess'])) {
		$xcdeAdminAccess = $_GET['xcdeAdminAccess']; 
		$_SESSION['xcdeEmpList_xcdeAdminAccess'] = $_GET['xcdeAdminAccess'];
	}
	if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($xcdeAdminAccess)) {
		$xcdeAdminAccess = 0;
		$_SESSION['xcdeEmpList_xcdeAdminAccess'] = 0;
	}

	if (!isset($xcdeAdminAccess) && isset($_SESSION['xcdeEmpList_xcdeAdminAccess'])) {
		$xcdeAdminAccess = $_SESSION['xcdeEmpList_xcdeAdminAccess'];
	} else if (!isset($xcdeAdminAccess)) {
		$xcdeAdminAccess = 0;
		$_SESSION['xcdeEmpList_xcdeAdminAccess'] = $xcdeAdminAccess;
	}
	if (!isset($_SESSION['xcdeEmpList_xcdeAdminAccess'] )) {	
		$_SESSION['xcdeEmpList_xcdeAdminAccess'] = $xcdeAdminAccess;
	}


	if (isset($_POST['empOrderBy'])) {
		$empOrderBy = $_POST['empOrderBy'];
		$_SESSION['xcdeEmpList_empOrderBy'] = $_POST['empOrderBy'];
	} else if (isset($_GET['WOB'])) {
		$empOrderBy = $_GET['WOB']; 
		$_SESSION['xcdeEmpList_empOrderBy'] = $_GET['WOB'];
	}
	if (!isset($empOrderBy) && isset($_SESSION['xcdeEmpList_empOrderBy'])) {
		$empOrderBy = $_SESSION['xcdeEmpList_empOrderBy'];
	} else if (!isset($empOrderBy)) {
		$empOrderBy = 7;
		$_SESSION['xcdeEmpList_empOrderBy'] = $empOrderBy;
	}
	if (!isset($_SESSION['xcdeEmpList_empOrderBy'] )) {	
		$_SESSION['xcdeEmpList_empOrderBy'] = $empOrderBy;
	}
	// This is the type of order
	// 0 = Ascending
	// 1 = Descending
	if (isset($_POST['empOrderType'])) {
		$empOrderType = $_POST['empOrderType'];
		$_SESSION['xcdeEmpList_empOrderType'] = $_POST['empOrderType'];
	} else if (isset($_GET['WOT'])) {
		$empOrderType = $_GET['WOT']; 
		$_SESSION['xcdeEmpList_empOrderType'] = $_GET['WOT'];
	}
	if (!isset($empOrderType) && isset($_SESSION['xcdeEmpList_empOrderType'])) {
		$empOrderType = $_SESSION['xcdeEmpList_empOrderType'];
	} else if (!isset($empOrderType)) {
		$empOrderType = 1;
		$_SESSION['xcdeEmpList_empOrderType'] = $empOrderType;
	}
	if (!isset($_SESSION['xcdeEmpList_empOrderType'] )) {	
		$_SESSION['xcdeEmpList_empOrderType'] = $empOrderType;
	}


	$currentPage = 'xcde_EmpList.php';
	$currentPageBodyClass = 'pxy_list';
	$breadCrumb = '<li><a href="#">XCDE</a> <span class="divider">/</span></li>
				   <li class="active"><a href="home.php">Home</a></li> <span class="divider">/</span></li>
				   <li class="active"><a href="#">Module Users</a></li>';
	$breadCrumb .= '<li class="pull-right">
						<a href="#issueReportingModal" data-toggle="modal" rel="tooltip" title="Instructions on how to report problems with the system.">Issues & Questions</a>
						
					</li>';
					// <span class="divider">/</span>
						// <a href="https://c3.csc.com/groups/pdms">C3 Proxy Documentation</a> 

	$pageResponsive = 0;
	include 'common/header.php';

	$thisPageRights = 0;
	$allowFieldEdit = 0;
	$XCDEAccessRights = $PageXCDEAccessRights;
	$XCDEUserRights = $PageXCDEUserRights;
	$XCDEAdminRights = $PageXCDEAdminRights;
	if ($XCDEAdminRights == 0) {
		// kick it back to home page with access message
		echo "<script>window.location = 'home.php?msg=1'</script>";exit;
	}


	$Status = "New Record";

	$thisPageRights = 1;

// xcdeViewAccess xcdeEditAccess xcdeAdminAccess
// XCDEUserID, UserRights, AdminRights

	$query = "SELECT    TOP 100    UserID, EmployeeName, Phone, Country, ManagerName, MgrSname, XCDEUserID, UserRights, AdminRights
				FROM            vXCDE_UserList ";			 	

	$query .= "WHERE (1 = 1) ";

	if ($toggleView == 0) {
		if ($xcdeViewAccess == 0 && $xcdeEditAccess == 0 && $xcdeAdminAccess == 0) {
			$query .= "AND (1 = 2) ";
		} else {
			if ($xcdeViewAccess > 0) {
				$query .= "AND (NOT XCDEUserID IS NULL) ";
			} 
			if ($xcdeEditAccess > 0) {
				$query .= "AND (UserRights > 0) ";
			} 
			if ($xcdeAdminAccess > 0) {
				$query .= "AND (AdminRights > 0) ";
			} 

		}

	} else if ($toggleView == 2) {
		$query .= "AND (1 = 1) ";

		if ($selUserID !='') {
			$query .=	" AND (UserID = '".$selUserID."')";
		}

		
	} else if ($toggleView == 1) {
		$query .= "AND (1 = 1) ";

		if ($selUserID !='') {
			$query .=	" AND (MgrSname = '".$selUserID."')";
		} else {
			$query .=	" AND (1 = 2)";
		}

	} 

	$query .= " ORDER BY EmployeeName";
	
	//echo $query; //exit;
 
    $empListResult = sqlsrv_query($conn,$query); 

    if ($empListResult === false ) {
		 die( print_r( sqlsrv_errors(), true));
    }




?>	
	<div id="mainBodyContainer" class="container-fluid">
		<?php 
			if ($msg == 1) {
				echo('<div class="alert alert-error op-alert">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						You do not have rights to view that record.
					  </div>');
			}

		?>	
	

			
		<div class="row-fluid">
			<fieldset>
				<legend>Module Users
				<span class="pull-right">
				<!-- <a id="createLink" href="pxy_Create.php" class="btn btn-mini btn-primary"> &nbsp;&nbsp;New Request&nbsp;&nbsp; </a> -->
				<!-- <a id="createLink" href="#createPxyModal" role="button" data-toggle="modal"><button class="btn btn-mini btn-primary" type="button"> &nbsp;&nbsp;New Request&nbsp;&nbsp; </button></a> -->
				</span>
				<?php // echo $toggleView; 
				?>
				</legend><!--onClick="openUrlbyString('al_Create.php')"-->
				
<!-- no-Margin -->
				<form class="form-inline " method="post" action="xcde_EmpList.php">
					<span id="toggleViewSpan">
						<select id="toggleView" name="toggleView" class="input-large setFormDirty"  >		
							<?php	
								echo('<option value=0');
								if ($toggleView==0) {echo(' selected');}
								echo('>All TWOS Users</option>');
								echo("\n\t\t");
								
								echo('<option value=1');
								if ($toggleView==1) {echo(' selected');}
								echo('>List By Manager</option>');
								echo("\n\t\t");
								
								echo('<option value=2');
								if ($toggleView==2) {echo(' selected');}
								echo('>Lookup Employee</option>');
								echo("\n\t\t");
								
		
							?>
						</select>
					</span>
					
					<span id="employeeFilterBlock" class="">
					  	<input id="selEmployeeName" name="selEmployeeName" type="text" placeholder="lookup name"
						class="input-medium" value="<?php echo htmlspecialchars($selEmployeeName); ?>"  
						onBlur="checkSelSearchValue()"  >
						<input id="selUserID" name="selUserID" type="text" placeholder="user ID" readonly="yes"
						class="input-small " value="<?php echo htmlspecialchars($selUserID); ?>">
					</span>
					
				    <button type="submit" class="btn btn-info">Refresh</button>
				    <a href="xcde_EmpProfile.php" class="btn" rel="tooltip" data-placement="bottom" title="Create new user record in TWOS."><i class="icon-plus"></i></a>
						<a href="#" onClick="launchCFTWOS(5, 0)" class="btn btn-inverse <?php if ($currentUser != 'csteele5') {echo ' hide';} ?>">Master List</a>

					<span id="moduleAccessBlock">

						<div class="control-group "> 
							<label class="control-label"></label>
							<div class="controls">

								<label class="checkbox inline radioLabel">
								  <input type="checkbox" id="xcdeViewAccess" name="xcdeViewAccess" value="1" 
								  <?php if ($xcdeViewAccess == 1) { echo ('checked'); } ?> > XCDE View Access
								</label>
								<label class="checkbox inline radioLabel">
								  <input type="checkbox" id="xcdeEditAccess" name="xcdeEditAccess" value="1" 
								  <?php if ($xcdeEditAccess == 1) { echo ('checked'); } ?> > Edit Access
								</label>
								<label class="checkbox inline radioLabel">
								  <input type="checkbox" id="xcdeAdminAccess" name="xcdeAdminAccess" value="1" 
								  <?php if ($xcdeAdminAccess == 1) { echo ('checked'); } ?> > Admin Access
								</label>
							</div>
						</div>


					</span>
					
				 <!--  <span class="pull-right">
				  </span> -->

				</form>

				<div class="span12 leftJustify"><!-- table-condensed  -->
					<table class="table table-striped table-hover">  
					<thead>		
						<tr>
							<th>Employee</th>
							<th>UserID</th>
							<th>Country</th>
							<th>Manager</th>
							<th>Read</th>
							<th>Edit</th>
							<th>Admin</th>
							<!-- <th class="" ></th> -->
						</tr>

					</thead>
					<tbody>					
						<?php
							$lastRowNum = 0;
							try {     
								$test = sqlsrv_has_rows($empListResult);	
							} catch (exception $e) {
								print_r($e);
							}
														
							if ($test) {
								$singlequote = "'";
								while($row = sqlsrv_fetch_array($empListResult)) {
									$lastRowNum ++;

									$rowClass = '';
									$rowUserID = rtrim($row['UserID']);
									$EmployeeName = stripslashes($row['EmployeeName']);
									$MgrSname = rtrim($row['MgrSname']);
									$ManagerName = stripslashes($row['ManagerName']);

									$Phone = stripslashes($row['Phone']);
									$Country = $row['Country'];


									$AdminRights = $row['AdminRights'];
									$EditRights = $row['UserRights'];
									$AccessRights = intval($row['XCDEUserID']);

						
									$expandedDesc = '';
									$functString = '';
									$functString = 'xcde_EmpProfile.php?UID='.$rowUserID;
									
							
									?> 

									<tr id="row_<?php echo $rowUserID; ?>" class="addPointer resourceUpdateModal" onClick="openUrlbyString(<?php echo $singlequote.$functString.$singlequote; ?>)">

										<td>
											<?php echo $EmployeeName; ?>
										</td>
										<td>
											<?php echo $rowUserID; ?>
										</td>
										<td>
											<?php echo $Country; ?>
										</td>
										<td>
											<?php echo $ManagerName; ?>
										</td>
										
										<td>
											<?php 
												if ($AccessRights > 0) {
													echo 'Yes';
												} else {
													echo 'No';
												}

											 ?>
										</td>
										<td>
											<?php 
												if ($EditRights > 0) {
													echo 'Yes';
												} else {
													echo 'No';
												}

											 ?>
										</td>
										<td>
											<?php 
												if ($AdminRights > 0) {
													echo 'Yes';
												} else {
													echo 'No';
												}

											 ?>
										</td>

									  	<input type="hidden" class="rowUserID" value="<?php echo $rowUserID; ?>" />
									  	<input type="hidden" class="employeeName" value="<?php echo $EmployeeName; ?>" />
									  	<input type="hidden" class="phone" value="<?php echo $Phone; ?>" />
									  	<!-- <input type="hidden" class="actualOutcome" value="<?php echo $ActualOutcome; ?>" />
									  	<input type="hidden" class="actOutDate" value="<?php echo $displayActOutDate; ?>" />
									  	<input type="hidden" class="budgetComments" value="<?php echo $BudgetComments; ?>" />
									  	<input type="hidden" class="addedBy" value="<?php echo $AddedBy; ?>" /> -->
									</tr>
																 
											 <?php	

							
								}   
		
																  
							} else {
								echo ('<tr><td colspan=7 class="tblCell_noResults text-error">No records returned.</td></tr>');
							}
							
							sqlsrv_free_stmt($empListResult);	

					  		
						?>
					</tbody>      
					</table>
				</div>
			</fieldset>
		</div>
	</div>	
<?php
	$TestJavascriptInclude = 'xcde_EmpList_script.js';
	include 'common/footer.php';
?>			
		