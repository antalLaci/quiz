<?php
// Database connection details
$db_host = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "project_testing";

// Create a database connection
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get quiz questions from the database
function getQuestions($conn, $questionIds)
{
    if (!$questionIds) {
        // Handle the case when $questionIds is not provided
        return [];
    }

    $questionIdsArray = explode(',', $questionIds);
    $questions = [];

    foreach ($questionIdsArray as $questionId) {
        $sql = "SELECT * FROM questions WHERE id = $questionId";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $questions[] = $row;
        }
    }

    return $questions;
}

// ... Other database-related functions ...
?>
