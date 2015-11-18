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
<script type="text/javascript">
$(document).ready(function(){
	$('#cancelevent').click(function(){ 
		var event_value = "<?php echo $_GET['update_schedule']; ?>";
		if (window.confirm('Are you sure about canceling the event ?'))
		{
		    window.location = "http://www.eventseventos.info/cancel_event.php?event=<?php echo $_GET['update_schedule']; ?>";
		}
		else
		{
		    // They clicked no
		}
		return false; 
	});
});
</script>

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

<!-- event_org_id denotes the id of the logged in user  -->
<?php $event_org_id = $mydb->getValue('user_id','tbl_login',"session_id = '".$_SESSION[CLIENT]."'"); ?>

<!-- takes post values from send invitations form and send the invitations through mail to the users -->
<?php 
	if(isset($_POST['send_invitations'])){

		$user_email = $mydb->getValue('email','tbl_organizers',"UID = '".$event_org_id."'");
		$user_fname = $mydb->getValue('fname','tbl_organizers',"UID = '".$event_org_id."'");
		$user_lname = $mydb->getValue('lname','tbl_organizers',"UID = '".$event_org_id."'");
		$fullname = $user_fname." ".$user_lname;
		$to = $_POST['emailids']; //emailids taken from POST

		$single_emailid = explode(",", $to); 
		
		for($i=0;$i<count($single_emailid);$i++){

			//to check if the email id has already been sent an invitaion for the current event
			$values = "SELECT email_id FROM tbl_booking WHERE event_id = ".$_POST['event_id'];  	   	
			$results = mysql_query($values);
		   	while($row = mysql_fetch_assoc($results)){    
				$_invitations[] = $row['email_id'];  
		    }		   		   

		    if (in_array($single_emailid[$i], $_invitations)) {
		    	$err_emails[] = $single_emailid[$i];		    	
		    	$event_table_id = $mydb->getValue('UID','tbl_booking',"email_id='".$single_emailid[$i]."' AND event_id='".$_POST['event_id']."'");
		    }else{
			    $data='';
			    $data['event_id']=$_POST['event_id'];
			    $data['email_id']=$single_emailid[$i];
			    $data['event_token']=$_POST['token'];
			    $data['status']="true";
				$event_table_id = $mydb->insertQuery('tbl_booking',$data);
			}
		}	

		$email_message = "<h2>INVITATION</h2><br>This message has been sent as an invitation for the event ".$_POST['name']."! Please use to link to book the desired seat. <br> 
					link : http://www.eventseventos.info/booking.php?token=".$_POST['token']."&value=".$event_table_id;
		$return_mail = $mydb->sendEmail($fullname,$to,"Admin","info@eventos.com","INVITATION",$email_message);
		if($return_mail == true){
			echo '<script type="text/javascript">           						
				window.location = "http://www.eventseventos.info/dashboard.php?events=invitation_success"
			</script>';	
		}
	}
?>

<!-- condition which checks if the event id entered in the invitation popup is valid or not, if not found page will redirect and show a pop up with error message -->
<?php 
	if(isset($_POST['sinvitation'])){
		$eventid = $_POST['eventid'];
		$count_eventid = $mydb->count_row($mydb->getQuery("*","tbl_events","event_num = '".$eventid."'"));	
		$eid = md5($eventid);

		if($count_eventid!=0){
			echo '<script type="text/javascript">           						
				window.location = "http://www.eventseventos.info/dashboard.php?invitation='.$eid.'"
			</script>';	
		}else{
			$err_reg = 1; ?>
			<script type="text/javascript">
				 $(document).ready(function(){
				        $('#usrregbutton').trigger('click');                      				    
				      });
			</script>
		<?php }

	}
?>

