<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>TEST YOUR SKILL </title>
<link  rel="stylesheet" href="css/bootstrap.min.css"/>
 <link  rel="stylesheet" href="css/bootstrap-theme.min.css"/>    
 <link rel="stylesheet" href="css/main.css">
 <link  rel="stylesheet" href="css/font.css">
 <script src="js/jquery.js" type="text/javascript"></script>

 
  <script src="js/bootstrap.min.js"  type="text/javascript"></script>
<link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
</head>
<?php
include_once 'dbConnection.php';
?>
<body>
<div class="header">
<div class="row">
<div class="col-lg-6">
<span class="logo">Test Your Skill</span></div>
<div class="col-md-2">
</div>
<div class="col-md-4">
<?php
 include_once 'dbConnection.php';
session_start();
if(!(isset($_SESSION['email']))){
header("location:index.php");
}
else
{
$name = $_SESSION['name'];
$email=$_SESSION['email'];
}
?>
<a href="logout.php?q=account.php" class="pull-right sub1"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>&nbsp;&nbsp;&nbsp;&nbsp;Signout</a></div>
</div></div>
<div class="container"><!--container start-->
<div class="row">
<div class="col-md-12">
<!--navigation menu-->
<ul class="nav nav-tabs title1">
<li <?php if(@$_GET['q']==1) echo'class="active"'; ?>><a href="account.php?q=1"><span class="glyphicon glyphicon-home" aria-hidden="true"></span>&nbsp;Home<span class="sr-only">(current)</span></a></li>
<li <?php if(@$_GET['q']==2) echo'class="active"'; ?>><a href="account.php?q=2"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>&nbsp;History</a></li>
<li <?php if(@$_GET['q']==3) echo'class="active"'; ?>><a href="account.php?q=3"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span>&nbsp;Ranking</a></li>
</ul>
<!--navigation menu closed-->

<!--home start-->
<?php if(@$_GET['q']==1) {
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
            <td><b><a href="account.php?q=quiz&step=2&eid='.$eid.'&n=1&t='.$total.'" class="pull-right btn btn-primary"><span class="glyphicon glyphicon-new-window" aria-hidden="true"></span>&nbsp;<span class="title1"><b>Start</b></span></a></b></td></tr>';
        }
        echo '</table></div></div>';
    } catch (MongoDB\Driver\Exception\Exception $e) {
        echo '<div class="alert alert-danger">Error fetching quizzes: ' . $e->getMessage() . '</div>';
    }
}?>
<!--home closed-->

<!--quiz start-->
<?php
if(@$_GET['q']=='quiz' && @$_GET['step']==2 ) {
    $eid=@$_GET['eid'];
    $n=@$_GET['n'];
    $total=@$_GET['t'];

    try {
        $quizzesCollection = $db->quizzes;
        $quiz = $quizzesCollection->findOne(['_id' => $eid]);

        if ($quiz && isset($quiz['questions']) && count($quiz['questions']) >= $n) {
            $question = $quiz['questions'][$n-1]; // Get the Nth question (0-indexed array)

            echo '<div class="panel" style="margin:5%">';
            echo '<b>Question &nbsp;'.$n.'&nbsp;::</b><br />';
            echo $question['question_text'].'<br /><br />';
            echo '<form action="update.php?q=quiz&step=2&eid='.$eid.'&n='.$n.'&t='.$total.'&ans=1" method="POST" class="form-horizontal">
            <br />';

            // Display options
            $optionLabels = ['a', 'b', 'c', 'd'];
            foreach ($optionLabels as $index => $label) {
                if (isset($question['options'][$index])) {
                    echo '<input type="radio" name="ans" value="'.$label.'">'.$question['options'][$index].'<br /><br />';
                }
            }
            echo '<br /><button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span>&nbsp;Submit</button></form></div>';
        } else {
            echo '<div class="alert alert-danger">Quiz or question not found.</div>';
        }
    } catch (MongoDB\Driver\Exception\Exception $e) {
        echo '<div class="alert alert-danger">Error fetching quiz details: ' . $e->getMessage() . '</div>';
    }
}

