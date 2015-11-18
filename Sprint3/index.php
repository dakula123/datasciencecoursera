<?php include('header.php'); ?> <!-- includes header  -->

<?php 
	if(isset($_GET['registration']) && $_GET['registration'] == "success"){
?>
<script type="text/javascript">
	  $(document).ready(function(){					        
	       $('#regsuccessmodal').modal('show'); 
	    });
</script>
<?php 
	}
?>		

<?php 
	if(isset($_GET['cancelregistration']) && $_GET['cancelregistration'] == "success"){
?>
<script type="text/javascript">
	  $(document).ready(function(){					        
	       $('#cancelbookingsuccessmodal').modal('show'); 
	    });
</script>
<?php 
	}
?>	

<?php 
	//defining default values
	$message = "";

	if(isset($_POST['login'])){
			$getpass =$_POST['loginpassword'];
   			$getuname = $_POST['loginusername'];
    if($mydb->count_row($mydb->getQuery("*","tbl_organizers","uname = '".$getuname."'")) == 1)
    {
    	 $dbpass = $mydb->getValue("upass","tbl_organizers","uname= '".$getuname."'");        
      if($dbpass == $getpass)
      { 
  					 $_ID = $mydb->getValue("UID","tbl_organizers","uname = '".$getuname."'");
								$randomnumber = date('Ymdhis');
								$_SESSION[CLIENT] =  $randomnumber.$_ID;
							             
							 $data='';
							 $data['user_id'] = $_ID;                           
							 $data['session_id'] = $_SESSION[CLIENT];
							 $data['date']=date('y/m/d');

							 $mydb->insertQuery('tbl_login',$data);
							 echo '<script type="text/javascript">           						
           			 				window.location = "http://www.eventseventos.info/dashboard.php?events"
      								</script>';					
      								// window.location = "http://www.eventseventos.info/dashboard.php?events"
      								// window.location = "http://localhost/eventos/dashboard.php?events"
  				}else{
		    	$login_err = "Login Failed! Please Check Username and Password.";
		    	?>
		    	<script type="text/javascript">
		    		  $(document).ready(function(){					        
					       $('#loginModal').modal('show'); 
					    });
		    	</script>
		    	<?php 
		    }
    }else{
    	$login_err = "Login Failed! Please Check Username and Password.";
    	?>
		<script type="text/javascript">
    		  $(document).ready(function(){					        
			       $('#loginModal').modal('show'); 
			    });
    	</script>
    	<?php 
    }
	}

	if(isset($_GET['reset_success']) && $_GET['reset_success']==1){
			$login_err = "Password reset successfull!";
			     	?>
			<script type="text/javascript">
	    		  $(document).ready(function(){					        				      				       				     
				       $('#loginModal').modal('show'); 				       		     
				    });

	    	</script>
    	<?php 

	}

	if(isset($_POST['reset'])){
			 if($mydb->count_row($mydb->getQuery("*","tbl_organizers","email = '".$_POST['forgetemail']."'")) == 1){			 	
			 	$dbemail = $mydb->getValue("email","tbl_organizers","email= '".$_POST['forgetemail']."'");
			 	$dbfname = $mydb->getValue("fname","tbl_organizers","email= '".$_POST['forgetemail']."'");  
			 	$dblname = $mydb->getValue("lname","tbl_organizers","email= '".$_POST['forgetemail']."'");
			 	$randomnumber = date('Ymdhis');
			 	$code = $dblname.$dbfname.$dbemail.$randomnumber;
			 	$reset_url = md5($code);		
				
				$data ="";
				$data['reset_code'] = $reset_url;

			 	$mydb->updateQuery("tbl_organizers",$data,"email = '".$_POST['forgetemail']."'");

				$fullname = $dbfname." ".$dblname;

				$email_message = "To reset the password please click on the link below.<br>
									<br>
									Link : http://www.eventseventos.info/reset.php?token=".$reset_url;

				$return_mail = $mydb->sendEmail($fullname,$dbemail,"Admin","info@eventos.com","Password Reset",$email_message);

				$forget_message = "The reset link has been set to your email address please use the link to reset password!";
			 }else{
			 	$forget_message = "We couldn't find your email address in our database. Please Try Again!";
			 }


			     	?>
			<script type="text/javascript">
	    		  $(document).ready(function(){					        				      				       				     
				       $('#loginModal').modal('show'); 
				       $('#loginModal a[href="#forget_password"]').tab('show');				      
				    });

	    	</script>
    	<?php 

	}

