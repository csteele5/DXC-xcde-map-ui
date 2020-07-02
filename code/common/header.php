<!-------------------------------------------------- 
|	 Name: Charles Steele                          |
|	 Payroll Number: cs13514                       |
|	 E-mail: csteele5@dxc.com                      |
|	 Phone: 310-321-8776                           |
| 	 Date Created: 2/4/20 					   |
--------------------------------------------------->
<!-------------------------------------------------------------------------
	2/4/20  - header
---------------------------------------------------------------------------->

    <?php 
    	header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
		header("Pragma: no-cache"); // HTTP 1.0.
		header("Expires: 0"); // Proxies.
    ?>
<?php

	if (!isset($hideTopMenu)) {
		$hideTopMenu = 0;
	}

	if (!isset($pageResponsive)) {
		$pageResponsive = 1;
	}

	if (!isset($pageTitle)) {
		$pageTitle = 'XCDE Configuration';
	}
	$pageTitle = 'XCDE Configuration';

	if (!isset($pageDescription)) {
		$pageDescription = '';
	}

	if (!isset($hideTopMenu)) {
		$hideTopMenu = 0;
	}
	if (!isset($loadCalendar)) {
		$loadCalendar = 0;
	}

	if (!isset($mainContainerClass)) {
		$mainContainerClass = "";
	}



	$TestUserID = $_SESSION['userID'];
	
	include 'userrightscheck.inc';
	/*
	$PageAppLogCoordrights = $ReturnAppLogCoordrights;
	$PageAppDataAdminrights = $ReturnAppDataAdminrights;
	$PageAppUserAdminrights = $ReturnAppUserAdminrights;
	$PageAppReportViewrights = $ReturnAppReportViewrights;
	*/
	$PageDisableCommRepeat = $ReturnDisableCommRepeat;
	/*
	$PageAreaTaskrights = $ReturnAreaTaskrights;
	$PageSiteEmprights = $ReturnSiteEmprights;
	$PageAreaMgrrights = $ReturnAreaMgrrights;
	$PageAnyLogCoordrights = $ReturnAnyLogCoordrights;
	$PageAnySiteCoordrights = $ReturnAnySiteCoordrights;
	$PageAnyAreaMgrrights = $ReturnAnyAreaMgrrights;
	$PageSwivelrights = $ReturnSwivelrights;
	$PageAnySwivelrights = $ReturnAnySwivelrights;
	$PageCntlRecordMgr = $ReturnCntlRecordMgr;
	$PageWorkLogUse = $ReturnWorkLogUse;
	$PageAnySiteApprovalrights = $ReturnAnySiteApprovalrights;	
	$PageRatedServicerights = $ReturnAppRatedServicerights;
	$PageActiveLBSrights = $ReturnAppActiveLBSrights;	
	$PageAcctBasicViewrights = $ReturnAcctBasicViewrights;
	$PageAnyAcctBasicViewrights = $ReturnAnyAcctBasicViewrights;
	$PageSAAdminrights = $ReturnSAAdminrights;
	$PageWETUserRights = $ReturnWETUserRights;	
	$PageWETReportRights = $ReturnWETReportRights;
	$PageDPLYUserRights = $ReturnDPLYUserRights;
	$PageDPLYAdminRights = $ReturnDPLYAdminRights;
	$PageDPLYReportRights = $ReturnDPLYReportRights;	
	$PageTechStopUserRights = $ReturnTechStopUserRights;
	$PageAppGlobalReportViewrights = $ReturnAppGlobalReportViewrights;
	$PageSolAssUserRights = $ReturnSolAssUserRights;
	$PageSolAssAdminRights = $ReturnSolAssAdminRights;
	$PageSolAssReportRights = $ReturnSolAssReportRights;
	$PageSOLTrackerCatrights = $ReturnSOLTrackerCatrights;
	*/

	$PageXCDEAccessRights = $ReturnXCDEAccessRights;
	$PageXCDEUserRights = $ReturnXCDEUserRights;
	$PageXCDEAdminRights = $ReturnXCDEAdminRights;

	$PageAnyAssignedMgrrights = $ReturnAnyAssignedMgrrights;
  
	// $PageSOLTrackerUserRights = 0;
	// if ($PageSolAssUserRights > 0 || $PageSolAssAdminRights > 0 || $PageSolAssReportRights > 0) {
	// 	$PageSOLTrackerUserRights = 1;
	// }


	include 'userparamcheck.inc';

	$userID = $_SESSION['userID'];
	$displayName = $_SESSION['EmployeeName'];
	//$workDataRestrict = $_SESSION['UserParams.WorkDataRestrictionLevelID'];
	

	if ($hideTopMenu == 0) {
	// BEGIN hide this if no header is displayed	

		/*******  Get system bulletin *********/
		$RBL = 0;
		if ($_SESSION['readBulletinList']) {$RBL = $_SESSION['readBulletinList'];}

		$query="SELECT  BulletinID, PublishDate, SubjectLine, Message, ExpirationDate, DateCreated, CreatedBy
				FROM    SysBulletins
		        WHERE   (PublishDate < GetUTCDate()) AND (ExpirationDate > GetUTCDate()) AND NOT BulletinID IN ($RBL)
		        ORDER BY PublishDate";
		/*echo $query;*/
		$result2 = sqlsrv_query($conn,$query);	

		if( $result2 === false ) {
			 die( print_r( sqlsrv_errors(), true));
		}


	// END hide this if no header is displayed	
	}