<!-- the condition verifies if the token is legit and redirects to the user invitation screen -->
<?php 
	$check_eventnum = "";
	if(isset($_GET['invitation']) && !empty($_GET['invitation'])){		
		$values = "SELECT event_num FROM tbl_events";  	   	
		$results = mysql_query($values);
	   	while($row = mysql_fetch_assoc($results)){    
			$event_nums[] = $row['event_num'];  
	    }	    

	    for($i=0;$i<count($event_nums);$i++){
	    	if($_GET['invitation'] == md5($event_nums[$i])){
	    		$check_eventnum = "true";
	    		$eventid_invite = $event_nums[$i];	    		
	    	}	    	
	    }
	    
	    if($check_eventnum == "true"){ ?>

	    <div class="container">
			<div class="row">
				<div class="col-md-8">
					<h2>SEND INVITATION</h2>
					<form class="form-horizontal" role="form" action="" method="POST" name="eventinvitations" >
						<div class="form-group">
						<label class="control-label col-sm-4" for="event name">Event Name</label>
							<div class="col-sm-6">
								<input type="text" class="form-control" id="name" placeholder="Event Name" name="name" value="<?php echo $mydb->getValue("event_name","tbl_events","event_num = '".$eventid_invite."'"); ?>" readonly>
							</div>
						</div>												
						<div class="form-group">
							<label class="control-label col-sm-4" for="Emails"></label>
							<div class="col-sm-6">
								<Textarea class="form-control" id="emailids" placeholder="Enter Email Ids using comma(,)" name="emailids" ></Textarea>
							</div>
						</div>
						<div class="form-group" style="display:none;">
							<label class="control-label col-sm-4" for="token"></label>
							<div class="col-sm-6">
								<input type="text" class="form-control" id="token" placeholder="" name="token" value="<?php echo $_GET['invitation']; ?>" readonly>
							</div>
						</div>
						<div class="form-group" style="display:none;">
							<label class="control-label col-sm-4" for="event_id"></label>
							<div class="col-sm-6">
								<input type="text" class="form-control" id="event_id" placeholder="" name="event_id" value="<?php echo $mydb->getValue("ID","tbl_events","event_num = '".$eventid_invite."'"); ?>" readonly>
							</div>
						</div>
						<div class="form-group">        						
							<label class="control-label col-sm-4" for="Send Invitations"></label>   
							<div class="col-sm-6"> 
								<button type="submit" class="btn btn-default" name="send_invitations">SEND INVITATIONS</button>
							</div>
						</div>
					</form>
					<?php 
						$get_event_id = $mydb->getValue("ID","tbl_events","event_num = '".$eventid_invite."'");
						$values = $mydb->getQuery("*","tbl_booking","event_id = '".$get_event_id."'");  						
						$count_row_user = $mydb->count_row($mydb->getQuery("*","tbl_booking","event_id = '".$get_event_id."'")); 
					?>
					<h2>REGISTERED USERS</h2>
					
					<?php 
					
					if($count_row_user == 0){				
							echo "<p class = 'alert alert-info' style='text-align:left; padding: 0px;'>&nbsp;&nbsp;You have no users registered.</p>";						
					}else{ 					

					?>

						<table class="table table-hover">
							<thead>
								<tr>
									<th>SN</th>
									<th>Name</th>
									<th>Email</th>
									<th>Mobile</th>
									<th>Seat Number</th>
								</tr>
							</thead>
							<tbody>
							<?php 		
							$i = 1;					
							while($results = $mydb->fetch_assoc($values)){
							?>
							<tr>
								<?php 
									$user_fname = $mydb->getValue("user_fname","tbl_users","user_email = '".$results['email_id']."'");
									$user_lname = $mydb->getValue("user_lname","tbl_users","user_email = '".$results['email_id']."'");
									$user_mobile = $mydb->getValue("user_mobile","tbl_users","user_email = '".$results['email_id']."'");

								?>
								<td> <?php echo $i; ?> </td>
								<td> <?php if(!empty($user_fname) && !empty($user_lname)){ echo $user_fname." ".$user_lname; }else{ echo "Not Responded to Invitation."; } ?> </td>
								<td> <?php echo $results['email_id']; ?> </td>
								<td> <?php if(!empty($user_mobile)){ echo $user_mobile; }else{ echo "Not Responded to Invitation."; } ?> </td>
								<td> <?php if($results['status']=="true"){ echo "N/A"; }else{ echo $results['seat_num']; } ?> </td>
							</tr>
							<?php 
									$i++;
								}					
							?>		
							</tbody>			
						</table>

					<?php } ?>

				</div>
			</div>
		</div>

	<?php 
	    }
	}
?>

