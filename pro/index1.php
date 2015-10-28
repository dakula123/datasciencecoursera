<?php
session_start();

if ( isset( $_POST["login"] ) ) {
 login();
}elseif ( isset( $_POST["register"] ) ) {
 submit();
} else {
 displayLoginForm("",array());
}


function validateField( $fieldName, $missingFields ) {
 if ( in_array( $fieldName, $missingFields ) ) {
 echo 'class="error"';
 }
}

function setValue( $fieldName ) {
 if ( isset( $_POST[$fieldName] ) ) {
 echo $_POST[$fieldName];
 }
}

function submit(){
	$requiredFields = array( "fname", "lname","email" , "password1", "password2",
 "username1", "gender" , "number");
 $missingFields = array();
 foreach ( $requiredFields as $requiredField ) {
 if ( !isset( $_POST[$requiredField] ) or !$_POST[$requiredField] ) {
 $missingFields[] = $requiredField;
 }
 }
 if ( $missingFields ) {
 displayLoginForm( "Please fill all red Fields",$missingFields );
 } else {
 processform();
 }
}


function processform() {
 if ( isset( $_POST["username"] ) and isset( $_POST["password"] )  ) {
	$a=$_POST["username1"];
	$b=$_POST["password1"];
	$c=$_POST["fname"];
	$d=$_POST["lname"];
	$e=$_POST["email"];
	$f=$_POST["gender"];
	$j=$_POST["number"];
	
	
	
$dsn="mysql:host=localhost;dbname=eventos";
$username="root";
$password="drpatel1993134";


try{
	$conn=new PDO($dsn,$username,$password);
	$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} 
catch(PDOException $e){
	echo "Connection failed : ".$e->getMessage();
}

$sql="INSERT INTO orga VALUES (NULL, '$c', '$d', '$a', '$b', '$e', , '$f', '$j')";

try {
$st = $conn->query( $sql );
$st->execute();
 } 
catch ( PDOException $e ) {
 echo 'Query failed: ' . $e->getMessage();
}

 $_SESSION["username"] = $a;
 session_write_close();
 ini_set( "session.cookie_lifetime", 100 );
 header( "Location: inside.html" );
 } 
 } 

