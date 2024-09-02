<?php
// Function to get all available quizzes
function getQuizzes($connDB)
{
    $quizzes = [];
    $sql = "SELECT * FROM quizes";
    $result = $connDB->prepare($sql);
	$result->execute();

    if ($result->rowCount() > 0) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $quizzes[] = $row;
        }
    }

    return $quizzes;
}

// Function to check answers
function checkAnswers($selectedAnswer, $correctAnswer)
{
    $isCorrect = ($selectedAnswer == $correctAnswer);

    return json_encode(["isCorrect" => $isCorrect]);
}
?>