<!-- the condition which takes the post data sent from the schedule an event form  -->
<?php 

	if (isset($_GET['schedule']) && isset($_POST['scheduleevent'])){ 
		$err_time = "";

		$event_name = $_POST['name'];
		$event_desc = $_POST['desc'];
		$event_stime = $_POST['stime'];
		$event_etime = $_POST['etime'];
		$event_date = $_POST['edate'];
		$event_sreq = $_POST['req'];	

		$starttime = str_replace(':', '', $event_stime);			
		$endtime = str_replace(':', '', $event_etime);			

		if($endtime < $starttime){
			$err_time = 1;
		}else{
			$err_time = 0;

			$data='';
			$data['event_name']=$event_name;
			$data['event_desc']=$event_desc;
			$data['event_starttime']=$event_stime;
			$data['event_endtime']=$event_etime;
			$data['event_date']=$event_date;
			$data['event_srequest']=$event_sreq;
			$data['event_org_id']=$event_org_id;

			$event_table_id = $mydb->insertQuery('tbl_events',$data);
			$event_num = "A".str_pad($event_table_id, 6, "0", STR_PAD_LEFT); 

			$data='';
			$data['event_num']=$event_num;
			$mydb->updateQuery('tbl_events', $data ,"ID ='".$event_table_id."'");

			// sendEmail($toName,$toEmail,$fromName,$fromEmail,$subject,$message)
			$user_email = $mydb->getValue('email','tbl_organizers',"UID = '".$event_org_id."'");
			$user_fname = $mydb->getValue('fname','tbl_organizers',"UID = '".$event_org_id."'");
			$user_lname = $mydb->getValue('lname','tbl_organizers',"UID = '".$event_org_id."'");
			$fullname = $user_fname." ".$user_lname;

			$email_message = "<html>
							<head>
							  <title>Event Information</title>
							</head>
							<body>
							<b>Thank You for scheduling an Event with us.</b>
							<br>						  
							  <table>
							    <tr>
							      <th>Event Name</th><td>".$event_name."</td>
							    </tr>
							    <tr>
							      <th>Event ID</th><td>".$event_num."</td>
							    </tr>
							    <tr>
							      <th>Event Description</th><td>".$event_desc."</td>
							    </tr>
							    <tr>
							      <th>Event Start Time</th><td>".$event_stime."</td>
							    </tr>
							    <tr>
							      <th>Event End Time</th><td>".$event_etime."</td>
							    </tr>
							    <tr>
							      <th>Event Date</th><td>".$event_date."</td>
							    </tr>
							  </table>
							</body>
							</html>";
			$return_mail = $mydb->sendEmail($fullname,$user_email,"Admin","info@eventos.com","Event Information",$email_message);
			
			if($event_table_id>0 && $return_mail == true){
				echo '<script type="text/javascript">           						
					window.location = "http://www.eventseventos.info/dashboard.php?events=success"
				</script>';	
			}
		}
	 } 
?>

<!-- condition for edit event where the event id is searched and if found will redirect to the update schedule page -->
<?php 
	if(isset($_POST['updateevent'])){
		$eventid = $_POST['eventid'];
		$count_eventid = $mydb->count_row($mydb->getQuery("*","tbl_events","event_num = '".$eventid."'"));	
		if($count_eventid!=0){
			echo '<script type="text/javascript">           						
				window.location = "http://www.eventseventos.info/dashboard.php?update_schedule='.$eventid.'"
			</script>';	
		}else{
			$err_update = 1; ?>
			<script type="text/javascript">
				 $(document).ready(function(){
				        $('#updatebutton').trigger('click');                      				    
				      });
			</script>
		<?php }

	}
?>

<!-- edit profile section -->
<?php 
	if(isset($_POST['edit_profile'])){
		$update = "";		
		$edit_uname = $_POST['editusername'];
		$edit_fname = $_POST['editfname'];
		$edit_lname = $_POST['editlname'];
		$edit_email = $_POST['editemail'];
		$edit_mobile = $_POST['editcontact_num'];			

		$data='';		
		$data='';
		$data['uname']=$edit_uname;
		$data['fname']=$edit_fname;
		$data['lname']=$edit_lname;
		$data['email']=$edit_email;
		$data['mobile']=$edit_mobile;		
				
		$update = $mydb->updateQuery('tbl_organizers', $data ,"UID ='".$mydb->getValue("user_id","tbl_login","session_id = '".$_SESSION[CLIENT]."'")."'");
		echo $update;
		if(!empty($update)){
			echo '<script type="text/javascript">           						
				window.location = "http://www.eventseventos.info/dashboard.php?profile=updatesuccess"
			</script>';	
		}

	}
?>

<!-- update event section where the post data from form is taken and stored in the database if no errors  -->
<?php 
	if(isset($_POST['updateevent2'])){
		$update = "";		
		$event_id = $_POST['eventid2'];
		$event_name2 = $_POST['name2'];
		$event_desc2 = $_POST['desc2'];
		$event_stime2 = $_POST['stime2'];
		$event_etime2 = $_POST['etime2'];
		$event_date2 = $_POST['edate2'];
		$event_sreq2 = $_POST['req2'];				

		$data='';		
		$data['event_name']=$event_name2;
		$data['event_desc']=$event_desc2;
		$data['event_starttime']=$event_stime2;
		$data['event_endtime']=$event_etime2;
		$data['event_date']=$event_date2;
		$data['event_srequest']=$event_sreq2;		
		
		
		$update = $mydb->updateQuery('tbl_events', $data ,"event_num ='".$event_id."'");
		echo $update;
		if(!empty($update)){
			echo '<script type="text/javascript">           						
				window.location = "http://www.eventseventos.info/dashboard.php?events=updatesuccess"
			</script>';	
		}

	}
