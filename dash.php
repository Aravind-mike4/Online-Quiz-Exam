<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>ADMIN DASHBOARD </title>
<link  rel="stylesheet" href="css/bootstrap.min.css"/>
 <link  rel="stylesheet" href="css/bootstrap-theme.min.css"/>    
 <link rel="stylesheet" href="css/main.css">
 <link  rel="stylesheet" href="css/font.css">
 <script src="js/jquery.js" type="text/javascript"></script>

  <script src="js/bootstrap.min.js"  type="text/javascript"></script>
 	<link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>

<script>
$(function () {
    $(document).on( 'scroll', function(){
        console.log('scroll top : ' + $(window).scrollTop());
        if($(window).scrollTop()>=$(".logo").height())
        {
             $(".navbar").addClass("navbar-fixed-top");
        }

        if($(window).scrollTop()<$(".logo").height())
        {
             $(".navbar").removeClass("navbar-fixed-top");
        }
    });
});
</script>
</head>

<body>
<div class="header">
<div class="row">
<div class="col-lg-6">
<span class="logo">Test Your Skill</span></div>
<?php
include_once 'dbConnection.php';
session_start();
$email=$_SESSION['email'];
if(!(isset($_SESSION['email']))){
header("location:index.php");
}
else
{
$name = $_SESSION['name'];
}?>

<div class="col-md-2">
</div>
<div class="col-md-4">
 <a href="logout.php?q=dashboard.php" class="pull-right sub1"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>&nbsp;&nbsp;&nbsp;&nbsp;Signout</a></div>
</div></div>
<div class="container"><!--container start-->
<div class="row">
<div class="col-md-12">
<!--navigation menu-->
<ul class="nav nav-tabs title1">
<li <?php if(@$_GET['q']==0) echo'class="active"'; ?>><a href="dash.php?q=0">Home</a></li>
<li <?php if(@$_GET['q']==1) echo'class="active"'; ?>><a href="dash.php?q=1">User</a></li>
<li <?php if(@$_GET['q']==2) echo'class="active"'; ?>><a href="dash.php?q=2">Ranking</a></li>
<li <?php if(@$_GET['q']==3) echo'class="active"'; ?>><a href="dash.php?q=3">Add Quiz</a></li>
<li <?php if(@$_GET['q']==5) echo'class="active"'; ?>><a href="dash.php?q=5">Remove Quiz</a></li>
</ul>
<!--navigation menu closed-->

<!--home start-->
<?php if(@$_GET['q']==0) { ?>
    <div class="row">
        <div class="col-md-12">
            <h3 class="page-header">Welcome to Admin Dashboard, <?php echo $name; ?>!</h3>
            <p>From here you can manage users, quizzes, and feedback.</p>
        </div>
    </div>
<?php } ?>
<!--home closed-->

<!--users start-->
<?php if(@$_GET['q']==1) {
    try {
        $usersCollection = $db->users;
        $users = $usersCollection->find([]);

        echo '<div class="panel"><div class="table-responsive"><table class="table table-striped title1">
        <tr><td><b>S.N.</b></td><td><b>Name</b></td><td><b>Email</b></td><td></td></tr>';
        $c=1;
        foreach($users as $user) {
            echo '<tr><td>'.$c++.'</td><td>'.$user['name'].'</td><td>'.$user['email'].'</td>
            <td><a title="Delete User" href="update.php?demail='.$user['email'].'"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td></tr>';
        }
        echo '</table></div></div>';
    } catch (MongoDB\Driver\Exception\Exception $e) {
        echo '<div class="alert alert-danger">Error fetching users: ' . $e->getMessage() . '</div>';
    }
}?>
<!--users end-->

