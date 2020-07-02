/*-------------------------------------------------- 
|	 Name: Charles Steele                          |
|	 Payroll Number: cs13514                       |
|	 E-mail: csteele5@csc.com                      |
|	 Phone: 310-321-8776                           |
| 	 Date Created: 5/22/13 					   |
---------------------------------------------------*/
/*-------------------------------------------------------------------------
	5/22/13  - common js
	6/5/13 - add openUrlbyStringNewTab
----------------------------------------------------------------------------*/
function ChildWindowHead(destination, windowparams){
	Qcodechild = window.open(destination,"ChildWindowHead", windowparams);
	Qcodechild.focus();
}
function ChildWindow(destination, windowparams){
	Qcodechild = window.open(destination,"ChildWindow", windowparams);
	Qcodechild.focus();
}

/*******************  launch page in cf twos or use form to open page and hide variables ********************/
function launchCFTWOS(page, WOID, UID, RID) {
	if (page == null) {page = 0;}	
	if (WOID == null) {WOID = 0;}	
	if (UID == null) {UID = '';}
	if (RID == null) {RID = 0;}					
	document.cfAccess.action = "../twosV4/home.cfm";
	var separatePage = 0;
	
	switch (parseInt(page))
	 {
		 case 19:
		   document.cfAccess.action = "../twosV2/userconfigAvail_Sub.cfm";
		   separatePage = 1;
		   break;
		 case 18:
		   document.cfAccess.action = "../twosV2/WOStatus_launch.cfm";
		   //document.cfAccess.action = "";
		   // onsubmit="window.open('about:blank','print_popup','width=1000,height=800');"
		   //$('#cfAccess').onSubmit = function() { alert("test on submit")};
		   //alert('after on submit');
		   separatePage = 1;
		   break;
		 case 17:
		   document.cfAccess.action = "../twosV2/firstlevel.cfm?selectedTab=reports";
		   separatePage = 1;
		   break;
		 case 16:
		   document.cfAccess.action = "../twosV4/reportsgbl.cfm";
		   separatePage = 1;
		   break;
		 case 15:
		   document.cfAccess.action = "../twosV2/TWOSPost.cfm";
		   break;
		 case 14:
		   document.cfAccess.action = "../twosV4/reports.cfm";
		   break;
		 case 13:
		  /* document.cfAccess.action = "../twosv2/userconfigAvail_sub.cfm?UserID="+UID;*/
		   document.cfAccess.action = "../twosv2/DailyTechCheckOut_sub.cfm";
		   document.cfAccess.userID.value = UID;
		   separatePage = 1;
		   break;
		 case 12:
		   document.cfAccess.action = "../twosV4/resReq_Request.cfm?ResLevReqID="+RID;
		   document.cfAccess.ResLevReqID.value = RID;
		   break;
		 case 11:
		   document.cfAccess.action = "../twosV4/RR_EmpProfile.cfm";
		   document.cfAccess.userID.value = UID;
		   break;
		 case 10:
		   document.cfAccess.action = "al_Create.php";
		   document.cfAccess.userID.value = UID;
		   /*alert('UID2 '+UID);*/
		   break;
		 case 9:
		  /* document.cfAccess.action = "../twosv2/userconfigAvail_sub.cfm?UserID="+UID;*/
		   document.cfAccess.action = "../twosv2/userconfigAvail_sub.cfm";
		   document.cfAccess.userID.value = UID;
		   separatePage = 1;
		   break;
		 case 8:
		   document.cfAccess.action = "../twosV4/RR_EmpAdmin.cfm";
		   document.cfAccess.userID.value = UID;
		   break;
		 case 7:
		   document.cfAccess.action = "../twosV4/client_SitePendingList.cfm";
		   break;
		 case 6:
		   document.cfAccess.action = "../twosV4/client_AcctList.cfm";
		   break;
		 case 5:
		   document.cfAccess.action = "../twosV4/RR_EmpList.cfm";
		   break;
		 case 4:
		   document.cfAccess.action = "../twosV4/resReq_List.cfm";
		   break;
		 case 3:
		   document.cfAccess.action = "../twosV4/client_SiteList.cfm";
		   break;
		 case 2:
		   document.cfAccess.action = "../twosV4/client_SiteCoverage.cfm";
		   break;
		 case 1:
		   newURL = "../twosV2/firstlevel.cfm?selectedTab=workorders&openWorkOrderID="+WOID;
			if (WOID == 0) {
				newURL = newURL+"&mineOnly=1";
			}
			
			document.cfAccess.action = newURL;
		   break;
		 default:
		   document.cfAccess.action = "../cfsessioncreate.cfm";
		   separatePage = 1;
	 } 
	
	document.cfAccess.method = "post" ;
	if (separatePage == 1) {
		document.cfAccess.target = "_blank";
	}

	document.cfAccess.submit();
}
/*******************  END launch page in cf twos ********************/


/*******************  launch report page in cf form to open page and hide variables ********************/
function launchCFReport(page, AGID) {
	if (page == null) {page = '';}
	if (AGID == null) {AGID = 0;}					
	
	if (page != '') {
		document.cfReportAccess.action = page;
		document.cfReportAccess.AreaGroupID.value = AGID;
		document.cfReportAccess.target = "_blank";

		document.cfReportAccess.submit();

	}
}
/*******************  END launch report in cf twos ********************/

/*******************  redirect functions ********************/
function openUrlbyString(urlString) { 
   if (urlString == null) {urlString = '';}
   window.location.href = urlString;
   return false;
}	
function openUrlbyStringNewTab(urlString) { 
   if (urlString == null) {urlString = '';}
	window.open(
	  urlString,
	  '_blank' // <- This is what makes it open in a new window.
	);
   return false;
}	
/*******************  END redirect functions ********************/