?>
<!--START HEADERWRAP-->
<div id="headerwrap">
	<div class="container">
		<div class="row centered">
			<div class="col-lg-8 col-lg-offset-2 mt">
				<h1 class="animation slideDown">Welcome to our EVENTOS Auditorium website and book your events with better deals.</h1>
				<?php if(empty($_SESSION[CLIENT])){ ?>
				<p class="mt">
					<button type="button" class="btn btn-cta btn-lg" data-toggle="modal" data-target="#loginModal" id="triggerbutton">Get Started</button>
				</p>
				<?php }else{ ?>
				<p class="mt">
					<a class="btn btn-cta btn-lg" href="dashboard.php?schedule" id="triggerbutton">Schedule Event</a>
				</p>
				<?php } ?>
				<!-- Modal -->
				<div id="loginModal" class="modal fade" role="dialog"  tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content login-modal">
							<div class="modal-header login-modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title text-center" id="loginModalLabel">USER AUTHENTICATION</h4>
							</div> 
							<div class="modal-body">
								<div class="text-center">
									<div role="tabpanel" class="login-tab">
										<!-- Nav tabs -->
									  	<ul class="nav nav-tabs" role="tablist">
									    	<li role="presentation" class="active"><a id="signin-taba" href="#home" aria-controls="home" role="tab" data-toggle="tab">Sign In</a></li>
									    	<li role="presentation"><a id="signup-taba" href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Sign Up</a></li>
									    	<li role="presentation"><a id="forgetpass-taba" href="#forget_password" aria-controls="forget_password" role="tab" data-toggle="tab">Forget Password</a></li>
									  	</ul>								
										<!-- Tab panes -->
										<div class="tab-content" id="myTabs">

											<!-- START OF LOGIN FORM-->
											<div role="tabpanel" class="tab-pane active text-center" id="home">
												&nbsp;&nbsp;												
												<div class="clearfix"></div>												
												<?php if ( $login_err ) echo "&nbsp;&nbsp;<p class = 'alert alert-danger' style='text-align:center; padding: 0px;'>".$login_err."</p>"; ?>
												<form name="loginform" action="" method="POST" >
													<div class="form-group">
														<div class="input-group">
															<div class="input-group-addon"><span class="glyphicon glyphicon-user"> Username</span></div>
																<input type="text" class="form-control" id="loginusername" placeholder="Username" name="loginusername" value="<?php if(isset($_POST['loginusername']) && !empty($_POST['loginusername'])){ echo $_POST['loginusername']; } ?>" required>
														</div>
														<span class="help-block has-error" id="email-error"></span>
													</div>
													<div class="form-group">
														<div class="input-group">
															<div class="input-group-addon"><span class="glyphicon glyphicon-lock"> Password</span></div>
															<input type="password" class="form-control" id="loginpassword" placeholder="Password" name="loginpassword" required>
														</div>
														<span class="help-block has-error" id="password-error"></span>
													</div>													
													<button type="submit" id="login" class="btn btn-block bt-login" data-loading-text="Signing In...." name="login">Login</button>
													<div class="clearfix"></div>
												</form><!-- form-->											
											</div>			

											<!-- START OF FORGET PASSWORD FORM-->											
											<div role="tabpanel" class="tab-pane text-center" id="forget_password">
												 &nbsp;&nbsp;
												 	<?php if ( $forget_message ) echo "&nbsp;&nbsp;<p class = 'alert alert-danger' style='text-align:center; padding: 0px;'>".$forget_message."</p>"; ?>													
													<div class="clearfix"></div>
													<form action="" method="POST" name="forgetpasswordform">
														<div class="form-group">
															<div class="input-group">
																<div class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span> Email</div>
																<input type="email" class="form-control" id="forgetemail" placeholder="Email" name="forgetemail" required>
															</div>
															<span class="help-block has-error" data-error='0' id="femail-error"></span>
														</div>
														<button type="submit" id="reset_btn" class="btn btn-block bt-login" data-loading-text="Please wait...." name="reset">Forget Password</button>
														<input type="hidden" name="button_pressed" value="1" />
														<div class="clearfix"></div>
													</form>
											</div>
										
											<!-- START OF REGISTRATION FORM-->
											<div role="tabpanel" class="tab-pane" id="profile">
						    	  		 &nbsp;&nbsp;						    	   
						    				<div class="clearfix"></div>
						    				<?php if ( $message ) echo "&nbsp;&nbsp;<p class = 'alert alert-danger' style='text-align:center; padding: 0px;'>".$message."</p>"; ?>																	   
											   <form action="register_user.php" method="POST" name="registration">
											   			<div class="form-group">
														    	<div class="input-group">
														      		<div class="input-group-addon"><span class="glyphicon glyphicon-user"> Username</span></div>
																	<input type="text" class="form-control" id="registerusername" placeholder="Username" name="registerusername" required> 
														    	</div>
														    	<span class="help-block has-error" data-error='0' id="username-error"></span>
														  	</div>								
																<div class="form-group">
																	<div class="input-group">
																		<div class="input-group-addon"><span class="glyphicon glyphicon-lock"> Password</span></div>
																			<input type="password" class="form-control" id="registerpassword" placeholder="Password" name="registerpassword" pattern=".{6,}" title="Six or more characters" required>
																	</div>
																</div>
																<div class="form-group">
																	<div class="input-group">
																		<div class="input-group-addon"><span class="glyphicon glyphicon-lock"> ConfirmPassword</span></div>
																			<input type="password" class="form-control" id="registerconfirm_password" placeholder="Confirm Password" name="registerconfirm_password" pattern=".{6,}" title="Six or more characters" required>
																	</div>
																</div>
																<div class="form-group">
															    	<div class="input-group">
															      		<div class="input-group-addon"><span class="glyphicon glyphicon-user"> Firstname</span></div>
															      		<input type="text" class="form-control" id="registerfname" placeholder="Firstname" name="registerfname" required>
															    	</div>
															 </div>
															<div class="form-group">
														    	<div class="input-group">
														      		<div class="input-group-addon"><span class="glyphicon glyphicon-user"> Lastname</span></div>
																	<input type="text" class="form-control" id="registerlname" placeholder="Lastname" name="registerlname" required>
														    	</div>
														  	</div>
															<div class="form-group">
																<div class="input-group">
																	<div class="input-group-addon"><span class="glyphicon glyphicon-envelope"> Email</span></div>
																		<input type="email" class="form-control" id="registeremail" placeholder="Email" name="registeremail" required>
																</div>
																	<span class="help-block has-error" data-error='0' id="email-error"></span>
															</div>
															<div class="form-group">
																<div class="input-group">
																	<div class="input-group-addon"><span class="glyphicon glyphicon-phone"> Mobile</span></div>
																		<input type="number" class="form-control" id="registercontact_num" placeholder="+001XXXXXXXXXX" name="registercontact_num" required>
																	</div>
																</div>
												  			<button type="submit" id="register_btn" class="btn btn-block bt-login" data-loading-text="Signing In...." name="register">Register</button>
															<div class="clearfix"></div>
														</form>
											    	</div>	
									    	</div>								    	
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div id="regsuccessmodal" class="modal fade" role="dialog"  tabindex="-1" aria-labelledby="regsuccessmodal" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content login-modal">
							<div class="modal-header login-modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title text-center" id="regsuccess">BOOKING SUCCESSFUL</h4>
							</div> 
							<div class="modal-body">
								<div class="text-center">
									<p>Thank You for booking your Seat for the Event!</p>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div id="cancelbookingsuccessmodal" class="modal fade" role="dialog"  tabindex="-1" aria-labelledby="cancelbookingsuccessmodal" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content login-modal">
							<div class="modal-header login-modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title text-center" id="regsuccess">BOOKING CANCELLATION SUCCESSFUL</h4>
							</div> 
							<div class="modal-body">
								<div class="text-center">
									<p>Your Booking has been canceled. Thank You for your cooperation!</p>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div></p>
	</div><!-- /container -->
