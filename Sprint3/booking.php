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
	
	#bookseat .radio{
   		width: 53px;  
	    height: 90px;
		background-image: url("images/not_booked.png");
		background-size:     cover;
    	background-repeat:   no-repeat;
    	background-position: center center;
	}

	#bookseat .radio-checked{
		position: relative;
	    display: block;
	    margin-top: 10px;
	    margin-bottom: 10px;
		width: 53px;  
	    height: 90px;
		background-image: url("images/booked.png");
		background-size:     cover;  
    	background-repeat:   no-repeat;
    	background-position: center center;
	}
	td{
		vertical-align: middle !important;
	}
</style>

<script type="text/javascript">
 $(document).ready(function(){
	$('input:radio').hide().each(function() {
        $(this).attr('data-radio-fx', this.name);
        var label = $("label[for=" + '"' + this.id + '"' + "]").text();
        $('<a ' + (label != '' ? 'title=" ' + label + ' "' : '' ) + ' data-radio-fx="'+this.name+'" class="radio-fx" href="#">'+
            '<span class="radio' + (this.checked ? ' radio-checked' : '') + '"></span></a>').insertAfter(this);
    });
    $('a.radio-fx').on('click', function(e) {
        e.preventDefault();
        var unique = $(this).attr('data-radio-fx');
        $("a[data-radio-fx='"+unique+"'] span").attr('class','radio');
        $(":radio[data-radio-fx='"+unique+"']").attr('checked',false);
        $(this).find('span').attr('class','radio-checked');
        $(this).prev('input:radio').attr('checked',true);
    }).on('keydown', function(e) {
        if ((e.keyCode ? e.keyCode : e.which) == 32) {
            $(this).trigger('click');
        }
    });
});
</script>

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
	    		$eventid_invite = $event_nums[$i];	    		
	    	}	    	
	    }

    	$getvaluecount = $mydb->getCount('UID','tbl_booking',"event_token='".$_GET['token']."' AND UID='".$_GET['value']."' AND status='true'");  

	    $get_email = $mydb->getValue('email_id','tbl_booking',"UID = '".$_GET['value']."' AND event_token='".$_GET['token']."'");  

		if($getvaluecount == 1){
    		$check_eventnum = "true";    		
    	}else{
    		$check_eventnum = "false";
    	}	 
	} 
?>

<?php 
	if(isset($_POST['userbooking'])){
		$getvaluecount = '';
		//insert user data to tbl_users table	
		$data = '';
		$data['user_fname'] = $_POST['userfname'];
		$data['user_lname'] = $_POST['userlname'];
		$data['user_email'] = $_POST['useremail'];
		$data['user_mobile'] = $_POST['usercontact_num'];
		$getvaluecount = $mydb->getCount('UID','tbl_users',"user_email='".$_POST['useremail']."'");

		if($getvaluecount != 1){			
			$user_table_id = $mydb->insertQuery('tbl_users',$data);
		}

		//update booked seat number to tbl_events 
		$data='';
		$data['seat_num'] = $_POST['seat'];
		$data['status'] = "false";
		$return_value = $mydb->updateQuery('tbl_booking', $data ,"email_id ='".$_POST['useremail']."' AND event_token='".$_GET['token']."'");

		if($return_value == 1){
			$full_name = $_POST['userfname']." ".$_POST['userlname'];
			$user_email = $_POST['useremail'];
			$booking_id = $mydb->getValue('UID','tbl_booking',"email_id = '".$_POST['useremail']."' AND event_token='".$_GET['token']."'");  
			$email_message = "<html>
								<head>
								  <title>Seat Information</title>
								</head>
								<body>
								<b>Thank You for booking you seat. The details of the event are given below.</b>
								<br>						  
								  <table>
								    <tr>
								      <th>Event Name</th><td>".$mydb->getValue("event_name","tbl_events","event_num = '".$eventid_invite."'")."</td>
								    </tr>
								    <tr>
								      <th>Seat Number</th><td>".$_POST['seat']."</td>
								    </tr>							   
								    <tr>
								      <th>Event Start Time</th><td>".$mydb->getValue("event_starttime","tbl_events","event_num = '".$eventid_invite."'")."</td>
								    </tr>
								    <tr>
								      <th>Event End Time</th><td>".$mydb->getValue("event_endtime","tbl_events","event_num = '".$eventid_invite."'")."</td>
								    </tr>
								    <tr>
								      <th>Event Date</th><td>".$mydb->getValue("event_date","tbl_events","event_num = '".$eventid_invite."'")."</td>
								    </tr>
								  </table>
								</body>
								</html>
								<br> For circumtances such as to cancel the booking please use the link: http://www.eventseventos.info/cancel_booking.php?token=".$_GET['token']."&value=".$booking_id;

			$confirmed_booking_mail = $mydb->sendEmail($fullname,$user_email,"Admin","info@eventos.com","Booking Information",$email_message);
		
			echo '<script type="text/javascript">           						
				window.location = "http://www.eventseventos.info?registration=success"
			</script>';	
		}
	}
