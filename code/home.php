<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->

<!-------------------------------------------------- 
|	 Name: Charles Steele                    |
|	 Payroll Number: cs13514                       |
|	 E-mail: csteele5@csc.com                   |
|	 Phone: 310-321-8776                           |
| 	 Date Created: 11/11/18 					       |
--------------------------------------------------->
<!-------------------------------------------------------------------------
	11/11/18  - dedicated page to launch PDXC support pages
---------------------------------------------------------------------------->		
<?php
	session_start();

	/* security include */
	include 'dbconn.inc';
	include 'security.inc';
	include 'common/mainFunctions.php';
	

	$currentUser = $_SESSION['userID'];
	
	$currentPage = 'home.php';
	$currentPageBodyClass = '';
	$breadCrumb = '<li><a href="#">XCDE</a> <span class="divider">/</span></li>
				   <li class="active"><a href="home.php">Home</a></li>';
				   // <li><a href="t_PDXCHome.php">Home</a> <span class="divider">/</span></li>

	$refreshrights = 1;
	include 'common/header.php';

	$thisPageRights = 0;
	$allowFieldEdit = 0;
	$XCDEAccessRights = $PageXCDEAccessRights;
	$XCDEUserRights = $PageXCDEUserRights;
	$XCDEAdminRights = $PageXCDEAdminRights;
	if ($XCDEAccessRights == 0) {
		// kick it back to home page with access message
		echo "<script>window.location = 'login.php?msg=1'</script>";exit;
	}

?>		
	<div class="hero-unit hero-unit-tight">
		<h2>PDXC Transport Configuration</h2>
		<p>CI and Attribute mapping through a web interface used to directly manage active maps in XCDE.  
			This capability breaks down the mapping to the "system-to-system" level with the XCDE system handling transformation
			between the start and end systems.  <a href="#pdxcTransportHelpModal" data-toggle="modal" >Issues & Questions</a></p>
		<table>
			<tr>
				<td>
		<p>
			<a href="xcde_MapWizard.php" class="btn btn-primary btn-large">
			CMDB Mapping Transformation Sets
			</a>
		</p>
				</td>
				<td class="span2">
				</td>
				<td rowspan="2" class="">
					<img src="img/MapConceptualDiagram.jpg" class="img-rounded mapDiagram hide">
					<p class="mapDiagramLinkText"><a href="#" id="mapDiagramLink">Show Mapping Diagram</a></p>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="input-topAlign">
					<p class="mapDiagramText hide">This is a high level diagram of a map as a part of a <i>transformation set</i>, the components that make up the map, and the primary output of the completed map.</p>
				</td>
			</tr>
		</table>
	</div>
	<div class="row-fluid container-Main">
		<div class="span6">
		  <span class="lead">Mapping Links</span>
		  <div class="widget-content">
			<table class="table table-hover modifiedText">
				<tbody>
					<!-- <tr onClick="openUrlbyString('xcde_Admin_Class.php')" class="addPointer" rel="tooltip" title="Class hierarchy for all systems used in mapping.">
						<td>CI Class Hierarchy</td>
					</tr> -->
					<tr onClick="openUrlbyString('xcde_MapWizard.php')" class="addPointer" rel="tooltip" title="System to System transformation sets.">
						<td>CMDB Mapping</td>
					</tr>
					<tr onClick="openUrlbyString('xcde_SNOW_Dictionary.php')" class="addPointer" rel="tooltip" title="ServiceNow CI/Attribute List.">
						<td>SNOW CMDB Dictionary</td>
					</tr>
					<tr onClick="openUrlbyString('xcde_UCMDB_Dictionary.php')" class="addPointer" rel="tooltip" title="UCMDB CI/Attribute List. Modify template parameters">
						<td>UCMDB Dictionary / Template Parameter Management</td>
					</tr>
					<tr onClick="openUrlbyString('xcde_MapTemplates.php')" class="addPointer" rel="tooltip" title="Mapping template creation.">
						<td>Mapping Templates</td>
					</tr>
				</tbody>
			</table>
		  </div>
				
			<hr>

			<span class="lead hide">Reports</span>
			  <div class="widget-content hide">
				<table class="table table-hover">
					<tbody>
						<tr class="addPointer" rel="tooltip" title="(placeholder).">
							<td>Report 1</td>
						</tr>
						<tr class="addPointer" rel="tooltip" title="(placeholder).">
							<td>Report 2</td>
						</tr>
						<tr class="addPointer" rel="tooltip" title="(placeholder).">
							<td>Report 3</td>
						</tr>
					</tbody>
				</table>
			  </div>
			
	
		</div>
		<div class="span6">
			<span class="lead">Admin Links</span>
			  <div class="widget-content">
				<table class="table table-hover">
					<tbody>
						<tr <?php if ($XCDEAdminRights == 0){ ?>onClick="alert('Admin rights required');" <?php } else { ?>onClick="openUrlbyString('xcde_EmpList.php')" <?php } ?> class="addPointer" rel="tooltip" title="Add and remove users for the XCDE Mapping module.">
							<td>Module Users</td>
						</tr>
						<tr onClick="openUrlbyString('xcde_Relationships_PairList.php')" class="addPointer" rel="tooltip" title="Relationship management for use in maps.">
							<td>Relationship Management</td>
						</tr>
						<tr <?php if ($XCDEAdminRights == 0){ ?>onClick="alert('Admin rights required');" <?php } else { ?>onClick="openUrlbyString('xcde_Relationships_SchemaList.php')" <?php } ?> class="addPointer" rel="tooltip" title="Relationship schema management for use in maps.">
							<td>Relationship Schema Management</td>
						</tr>
						<tr onClick="openUrlbyString('xcde_Config_List.php')" class="addPointer" rel="tooltip" title="XCDE Configurations for use in maps.">
							<td>Map Configuration Management</td>
						</tr>
						<tr onClick="openUrlbyString('xcde_Connection_List.php')" class="addPointer" rel="tooltip" title="Connections groups for use cases.">
							<td>Connection Management</td>
						</tr>
						<tr <?php if ($XCDEAdminRights == 0){ ?>onClick="alert('Admin rights required');" <?php } else { ?>onClick="openUrlbyString('xcde_Systems.php')" <?php } ?> class="addPointer" rel="tooltip" title="Systems for mapping.">
							<td>System List</td>
						</tr>
						<tr <?php if ($XCDEAdminRights == 0){ ?>onClick="alert('Admin rights required');" <?php } else { ?>onClick="openUrlbyString('xcde_Environments.php')" <?php } ?> class="addPointer" rel="tooltip" title="Environments for mapping.">
							<td>Environment List</td>
						</tr>
					</tbody>
				</table>
			  </div>
	
		</div>
	</div>



<div id="pdxcTransportHelpModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="pdxcTransportHelp" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3>Issue & Questions</h3>
	</div>
	<div class="modal-body">
		<span class="">If there are missing options, functionality requests or other issues, contact PDXC Orchestration for support.  csteele5@dxc.com</span>
	</div>

	<div class="modal-footer">
		<span class="pull-left">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		</span>
	</div>

</div>

<?php
	$TestJavascriptInclude = 'home_script.js';
	include 'common/footer.php';
?>			
		