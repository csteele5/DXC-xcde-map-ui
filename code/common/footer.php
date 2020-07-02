<!-------------------------------------------------- 
|	 Name: Charles Steele                          |
|	 Payroll Number: cs13514                       |
|	 E-mail: csteele5@csc.com                      |
|	 Phone: 310-321-8776                           |
| 	 Date Created: 4/8/13 					   |
--------------------------------------------------->
<!-------------------------------------------------------------------------
	4/8/13  - footer
	6/23/13 - add refreshrights=1 to launchCFTWOS form
	4/2/14 - add conditional loading of calendar
	4/9/14 - add close to reporting server connection string
	5/23/16 - add google analytics for Aaron.  may be only temporary
	4/3/17 - change for DXC
---------------------------------------------------------------------------->
<?php
	if (!isset($hideFooter)) {
		$hideFooter = 0;
	}
	if (!isset($loadHighCharts)) {
		$loadHighCharts = 0;
	}
	if (!isset($loadCalendar)) {
		$loadCalendar = 0;
	}
?>
<!-- BEGIN FOOTER -->

 	<?php
	if ($hideFooter == 0) {
	// BEGIN hide this if no footer is displayed
	?>	
            <hr class="shorthr">
    
			<footer>
			&copy;Copyright 2019, DXC Technology Company 
			</footer>
 	<?php
	// END hide this if no footer is displayed
	}
	?>	

            <div id="resetModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="pwdRstRequest" aria-hidden="true">
                <form method="post" id="pwdResetForm" name="pwdResetForm">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 id="myModalLabel">Password Reset</h3>
                </div>
                <div class="modal-body">
                    <p id="pwdMessage"><i>Enter your current and new password.</i></p>
                        
                    <!--[if lt IE 10]>
                        <label class="control-label hideOnSuccess">Current Password</label>
                    <![endif]-->
                    <div class="controls hideOnSuccess">
                        <input id="origPwd" name="origPwd" type="password" class="input-large" placeholder="current password"
                        value="">
                    </div>
                        <!-- <span class="help-inline"><i>An email will be sent</i></span> -->
                        
                    <!--[if lt IE 10]>
                        <label class="control-label hideOnSuccess">New Password</label>
                    <![endif]-->
                    <div class="controls hideOnSuccess">
                        <input id="newPwd1" name="newPwd1" type="password" class="input-large" placeholder="new password"
                        value="">
                    </div>
                        
                    <!--[if lt IE 10]>
                        <label class="control-label hideOnSuccess">Confirm Password</label>
                    <![endif]-->
                    <div class="controls hideOnSuccess">
                        <input id="newPwd2" name="newPwd2" type="password" class="input-large" placeholder="confirm new password"
                        value="">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                    <!-- <button class="btn btn-primary" onclick="pwdResetValidate()">Submit</button>
                    <button class="btn">test</button> -->
                    <input class="btn btn-primary hideOnSuccess" type="button" value="Submit" onclick="pwdResetValidate()">
                </div>
                <input type="hidden" id="userID" name="userID" value="<?php echo $_SESSION['userID'] ?>">
                <input type="hidden" id="dbcurrentpwd" name="dbcurrentpwd" value="<?php echo $_SESSION['TWOSPword'] ?>">  
                </form>
            </div>

        </div> <!-- /container -->
		<div id="issueReportingModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="issueReporting" aria-hidden="true">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3>Issues & Questions</h3>
			</div>
			<div class="modal-body">
				<dl>
				  <dt>For questions about how to use PDXC Transport Configuration...</dt>
				  <dd>Contact the Corellia team technical support.  You may also send an email with your question or suggestion 
				  	to Charles Steele (csteele5@dxc.com)</dd>
				  
				  
				</dl>

			</div>

			<div class="modal-footer">
				<!-- <button class="btn btn-primary" onclick="pwdResetValidate()">Submit</button>
				<button class="btn">test</button>  onclick="pwdResetValidate()"-->
				<span class="pull-left">
					<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Close</button>
				</span>
				<!-- <input id="addLocBtn" class="btn btn-primary hideOnSuccess reqButtons setFormDirty" type="submit" value="Add Location"> -->
			</div>
		</div>

        <script src="js/vendor/jquery-1.9.1.min.js"></script>

        <!--<script src="js/vendor/jquery-ui-1.9.2.custom.min.js"></script>-->

        <script src="js/vendor/bootstrap.min.js"></script>
        <script src="js/vendor/bootstrap-dropdown.js"></script>
        <script src="js/vendor/bootstrap-datepicker.js"></script> <!-- IMPORTANT - This file has been modified, so do not replace -->
        <script src="js/vendor/bootstrap-datetimepicker.min.js"></script>
		
       
		<script src="js/c_add/jquery-migrate-1.2.1.min.js"></script>
		<script src="js/c_add/jquery.autocomplete.js"></script>



        <script src="js/main.js"></script>

		<?php //echo $TestJavascriptInclude.'<br>';
			// text for javascript include<?php 
			if (isset($TestJavascriptInclude)) {
				echo ('<script src="'.$TestJavascriptInclude.'"></script>');
			}
			if (isset($TestFunctionJavascriptInclude)) {
				echo ('<script src="'.$TestFunctionJavascriptInclude.'"></script>');
			}
			// this is initially for highcharts.  php must be integrated with javascript
			if (isset($TestPHPInclude)) {
				include $TestPHPInclude;
			}


			
		?>			
		
        <script>
            /*var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
*/

/*  comment this out in dev
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-78188171-1', 'auto');
		  ga('send', 'pageview');
*/

			$(document).ready(function(){			
				 $(".dropdown-toggle").dropdown();
				 $('body').on('touchstart.dropdown', '.dropdown-menu', function (e) { e.stopPropagation(); });
				 $('.datepicker').datepicker();
				 
				 $('[rel=tooltip]').tooltip(); 
				 
				 /* where loading divs exist */
				 $("tr.loadingRow").hide();
				 $("tr.hidden-loading").show();

				 
				// add this class to all processing message alerts to fade out after users sees it
				$('div.alert.timedFadeMsg').delay(3000).slideUp('slow');
				 
				 /* function origin : http://www.html-form-guide.com/jquery/drop-down-list-jquery.html */
				/* function loadlist(selobj,url,nameattr)
				 {
					$(selobj).empty();
					$.getJSON(url,{},function(data)
					{
						$.each(data, function(i,obj)
						{
							$(selobj).append(
								 $('<option></option>')
										.val(obj[nameattr])
										.html(obj[nameattr]));
						});
					});
				 }*/

				$('.longProcessingButton').click(function(){	
					$(this).html('Processing...');
					 $(this).attr("disabled", true);
					var div= document.createElement("div");
				    div.className += "overlay";
				    document.body.appendChild(div);
				});

				 
			});



			
        </script>
		
		<?php 
			sqlsrv_close($conn);
		?>	
		
    </body>
</html>