<!--feedback start-->
<?php if(@$_GET['q']==2) {
    try {
        $feedbackCollection = $db->feedback;
        $feedbacks = $feedbackCollection->find([], ['sort' => ['date' => -1]]);

        echo '<div class="panel"><div class="table-responsive"><table class="table table-striped title1">
        <tr><td><b>S.N.</b></td><td><b>Subject</b></td><td><b>Email</b></td><td><b>Name</b></td><td><b>Date</b></td><td><b>Time</b></td><td><b>By</b></td><td></td></tr>';
        $c=1;
        foreach($feedbacks as $feedback) {
            $date = $feedback['date']->toDateTime()->format('Y-m-d');
            $time = $feedback['date']->toDateTime()->format('H:i:s');
            echo '<tr><td>'.$c++.'</td>';
            echo '<td><a title="Click to view feedback" href="dash.php?q=3&id='.$feedback['_id'].'">'.$feedback['subject'].'</a></td>';
            echo '<td>'.$feedback['email'].'</td><td>'.$feedback['name'].'</td><td>'.$date.'</td><td>'.$time.'</td>';
            echo '<td>'.$feedback['email'].'</td>';
            echo '<td><a title="Delete Feedback" href="update.php?fdid='.$feedback['_id'].'"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td></tr>';
        }
        echo '</table></div></div>';
    } catch (MongoDB\Driver\Exception\Exception $e) {
        echo '<div class="alert alert-danger">Error fetching feedback: ' . $e->getMessage() . '</div>';
    }
}?>
<!--feedback closed-->

<!--add quiz start-->
<?php
if(@$_GET['q']==3 && !isset($_GET['step']) ) {
echo '
<div class="row">
<span class="title1" style="font-size:30px;"><b>Enter Quiz Details</b></span><br /><br />
 <div class="col-md-3"></div><div class="col-md-6">   <form class="form-horizontal title1" name="form" action="update.php?q=addquiz" method="POST">
<fieldset>


<!-- Text input-->
<div class="form-group">
  <label class="col-md-12 control-label" for="name"></label>  
  <div class="col-md-12">
  <input id="name" name="name" placeholder="Enter Quiz title" class="form-control input-md" type="text">
    
  </div>
</div>



<!-- Text input-->
<div class="form-group">
  <label class="col-md-12 control-label" for="total"></label>  
  <div class="col-md-12">
  <input id="total" name="total" placeholder="Enter total number of questions" class="form-control input-md" type="number">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-12 control-label" for="right"></label>  
  <div class="col-md-12">
  <input id="right" name="right" placeholder="Enter marks on right answer" class="form-control input-md" min="0" type="number">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-12 control-label" for="wrong"></label>  
  <div class="col-md-12">
  <input id="wrong" name="wrong" placeholder="Enter minus marks on wrong answer without sign" class="form-control input-md" min="0" type="number">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-12 control-label" for="time"></label>  
  <div class="col-md-12">
  <input id="time" name="time" placeholder="Enter time limit for test in minutes" class="form-control input-md" min="1" type="number">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-12 control-label" for="tag"></label>  
  <div class="col-md-12">
  <input id="tag" name="tag" placeholder="Enter #tag which is used for searching" class="form-control input-md" type="text">
    
  </div>
</div>


<!-- Text input-->
<div class="form-group">
  <label class="col-md-12 control-label" for="desc"></label>  
  <div class="col-md-12">
  <textarea rows="8" cols="8" name="desc" class="form-control" placeholder="Write description here..."></textarea>  
  </div>
</div>


<div class="form-group">
  <label class="col-md-12 control-label" for=""></label>
  <div class="col-md-12"> 
    <input  type="submit" style="margin-left:45%" class="btn btn-primary" value="Submit" class="btn btn-primary"/>
  </div>
</div>

</fieldset>
</form></div></div>';
}
?>
<!--add quiz end-->