</div> 
<!--END HEADERWRAP-->

<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header"><a class="navbar-brand" href="index.php">Eventos</a></div>
      <div class="collapse navbar-collapse" id="myNavbar">
        <ul class="nav navbar-nav">
          <li><a href="#section1">Home</a></li>
          <li><a href="#section2">Layout</a></li>
		  <li><a href="#section3">Features</a></li>
		  <li><a href="#section4">Location</a></li>
          <li><a href="#section5">FAQ</a>  </li></ul>
		   <ul class="nav navbar-nav navbar-right">
		   	<?php if(isset($_SESSION[CLIENT]) && !empty($_SESSION[CLIENT])){ ?>
		   	<?php $_ID = $mydb->getValue("user_id","tbl_login","session_id = '".$_SESSION[CLIENT]."'"); ?>
		   	<?php $_uname = $mydb->getValue("uname","tbl_organizers","UID = '".$_ID."'"); ?>
		    	<li><a>Welcome, <?php echo ucfirst($_uname); ?></a></li>		   
		    	<li><a href="?logout">Logout</a></li>
		    <?php }else{ ?>
       <li><a href="#" class="btn btn-link" data-toggle="modal" data-target="#loginModal">Register/Login</a></li>
      <?php } ?>
      </ul>
		  </div>
    </div>
  </div>