function login() {
 if( isset( $_POST["username"] ) and isset( $_POST["password"] ) ) {
	 $a=$_POST["username"];
	 $b=$_POST["password"];

$dsn="mysql:host=localhost;dbname=eventos";
$username="root";
$password="drpatel1993134";

try{
	$conn=new PDO($dsn,$username,$password);
	$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} 
catch(PDOException $e){
	echo "Connection failed : ".$e->getMessage();
}

	 $sql="SELECT upass FROM orga where uname= '$a'";

try {
$st = $conn->query( $sql );
$st->execute();
$row=$st->fetch(PDO::FETCH_ASSOC);
 } 
catch ( PDOException $e ) {
 echo 'Query failed: ' . $e->getMessage();
}

 if ( $b==$row['upass'] ) {
 $_SESSION["uname"] = $a;
 session_write_close();
 ini_set( "session.cookie_lifetime", 100 );
 header( "Location: inside.html" );
	  } else {
 displayLoginForm( "Sorry, that username/password could not be found.
Please try again." );
 }
 }
 else {
 displayLoginForm( "Please fill both fields for Login." );
 }
 }

function displayLoginForm( $message="",$missingFields ) {
 displayPageHeader();
?>
<! ========== HEADERWRAP ==================================================================================================== 
=============================================================================================================================>
    <div id="headerwrap">
    	<div class="container">
			<div class="row centered">
				<div class="col-lg-8 col-lg-offset-2 mt">
					<h1 class="animation slideDown">Welcome to our EVENTOS Auditorium website and book your events with better deals.</h1>
    				<p class="mt"><button type="button" class="btn btn-cta btn-lg" data-toggle="modal" data-target="#loginModal">Get Started</button>
					<!-- Modal -->
					<div  id="loginModal" class="modal fade" role="dialog"  tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
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
					 	<div class="tab-content">
					    	<div role="tabpanel" class="tab-pane active text-center" id="home">
					    		&nbsp;&nbsp;
					    		<span id="login_fail" class="response_error" style="display: none;">Log in failed, please try again.</span>
					    		<div class="clearfix"></div>
					     <?php if ( $message ) echo $message ?>
										<form action="index1.php" method="post">
																	<div class="form-group">
								    	<div class="input-group">
								      		<div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
								      		<input type="text" class="form-control" id="login_username" placeholder="Username" name="username">
								    	</div>
								    	<span class="help-block has-error" id="email-error"></span>
								  	</div>
								  	<div class="form-group">
								    	<div class="input-group">
								      		<div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
								      		<input type="password" class="form-control" id="password" placeholder="Password" name="password">
								    	</div>
								    	<span class="help-block has-error" id="password-error"></span>
								  	</div>
						  			<button type="submit" id="login_btn" class="btn btn-block bt-login" data-loading-text="Signing In...." name="login">Login</button>
						  			<div class="clearfix"></div>
						  			
								</form>
					    	</div>
					    	<div role="tabpanel" class="tab-pane" id="profile">
					    	    &nbsp;&nbsp;
					    	    <span id="registration_fail" class="response_error" style="display: none;">Registration failed, please try again.</span>
					    		<div class="clearfix"></div>
						<?php if ( $message ) echo $message ?>					   
					   <form action="index1.php" method="post">
								<div class="form-group">
								    	<div class="input-group">
								      		<div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
								      		<input type="text" class="form-control" id="firstname" placeholder="firstname" name="fname" <?php validateField( "fname",$missingFields ); setValue( "fname" ); ?>>
								    	</div>
								  	</div>
									<div class="form-group">
								    	<div class="input-group">
								      		<div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
								      		<input type="text" class="form-control" id="lastname" placeholder="lastname" name="lname" <?php validateField( "lname",$missingFields ); setValue( "lname" ) ?>>
								    	</div>
								  	</div>
									<div class="form-group">
								    	<div class="input-group">
								      		<div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
								      		<input type="text" class="form-control" id="username" placeholder="Username" name="username1" <?php validateField( "username1",$missingFields ); setValue( "username1" ) ?>>
								    	</div>
								    	<span class="help-block has-error" data-error='0' id="username-error"></span>
								  	</div>								
	<div class="form-group">
	<div class="input-group">
	<div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
	<input type="password" class="form-control" id="password" placeholder="password" name="password1" <?php validateField( "password1",$missingFields ) ?>>
	</div>
    </div>
	<div class="form-group">
    <div class="input-group">
	<div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
	<input type="password" class="form-control" id="confirm password" placeholder="confirm password" name="password2" <?php validateField( "password2",$missingFields ) ?>>
	</div>
    </div>
	  	<div class="form-group">
								    	<div class="input-group">
								      		<div class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></div>
								      		<input type="text" class="form-control" id="email" placeholder="Email" name="email" <?php validateField( "email",$missingFields ); setValue( "email" ) ?>>
								    	</div>
								    	<span class="help-block has-error" data-error='0' id="email-error"></span>
								  	</div>
   <div class="form-group">
    <div class="input-group">
   <label class="input-group-addon">Gender</label>
   <input type="text" class="form-control" id="Gender" placeholder="Gender" name="gender" <?php validateField( "gender",$missingFields ); setValue( "gender" ) ?>>
	</div>
	</div>
	<div class="form-group">
    <div class="input-group">
   <div class="input-group-addon"><span class="glyphicon glyphicon-phone"></span></div>
   <input type="text" class="form-control" id="Contactnum" placeholder="Contact number" name="number" <?php validateField( "number",$missingFields ); setValue( "number" ) ?>>
	</div>
	</div>
	<button <?php  if($missingFields){echo 'type="button"';} else {echo 'type="submmit"';} ?>type="submit" id="register_btn" class="btn btn-block bt-login" data-loading-text="Registering...." name="register">Register</button>
									<div class="clearfix"></div>
								</form>
					    	</div>
					    	<div role="tabpanel" class="tab-pane text-center" id="forget_password">
					    		&nbsp;&nbsp;
					    	    <span id="reset_fail" class="response_error" style="display: none;"></span>
						    		<div class="clearfix"></div>
						    		<form>
										<div class="form-group">
									    	<div class="input-group">
									      		<div class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></div>
									      		<input type="text" class="form-control" id="femail" placeholder="Email" name="email1">
									    	</div>
									    	<span class="help-block has-error" data-error='0' id="femail-error"></span>
									  	</div>
									  	
							  			<button type="button" id="reset_btn" class="btn btn-block bt-login" data-loading-text="Please wait...." name="forgot">Forget Password</button>
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
					  </div>
					</div></p>
									</div>
									
								</div><!-- /row -->
							</div><!-- /container -->
						</div> <!-- /headerwrap -->


<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header"><a class="navbar-brand" href="#">Eventos</a></div>
      <div class="collapse navbar-collapse" id="myNavbar">
        <ul class="nav navbar-nav">
          <li><a href="#section1">Home</a></li>
          <li><a href="#section2">Layout</a></li>
		  <li><a href="#section3">Features</a></li>
		  <li><a href="#section4">Location</a></li>
          <li><a href="#section5">FAQ</a>  </li></ul>
		   <ul class="nav navbar-nav navbar-right">
		    <li><a href="#" class="btn btn-link">Register</a></li>
           <li><a href="#" class="btn btn-link">Login</a></li>
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
<div id="section6" class="container-fluid">
			<div class="row ">
				<!-- ADDRESS -->
				<div class="col-lg-4">
					<h4>Our Studio</h4>
					<p>
						Eventos,<br/>
						777 drive straight<br/>
						Prairie view, TX.<br/>
					</p>
					<p>
						<span class="glyphicon glyphicon-phone"></span> +001 512-936-8943<br/>
						<span class="glyphicon glyphicon-envelope"></span> hello@eventseventos.com
					</p>
				</div><! --/col-lg-4 -->
				<div class="col-lg-4">
					<h4>More Information</h4>
					<p>
						Investors<br/>
						Careers<br/>
						Working with us<br/>
						Awards<br/>
					    Rewards<br/>
					</p>
				</div><!-- /col-lg-4 -->
				
				<div class="col-lg-4">
					<h4>Latest Posts</h4>
					<p>
						A post with an image<br/>
						Other post with a video<br/>
						A full width post<br/>
						We talk about something nice<br/>
					    Yet another single post<br/>
					</p>
				</div><!-- /col-lg-4 -->
				</div></div>
				<div id="footer">
				<p>
					<span class="glyphicon glyphicon-facebook"></span>
					<span class="glyphicon glyphicon-twitter"></span>
					<span class="glyphicon glyphicon-instagram"></span>
					<span class="glyphicon glyphicon-linkedin"></span></p>
					<div class="copyright">Copyright @ Eventos 2015</div>
				</div>
			</footer>
				
<script src="bootstrap-3.3.5-dist/jquery-1.11.3.min.js"></script>
<script src="bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>

</body>

</html>
 <?php
}
function displayPageHeader() {
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Eventos-Auditorium Management System</title>
	 <link href="bootstrap-3.3.5-dist/css/bootstrap.css" rel="stylesheet">
	 <link href="macro/animations.css" rel="stylesheet">
	 <link href="macro/main.css" rel="stylesheet">
	  <link href="macro/hover_pack.css" rel="stylesheet">
	  
	<style>
  body { position: relative; }
  #section1 {padding-top:50px;height:auto;color: ffff; background-color: white;}
  #section2 {padding-top:50px;height:auto;color: ffff; background-color:white;}
  #section3 {padding-top:50px;height:auto;color: ffff; background-color: white;}
  #section4 {padding-top:50px;height:auto;color: ffff; background-color:white;}
  #section5 {padding-top:50px;height:auto;color: ffff; background-color: white;}
  .error{background-color:red;}
  </style>
<script type="text/javascript">
	$( document ).ready( function() {
		$( '#loginModal' ).modal( 'toggle' );
	});
</script>
  </head>
<body data-spy="scroll" data-target=".navbar" data-offset="50">

 <?php
}
?>