?>

<?php if (isset($_GET['schedule'])) { ?>
	<div class="container">
		<div class="row">
			<div class="col-md-8">		
				<h2>SCHEDULE EVENT</h2>
				<form class="form-horizontal" role="form" action="" method="POST" name="event_schedule" >
					<div class="form-group">
					<label class="control-label col-sm-4" for="event name">Event Name</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="name" placeholder="Event Name" name="name" value="<?php if(!empty($event_name)){ echo $event_name; }?>" required>
						</div>
					</div>
					<div class="form-group">
					<label class="control-label col-sm-4" for="description">Event Description</label>
						<div class="col-sm-6">
							<Textarea class="form-control" id="desc" placeholder="Event Description" name="desc" required><?php if(!empty($event_desc)){ echo $event_desc; }?></Textarea>
						</div>
					</div>
					<div class="form-group">		
						<label class="control-label col-sm-4" for="start time">Start Time</label>
						<div class="col-sm-6">
							<select class="form-control" name="stime">
								<option value="08:00:00">8:00 AM</option>								
								<option value="09:00:00">9:00 AM</option>								
								<option value="10:00:00">10:00 AM</option>							
								<option value="11:00:00">11:00 AM</option>								
								<option value="12:00:00">12:00 AM</option>								
								<option value="13:00:00">1:00 PM</option>								
								<option value="14:00:00">2:00 PM</option>								
								<option value="15:00:00">3:00 PM</option>								
								<option value="16:00:00">4:00 PM</option>								
								<option value="17:00:00">5:00 PM</option>								
								<option value="18:00:00">6:00 PM</option>								
								<option value="19:00:00">7:00 PM</option>								
								<option value="20:00:00">8:00 PM</option>							
								<option value="21:00:00">9:00 PM</option>																
							</select>
							<?php if ( $err_time == 1 ) echo "&nbsp;&nbsp;<p class = 'alert alert-danger' style='text-align:center; padding: 0px;'>Please reselect the time!<br>(Endtime cannot be greater than Starttime)</p>"; ?>
						</div>						
					</div>					
					<div class="form-group">		
						<label class="control-label col-sm-4" for="end time">End Time</label>
						<div class="col-sm-6">
							<select class="form-control" name="etime">
								<option value="08:30:00">8:30 AM</option>								
								<option value="09:30:00">9:30 AM</option>								
								<option value="10:30:00">10:30 AM</option>							
								<option value="11:30:00">11:30 AM</option>								
								<option value="12:30:00">12:30 AM</option>								
								<option value="13:30:00">1:30 PM</option>								
								<option value="14:30:00">2:30 PM</option>								
								<option value="15:30:00">3:30 PM</option>								
								<option value="16:30:00">4:30 PM</option>								
								<option value="17:30:00">5:30 PM</option>								
								<option value="18:30:00">6:30 PM</option>								
								<option value="19:30:00">7:30 PM</option>								
								<option value="20:30:00">8:30 PM</option>							
								<option value="21:30:00">9:30 PM</option>	
							</select>
							<?php if ( $err_time == 1 ) echo "&nbsp;&nbsp;<p class = 'alert alert-danger' style='text-align:center; padding: 0px;'>Please reselect the time!<br>(Endtime cannot be greater than Starttime)</p>"; ?>
						</div>						
					</div>					
					<div class="form-group">
						<label class="control-label col-sm-4" for="date">Date</label>
						<div class="col-sm-6">
							<?php 
								$datetime = new DateTime('tomorrow');								
							?>
							<input type="date" class="form-control" id="edate" name="edate" value="<?php if(!empty($event_date)){ echo $event_date; }else{echo $datetime->format('Y-m-d');}?>" required> Defult value is set to tomorrow.
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4" for="special request">Special Requests</label>
						<div class="col-sm-6">
							<Textarea class="form-control" id="req" placeholder="Special requests" name="req" ><?php if(!empty($event_sreq)){ echo $event_sreq; }?></Textarea>
						</div>
					</div>
					<div class="form-group">        						
							<label class="control-label col-sm-4" for="Schedule Event"></label>   
							<div class="col-sm-6"> 
								<button type="submit" class="btn btn-default" name="scheduleevent">Schedule Event</button>
							</div>
					</div>
				</form>
			</div>
		</div>
	</div>
<?php }elseif (isset($_GET['update_schedule']) && !empty($_GET['update_schedule'])) { ?>
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<h2>UPDATE SCHEDULE</h2>
				<a id="cancelevent" href="#"><span class="glyphicon glyphicon-warning-sign"></span>  Cancel This Event</a>				
		   		<?php $_ename = $mydb->getValue("event_name","tbl_events","event_num = '".$_GET['update_schedule']."'"); ?>
		   		<?php $_edate = $mydb->getValue("event_date","tbl_events","event_num = '".$_GET['update_schedule']."'"); ?>
		   		<?php $_estarttime = $mydb->getValue("event_starttime","tbl_events","event_num = '".$_GET['update_schedule']."'"); ?>
		   		<?php $_eendtime = $mydb->getValue("event_endtime","tbl_events","event_num = '".$_GET['update_schedule']."'"); ?>
		   		<?php $_edesc = $mydb->getValue("event_desc","tbl_events","event_num = '".$_GET['update_schedule']."'"); ?>	
		   		<?php $_eereq = $mydb->getValue("event_srequest","tbl_events","event_num = '".$_GET['update_schedule']."'"); ?>
				<form class="form-horizontal" role="form" action="" method="POST" name="update_schedule2" >
					<div class="form-group">
					<label class="control-label col-sm-4" for="event id">Event ID</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="eventid2" placeholder="Event Id" name="eventid2" value="<?php echo $_GET['update_schedule']; ?>" readonly>
						</div>
					</div>
					<div class="form-group">
					<label class="control-label col-sm-4" for="event name">Event Name</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="name2" placeholder="Event Name" name="name2" value="<?php echo $_ename; ?>" required>
						</div>
					</div>
					<div class="form-group">
					<label class="control-label col-sm-4" for="description">Event Description</label>
						<div class="col-sm-6">
							<Textarea class="form-control" id="desc2" placeholder="Event Description" name="desc2" required><?php echo $_edesc; ?></Textarea>
						</div>
					</div>
					<div class="form-group">		
						<label class="control-label col-sm-4" for="start time">Start Time</label>
						<div class="col-sm-6">
							<select class="form-control" name="stime2">
								<option value="08:00:00" <?php if($_estarttime == "08:00:00"){echo "selected";}?>>8:00 AM</option>								
								<option value="09:00:00" <?php if($_estarttime == "09:00:00"){echo "selected";}?>>9:00 AM</option>								
								<option value="10:00:00" <?php if($_estarttime == "10:00:00"){echo "selected";}?>>10:00 AM</option>							
								<option value="11:00:00" <?php if($_estarttime == "11:00:00"){echo "selected";}?>>11:00 AM</option>								
								<option value="12:00:00" <?php if($_estarttime == "12:00:00"){echo "selected";}?>>12:00 AM</option>								
								<option value="13:00:00" <?php if($_estarttime == "13:00:00"){echo "selected";}?>>1:00 PM</option>								
								<option value="14:00:00" <?php if($_estarttime == "14:00:00"){echo "selected";}?>>2:00 PM</option>								
								<option value="15:00:00" <?php if($_estarttime == "15:00:00"){echo "selected";}?>>3:00 PM</option>								
								<option value="16:00:00" <?php if($_estarttime == "16:00:00"){echo "selected";}?>>4:00 PM</option>								
								<option value="17:00:00" <?php if($_estarttime == "17:00:00"){echo "selected";}?>>5:00 PM</option>								
								<option value="18:00:00" <?php if($_estarttime == "18:00:00"){echo "selected";}?>>6:00 PM</option>								
								<option value="19:00:00" <?php if($_estarttime == "19:00:00"){echo "selected";}?>>7:00 PM</option>								
								<option value="20:00:00" <?php if($_estarttime == "20:00:00"){echo "selected";}?>>8:00 PM</option>							
								<option value="21:00:00" <?php if($_estarttime == "21:00:00"){echo "selected";}?>>9:00 PM</option>																
							</select>
						</div>
					</div>
					<div class="form-group">		
						<label class="control-label col-sm-4" for="end time">End Time</label>
						<div class="col-sm-6">
							<select class="form-control" name="etime2">
								<option value="08:30:00" <?php if($_eendtime == "08:30:00"){echo "selected";}?>>8:30 AM</option>								
								<option value="09:30:00" <?php if($_eendtime == "09:30:00"){echo "selected";}?>>9:30 AM</option>								
								<option value="10:30:00" <?php if($_eendtime == "10:30:00"){echo "selected";}?>>10:30 AM</option>							
								<option value="11:30:00" <?php if($_eendtime == "11:30:00"){echo "selected";}?>>11:30 AM</option>								
								<option value="12:30:00" <?php if($_eendtime == "12:30:00"){echo "selected";}?>>12:30 AM</option>								
								<option value="13:30:00" <?php if($_eendtime == "13:30:00"){echo "selected";}?>>1:30 PM</option>								
								<option value="14:30:00" <?php if($_eendtime == "14:30:00"){echo "selected";}?>>2:30 PM</option>								
								<option value="15:30:00" <?php if($_eendtime == "15:30:00"){echo "selected";}?>>3:30 PM</option>								
								<option value="16:30:00" <?php if($_eendtime == "16:30:00"){echo "selected";}?>>4:30 PM</option>								
								<option value="17:30:00" <?php if($_eendtime == "17:30:00"){echo "selected";}?>>5:30 PM</option>								
								<option value="18:30:00" <?php if($_eendtime == "18:30:00"){echo "selected";}?>>6:30 PM</option>								
								<option value="19:30:00" <?php if($_eendtime == "19:30:00"){echo "selected";}?>>7:30 PM</option>								
								<option value="20:30:00" <?php if($_eendtime == "20:30:00"){echo "selected";}?>>8:30 PM</option>							
								<option value="21:30:00" <?php if($_eendtime == "21:30:00"){echo "selected";}?>>9:30 PM</option>	
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4" for="date">Date</label>
						<div class="col-sm-6">
							<?php 
								$datetime = new DateTime('tomorrow');								
							?>
							<input type="date" class="form-control" id="edate2" value="<?php if(!empty($_edate)){echo $_edate;}else{echo $datetime->format('Y-m-d');} ?>" name="edate2" required> Defult value is set to tomorrow.
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4" for="special request">Special Requests</label>
						<div class="col-sm-6">
							<Textarea class="form-control" id="req2" placeholder="Special requests" name="req2"><?php echo $_eereq; ?></Textarea>
						</div>
					</div>
					<div class="form-group">        						
						<label class="control-label col-sm-4" for="Update Event"></label>   
						<div class="col-sm-6"> 
							<button type="submit" class="btn btn-default" name="updateevent2">Update Event</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
<?php }elseif (isset($_GET['myprofile'])) { ?>
	<div class="container">	
		<div class="row">
			<div class="col-md-12">
				<?php $_ID = $mydb->getValue("user_id","tbl_login","session_id = '".$_SESSION[CLIENT]."'"); ?>
			   		<?php $_uname = $mydb->getValue("uname","tbl_organizers","UID = '".$_ID."'"); ?>
			   		<?php $_fname = $mydb->getValue("fname","tbl_organizers","UID = '".$_ID."'"); ?>
			   		<?php $_lname = $mydb->getValue("lname","tbl_organizers","UID = '".$_ID."'"); ?>
			   		<?php $_email = $mydb->getValue("email","tbl_organizers","UID = '".$_ID."'"); ?>
			   		<?php $_mobile = $mydb->getValue("mobile","tbl_organizers","UID = '".$_ID."'"); ?>		   		
				<?php if(isset($_GET['editprofile'])){ ?>
				
					<h2>EDIT PROFILE</h2>				
					<?php if ( $message ) echo $message; ?>					   
					<form action="" method="POST" name="updateprofile">
			   			<div class="form-group">
						    	<div class="input-group">
						      		<div class="input-group-addon"><span class="glyphicon glyphicon-user"> Username</span></div>
									<input type="text" class="form-control" id="editusername" placeholder="Username" name="editusername" value="<?php if(!empty($_uname)){ echo $_uname; }?>" required>
						    	</div>					    	
						  	</div>								
							<div class="form-group">
							    	<div class="input-group">
							      		<div class="input-group-addon"><span class="glyphicon glyphicon-user"> Firstname</span></div>
							      		<input type="text" class="form-control" id="editfname" placeholder="Firstname" name="editfname" value="<?php if(!empty($_fname)){ echo $_fname; }?>" required>
							    	</div>
							 </div>
							<div class="form-group">
						    	<div class="input-group">
						      		<div class="input-group-addon"><span class="glyphicon glyphicon-user"> Lastname</span></div>
									<input type="text" class="form-control" id="editlname" placeholder="Lastname" name="editlname" value="<?php if(!empty($_lname)){ echo $_lname; }?>" required>
						    	</div>
						  	</div>
							<div class="form-group">
								<div class="input-group">
									<div class="input-group-addon"><span class="glyphicon glyphicon-envelope"> Email</span></div>
										<input type="email" class="form-control" id="editemail" placeholder="Email" name="editemail" value="<?php if(!empty($_email)){ echo $_email; }?>" required>
								</div>								
							</div>
							<div class="form-group">
								<div class="input-group">
									<div class="input-group-addon"><span class="glyphicon glyphicon-phone"> Mobile</span></div>
										<input type="number" class="form-control" id="editcontact_num" placeholder="+001XXXXXXXXXX" name="editcontact_num" value="<?php if(!empty($_mobile)){ echo $_mobile; }?>" required>
									</div>
								</div>
				  			<button type="submit" id="edit_profile" class="btn btn-block bt-login" data-loading-text="Signing In...." name="edit_profile">SAVE</button>
							<div class="clearfix"></div>
						</form>									
				<?php }else{ ?>

					<h2>MY PROFILE</h2>
					<?php if ( $_GET['myprofile'] == "updatesuccess" ) echo "<p class = 'alert alert-info' style='text-align:left; padding: 0px;'>&nbsp;&nbsp;<b>Update Successful!</b></p>"; ?>
					<table class="table table-hover">
						<tr>
							<th>Username</th>
							<td><?php echo $_uname; ?></td>
						</tr>
						<tr>
							<th>Name</th>
							<td><?php echo $_fname." ".$_lname; ?></td>
						</tr>
						<tr>
							<th>Email</th>
							<td><?php echo $_email; ?></td>
						</tr>
						<tr>
							<th>Mobile</th>
							<td><?php echo $_mobile; ?></td>
						</tr>					
					</table>
					<a href="?myprofile&editprofile" class="btn btn-default">EDIT PROFILE</a>

				<?php } ?>
			</div>
		</div>
	</div>
<?php }elseif (isset($_GET['events'])) { ?>
	<div class="container">				
		<div class="row">
			<div class="col-md-12">			
				<?php if(isset($_POST['search'])){ echo "<h2>SEARCH RESULTS FOR:'".$_POST['searchtext']."'</h2>"; }else{ echo "<h2>MY EVENTS</h2>"; } ?>
				<?php if ( $_GET['events'] == "success" ) echo "<p class = 'alert alert-info' style='text-align:left; padding: 0px;'>&nbsp;&nbsp;<b>Thank You </b>,Your event has been scheduled. Detailed info about the event has been mailed to you!</p>"; ?>
				<?php if ( $_GET['events'] == "updatesuccess" ) echo "<p class = 'alert alert-info' style='text-align:left; padding: 0px;'>&nbsp;&nbsp;<b>Update Successful!</b></p>"; ?>
				<?php if ( $_GET['events'] == "invitation_success" ) echo "<p class = 'alert alert-info' style='text-align:left; padding: 0px;'>&nbsp;&nbsp;<b>Invitations sent Successfully!</b></p>"; ?>
				<?php if ( $_GET['events'] == "cancelation_success" ) echo "<p class = 'alert alert-info' style='text-align:left; padding: 0px;'>&nbsp;&nbsp;<b>Event Cancelled Successfully!</b></p>"; ?>				
				<?php 
				 if(isset($_POST['search'])){ 				 	
					$values = $mydb->getLike("*","tbl_events","event_org_id = '".$event_org_id."' AND ".$_POST['searchby'],$_POST['searchtext']);  						
					$count_row = $mydb->count_row($mydb->getLike("*","tbl_events","event_org_id = '".$event_org_id."' AND ".$_POST['searchby'],$_POST['searchtext']));						
				 }else{
					$values = $mydb->getQuery("*","tbl_events","event_org_id = '".$event_org_id."'");  						
					$count_row = $mydb->count_row($mydb->getQuery("*","tbl_events","event_org_id = '".$event_org_id."'"));						
				}
					if($count_row == 0){
						if(isset($_POST['search'])){ 
							echo "<p class = 'alert alert-info' style='text-align:left; padding: 0px;'>&nbsp;&nbsp;No Results Found! <a href='dashboard.php?events'><b> Try Again?</b></a></p>";
						}else{
							echo "<p class = 'alert alert-info' style='text-align:left; padding: 0px;'>&nbsp;&nbsp;You have not scheduled any events yet.</p>";
						}
					}else{ 
				?>	
					<div class="row">
						<form action="dashboard.php?events" method="POST" name="search">
							<div class="col-md-6">	
								<div class="form-group">
									<div class="input-group">
										<div class="input-group-addon"><span class="glyphicon glyphicon-search"></span> Search BY</div>							
											<select class="form-control" name="searchby">
												<option value="event_name" <?php if(!empty($_POST['searchby']) && $_POST['searchby']== "event_name"){ echo "selected"; }?>>Event Name</option>												
												<option value="event_starttime" <?php if(!empty($_POST['searchby']) && $_POST['searchby']== "event_starttime"){ echo "selected"; }?>>Event Start Time</option>
												<option value="event_endtime" <?php if(!empty($_POST['searchby']) && $_POST['searchby']== "event_endtime"){ echo "selected"; }?>>Event End Time</option>
												<option value="event_date" <?php if(!empty($_POST['searchby']) && $_POST['searchby']== "event_date"){ echo "selected"; }?>>Event Date</option>
												<option value="event_desc" <?php if(!empty($_POST['searchby']) && $_POST['searchby']== "event_desc"){ echo "selected"; }?>>Event Description</option>
											</select>
									</div>
										<span class="help-block has-error" data-error='0' id="email-error"></span>
								</div>
							</div>
							<div class="col-md-6">	
									<div class="input-group">
								      <input type="text" class="form-control" placeholder="Search for..." name="searchtext" value = "<?php if(!empty($_POST['searchtext'])){ echo $_POST['searchtext']; }?>">
								      <span class="input-group-btn">
								        <button class="btn btn-default" type="submit" name="search">Go!</button>
								      </span>
								    </div><!-- /input-group -->
							</div>
						</form>
					</div>
					<table class="table table-hover">
						<thead>
						<tr>
						<th>SN</th>
						<th>Event Name</th>
						<th>Event Start Time</th>
						<th>Event End Time</th>
						<th>Event Date</th>
						<th>Events Description</th>
						</tr>
						</thead>
						<tbody>
						<?php 		
						$i = 1;					
						while($results = $mydb->fetch_assoc($values)){
						?>
						<tr>
							<td> <?php echo $i; ?> </td>
							<td> <?php echo $results['event_name']; ?> </td>
							<td> <?php echo $results['event_starttime']; ?> </td>
							<td> <?php echo $results['event_endtime']; ?> </td>
							<td> <?php echo $results['event_date']; ?> </td>
							<td> <?php echo $results['event_desc']; ?> </td>				
						</tr>
						<?php 
								$i++;
							}					
						?>
						</tbody>
					</table>
				<?php } ?>
			</div>
		</div>
	</div>
<?php }elseif (isset($_GET['userreg'])) { ?>
	<div class="container">
		<div class="row">			
			<div class="col-md-8">
				<h2>USER REGISTRATION</h2>
				<form class="form-horizontal" role="form" action="" method="POST" name="user_reg" >
					<div class="form-group">
					<label class="control-label col-sm-5" for="event id">Please Enter the Event ID</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" id="eventid" placeholder="Event ID" name="eventid" />
						</div>
					</div>					
					<div class="form-group">        						
							<label class="control-label col-sm-5" for="Register Users"></label>   
							<div class="col-sm-6"> 
								<button type="submit" class="btn btn-default" name="userreg">Regiser Users</button>
							</div>
					</div>
				</form>
			</div>
		</div>
	</div>
<?php } ?>

