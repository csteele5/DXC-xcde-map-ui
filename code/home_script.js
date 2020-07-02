//<!-------------------------------------------------- 
//	 Name: Charles Steele                          |
//	 Payroll Number: cs13514                       |
//	 E-mail: csteele5@csc.com                      |
//	 Phone: 310-321-8776                           |
// 	 Created: 11/11/18 					   		|
//--------------------------------------------------->
//<!-------------------------------------------------------------------------
//	11/11/18 - head script include
//---------------------------------------------------------------------------->

$(document).ready(function(){
	

	$('span.removeDelegateLink').click(function(){
		resetEditButtons();
		$(this).hide();
		$(this).next('span.deleteDelegateLink').fadeIn('fast');

	}); 


	$("#RegionMgrName").autocomplete("ajax/findAnyUserwInfo.php", {
		width: 260,
		matchContains: true,
		mustMatch: true,
		//minChars: 0,
		//multiple: true,
		//highlight: false,
		//multipleSeparator: ",",
		selectFirst: false
	});
 
    $("#RegionMgrName").result(function(event, data, formatted) {
		if((typeof data != 'undefined') && (data instanceof Array)) {
			$("#RegionMgrID").val(data[1]);
			resetEditButtons();
			$('#saveRegionChangesBtn').slideDown();
		}
    });


	$("#newDelegateName").autocomplete("ajax/findAnyUserwInfo.php", {
		width: 260,
		matchContains: true,
		mustMatch: true,
		//minChars: 0,
		//multiple: true,
		//highlight: false,
		//multipleSeparator: ",",
		selectFirst: false
	});
 
    $("#newDelegateName").result(function(event, data, formatted) {
		if((typeof data != 'undefined') && (data instanceof Array)) {
			$("#newDelegateID").val(data[1]);
			resetEditButtons();
			$('#addDelegateBtn').slideDown();
		}
    });

 
	$('#RegionGroupID').change(function(){	
		resetEditButtons();
		$('#saveRegionChangesBtn').slideDown();

	});



	$('#saveRegionChangesBtn').click(function(){
		$('#actionType').val(1);
		document.regEditForm.submit();
	});	


	$('.deleteDelegateBtn').click(function(){	

		mgrid = $(this).closest("div.controls").find("input.DelegateID").val();
		$('#removeDelegateID').val(mgrid);

	});	


	$('#confirmDelegateRemovalBtn').click(function(){
		$('#actionType').val(3);
		document.regEditForm.submit();
	});	


	$('#addDelegateBtn').click(function(){
		$('#actionType').val(2);
		document.regEditForm.submit();
	});	

	
 	// region
	$('#newCountryID').change(function(){	
		resetEditButtons();
		if ($(this).val() > 0) {
			$('#addCountryBtn').slideDown();
		}		

	});


	$('span.removeCountryLink').click(function(){
		resetEditButtons();
		$(this).hide();
		$(this).next('span.deleteCountryLink').fadeIn('fast');

	}); 


	$('.deleteCountryBtn').click(function(){	
		cid = $(this).closest("div.controls").find("input.CountryID").val();
		$('#removeCountryID').val(cid);

	});	


	$('#confirmCountryRemovalBtn').click(function(){
		$('#actionType').val(5);
		document.regEditForm.submit();
	});	


	$('#addCountryBtn').click(function(){
		$('#actionType').val(4);
		document.regEditForm.submit();
	});	


	$('#dotNetLink').click(function(){
		//$('#actionType').val(4);
		document.dotnetAccess.submit();
	});	


	$('#mapDiagramLink').click(function(){
		$(".mapDiagramLinkText").hide();
		
		$(".mapDiagram").show();
		$(".mapDiagramText").show();


	});	


});


function checkRequestedManagerSearchValue()
{
	var valCheck = $("#RegionMgrName").val();
	if (!valCheck) {
		$("#RegionMgrID").val('');
	}
}

function checkDelegateSearchValue()
{
	var valCheck = $("#newDelegateName").val();
	if (!valCheck) {
		$("#newDelegateID").val('');
	}
}	

function resetEditButtons() {
	$('span.deleteDelegateLink').hide();
	$('span.removeDelegateLink').show();	
	$('#addDelegateBtn').hide(); 
	$('#saveRegionChangesBtn').hide(); 

	$('span.deleteCountryLink').hide();
	$('span.removeCountryLink').show();	
	$('#addCountryBtn').hide(); 
}