/*******************  hide bulletin ********************/
function hideBulletin(BID)
{
	//alert('TEST3');
	//document.getElementById("txtHint").innerHTML="";
	if (BID=="")
	  {
	  return;
	  } 
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }

	xmlhttp.onreadystatechange=function()
	  {
	  // this is not needed since we are just setting a session variable
	  if (xmlhttp.readyState==4 && xmlhttp.status==200 && 1==2)
	    {	    
	    	//alert('successful shut off of message again')
	    	document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
	    }
	  }
	xmlhttp.open("GET","ajax/hideBulletin.php?BID="+BID,true);
	xmlhttp.send();
}
/*******************  END hide bulletin ********************/


/*******************  pwd reset functions ********************/
// password link is clicked in header
$('#resetLink').click(function(){
	document.getElementById('pwdMessage').innerHTML = '<i>Enter your current and new password.</i>';
	document.pwdResetForm.origPwd.value = '';
	document.pwdResetForm.newPwd1.value = '';
	document.pwdResetForm.newPwd2.value = '';
	$('.hideOnSuccess').show();
}); 

function pwdResetValidate() {
	var S = document.pwdResetForm.userID.value; 
	var S0 = document.pwdResetForm.origPwd.value;                                           
	var S1 = document.pwdResetForm.newPwd1.value;                                                     
	var S2 = document.pwdResetForm.dbcurrentpwd.value;                                         
	var nums = '0123456789';        
	var pwdValidateMsg = 'There is 1 or more problem! ';  
	var stopValidation = 0;   
	var failValidation = 0;                                  
	if (S0 != S2)
	{
	   // alert('Current password is incorrect');
		pwdValidateMsg = 'Current password is incorrect!'; 
		document.pwdResetForm.origPwd.value = '';
		failValidation = 1;
		stopValidation = 1;
		//return false;
	}                                       
	if (S0 == S1 && S0 != '' && stopValidation == 0)
	{
		//alert('You cannot reuse current password');
		pwdValidateMsg = 'You cannot reuse current password!'; 
		document.pwdResetForm.newPwd1.value = '';
		document.pwdResetForm.newPwd2.value = '';
		failValidation = 1;
		stopValidation = 1;
		//return false;
	}                                           
	if (S1 != document.pwdResetForm.newPwd2.value && stopValidation == 0)
	{
	   // alert('Confirmed passwords do not match');
		pwdValidateMsg = 'The new passwords do not match!'; 
		stopValidation = 1;
		document.pwdResetForm.newPwd1.value = '';
		document.pwdResetForm.newPwd2.value = '';
		failValidation = 1;
		stopValidation = 1;
	   // return false;
	}                                               
	if (S1.length < 8 && stopValidation == 0)
	{
		//alert('Password must be at least 8 characters');
		pwdValidateMsg = pwdValidateMsg+'<br>Password must be at least 8 characters.'; 
		document.pwdResetForm.newPwd1.value = '';
		document.pwdResetForm.newPwd2.value = '';
		failValidation = 1;
		//return false;
	}                                               
	if (S1.toUpperCase() == S1 && stopValidation == 0)
	{
		//alert('Password must have at least one Lowercase Letter');
		pwdValidateMsg = pwdValidateMsg+'<br>Password must have at least one lowercase letter.'; 
		document.pwdResetForm.newPwd1.value = '';
		document.pwdResetForm.newPwd2.value = '';
		failValidation = 1;
		//return false;
	}
	if (S1.toLowerCase() == S1 && stopValidation == 0)
	{
		//alert('Password must have at least one Uppercase Letter');
		pwdValidateMsg = pwdValidateMsg+'<br>Password must have at least one uppercase letter.'; 
		document.pwdResetForm.newPwd1.value = '';
		document.pwdResetForm.newPwd2.value = '';
		failValidation = 1;
		//return false;
	}
	Num_in = 'no';
	for (var I = 0; I < S1.length; I++)
		{
		 testbyte = S1.charAt(I);
		 if (nums.indexOf(testbyte) != -1)
		  {
			Num_in = 'yes';                                                        
		  } 
		 }
	 if (Num_in == 'no' && stopValidation == 0)                            
	   {
	   // alert('Password must have at least one Number');
		pwdValidateMsg = pwdValidateMsg+'<br>Password must have at least one number.'; 
		document.pwdResetForm.newPwd1.value = '';
		document.pwdResetForm.newPwd2.value = '';
		failValidation = 1;
	   // return false;
	   } 
	//document.pwdResetForm.submit();  
	if (failValidation == 0) {
		//begin ajax call
		var xmlhttp;
		if (window.XMLHttpRequest)
		{// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else
		{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		//alert('TEST5a');  
		xmlhttp.onreadystatechange=function()
		{
			//alert('ready state '+xmlhttp.readyState);
			//alert('ready status '+xmlhttp.status);
			if (xmlhttp.readyState == 4)
			{
				//alert('ready status-inner '+xmlhttp.status);
				// only if "OK"
				if (xmlhttp.status == 200)
				{
					document.getElementById('pwdMessage').innerHTML = xmlhttp.responseText;
					$('.hideOnSuccess').hide();
					$('#dbcurrentpwd').val(S1);                                
				} else {
					alert("There was a problem while using XMLHTTP:\n" + xmlhttp.statusText);
				}
			}
		}
		xmlhttp.open("GET","ajax/setPassword.php?UID="+S+"&PWD="+S1,true);
		xmlhttp.send();
		//end ajax call

	} else {
		//alert(pwdValidateMsg);
		document.getElementById('pwdMessage').innerHTML = '<span class="text-error">'+pwdValidateMsg+'</span>';
	}

	
}
/*******************  END pwd reset functions ********************/


