//<!-------------------------------------------------- 
//	 Name: Charles Steele                          |
//	 Payroll Number: cs13514                       |
//	 E-mail: csteele5@csc.com                      |
//	 Phone: 310-321-8776                           |
// 	 Created: 6/4/18 					   |
//--------------------------------------------------->
//<!-------------------------------------------------------------------------
//	6/4/18 - head script include
//---------------------------------------------------------------------------->

$(document).ready(function(){

	
	// findEmployeewInfo includes users not listed as active in LBS
	$("#selEmployeeName").autocomplete("ajax/findAnyUserwInfo.php", {
		width: 260,
		matchContains: true,
		mustMatch: true,
		//minChars: 0,
		//multiple: true,
		//highlight: false,
		//multipleSeparator: ",",
		selectFirst: false
	});
 
    $("#selEmployeeName").result(function(event, data, formatted) {
		if((typeof data != 'undefined') && (data instanceof Array)) {
			$("#selUserID").val(data[1]);
		}
    });
	
	
	// check for which dropdown should show in the filter list	
	$("#toggleView").change(function () {
		toggleSelView();
	});

	toggleSelView();
	
		
}); 	

function toggleSelView() {
	var t = $('#toggleView').val();  
	//alert('TEST '+t);
	if (t == 2) {
		$('#projectOrgBlock').hide();	
		$('#employeeFilterBlock').fadeIn('slow');
		$('#moduleAccessBlock').hide();

	} else if (t == 1) {
		$('#projectOrgBlock').hide();	
		$('#employeeFilterBlock').fadeIn('slow');
		$('#moduleAccessBlock').hide();

	} else {
		$('#projectOrgBlock').fadeIn('slow');	
		$('#employeeFilterBlock').hide();	
		$('#moduleAccessBlock').fadeIn('slow');
	} 

} 


function checkSelSearchValue() {
	var valCheck = $("#selEmployeeName").val();
	if (!valCheck) {
		$("#selUserID").val('');
	}
}	