?>	
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<!-- <meta http-equiv="cache-control" content="no-cache" /> -->
        <title><?php echo $pageTitle ?></title>
        <meta name="description" content="<?php echo $pageDescription ?>">
        <?php 
        	if ($pageResponsive == 1) {
	        	echo '<meta name="viewport" content="width=device-width">';
	        }
	    ?>        

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
        </style>

        <?php 
        	if ($pageResponsive == 1) {
	        	echo '<link rel="stylesheet" href="css/bootstrap-responsive.min.css">';
	        }
	    ?>

        <!-- <link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.10.4.custom.min.css"> -->

        <link rel="stylesheet" href="css/datepicker.css">
        <link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css">
        <link rel="stylesheet" href="css/jquery.autocomplete.css">

        <?php 
        	if ($loadCalendar == 1) {
	        	echo '<link href="css/fullcalendar.css" rel="stylesheet" />';
	        	echo '<link href="css/fullcalendar.print.css" rel="stylesheet" media="print" />';
	        }
	    ?>


        <link rel="stylesheet" href="css/main.css">
		
		<!--[if lt IE 9]><!--> 
			<link rel="stylesheet" href="css/main_ie8below.css">
		<!--<![endif]-->

		<!--[if lt IE 9]>
			<script type="text/javascript" src="js/c_add/html5shiv.js"></script>
		<![endif]-->

        <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>
    <body class="<?php 
						echo $currentPageBodyClass;
						if ($pageResponsive == 1) {
				        	echo ' responsiveBody';
				        } else {
				        	echo ' staticBody';
				        }
				 ?>">	
        <!--[if lt IE 8]>
			<div class="alert alert-error op-alert">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				You are either using an older version of IE or have turned on Compatibility Mode.  To proceed, turn off compatibility mode or <a href="http://browsehappy.com/">upgrade your browser</a>.  You may also view this site on your mobile device.				
		  	</div>
        <![endif]-->
