<?php
session_start();

include 'db.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if 'quizId' is set in the $_GET array
if (isset($_GET['quizId'])) {
    $quizId = $_GET['quizId'];

    // Fetch questions for the selected quiz
    $sql = "SELECT * FROM quizes WHERE id = $quizId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $questionIds = $row['questionIds'];

        // Fetch questions using the $questionIds
        $questions = [];
        if ($questionIds) {
            $questionIdsArray = explode(',', $questionIds);

            foreach ($questionIdsArray as $questionId) {
                $sqlQuestion = "SELECT * FROM questions WHERE id = $questionId";
                $resultQuestion = $conn->query($sqlQuestion);

                if ($resultQuestion->num_rows > 0) {
                    $rowQuestion = $resultQuestion->fetch_assoc();
                    $questions[] = $rowQuestion;
                }
            }
        }

        // Output the questions as JSON
        echo json_encode($questions);
    } else {
        echo "Quiz not found";
    }
} else {
    echo "Quiz ID not provided";
}
?>
