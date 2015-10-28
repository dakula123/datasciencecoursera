 
<?php

if ( isset( $_POST["profile"] ) ) {
 myprofile();
}elseif(isset( $_POST["events"] )){
	myevents();
}elseif(isset( $_POST["add"]) or isset($_POST["save"])){
	myschedule();
}elseif(isset( $_POST["track"] )){
	mytrack();
}elseif(isset( $_POST["help"] )){
	myhelp();
} elseif ( isset( $_GET["action"] ) and $_GET["action"] == "logout" ) {
 logout();
} else {
 displayPage(); 
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

function myprofile(){
	
}

function myevents(){
	
}

function mytrack(){
	
}

function myhelp(){
	
}

function myschedule(){
	$requiredFields = array( "name", "description","time1" , "time2",
 "date");
 $missingFields = array();
 foreach ( $requiredFields as $requiredField ) {
 if ( !isset( $_POST[$requiredField] ) or !$_POST[$requiredField] ) {
 $missingFields[] = $requiredField;
 }
 }
 if ( $missingFields ) {
 displayPage(3);
 } else {
 processform();
 }
}


function processform() {
 if ( isset( $_POST["name"] ) and isset( $_POST["description"] )  ) {
	 $a=$_POST["name"];
	 $b=$_POST["description"];
	$c=$_POST["time1"];
	$d=$_POST["time2"];
	$e=$_POST["date"];
	$f="";
	
$dsn="mysql:host=localhost;dbname=eventseventos";
$username="dakula1234";
$password="Eventos1234";


try{
	$conn=new PDO($dsn,$username,$password);
	$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} 
catch(PDOException $e){
	echo "Connection failed : ".$e->getMessage();
}

$sql="INSERT INTO events VALUES (NULL, '$a', '$b', '$c', '$d', '$e', '$f')";

try {
$st = $conn->query( $sql );
 } 
catch ( PDOException $e ) {
 echo 'Query failed: ' . $e->getMessage();
}
 displayPage(3);
 }
}

function logout() {
 unset( $_SESSION["username"] );
 session_write_close();
 header( "Location: inside.php" );
}

function displayPage($id) {
 displayPageHeader();
?>
<body onload=" if($id){showDiv('<?php echo $id ?>');return false} else{showDiv('1');return false}"> 
 <div id="header"><a href="#"><img src="logo.png" width="200" height="50"></a> </div>
 <div id="header2">
 <a href="https://twitter.com/"><img title="Twitter" src="Twitter.png" alt="Twitter" width="35" height="35" /></a>
 <a href="https://www.facebook.com/"><img title="facebook" src="fb.png" alt="facebook" width="35" height="35" /></a>
 <a href="https://plus.google.com/"><img title="googleplus" src="google+.png" alt="googleplus" width="35" height="35" /></a>
  <a href="https://www.pinterest.com/"><img title="pinterest" src="pinterest.png" alt="pinterest" width="35" height="35" /></a>
 </div>
 
  <div id="navigation">
  <ul class="menu">
  <li name="profile"><a href="My Profile" Onclick="showDiv('1');return false">My Profile</a></li>
   <li name="events"><a href="My Events" Onclick="showDiv('2');return false">My Events</a></li>
  <li name="schedule"><a href="Schedule" Onclick="showDiv('3');return false" >Schedule</a></li>
  <li name="track"><a href="Track Events" Onclick="showDiv('4');return false">Track</a></li>
  <li name="help"><a href="Help" Onclick="showDiv('5');return false">Help</a></li>
  <li name="logout"><a href="login.php?action=logout"> Logout </a ></li>
  </div>
  <div id="divlinks" width="100%" height="550px">
    <div id = "container1">
    		<form id="contactform" class="form" style = "margin-top: 5em;"> 
    			<p class="contact"><label for="name">Name</label></p> 
    			<input id="name" name="name" placeholder="First and last name" required="" tabindex="1" type="text"> 
    			 
    			<p class="contact"><label for="email">Email</label></p> 
    			<input id="email" name="email" placeholder="example@domain.com" required="" type="email"> 
                
                <p class="contact"><label for="password">Change password</label></p> 
    			<input type="password" id="password" name="password" required=""> 
                <p class="contact"><label for="repassword">Confirm your password</label> </p>
    			<input type="password" id="repassword" name="repassword" required="">
        
               <fieldset style="width:550" align="center" >
                 <label>Birthday</label>
                  <label class="month"> 
                  <select class="select-style" name="BirthMonth">
                  <option value="">Month</option>
                  <option  value="01">January</option>
                  <option value="02">February</option>
                  <option value="03" >March</option>
                  <option value="04">April</option>
                  <option value="05">May</option>
                  <option value="06">June</option>
                  <option value="07">July</option>
                  <option value="08">August</option>
                  <option value="09">September</option>
                  <option value="10">October</option>
                  <option value="11">November</option>
                  <option value="12" >December</option>
                  </label>
                 </select>    
                <label>Day<input class="birthday" maxlength="2" name="BirthDay"  placeholder="Day" required=""></label>
                <label>Year <input class="birthyear" maxlength="4" name="BirthYear" placeholder="Year" required=""></label>
              </fieldset></br>
  
            <select class="select-style gender" name="gender">
            <option value="select">i am..</option>
            <option value="m">Male</option>
            <option value="f">Female</option>
            <option value="others">Other</option>
            </select></br></br>
            
            <p class="contact"><label for="phone">Mobile phone</label></p> 
            <input id="phone" name="phone" placeholder="phone number" required="" type="text"></br></br>
            <p><input class="button" name="edit" id="edit" tabindex="5" value="Edit" type="submit"><input class="button" name="save" id="save" tabindex="5" value="Save" type="submit"></p></br> 	 
   </form>
   </div>
      <div id="container2">
	  <table class="tg" style = "margin-top: 3em">
  <tr>
    <th class="tg-infz">Event id</th>
    <th class="tg-infz">Event Name</th>
    <th class="tg-infz">Event Start Date</th>
    <th class="tg-infz">Event End Date</th>
    <th class="tg-infz">Total Number of Days</th>
    <th class="tg-infz">Paid Amount</th>
    <th class="tg-infz">Comments            </th>
  </tr>
  <tr>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
  </tr>
  <tr>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
  </tr>
  <tr>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
  </tr>
  <tr>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
  </tr>
  <tr>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
  </tr>
</table>
</div>
      
      <div id="container3"><p>
	  <form id="contactform" class="form" style = "margin-top: 5em;" action="contactform" method="POST"> 
    			<p class="contact"><label for="name" <?php validateField( "name",
$missingFields ) ?>>Name</label></p> 
    			<input id="name" name="name" placeholder="EventName" required="" tabindex="1" type="text"  <?php setValue( "name" ) ?>> 
				
    			<p class="contact"><label for="description"<?php validateField( "description",
$missingFields ) ?>>Description</label></p> 
    			<input id="Description" name="description" placeholder="Description" required="" tabindex="1" type="text"  <?php setValue( "description" ) ?>>
				
				<p class="contact"><label for="time1"<?php validateField( "time1",
$missingFields ) ?>> Start Time</label></p> 
				<select class="Time" name="time1"  <?php setValue( "time" ) ?>>
                <option value="08:00:00">08:00</option>
                <option value="08:30:00">08:30</option>
                <option value="09:00:00">09:00</option>
                <option value="09:30:00">09:30</option>
                <option value="10:00:00">10:00</option>
                <option value="10:30:00">10:30</option>
                <option value="11:00:00">11:00</option>
                <option value="11:30:00">11:30</option>
                <option value="12:00:00">12:00</option>
                <option value="12:30:00">12:30</option>
                <option value="13:00:00">13:00</option>
                <option value="13:30:00">13:30</option>
				<option value="14:00:00">14:00</option>
				<option value="14:30:00">14:30</option>
				<option value="15:00:00">15:00</option>
				<option value="15:30:00">15:30</option>
				<option value="16:00:00">16:00</option>
				<option value="16:30:00">16:30</option>
				<option value="17:00:00">17:00</option>
				<option value="17:30:00">17:30</option>
				<option value="18:00:00">18:00</option>
				<option value="18:30:00">18:30</option>
				<option value="19:00:00">19:00</option>
				<option value="19:30:00">19:30</option>
				<option value="20:00:00">20:00</option>
				<option value="20:30:00">20:30</option>				
               </select><br>
			   	<p class="contact"><label for="time2" <?php validateField( "time2",
$missingFields ) ?>> End Time</label></p> 
            	<select class="Time" name="time2"  <?php setValue( "time2" ) ?>>
                <option value="08:30:00">08:30</option>
                <option value="09:00:00">09:00</option>
                <option value="09:30:00">09:30</option>
                <option value="10:00:00">10:00</option>
                <option value="10:30:00">10:30</option>
                <option value="11:00:00">11:00</option>
                <option value="11:30:00">11:30</option>
                <option value="12:00:00">12:00</option>
                <option value="12:30:00">12:30</option>
                <option value="13:00:00">13:00</option>
                <option value="13:30:00">13:30</option>
				<option value="14:00:00">14:00</option>
				<option value="14:30:00">14:30</option>
				<option value="15:00:00">15:00</option>
				<option value="15:30:00">15:30</option>
				<option value="16:00:00">16:00</option>
				<option value="16:30:00">16:30</option>
				<option value="17:00:00">17:00</option>
				<option value="17:30:00">17:30</option>
				<option value="18:00:00">18:00</option>
				<option value="18:30:00">18:30</option>
				<option value="19:00:00">19:00</option>
				<option value="19:30:00">19:30</option>
				<option value="20:00:00">20:00</option>
				<option value="20:30:00">20:30</option>				
               <option value="21:00:00">21:00</option>				
               <option value="21:30:00">21:30</option>				
               <option value="22:00:00">22:00</option>				
               <option value="22:30:00">22:30</option>				
               <option value="23:00:00">23:00</option>				
               <option value="23:30:00">23:30</option>				
               <option value="00:00:00">00:00</option>				               
			   </select> 
			   
			   <p class="contact"><label for="date"<?php validateField( "date",
$missingFields ) ?>>Date</label> 
                <input id="datepicker" style="width: 40%" name="date"><img title="calendar" src="Calender.jpg" alt="calendar" width="35" height="35" style top-margin="2em"  <?php setValue( "date" ) ?> /></p>
               
			   <p> <button formnovalidate="formnovalidate" name="add" value="add">Add</button> 
               <button name="save" value="update">Save</button> </p>				
           

        </div>      
      <div id="container4">
  <table class="tg" style = "margin-top: 3em">
  <tr>
    <th class="tg-infz">Event id</th>
    <th class="tg-infz">Event Name</th>
    <th class="tg-infz">Event Start Date</th>
    <th class="tg-infz">Event End Date</th>
    <th class="tg-infz">Total Number of Days</th>
    <th class="tg-infz">Paid Amount</th>
    <th class="tg-infz">Comments            </th>
  </tr>
  <tr>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
  </tr>
  <tr>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
  </tr>
  <tr>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
  </tr>
  <tr>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
  </tr>
  <tr>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
    <td class="tg-56bi"></td>
  </tr>
</table>
       </div>
	  <div id="container5"><p> Write descrip of 5
       </div>
	   </div>
 <div id="footer">
copyright@EVENTOS   2015 </div>
 </body>
 </html>
 <?php
}


function displayPageHeader() {
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
<script type="text/javascript">
function showDiv(idInfo) {
  var sel = document.getElementById('divlinks').getElementsByTagName('div');
  for (var i=0; i<sel.length; i++) {
    sel[i].style.display = 'none';
  }
  document.getElementById('container'+idInfo).style.display = 'block';
}
</script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
 <script>
  $(function() {
    $( "#datepicker" ).datepicker();
  });
  </script>
  
  <style>
 	#header{
	    height:50px;
		width:20%;
		float:left;
		margin-top:0px;
	}
	#header2{
	    height:35px;
		margin-top:0px;
		margin-left:85%;
	}
	ul
	{
	float:left;
	list-style:none;
    margin:0px auto;
	}
	.menu > li {
    float:left;
    display:inline-block;
    font-size:19px;
	padding:8 px;
    }
    .menu > li > a {
	text-decoration:none;
    padding:10px 40px;
    display:block;
    text-shadow:0px 1px 0px rgba(0,0,0,0.4);
    }
	.menu li:hover > a
	{
    text-decoration:none;
    color:#be5b70;
    background:#2e2728;
    }
   	#content
	{
	width:100%;
	height:550px;
	float:left;
    }
	#footer {
	    height:50 px;
		width:100%;
		float:left;
		margin-bottom: 10px;
	}
