<?php include('header.php'); ?> <!-- includes header  -->

<?php if(isset($_GET['token']) && !empty($_GET['token']) && $mydb->count_row($mydb->getQuery("*","tbl_organizers","reset_code = '".$_GET['token']."'")) == 1){ ?>

<style type="text/css">
	.navbar-inverse .navbar-nav > li > a{ color: #337ab7; }
	header>.navbar { background-color: #7EEEEB; }
	.container {background-color: white; margin: auto; width: auto; height: 650px; }
	.container>.row { margin: auto; width: auto; padding:0px; padding-top: 80px; padding-bottom: 15px;}
	label.control-label.col-sm-4 {
	    width: 18%;
	}

	label.control-label.col-sm-5 {
	    width: 25%;
	}
</style>

<?php 
	if(isset($_POST['reset_pass'])){
		if($_POST['pass'] == $_POST['cpass']){

			$data = '';
			$data['upass'] = $_POST['pass'];
			$data['reset_code'] = "nullnullnullnull";

			$mydb->updateQuery("tbl_organizers",$data,"reset_code = '".$_GET['token']."'");

			echo '<script type="text/javascript">           						
				window.location = "http://www.eventseventos.info/?reset_success=1"
			</script>';

		}else{
			echo '<script type="text/javascript">           						
				window.location = "http://www.eventseventos.info/reset.php?err=1"
			</script>';
		}
	}

?>

<header>
	<nav class="navbar navbar-inverse navbar-fixed-top">
	  <div class="container-fluid">
	    <div class="navbar-header"><a class="navbar-brand" href="index.php">Eventos</a></div>
	    </div>
	  </div>
	</nav>
</header>

<div class="container">
	<div class="row">
		<div class="col-md-8">
			<h2>RESET PASSWORD</h2>
			<form class="form-horizontal" role="form" action="" method="POST" name="reset_pass_form" >
				<div class="form-group">
				<label class="control-label col-sm-4" for="event name">New Password</label>
					<div class="col-sm-6">
						<input type="password" class="form-control" id="pass" placeholder="Enter Password" name="pass" required>
					</div>
					<?php if ( $_GEt['err'] ) echo "&nbsp;&nbsp;<p class = 'alert alert-danger' style='text-align:center; padding: 0px;'>The passwords didnt match!</p>"; ?>
				</div>
				<div class="form-group">
				<label class="control-label col-sm-4" for="description">Retype Password</label>
					<div class="col-sm-6">
						<input type="password" class="form-control" id="cpass" placeholder="Confirm Password" name="cpass" required>						
					</div>
					<?php if ( $_GEt['err'] ) echo "&nbsp;&nbsp;<p class = 'alert alert-danger' style='text-align:center; padding: 0px;'>The passwords didnt match!</p>"; ?>
				</div>				
				<div class="form-group">        						
						<label class="control-label col-sm-4" for="Reset Password"></label>   
						<div class="col-sm-6"> 
							<button type="submit" class="btn btn-default" name="reset_pass">Reset Password</button>
						</div>
				</div>
			</form>
		</div>
	</div>
</div>

<script src="bootstrap-3.3.5-dist/jquery-1.11.3.min.js"></script>
<script src="bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>

</body>

</html>

<?php 

	}else{
		echo '<script type="text/javascript">           						
			window.location = "http://www.eventseventos.info/"
		</script>';	
	}

?>