</nav>    
<div id="section1" class="container-fluid">
 <div class="row">
    <div class="col-sm-6">
	<div class="text-justify">
  <h1>About us</h1>
  <p>Welcome to the world of Eventos. We are based in Texas established for delivering best services.
  Our services are especially targeted to your requirement, check those and connect us back, because your idea gets life here!</p>
  How we differ?
  <ul class="list-group">
   <li class="list-group-item">We are a ZERO investment company.</li>
   <li class="list-group-item">Within a short period we were able to make premium clients Internationally and Nationally.</li>
   <li class="list-group-item">Our Quality Services do marketing for us</li></ul>
   </div>
   </div>
   <div class="col-sm-6">
   <video autoplay loop muted poster="screenshot.jpg" id="background">
        <source src="Casino Night Blue.mp4" type="video/mp4">
		</div>
</div>
</div>
<div id="section2" class="container-fluid">
  <h1>Layout</h1>
  <div id="myCarousel" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
    <li data-target="#myCarousel" data-slide-to="1"></li>
    <li data-target="#myCarousel" data-slide-to="2"></li>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
    <div class="item active">
      <img src="images/auditorium.jpg" class="img-responsive" alt="auditorium" width="300" height="380">
	   <div class="carousel-caption">
          <h3 style ="color:white">Seating arrangement</h3>
          <p>Comfortable seating arrangement.</p>
        </div>
    </div>

    <div class="item">
	    <img src="images/curtains.jpg" class="img-responsive" alt="Curtains" width="300" height="380">
	   <div class="carousel-caption">
          <h3 style ="color:white">Closed view</h3>
          <p>Awesome Gallery.</p>
        </div>
    </div>

    <div class="item">
      <img src="images/Theater.jpg" alt="Theater" class="img-responsive" width="300" height="380">
	   <div class="carousel-caption">
          <h3 style ="color:white">Open View </h3>
          <p>Elegant Lighting arrangement.</p>
        </div>
    </div>

  <!-- Left and right controls -->
  <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
