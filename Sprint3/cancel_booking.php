<?php include('header.php'); ?> <!-- includes header  -->
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

<header>
	<nav class="navbar navbar-inverse navbar-fixed-top">
	  <div class="container-fluid">
	    <div class="navbar-header"><a class="navbar-brand" href="index.php">Eventos</a></div>
	    </div>
	  </div>
	</nav>
</header>

<?php 
	if(isset($_GET['token']) && !empty($_GET['token']) && isset($_GET['value']) && !empty($_GET['value'])){
		
		$values = "SELECT event_num FROM tbl_events";  	   	
		$results = mysql_query($values);
	   	while($row = mysql_fetch_assoc($results)){    
			$event_nums[] = $row['event_num'];  
	    }	    

	    for($i=0;$i<count($event_nums);$i++){
	    	if($_GET['token'] == md5($event_nums[$i])){
	    		$check_eventnum = "true";
	    		$event_num_value = $event_nums[$i];	    		
	    	}	    	
	    }

    	$getvaluecount = $mydb->getCount('UID','tbl_booking',"event_token='".$_GET['token']."' AND UID='".$_GET['value']."' AND status='false'");	    

		if($getvaluecount == 1){
    		$check_eventnum = "true";    		
    	}else{
    		$check_eventnum = "false";
    	}	 
	} 
?>

<?php 
	if(isset($_POST['cancelbookingcon'])){

		//update seat number to empty
		$data='';
		$data['seat_num'] = "";
		$data['status'] = "true";
		$return_value = $mydb->updateQuery('tbl_booking', $data ,"UID ='".$_GET['value']."' AND event_token='".$_GET['token']."'");

		$user_email_from_bookingtable = $mydb->getValue('email_id','tbl_booking',"event_token='".$_GET['token']."' AND UID='".$_GET['value']."' AND status='true'");

		if($return_value == 1){
			$fname_user = $mydb->getValue("user_fname","tbl_users","user_email = '".$user_email_from_bookingtable."'"); 
			$lname_user = $mydb->getValue("user_lname","tbl_users","user_email = '".$user_email_from_bookingtable."'");
			$full_name = $fname_user." ".$lname_user;			

			$email_message = "You have successfully canceled your booking for event '".$mydb->getValue("event_name","tbl_events","event_num = '".$event_num_value."'")."'. Thank You for your cooperation.";

			$cancel_booking_mail = $mydb->sendEmail($full_name,$user_email_from_bookingtable,"Admin","info@eventos.com","Booking Cancellation Information",$email_message);
		
			echo '<script type="text/javascript">           						
				window.location = "http://www.eventseventos.info?cancelregistration=success"
			</script>';	
		}
	}
?>

<div class="container">
	<div class="row">

		<?php if($check_eventnum == "true"){ ?>

		<div class="col-md-8">		
			<h1>CANCEL BOOKING</h1>
			<div class="alert alert-warning" role="alert">
			  <a href="#" class="alert-link">Warning! You are about to cancel the booking.</a>
			</div>

			<table class="table">
				<tr>
					<th>Event Name</th>
					<td><?php echo $mydb->getValue("event_name","tbl_events","event_num = '".$event_num_value."'"); ?></td>

					<th>Event Date</th>
					<td><?php echo $mydb->getValue("event_date","tbl_events","event_num = '".$event_num_value."'"); ?></td>
				</tr>
				<tr>
					<th>Event Start Time</th>
					<td><?php echo $mydb->getValue("event_starttime","tbl_events","event_num = '".$event_num_value."'"); ?></td>

					<th>Event End Time</th>
					<td><?php echo $mydb->getValue("event_endtime","tbl_events","event_num = '".$event_num_value."'"); ?></td>
				</tr>
				<tr>
					<th>Seat Number</th>
					<td colspan="3"><?php echo $mydb->getValue("seat_num","tbl_booking","event_token='".$_GET['token']."' AND UID='".$_GET['value']."' AND status='false'"); ?></td>
				</tr>		      					    		
			</table>

			<h3>To confirm cancellation, press confirm.</h3>
			<form class="form-horizontal" role="form" action="" method="POST" name="cancel_booking_form" >
				<div class="form-group">        						
						<label class="control-label col-sm-4" for="Cance Booking"></label>   
						<div class="col-sm-6"> 
							<button type="submit" class="btn btn-default" name="cancelbookingcon">Confirm</button>
						</div>
				</div>
			</form>
		</div>

		<?php }else{ 
 			echo "<p class = 'alert alert-info' style='text-align:left; padding: 0px;'>&nbsp;&nbsp;<b>INCORRECT TOKEN INFORMATION</b></p>";
  		} ?>

	</div>
</div>


<script src="bootstrap-3.3.5-dist/jquery-1.11.3.min.js"></script>
<script src="bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>

</body>

</html>