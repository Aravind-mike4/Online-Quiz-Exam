<?php
include_once 'dbConnection.php';
session_start();
$email = $_SESSION['email'];

// Delete user
if (isset($_GET['demail'])) {
    $demail = htmlspecialchars($_GET['demail']);
    try {
        $usersCollection = $db->users;
        $resultsCollection = $db->results;
        
        $usersCollection->deleteOne(['email' => $demail]);
        $resultsCollection->deleteMany(['user_email' => $demail]);
        
        header("location:dash.php?q=1");
    } catch (MongoDB\Driver\Exception\Exception $e) {
        header("location:dash.php?q=1&w=Delete User Error: " . urlencode($e->getMessage()));
    }
    exit();
}

// Add Quiz
if (isset($_GET['q']) && $_GET['q'] == 'addquiz') {
    $name = htmlspecialchars($_POST['name']);
    $total = (int)$_POST['total'];
    $sahi = (int)$_POST['right'];
    $wrong = (int)$_POST['wrong'];
    $time = (int)$_POST['time'];
    $tag = htmlspecialchars($_POST['tag']);
    $desc = htmlspecialchars($_POST['desc']);
    $id = uniqid(); // Generate a unique ID for the quiz

    try {
        $quizzesCollection = $db->quizzes;
        $quizzesCollection->insertOne([
            '_id' => $id,
            'title' => $name,
            'total_questions' => $total,
            'marks_per_correct_answer' => $sahi,
            'minus_marks_per_wrong_answer' => $wrong,
            'time_limit_minutes' => $time,
            'tag' => $tag,
            'description' => $desc,
            'creation_date' => new MongoDB\BSON\UTCDateTime(),
            'questions' => [] // Initialize with an empty array for questions
        ]);
        header("location:dash.php?q=4&step=2&n=$total&eid=$id&ch=4");
    } catch (MongoDB\Driver\Exception\Exception $e) {
        header("location:dash.php?q=3&w=Add Quiz Error: " . urlencode($e->getMessage()));
    }
    exit();
}

// Add Questions to Quiz
if (isset($_GET['q']) && $_GET['q'] == 'addqns') {
    $n = (int)$_GET['n'];
    $eid = htmlspecialchars($_GET['eid']);
    $ch = (int)$_GET['ch']; // Number of choices (always 4 in your original code)

    try {
        $quizzesCollection = $db->quizzes;
        $questions = [];

        for ($i = 1; $i <= $n; $i++) {
            $qns = htmlspecialchars($_POST['qns' . $i]);
            $option1 = htmlspecialchars($_POST[$i . '1']);
            $option2 = htmlspecialchars($_POST[$i . '2']);
            $option3 = htmlspecialchars($_POST[$i . '3']);
            $option4 = htmlspecialchars($_POST[$i . '4']);
            $ans = htmlspecialchars($_POST['ans' . $i]);

            $options = [$option1, $option2, $option3, $option4];

            $questions[] = [
                'question_text' => $qns,
                'options' => $options,
                'correct_answer_label' => $ans // Store 'a', 'b', 'c', 'd'
            ];
        }

        $quizzesCollection->updateOne(
            ['_id' => $eid],
            ['$set' => ['questions' => $questions]]
        );

        header("location:dash.php?q=0");
    } catch (MongoDB\Driver\Exception\Exception $e) {
        header("location:dash.php?q=4&w=Add Questions Error: " . urlencode($e->getMessage()));
    }
    exit();
}

// Remove Quiz
if (isset($_GET['q']) && $_GET['q'] == 'rmquiz') {
    $eid = htmlspecialchars($_GET['eid']);
    try {
        $quizzesCollection = $db->quizzes;
        $resultsCollection = $db->results;

        $quizzesCollection->deleteOne(['_id' => $eid]);
        $resultsCollection->deleteMany(['quiz_id' => $eid]); // Delete associated results

        header("location:dash.php?q=5");
    } catch (MongoDB\Driver\Exception\Exception $e) {
        header("location:dash.php?q=5&w=Remove Quiz Error: " . urlencode($e->getMessage()));
    }
    exit();
}

// Submit Quiz Answers
if (isset($_GET['q']) && $_GET['q'] == 'quiz' && isset($_GET['step']) && $_GET['step'] == 2) {
    $eid = htmlspecialchars($_GET['eid']);
    $n = (int)$_GET['n']; // Current question number (1-indexed)
    $total = (int)$_GET['t']; // Total questions in quiz
    $ans = isset($_POST['ans']) ? htmlspecialchars($_POST['ans']) : ''; // User's selected answer label ('a', 'b', 'c', 'd')

    try {
        $quizzesCollection = $db->quizzes;
        $resultsCollection = $db->results;

        $quiz = $quizzesCollection->findOne(['_id' => $eid]);

        if (!$quiz) {
            header("location:account.php?q=result&eid=$eid&w=Quiz not found");
            exit();
        }

        $correctAnswers = 0;
        $wrongAnswers = 0;
        $unattempted = 0;
        $score = 0;

        // Find or create the user's result document for this quiz
        $userResult = $resultsCollection->findOne(['user_email' => $email, 'quiz_id' => $eid]);

        if (!$userResult) {
            // First question for this quiz, initialize result
            $userResult = [
                'user_email' => $email,
                'quiz_id' => $eid,
                'correct_answers' => 0,
                'wrong_answers' => 0,
                'unattempted' => $total, // Initially all are unattempted
                'score' => 0,
                'attempt_date' => new MongoDB\BSON\UTCDateTime()
            ];
            $resultsCollection->insertOne($userResult);
        } else {
            // Load existing values for calculation
            $correctAnswers = $userResult['correct_answers'];
            $wrongAnswers = $userResult['wrong_answers'];
            $unattempted = $userResult['unattempted'];
            $score = $userResult['score'];
        }

        // Get the current question's details from the quiz document
        $currentQuestionIndex = $n - 1; // Convert to 0-indexed
        if (isset($quiz['questions'][$currentQuestionIndex])) {
            $currentQuestion = $quiz['questions'][$currentQuestionIndex];
            $correctAnswerLabel = $currentQuestion['correct_answer_label'];
            $marksPerCorrect = $quiz['marks_per_correct_answer'];
            $minusMarksPerWrong = $quiz['minus_marks_per_wrong_answer'];

            if (!empty($ans)) {
                // User attempted the question
                $unattempted--; // Decrement unattempted
                if ($ans == $correctAnswerLabel) {
                    $correctAnswers++;
                    $score += $marksPerCorrect;
                } else {
                    $wrongAnswers++;
                    $score -= $minusMarksPerWrong;
                }
            }
        }

        // Update the result in MongoDB
        $resultsCollection->updateOne(
            ['user_email' => $email, 'quiz_id' => $eid],
            [
                '$set' => [
                    'correct_answers' => $correctAnswers,
                    'wrong_answers' => $wrongAnswers,
                    'unattempted' => $unattempted,
                    'score' => $score
                ]
            ]
        );

        // Move to the next question or show result
        if ($n < $total) {
            $nextQuestion = $n + 1;
            header("location:account.php?q=quiz&step=2&eid=$eid&n=$nextQuestion&t=$total");
        } else {
            header("location:account.php?q=result&eid=$eid");
        }
    } catch (MongoDB\Driver\Exception\Exception $e) {
        header("location:account.php?q=1&w=Quiz Submission Error: " . urlencode($e->getMessage()));
    }
    exit();
}
?>
