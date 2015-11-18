<?php include('header.php'); ?> <!-- includes header  -->
<?php 
	//defining default values
	$message = "";

	//checking for $_POST values from form
	if(isset($_POST['register'])){
		//assigning values of POST values to variables
		$username = $_POST['registerusername'];
		$fname = $_POST['registerfname'];
		$lname = $_POST['registerlname'];
		$password = $_POST['registerpassword'];
		$cpassword = $_POST['registerconfirm_password'];
		$contact = $_POST['registercontact_num'];			
		$email = $_POST['registeremail'];		
	
		//if the passwords dont match then unset the values , set an error message and set the a variable that will denote the form has errors.
		if($password != $cpassword){ 
			$password = "";
			$cpassword = "";
			$messagepass = "The Passwords didn't match!";
			$hasError = true;
		}

		$values = "SELECT * FROM tbl_organizers";  	   	//get values from tbl_organizers
		$results = mysql_query($values);
	   	while($row = mysql_fetch_assoc($results)){    
			$check_username[] = $row['uname'];  
			$check_email[] = $row['email'];  
	    }	    

	    for($i=0;$i<count($check_username);$i++){  // loop to check if the entered username is already in our database
	    	if($username == $check_username[$i]){
	    		$messageuname = "Username already exists!";
				$hasError = true;			    		
	    	}	    	
	    }

	    for($i=0;$i<count($check_email);$i++){ // loop to check if the entered email is already registerd in our system
	    	if($email == $check_email[$i]){
	    		$messageemail = "Email Id is already registered!";
				$hasError = true;
	    	}	    	
	    }

		//if $hasError is false submit the form and send the values to database
		if(!$hasError){
			$data='';
			$data['uname']=$username;
			$data['upass']=$password;
			$data['fname']=$fname;
			$data['lname']=$lname;
			$data['email']=$email;
			$data['mobile']=$contact;

			$return_value = "";
			$return_value = $mydb->insertQuery('tbl_organizers',$data);

			if($return_value>0){			  
			  $randomnumber = date('Ymdhis');
			  $_SESSION[CLIENT] =  $randomnumber.$return_value;
			                
              $data='';
              $data['user_id'] = $return_value;                           
              $data['session_id'] = $_SESSION[CLIENT];
              $data['date']=date('y/m/d');
             
              $mydb->insertQuery('tbl_login',$data); 

              $email_message = "Thank You for registering with Eventos Auditorium. You will now be able to login to the website to schedule your events.";
              $mydb->sendEmail($fname,$email,"Admin","info@eventos.com","Registration Succesful",$email_message);

			  echo '<script type="text/javascript">
						 window.location = "http://www.eventseventos.info/dashboard.php?events"
					</script>';				
			}
		}
	}
	// window.location = "http://www.eventseventos.info/dashboard.php?events"
?>
<style type="text/css">
header>.navbar { background-color: #7EEEEB; }
.container {background-color: white; margin: auto; width: auto;}
.container>.row { padding-top: 45px; margin: auto; width: auto; padding:0px; padding-top: 40px; padding-bottom: 15px;}
</style>

<header>
	<nav class="navbar navbar-inverse navbar-fixed-top"> 
		<div class="container-fluid">
	    	<div class="navbar-header"><a class="navbar-brand" href="index.php">Eventos</a></div>
		</div>
	</nav> 
</header>
<div class="container">
		<div class="row">
			<div id="registration">
				<h2>REGISTRATION</h2>
				&nbsp;&nbsp;
				<span id="registration_fail" class="response_error" style="display: none;">Registration failed, please try again.</span>
				<div class="clearfix"></div>
				<?php if ( $message ) echo $message; ?>					   
				<form action="" method="POST" name="registration">
		   			<div class="form-group">
					    	<div class="input-group">
					      		<div class="input-group-addon"><span class="glyphicon glyphicon-user"> Username</span></div>
								<input type="text" class="form-control" id="registerusername" placeholder="Username" name="registerusername" value="<?php if(!empty($username)){ echo $username; }?>" required>
					    	</div>
					    	<?php if ( $messageuname ) echo "&nbsp;&nbsp;<p class = 'alert alert-danger' style='text-align:center; padding: 0px;'>".$messageuname."</p>"; ?>
					    	<span class="help-block has-error" data-error='0' id="username-error"></span>
					  	</div>								
							<div class="form-group">
								<div class="input-group">
									<div class="input-group-addon"><span class="glyphicon glyphicon-lock"> Password</span></div>
										<input type="password" class="form-control" id="registerpassword" placeholder="Password" name="registerpassword" value="<?php if(!empty($password)){ echo $password; }?>" pattern=".{6,}" title="Six or more characters" required>
								</div>								
								<?php if ( $messagepass ) echo "&nbsp;&nbsp;<p class = 'alert alert-danger' style='text-align:center; padding: 0px;'>".$messagepass."</p>"; ?>
							</div>
							<div class="form-group">
								<div class="input-group">
									<div class="input-group-addon"><span class="glyphicon glyphicon-lock"> ConfirmPassword</span></div>
										<input type="password" class="form-control" id="registerconfirm_password" placeholder="Confirm Password" name="registerconfirm_password" value="<?php if(!empty($cpassword)){ echo $cpassword; }?>" pattern=".{6,}" title="Six or more characters" required>
								</div>								
								<?php if ( $messagepass ) echo "&nbsp;&nbsp;<p class = 'alert alert-danger' style='text-align:center; padding: 0px;'>".$messagepass."</p>"; ?>
							</div>
							<div class="form-group">
						    	<div class="input-group">
						      		<div class="input-group-addon"><span class="glyphicon glyphicon-user"> Firstname</span></div>
						      		<input type="text" class="form-control" id="registerfname" placeholder="Firstname" name="registerfname" value="<?php if(!empty($fname)){ echo $fname; }?>" required>
						    	</div>
						 </div>
						<div class="form-group">
					    	<div class="input-group">
					      		<div class="input-group-addon"><span class="glyphicon glyphicon-user"> Lastname</span></div>
								<input type="text" class="form-control" id="registerlname" placeholder="Lastname" name="registerlname" value="<?php if(!empty($lname)){ echo $lname; }?>" required>
					    	</div>
					  	</div>
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-addon"><span class="glyphicon glyphicon-envelope"> Email</span></div>
									<input type="email" class="form-control" id="registeremail" placeholder="Email" name="registeremail" value="<?php if(!empty($email)){ echo $email; }?>" required>
							</div>
							<?php if ( $messageemail ) echo "&nbsp;&nbsp;<p class = 'alert alert-danger' style='text-align:center; padding: 0px;'>".$messageemail."</p>"; ?>
								<span class="help-block has-error" data-error='0' id="email-error"></span>
						</div>
						<div class="form-group">
						<div class="input-group">
							<div class="input-group-addon"><span class="glyphicon glyphicon-phone"> Mobile</span></div>
								<input type="number" class="form-control" id="registercontact_num" placeholder="+001XXXXXXXXXX" name="registercontact_num" value="<?php if(!empty($contact)){ echo $contact; }?>" required>
							</div>
						</div>
			  			<button type="submit" id="register_btn" class="btn btn-block bt-login" data-loading-text="Signing In...." name="register">Register</button>
						<div class="clearfix"></div>
					</form>
			</div>

		</div>
</div> 

<?php include('footer.php'); ?> <!-- includes footer  -->