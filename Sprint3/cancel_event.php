<?php include('header.php'); ?> <!-- includes header  -->
<?php 
	if(isset($_GET['event']) && !empty($_GET['event'])){
		$count_row_of_event = $mydb->getCount('ID','tbl_events',"event_num='".$_GET['event']."'");
		if($count_row_of_event == 1){
?>

<style type="text/css">
	.navbar-inverse .navbar-nav > li > a{ color: #337ab7; }
	header>.navbar { background-color: #7EEEEB; }
	.container {background-color: white; margin: auto; width: auto; height: 100%; }
	.container>.row { margin: auto; width: auto; padding:0px; padding-top: 80px; padding-bottom: 15px;}
	label.control-label.col-sm-4 {
	    width: 18%;
	}

	label.control-label.col-sm-5 {
	    width: 25%;
	}
</style>

<?php 
	if(isset($_POST['canceleventcon'])){
		$event_org_id = $mydb->getValue('user_id','tbl_login',"session_id = '".$_SESSION[CLIENT]."'"); 

		$user_fname = $mydb->getValue('fname','tbl_organizers',"UID = '".$event_org_id."'");
		$user_lname = $mydb->getValue('lname','tbl_organizers',"UID = '".$event_org_id."'");
		$fullname = $user_fname." ".$user_lname;

		$get_eventid = $mydb->getValue("ID","tbl_events","event_num='".$_GET['event']."'");

		$values_foremailids = $mydb->getQuery("email_id","tbl_booking","event_id = '".$get_eventid."'");  						

		while($results = $mydb->fetch_assoc($values_foremailids)){
			$to_emails .= ",".$results['email_id']; 			
		}		

			$to= ltrim ($to_emails,',');					
										
			$email_message = "<h2>EVENT CANCELLATION</h2><br>This message has been sent to notify the event ".$mydb->getValue("event_name","tbl_events","event_num = '".$_GET['event']."'")." has been canceled. Sorry for your inconvinence!<br>";
			$return_mail = $mydb->sendEmail("User",$to,"Admin","info@eventos.com","EVENT CANCELLATION",$email_message);
			if($return_mail == true){
				echo '<script type="text/javascript">           						
					window.location = "http://www.eventseventos.info/dashboard.php?events=cancelation_success"
				</script>';	
			}
		
		$mydb->deleteQuery('tbl_events',"event_num='".$_GET['event']."'");
		$mydb->deleteQuery('tbl_booking',"event_id='".$get_eventid."'");
	}
?>
<header>
	<nav class="navbar navbar-inverse navbar-fixed-top">
	  <div class="container-fluid">
	    <div class="navbar-header"><a class="navbar-brand" href="index.php">Eventos</a></div>
	      <div class="collapse navbar-collapse" id="myNavbar">
	        <ul class="nav navbar-nav">
	        	<li><a href="?events"><span class="glyphicon glyphicon-star"></span> My events</a></li>
	        	<li><a href="?schedule"><span class="glyphicon glyphicon-calendar"></span> Schedule</a></li>
	        	<li><a href="#" data-toggle="modal" data-target="#eventscheduleModal" id="updatebutton"><span class="glyphicon glyphicon-calendar"></span> Update Schedule</a></li>	        	
	          	<li><a href="#" data-toggle="modal" data-target="#userregModal" id="usrregbutton"><span class="glyphicon glyphicon-user"></span> User Registration</a></li>	 
	          	<li><a href="?myprofile"><span class="glyphicon glyphicon-user"></span> My profile</a></li>	         
	        </ul>
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
</header>

<div class="container">
	<div class="row">
		<div class="col-md-8">		
			<h1>CANCEL EVENT</h1>
			<div class="alert alert-warning" role="alert">
			  <a href="#" class="alert-link">Warning! You are about to cancel the scheduled event.</a>
			</div>

			<div class="alert alert-info" role="alert">
			  <a href="#" class="alert-link">Everyone associated with the event will be informed through an email.</a>
			</div>
			<table class="table">
				<tr>
					<th>Event Name</th>
					<td><?php echo $mydb->getValue("event_name","tbl_events","event_num = '".$_GET['event']."'"); ?></td>

					<th>Event Date</th>
					<td><?php echo $mydb->getValue("event_date","tbl_events","event_num = '".$_GET['event']."'"); ?></td>
				</tr>
				<tr>
					<th>Event Start Time</th>
					<td><?php echo $mydb->getValue("event_starttime","tbl_events","event_num = '".$_GET['event']."'"); ?></td>

					<th>Event End Time</th>
					<td><?php echo $mydb->getValue("event_endtime","tbl_events","event_num = '".$_GET['event']."'"); ?></td>
				</tr>		      					    		
			</table>
			<h3>To confirm cancellation, press confirm.</h3>
			<form class="form-horizontal" role="form" action="" method="POST" name="cancel_event_form" >
				<div class="form-group">        						
						<label class="control-label col-sm-4" for="Cance Event"></label>   
						<div class="col-sm-6"> 
							<button type="submit" class="btn btn-default" name="canceleventcon">Confirm</button>
						</div>
				</div>
			</form>
		</div>
	</div>
</div>


<?php 		}else{
			echo '<script type="text/javascript">           						
				window.location = "http://www.eventseventos.info";
			</script>';	
		}
	}
?>

<script src="bootstrap-3.3.5-dist/jquery-1.11.3.min.js"></script>
<script src="bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>

</body>
</html>