</div>
</div>
<div id="section3" class="container-fluid">
  <h1>Features</h1>  
    	<div class="row mt centered">	
			<div class="col-lg-4 desc">
				<a class="b-link-fade b-animate-go" href="#"><img class="img-responsive" src="images/cloud.jpe" alt="cloud" />
					<div class="b-wrapper">
					  	<h4 class="b-from-left b-animate b-delay03">Feature 1</h4>
					  	<p class="b-from-right b-animate b-delay03">Access on cloud</p>
					</div>
				</a>
			</div>
			<div class="col-lg-4 desc">
				<a class="b-link-fade b-animate-go" href="#"><img class="img-responsive" src="images/booking.jpe" alt="booking" />
					<div class="b-wrapper">
					  	<h4 class="b-from-left b-animate b-delay03">Feature 2</h4>
					  	<p class="b-from-right b-animate b-delay03">Scheduling events</p>
					</div>
				</a>
			</div>
			<div class="col-lg-4 desc">
				<a class="b-link-fade b-animate-go" href="#"><img class="img-responsive" src="images/email.jpe" alt="emails" />
					<div class="b-wrapper">
					  	<h4 class="b-from-left b-animate b-delay03">Feature 3</h4>
					  	<p class="b-from-right b-animate b-delay03">Emailing audience</p>
					</div>
				</a>
			</div>
		</div><!-- /row -->
		<div class="row mt centered">	
			<div class="col-lg-4 desc">
				<a class="b-link-fade b-animate-go" href="#"><img class="img-responsive" src="images/users.png" alt="users" />
					<div class="b-wrapper">
					  	<h4 class="b-from-left b-animate b-delay03">Feature 4</h4>
					  	<p class="b-from-right b-animate b-delay03">user management</p>
					</div>
				</a>
			</div>
			<div class="col-lg-4 desc">
				<a class="b-link-fade b-animate-go" href="#"><img  class="img-responsive" src="images/seat.jpe" alt="seat" />
					<div class="b-wrapper">
					  	<h4 class="b-from-left b-animate b-delay03">Feature 5</h4>
					  	<p class="b-from-right b-animate b-delay03">Seat Reservation</p>
					</div>
				</a>
			</div>
			<div class="col-lg-4 desc">
				<a class="b-link-fade b-animate-go" href="#"><img class="img-responsive" src="images/support.jpe" alt="support" />
					<div class="b-wrapper">
					  	<h4 class="b-from-left b-animate b-delay03">Feature 6</h4>
					  	<p class="b-from-right b-animate b-delay03">24/7 support</p>
					</div>
				</a>
			</div>
		</div><!-- /row -->
    </div><!-- /container -->
<div id="section4" class="container-fluid">
				<h1>Location </h1>
				<iframe width='100%' height='380px' frameBorder='2' src='https://a.tiles.mapbox.com/v4/dakula.cifix1dmyc1x8sukna9oroyst/attribution,zoompan,zoomwheel,geocoder,share.html?access_token=pk.eyJ1IjoiZGFrdWxhIiwiYSI6ImNpZml4MWV3bmMxbGZza2tuc3E5bzA2bXcifQ.39lTAbY894PuXsvmw9cHQg'></iframe> 
<p></p>	</div><!-- /container -->
		
<div id="section5" class="container-fluid">
  <h1>FAQ</h1>
  <ul>
  <li>
  <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo">How much does it cost to rent the Auditorium?</button>
  <div id="demo" class="collapse">
    It is $1,250 for non-ticketed events; for ticketed events it’s either ten percent of the gross box office receipts or $1,750, whichever is greater. The daily facility rent charge
	is the fee for utilizing the facility. The rental charge covers a 24-hour period. The fee includes box office and ticketing services, provided exclusively by IU Auditorium Box Office. Labor, equipment, and performance fees are not included.
  </div></li></br>
  <li><button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo1">Can I rent the Auditorium even though I’m not affiliated with Indiana University?</button>
  <div id="demo1" class="collapse">
    Yes, the Auditorium is available to anyone, whether a private individual, non-profit organization, or corporation.  </div>
  </li></br>
  <li><button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo4">Is alcohol allowed at receptions?</button>
  <div id="demo4" class="collapse">
   Alcohol is permitted, but service and use are subject to the rules and regulations and the statutes governing the Board of Trustees of Indiana University. The Events Manager can provide information on these policies. </div></li>
  </br><li><button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo5">Where do you park for events at the Auditorium?</button>
  <div id="demo5" class="collapse">
    Parking at the IU Auditorium is limited. There are four parking garages around the IU campus, the closest being the Jordan Avenue garage, located east of the Auditorium on Jordan Avenue between 3rd and 7th streets</div></li>
  </br><li><button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo6">Do we have to use a certain caterer for events at the Auditorium? </button>
  <div id="demo6" class="collapse">We have a list of preferred caterers, but do not contract with any one particular caterer for events at the Auditorium.
    </div></li></br>
  <li><button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo7">What kinds of equipment can we rent from the Auditorium?</button>
  <div id="demo7" class="collapse">A complete inventory list is available upon request and includes items such as pianos, audio visual (front or rear projection), lecterns, music stands with lights, choral risers, dance floor, tables, and portable bars. Additional charges may apply for certain items.
     </div> </li></br></ul>
</div>
				
<?php include('footer.php'); ?> <!-- includes footer  -->