?>

<div class="container">
	<div class="row">
		<?php if($check_eventnum == "true"){ ?>
							
		<form action="" method="POST" name="user_profile">			
			<div class="col-md-8">				
					<h1>BOOK YOUR SEAT </h1>
					<table class="table">
						<tr>
							<th>Event Name</th>
							<td><?php echo $mydb->getValue("event_name","tbl_events","event_num = '".$eventid_invite."'"); ?></td>

							<th>Event Date</th>
							<td><?php echo $mydb->getValue("event_date","tbl_events","event_num = '".$eventid_invite."'"); ?></td>
						</tr>
						<tr>
							<th>Event Start Time</th>
							<td><?php echo $mydb->getValue("event_starttime","tbl_events","event_num = '".$eventid_invite."'"); ?></td>

							<th>Event End Time</th>
							<td><?php echo $mydb->getValue("event_endtime","tbl_events","event_num = '".$eventid_invite."'"); ?></td>
						</tr>		      					    		
					</table>						    		    				
					<div class="form-group">
					    	<div class="input-group">
					      		<div class="input-group-addon"><span class="glyphicon glyphicon-user"> Firstname</span></div>
					      		<input type="text" class="form-control" id="userfname" placeholder="Firstname" name="userfname" value="<?php if(!empty($_fname)){ echo $_fname; }?>" required>
					    	</div>
					 </div>
					<div class="form-group">
				    	<div class="input-group">
				      		<div class="input-group-addon"><span class="glyphicon glyphicon-user"> Lastname</span></div>
							<input type="text" class="form-control" id="userlname" placeholder="Lastname" name="userlname" value="<?php if(!empty($_lname)){ echo $_lname; }?>" required>
				    	</div>
				  	</div>
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-addon"><span class="glyphicon glyphicon-envelope"> Email</span></div>							
								<input type="email" class="form-control" id="useremail" placeholder="Email" name="useremail" value="<?php  if(!empty($get_email)){ echo $get_email; }?>" readonly>
						</div>								
					</div>

					<div class="form-group">
						<div class="input-group">
							<div class="input-group-addon"><span class="glyphicon glyphicon-phone"> Mobile</span></div>
								<input type="number" class="form-control" id="usercontact_num" placeholder="+001XXXXXXXXXX" name="usercontact_num" value="<?php if(!empty($_mobile)){ echo $_mobile; }?>" required>
							</div>
						</div>		  			
			
				<table class="table" id="bookseat">
						<tr>
							<td>Row/Column<td>
							<td>1</td>
							<td>2</td>
							<td>3</td>
							<td>4</td>
							<td>5</td>
							<td>6</td>
							<td>7</td>
							<td>8</td>
							<td>9</td>
							<td>10</td>					
						</tr>
						<?php 
						$booking_check = '';
						for($i=0;$i<7;$i++){ 
							$q = array('A','B','C','D','E','F','G');						
							?>
							<tr>
								<td style="text-align:center;"><?php echo $q[$i]; ?><td>							
								<?php for($j=1;$j<11;$j++){ 
									$seat_number = $q[$i].$j;
									$booking_check = $mydb->getCount('UID','tbl_booking',"seat_num='".$seat_number."' AND event_token='".$_GET['token']."'");
								?>
								<?php if($booking_check == 1){ ?>
										<td><img src="images/booked.png" href="#" height="90" width="53" /></td>							
									<?php }else{ ?>
										<td><input type="radio" value="<?php echo $q[$i].$j; ?>" name="seat" /></td>							
									<?php }
									$booking_check = '';
									 ?>
								<?php } ?>
							</tr>
						<?php } ?>
					</table>
				<button type="submit" id="userbooking" class="btn btn-block bt-login" data-loading-text="Signing In...." name="userbooking">CONFIRM BOOKING</button>
			  	<div class="clearfix"></div>				
			</div>
		</form>
		<?php }else{ 
	 		echo "<p class = 'alert alert-info' style='text-align:left; padding: 0px;'>&nbsp;&nbsp;<b>INCORRECT TOKEN INFORMATION</b></p>";
	  	} ?>

	</div>
</div>

<script src="bootstrap-3.3.5-dist/jquery-1.11.3.min.js"></script>
<script src="bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>

</body>

</html>