<!-- You are using an <strong>outdated</strong> browser that is not HTML5 compliant (this includes IE compatibility mode). Some functionality may be compromised. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.   -->
 	<?php
	if ($hideTopMenu == 0) {
	// BEGIN hide this if no header is displayed
	?>	
        <!-- This code is taken from http://twitter.github.com/bootstrap/examples/hero.html -->

        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
					<!--<a class="brand" href="landing.php">TWOS</a>onClick="launchCFTWOS(0, 0)" <img id="smLogo" src="img/CSClogo2.gif" style="border:0;" alt="CSC Logo">-->
					<a class="brand" href="#" ><img id="smLogo" src="img/dxcHeaderImageNoPad.jpg" style="border:0;" alt="DXC Logo"> <span class="logoText">XCDE</span></a>
                    <div class="nav-collapse collapse">
                        <ul class="nav">
                            

                            <li class="<?php if ($currentPage == 'home.php') {echo 'active';} ?>">
								<a href="#"><span onClick="openUrlbyString('<?php echo 'home.php';?>')" >XCDE Mapping</span></a>
							</li>
						
							<!-- <li class="">
								<a href="db_pxyHome.php">Dashboards</a>
							</li> -->

                            <li class="dropdown hide">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Reports <b class="caret"></b></a>
                                <ul class="dropdown-menu">
									<!-- <li class="nav-header">Accounts</li> -->
									<li><a href="db_pxyHome.php">Dashboards</a></li>
                                </ul>
                            </li>

                            
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                	<?php echo $displayName; ?>
                            		<b class="caret"></b>
                            	</a>
                                <ul class="dropdown-menu">  
                           			<li class="<?php if (0 == 0) {echo 'hidden';} ?>"><a href="t_AdminHome.php">TWOS Admin</a></li>

									<li class="<?php if ($currentPage == 'usr_Profile.php') {echo 'active';} ?>"><a href="usr_Profile.php?self=1">Basic User Profile</a></li>         
									 <!--onClick="ChildWindowHead('../twosv2/userconfigAvail_sub.cfm?UserID=#session.userID#', 'height=500,width=750,location=no,status=no,menubar=no,directories=no,toolbar=no, scrollbars');"-->
                                    <li><a id="resetLink" href="#resetModal" role="button" data-toggle="modal">Password</a></li>
									<li><a href="login.php">Log Out</a></li>
                                </ul>
                            </li>
                        </ul>
                   		<span class="pull-right">
						<!-- <a class="btn visible-tablet visible-phone" href="#" onClick="openUrlbyStringNewTab('https://etes.csc.com/iTES/Loginframe.asp')">Open eTes</a>
                   		 --><a class="btn" href="login.php">Log Out</a>
						</span>
                        <!--<form class="navbar-form pull-right">
                            <input class="span2" type="text" placeholder="Email">
                            <input class="span2" type="password" placeholder="Password">
                            <button type="submit" class="btn">Sign in</button>
                        </form>-->
                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </div>
 	<?php
	// END hide this if no header is displayed
	}
	?>	
        <div class="container <?php echo $mainContainerClass; ?>">
			<!-- show bulletins -->
			<?php
				if ($hideTopMenu == 0) {
				// BEGIN hide this if no header is displayed
					try {	  
						$test = sqlsrv_has_rows($result2);
						
						if ($test) {
							while($row = sqlsrv_fetch_array($result2)) {
								echo '<div class="alert alert-success">';
								echo '<button type="button" class="close" data-dismiss="alert" onclick="hideBulletin('.$row['BulletinID'].')">&times;</button>';
								echo '<strong>'.$row['SubjectLine'].'</strong> '.$row['Message'];
								echo '</div>';
							}
						}
					} catch (exception $e) {
						print_r($e);
					}	
					sqlsrv_free_stmt($result2);
					$breadCrumb = str_replace("csc.com","dxc.com",$breadCrumb);
					echo '<ul class="breadcrumb hidden-tablet hidden-phone">'.$breadCrumb.'</ul>';
				// END hide this if no header is displayed
				}
			
			?>
			<!-- <ul class="breadcrumb hidden-tablet hidden-phone">
				<?php /*echo $breadCrumb*/ ?>
			</ul>
 			-->