<div id="eventscheduleModal" class="modal fade" role="dialog"  tabindex="-1" aria-labelledby="eventscheduleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">									
			<div class="modal-header login-modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title text-center" id="loginModalLabel"><span class="glyphicon glyphicon-calendar"></span> UPDATE SCHEDULE</h4>
			</div> 
			<div class="modal-body">
				<div class="text-center">
					<?php
					if($err_update == 1){
						echo "<p class = 'alert alert-danger' style='text-align:left; padding: 0px;'>&nbsp;&nbsp;Can't find the event. Please recheck the Event ID!</p>";
					} ?>
					<form class="form-horizontal" role="form" action="" method="POST" name="event_update" >
						<div class="form-group">
						<label class="control-label col-sm-6" for="event id">Please Enter the Event ID</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="eventid" placeholder="Event ID" name="eventid" />
							</div>
						</div>					
						<div class="form-group">        														
								<div class="col-sm-11"> 
									<button type="submit" class="btn btn-default" name="updateevent">Update Event</button>
								</div>
						</div>
					</form>
				</div>
			</div>			
		</div>
	</div>
</div>

<div id="userregModal" class="modal fade" role="dialog"  tabindex="-1" aria-labelledby="userregModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">									
			<div class="modal-header login-modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title text-center" id="loginModalLabel"><span class="glyphicon glyphicon-user"></span> USER REGISTRATION</h4>
			</div> 
			<div class="modal-body">
				<div class="text-center">
					<?php
					if($err_reg == 1){
						echo "<p class = 'alert alert-danger' style='text-align:left; padding: 0px;'>&nbsp;&nbsp;Can't find the event. Please recheck the Event ID!</p>";
					} ?>
					<form class="form-horizontal" role="form" action="" method="POST" name="invitation" >
						<div class="form-group">
						<label class="control-label col-sm-6" for="event id">Please Enter the Event ID</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="eventid" placeholder="Event ID" name="eventid" />
							</div>
						</div>					
						<div class="form-group">        														
								<div class="col-sm-11"> 
									<button type="submit" class="btn btn-default" name="sinvitation">SUBMIT</button>
								</div>
						</div>
					</form>
				</div>
			</div>			
		</div>
	</div>
</div>

<script src="bootstrap-3.3.5-dist/jquery-1.11.3.min.js"></script>
<script src="bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>

</body>

</html>