.form{
	background:#99CCFF ; width:450px; margin:0 auto; padding-left:50px; padding-top:20px;
}
.form fieldset{border:0px; padding:0px; margin:0px;}
.form p.contact { font-size: 12px; margin:0px 0px 10px 0;line-height: 14px; font-family:serif}

.form input[type="text"] { width: 400px; }
.form input[type="email"] { width: 400px; }
.form input[type="password"] { width: 400px; }
.form input.birthday{width:60px;}
.form input.birthyear{width:120px;}
.form label { color: Blue; font-weight:bold;font-size: 15px;font-family:serif; }
.form label.month {width: 135px;}
.form input, textarea { border: 1px solid rgba(122, 192, 0, 0.15); padding: 7px; font-family: serif; color: red; font-size: 14px; -webkit-border-radius: 5px; margin-bottom: 15px; margin-top: -10px; }
.form input:focus, textarea:focus { border: 1px solid #ff5400; background-color: rgba(255, 255, 255, 1); }
.form .select-style {
  -webkit-appearance: button;
  -webkit-border-radius: 2px;
  -webkit-box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.1);
  -webkit-padding-end: 20px;
  -webkit-padding-start: 2px;
  -webkit-user-select: none; 
 -webkit-linear-gradient(#FAFAFA, #F4F4F4 40%, #E5E5E5);
  background-position: center;
  border: 0px solid #FFF;
  color: red;
  font-size: 14px;
  margin: 0;
  overflow: hidden;
  padding-top: 5px;
  padding-bottom: 5px;
  text-overflow: ellipsis;
  white-space: nowrap;
  }
.form .gender 
{
  width:410px;
 }
.form input.button
{ background: lime; display:block; padding: 5px 10px 6px; text-decoration: none; font-weight: bold; line-height: 1; -moz-border-radius: 5px; -webkit-border-radius: 5px; border-radius: 5px; -moz-box-shadow: 0 1px 3px #999; -webkit-box-shadow: 0 1px 3px #999; box-shadow: 0 1px 3px #999; border: none; position: relative; cursor: pointer; font-size: 14px; font-family:serif;}
.form input.button:hover
{ background-color: blue; }
.tg  {float:left;width:100%;border-collapse:collapse;border-spacing:0;border-color:#999;margin:0px auto;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:14px 20px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-color:#999;color:#444;background-color:#F7FDFA;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:14px 20px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-color:#999;color:#fff;background-color:#26ADE4;}
.tg .tg-infz{font-weight:bold;font-size:16px;font-family:"Times New Roman", Times, serif !important;;background-color:#fe0000;color:#ffffff}
.tg .tg-56bi{background-color:#99CCFF;color:#000000}
</style>
</head>
<?php
}
?>