if(@$_GET['q']=='result' && @$_GET['eid']) {
    $eid=@$_GET['eid'];
    try {
        $resultsCollection = $db->results;
        $quizzesCollection = $db->quizzes;

        $userResult = $resultsCollection->findOne(['user_email' => $email, 'quiz_id' => $eid]);
        $quiz = $quizzesCollection->findOne(['_id' => $eid]);

        if ($userResult && $quiz) {
            echo '<div class="panel">
            <center><h1 class="title" style="color:#660033">Result</h1><center><br /><table class="table table-striped title1" style="font-size:20px;font-weight:1000;">';
            echo '<tr><td>Total Questions</td><td>'.$quiz['total_questions'].'</td></tr>';
            echo '<tr><td>Correct Answers</td><td>'.$userResult['correct_answers'].'</td></tr>';
            echo '<tr><td>Wrong Answers</td><td>'.$userResult['wrong_answers'].'</td></tr>';
            echo '<tr><td>Unattempted</td><td>'.$userResult['unattempted'].'</td></tr>';
            echo '<tr><td>Total Score</td><td>'.$userResult['score'].'</td></tr>';
            echo '</table></div>';
        } else {
            echo '<div class="alert alert-danger">Result or Quiz not found.</div>';
        }
    } catch (MongoDB\Driver\Exception\Exception $e) {
        echo '<div class="alert alert-danger">Error fetching result: ' . $e->getMessage() . '</div>';
    }
}
?>
<!--quiz end-->

<!--history start-->
<?php
if(@$_GET['q']==2) {
    try {
        $resultsCollection = $db->results;
        $quizzesCollection = $db->quizzes;

        $userResults = $resultsCollection->find(['user_email' => $email], ['sort' => ['attempt_date' => -1]]);

        echo '<div class="panel title">
        <table class="table table-striped title1">
        <tr style="color:red"><td><b>S.N.</b></td><td><b>Quiz</b></td><td><b>Total Questions</b></td><td><b>Correct</b></td><td><b>Wrong</b></td><td><b>Unattempted</b></td><td><b>Score</b></td><td><b>Date</b></td></tr>';
        $c=0;
        foreach($userResults as $result) {
            $c++;
            $quiz = $quizzesCollection->findOne(['_id' => $result['quiz_id']]);
            $quizTitle = $quiz ? $quiz['title'] : 'Unknown Quiz';
            $attemptDate = $result['attempt_date']->toDateTime()->format('Y-m-d H:i:s');

            echo '<tr><td>'.$c.'</td><td>'.$quizTitle.'</td><td>'.$quiz['total_questions'].'</td><td>'.$result['correct_answers'].'</td><td>'.$result['wrong_answers'].'</td><td>'.$result['unattempted'].'</td><td>'.$result['score'].'</td><td>'.$attemptDate.'</td></tr>';
        }
        echo '</table></div>';
    } catch (MongoDB\Driver\Exception\Exception $e) {
        echo '<div class="alert alert-danger">Error fetching history: ' . $e->getMessage() . '</div>';
    }
}
?>
<!--history end-->

<!--ranking start-->
<?php
if(@$_GET['q']==3) {
    try {
        $resultsCollection = $db->results;
        $usersCollection = $db->users;

        $pipeline = [
            ['$group' => [
                '_id' => '$user_email',
                'totalScore' => ['$sum' => '$score']
            ]],
            ['$sort' => ['totalScore' => -1]],
            ['$limit' => 10] // Top 10 ranks
        ];

        $ranks = $resultsCollection->aggregate($pipeline);

        echo '<div class="panel title">
        <table class="table table-striped title1">
        <tr style="color:red"><td><b>Rank</b></td><td><b>Name</b></td><td><b>Score</b></td></tr>';
        $c=0;
        foreach($ranks as $rankEntry) {
            $c++;
            $userEmail = $rankEntry['_id'];
            $user = $usersCollection->findOne(['email' => $userEmail]);
            $userName = $user ? $user['name'] : 'Unknown User';
            $score = $rankEntry['totalScore'];

            echo '<tr><td>'.$c.'</td><td>'.$userName.'</td><td>'.$score.'</td></tr>';
        }
        echo '</table></div>';
    } catch (MongoDB\Driver\Exception\Exception $e) {
        echo '<div class="alert alert-danger">Error fetching ranking: ' . $e->getMessage() . '</div>';
    }
}
?>
<!--ranking end-->


</div></div></div>
</body>
</html>
