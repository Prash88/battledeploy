<?php

	include 'includes/constant/sql_constants.php';
	include 'includes/constant/dbc.php';	
	define('MyConst', TRUE);

?>

<html>
	<head> 		
		<title>Registration Form</title>
		<link href="css/bootstrap.css" rel="stylesheet">
		<link href="css/bootstrap-responsive.css" rel="stylesheet">
		<?php include 'header.php'; ?>
		<script src="js/jquery-1.9.1.min.js"></script>
		<script src="js/sorttable.js"></script>
		<script src="js/validate.js"></script>
	</head>
	<body>
		<h2> Fill the details and submit the form </h2>
		<!--Registration form-->
		<form action="data.php" class="form-horizontal" method="post" id="signup">
     		<div class="control-group">
        		<label class="control-label">First Name:</label>
        		<div class="controls">
        			<input name="first_name" required="required" placeholder="Jon" type="text" id="fname">
    			</div>
    		</div>
    		<div class="control-group">
        		<label class="control-label">Last Name:</label>
        		<div class="controls">
		    		<input name="last_name" required="required" placeholder="Doe" type="text" id="lname">
    			</div>
    		</div>
    		<div class="control-group">
        		<label class="control-label">Gender:</label>
          		<div class="controls" id="gender">
        			<input type="radio" name="sex" value="male" checked="checked" /><label>Male</label>
        			<input type="radio" name="sex" value="female" /> <label>Female</label>                  
    			</div>
    		</div>
    		<div class="control-group">
        		<label class="control-label">Email:</label>
          		<div class="controls">
        			<input name="email" required="required" placeholder="random@mail.com" type="email" id="email">
    			</div>
    		</div>
    		<div class="control-group">
        		<label class="control-label">Password:</label>
        		<div class="controls">
        			<input name="password" required="required" placeholder="e.g. X8df!90EO" type="password" id="passwd"> <br \> <br \> <br \>
        			<input value="Submit" type="submit" class="btn btn-primary btn-small">
        		</div>
        	</div>
		</form>
		<!--Validation script-->
		<script src="js/validate.js" type="text/javascript">
	  		$(document).ready(function(){
				$("#signup").validate({
					rules:{
						gender:"required",
						fname:"required",
						lname:"required",
						email:{
								required:true,
								email: true
							},
						passwd:{
							required:true,
							minlength: 8
						},
						conpasswd:{
							required:true,
							equalTo: "#passwd"
						},	
					},
					errorClass: "help-inline"
				});
			});
		</script>
	</body>
</html>
