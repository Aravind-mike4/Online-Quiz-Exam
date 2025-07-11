<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Project Worlds || FEEDBACK </title>
<link  rel="stylesheet" href="css/bootstrap.min.css"/>
 <link  rel="stylesheet" href="css/bootstrap-theme.min.css"/>    
 <link rel="stylesheet" href="css/main.css">
 <link  rel="stylesheet" href="css/font.css">
 <script src="js/jquery.js" type="text/javascript"></script>

  <script src="js/bootstrap.min.js"  type="text/javascript"></script>
 	<link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
<?php if(@$_GET['w'])
{echo'<script>alert("'.@$_GET['w'].'");</script>';}
?>


</head>

<body>
<div class="row header">
<div class="col-lg-6">
<span class="logo">Test Your Skill</span></div>
<div class="col-md-2">
</div>
<div class="col-md-4">
<?php
 include_once 'dbConnection.php';
session_start();
if((isset($_SESSION['email']))){
echo '<a href="logout.php?q=feedback.php" class="pull-right sub1"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>&nbsp;&nbsp;&nbsp;&nbsp;Signout</a>';
}
else{
echo '<a href="index.php" class="pull-right sub1"><span class="glyphicon glyphicon-home" aria-hidden="true"></span>&nbsp;&nbsp;&nbsp;&nbsp;Home</a>';
}
?>
</div></div>

<div class="bg1">
<div class="row">
<div class="col-md-3"></div>
<div class="col-md-6 panel" style="background-image:url(image/bg1.jpg); min-height:430px;">
<h2 align="center" style="font-family:'typo'; color:#000066">FEEDBACK/REPORT A PROBLEM</h2>
<div style="font-size:14px">
<?php if(@$_GET['q'])echo '<span style="font-size:18px;"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span>&nbsp;'.@$_GET['q'].'</span><br /><br />';
else{echo' 
You can send us your feedback by filling the form below:-<h4 align="center" style="font-family:'typo'; color:#000066"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>&nbsp;&nbsp;Fill up Feedback Form</h4>
<form role="form"  method="post" action="feed.php?q=feedback.php">
<div class="form-group">
<input type="text" name="name" id="name" placeholder="Enter your name" class="form-control"/>
</div>
<div class="form-group">
<input type="email" name="email" id="email" placeholder="Enter your email-id" class="form-control"/></div>
<div class="form-group">
<input type="text" name="subject" id="subject" placeholder="Enter subject" class="form-control"/></div>
<div class="form-group">
<textarea rows="5" cols="8" name="feedback" id="feedback" class="form-control" placeholder="Write your feedback here..."></textarea>
</div>
<div class="form-group" align="center">
<input type="submit" name="submit" value="Submit" class="btn btn-primary" />
</div>
</form>';}?>
</div>
<div class="col-md-3"></div></div>
</div></div>
</div><!--container end-->


<div class="row footer">
<div class="col-md-3 box">
<a href="about.php" target="_blank">About us</a>
</div>
<div class="col-md-3 box">
<a href="#" data-toggle="modal" data-target="#login">Admin Login</a></div>
<div class="col-md-3 box">
<a href="#" data-toggle="modal" data-target="#developers">Developers</a>
</div>
<div class="col-md-3 box">
<a href="feedback.php" target="_blank">Feedback</a></div></div>
<!-- Modal For Developers-->
<div class="modal fade title1" id="developers">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" style="font-family:'typo' "><span style="color:orange">Developers</span></h4>
      </div>
    
      <div class="modal-body">
        <p>
		<div class="row">
		<div class="col-md-5">
		 <a href="https://www.facebook.com/profile.php?id=100008432658826" style="color:#202020; font-family:'typo' ; font-size:18px" title="Find on Facebook">Aravind</a>
		<h4 style="color:#202020; font-family:'typo' ;font-size:16px" class="title1">+91 8186977409</h4>
		<h4 style="font-family:'typo' ">pallepoguaravind3200@gmail.com</h4>
		<h4 style="font-family:'typo' ">Lakireddy Balireddy College of Engineering,Mylavaram</h4></div></div>
		</p>
      </div> 
    
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!--Modal for admin login-->
	 <div class="modal fade" id="login">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title"><span style="color:orange;font-family:'typo' ">LOGIN</span></h4>
      </div>
      <div class="modal-body title1">
<div class="row">
<div class="col-md-3"></div>
<div class="col-md-6">
<form role="form" method="post" action="admin.php?q=index.php">
<div class="form-group">
<input type="text" name="uname" maxlength="20"  placeholder="Admin user id" class="form-control"/> 
</div>
<div class="form-group">
<input type="password" name="password" maxlength="15" placeholder="Password" class="form-control"/>
</div>
<div class="form-group" align="center">
<input type="submit" name="login" value="Login" class="btn btn-primary" />
</div>
</form>
</div><div class="col-md-3"></div></div>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!--footer closed-->
</body>
</html>