<!--add quiz step2 start-->
<?php
if(@$_GET['q']==4 && @$_GET['step']==2 ) {
echo ' 
<div class="row">
<span class="title1" style="font-size:30px;"><b>Enter Question Details</b></span><br /><br />
 <div class="col-md-3"></div><div class="col-md-6"><form class="form-horizontal title1" name="form" action="update.php?q=addqns&n='.@$_GET['n'].'&eid='.@$_GET['eid'].'&ch=4 " method="POST">
<fieldset>
';
 
 for($i=1;$i<=@$_GET['n'];$i++)
 {
 echo '<b>Question number&nbsp;'.$i.'&nbsp;:</><br /><!-- Text input-->
<div class="form-group">
  <label class="col-md-12 control-label" for="qns'.$i.' "></label>  
  <div class="col-md-12">
  <textarea rows="3" cols="5" name="qns'.$i.'" class="form-control" placeholder="Write question number '.$i.' here..."></textarea>  
  </div>
</div>
<!-- Text input-->
<div class="form-group">
  <label class="col-md-12 control-label" for="'.$i.'1"></label>  
  <div class="col-md-12">
  <input id="'.$i.'1" name="'.$i.'1" placeholder="Enter option a" class="form-control input-md" type="text">
    
  </div>
</div>
<!-- Text input-->
<div class="form-group">
  <label class="col-md-12 control-label" for="'.$i.'2"></label>  
  <div class="col-md-12">
  <input id="'.$i.'2" name="'.$i.'2" placeholder="Enter option b" class="form-control input-md" type="text">
    
  </div>
</div>
<!-- Text input-->
<div class="form-group">
  <label class="col-md-12 control-label" for="'.$i.'3"></label>  
  <div class="col-md-12">
  <input id="'.$i.'3" name="'.$i.'3" placeholder="Enter option c" class="form-control input-md" type="text">
    
  </div>
</div>
<!-- Text input-->
<div class="form-group">
  <label class="col-md-12 control-label" for="'.$i.'4"></label>  
  <div class="col-md-12">
  <input id="'.$i.'4" name="'.$i.'4" placeholder="Enter option d" class="form-control input-md" type="text">
    
  </div>
</div>
<br />
<b>Correct answer for question number&nbsp;'.$i.'&nbsp;:</><br />
<select id="ans'.$i.'" name="ans'.$i.'" placeholder="Choose correct answer " class="form-control input-md" >
   <option value="a">Select answer for question '.$i.'</option>
  <option value="a">option a</option>
  <option value="b">option b</option>
  <option value="c">option c</option>
  <option value="d">option d</option> </select><br /><br />'; 
 }
    
echo '<div class="form-group">
  <label class="col-md-12 control-label" for=""></label>
  <div class="col-md-12"> 
    <input  type="submit" style="margin-left:45%" class="btn btn-primary" value="Submit" class="btn btn-primary"/>
  </div>
</div>

</fieldset>
</form></div>';



}
?><!--add quiz step 2 end-->

<!--remove quiz-->
<?php if(@$_GET['q']==5) {

    try {
        $quizzesCollection = $db->quizzes;
        $quizzes = $quizzesCollection->find([], ['sort' => ['creation_date' => -1]]);

        echo '<div class="panel"><div class="table-responsive"><table class="table table-striped title1">
        <tr><td><b>S.N.</b></td><td><b>Topic</b></td><td><b>Total question</b></td><td><b>Marks</b></td><td><b>Time limit</b></td><td></td></tr>';
        $c=1;
        foreach($quizzes as $quiz) {
            $title = $quiz['title'];
            $total = $quiz['total_questions'];
            $sahi = $quiz['marks_per_correct_answer'];
            $time = $quiz['time_limit_minutes'];
            $eid = $quiz['_id'];
            echo '<tr><td>'.$c++.'</td><td>'.$title.'</td><td>'.$total.'</td><td>'.$sahi*$total.'</td><td>'.$time.'&nbsp;min</td>
            <td><b><a href="update.php?q=rmquiz&eid='.$eid.'" class="pull-right btn btn-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span>&nbsp;<span class="title1"><b>Remove</b></span></a></b></td></tr>';
        }
        echo '</table></div></div>';

    } catch (MongoDB\Driver\Exception\Exception $e) {
        echo '<div class="alert alert-danger">Error fetching quizzes: ' . $e->getMessage() . '</div>';
    }
}?>
<!--remove quiz end-->


</div><!--container closed-->
</div></div>
</body>
</html>
