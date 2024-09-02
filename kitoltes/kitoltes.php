<?php
session_start();
//include 'db.php';
include '../server.php';
//include 'function.php'; // Add this line to include functions.php
?>

<?php if (!isset($_GET['quizId']) || empty($_GET['quizId'])) : ?>
    <body style="margin:0;">
    <div class="container" style="width: 100%; height: 100%; background-image: url('wallpaper.jpg'); background-size: cover; display: flex; align-items: center; flex-direction: column;">
        <a href="../index.php"><button style="background-color: rgba(128, 128, 128, 0.3); color: white; font-size: large; margin-top: 20px; margin-right: 20px; border-radius: 50px; border: none; padding: 0 20px 0 20px; box-shadow: 7px 7px 23px -19px rgba(0,0,0,0.5); padding: 7 10 7 10;
    position: absolute;
    left: 15px;
    top: 0;" onmouseover="this.style.background='rgb(143, 143, 143)', this.style.cursor='pointer'" onmouseout="this.style.background='rgba(128, 128, 128, 0.3)'">⬅</button></a>
        <h2 class="cim" style="background-color: #39464C;
                            color: white;
                            padding: 10px 40px 10px 40px;
                            border-radius: 50px;
                            font-size: 20px;
                            font-weight: bold;
                            margin-bottom: 20px;
                            box-shadow: 7px 7px 23px -19px rgba(0,0,0,0.5);
                            width: 500px;
                            margin-left: 15px;
                            height: 100px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-family: Arial;">
                            Válassz egy kitöltendő kvízt:</h2>
        <ol style="box-shadow: 7px 7px 23px -19px rgba(0,0,0,0.5); margin-top: 80px; width: 500px; background-color: transparent; backdrop-filter: blur(5px); border: 1px solid #849396  ; display: flex; align-items: center; flex-direction: column; border-radius: 20px; position: absolute; left: 500px; top: 100px;">
            <?php $availableQuizzes = getQuizes($connDB); ?>
            <?php if (isset($availableQuizzes)): ?>
                <?php foreach ($availableQuizzes as $quiz) : ?>
                    <a href="?quizId=<?php echo $quiz['id']; ?>" style="text-decoration: none; color: black; font-size: larger;"><li style="background-color: #808080; color: white; font-size: large; width: 200px; font-size: larger; text-align: center; margin-top: 10px; margin-right: 40px; margin-bottom: 10px; border-radius: 50px; border: none; padding: 20px 20px 20px 20px; list-style-type:none; transition: transform 0.3s; transform-origin: center; box-shadow: 7px 7px 23px -19px rgba(0,0,0,0.5);font-family: Arial;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'"><?php echo $quiz['name']; ?></li></a>
                <?php endforeach; ?>
            <?php endif; ?>
        </ol>
    </div>
    </body>
<?php else : ?>
    <?php
    $quizId = $_GET['quizId'];

    // Fetch questions for the selected quiz
    $resultQuiz = getQuizes($connDB, $quizId);;
	$maxPoints = 0;
	$_SESSION["maxPoints"] = 0;

    if ($resultQuiz->rowCount() > 0) {
        while($rowQuiz = $resultQuiz->fetch(PDO::FETCH_ASSOC)){
			$questionIds = $rowQuiz['questionIds'];
			$maxPoints = $rowQuiz['points'];
			$_SESSION["maxPoints"] = $maxPoints;;
		}

        // Fetch correct answers for the selected quiz
        $sqlAnswers = "SELECT id, correct FROM questions WHERE id IN ($questionIds)";
        if (!empty($questionIds)) {
            $resultAnswers = $connDB->query($sqlAnswers);

            if ($resultAnswers->rowCount() > 0) {
                $correctAnswers = [];
                while ($rowAnswer = $resultAnswers->fetch(PDO::FETCH_ASSOC)) {
                    $correctAnswers[] = $rowAnswer['correct'];
                }
            }
        }
        $quizQuestions = getQuestions($connDB, $questionIds);
    }
    ?>

<!DOCTYPE html>
<html lang="en" style="font-family: Arial;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="kitoltesStyle.css" type="text/css" onload="this.media='all'">
		<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        /* Add styling for correct and incorrect answers */
        .correct {
            background-color: green;
        }

        .incorrect {
            background-color: red;
        }
        .locked {
            pointer-events: none; /* Disable click events */
            opacity: 0.5; /* Reduce opacity to visually indicate the answer is locked */
        }
        body{
            background-color: #CDD2D8;
        }
        
    </style>
    <title>Kitöltés</title>
    <script>
            let currentQuestionIndex = 0;
            let quizQuestions = [];
            let correctAnswers = [];

            function loadQuestions() {
                // Fetch questions from the server using AJAX
                const quizId = getParameterByName('quizId');
                const url = `loadQuestions.php?quizId=${quizId}`;

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        quizQuestions = data;
                        loadQuestion();
                    })
                    .catch(error => console.error('Error fetching questions:', error));
            }

            let totalPoints = 0; // Variable to store the total points

        function checkAnswer(selectedAnswer) {
            const correctAnswer = correctAnswers[currentQuestionIndex];

            // Lock all answers to prevent further clicks
            document.querySelectorAll('.answer-container div').forEach(answer => {
                answer.classList.add('locked');
            });

            // Remove previous styling from all answers
            document.querySelectorAll('.answer-container div').forEach(answer => {
                answer.classList.remove('correct', 'incorrect');
            });

            // Add styling based on the correctness of the selected answer
            if (selectedAnswer == correctAnswer) {
                document.querySelector(`.answer${selectedAnswer}`).classList.add('correct');

                // Increment total points if the answer is correct
                totalPoints += parseInt(quizQuestions[currentQuestionIndex]['point']);
            } else {
                document.querySelector(`.answer${selectedAnswer}`).classList.add('incorrect');
            }

            // Optionally, you can perform additional actions here based on the correctness

            // Move to the next question after checking the answer
            setTimeout(nextQuestion, 1000);
        }
            function loadQuestion() {
                const question = quizQuestions[currentQuestionIndex];
                document.querySelector('.question').innerText = question['question'];
                document.querySelector('.answer1').innerText = question['answer1'];
                document.querySelector('.answer2').innerText = question['answer2'];
                document.querySelector('.answer3').innerText = question['answer3'];
                document.querySelector('.answer4').innerText = question['answer4'];

                // Remove previous styling from all answers
                document.querySelectorAll('.answer-container div').forEach(answer => {
                    answer.classList.remove('correct', 'incorrect');
                });

                // Store the correct answer for the current question
                correctAnswers[currentQuestionIndex] = question['correct'];
            }

            function nextQuestion() {
            // Unlock all answers for the next question
            document.querySelectorAll('.answer-container div').forEach(answer => {
                answer.classList.remove('locked');
            });

            currentQuestionIndex++;

            if (currentQuestionIndex < quizQuestions.length) {
                loadQuestion();
            } else {
                // Quiz completed, show total points
                finishQuiz();
                // Optionally, you can redirect to another page or perform another action

                // Reset total points for a new quiz session
                totalPoints = 0;
            }
        }

        function lockAllAnswers() {
            // Add the "locked" class to all answer elements
            document.querySelectorAll('.answer-container div').forEach(answer => {
                answer.classList.add('locked');
            });
        }

        function unlockAllAnswers() {
            // Remove the "locked" class from all answer elements
            document.querySelectorAll('.answer-container div').forEach(answer => {
                answer.classList.remove('locked');
            });
        }


            // Function to get query parameters from the URL
            function getParameterByName(name) {
                const urlParams = new URLSearchParams(window.location.search);
                return urlParams.get(name);
            }

            function confirmFinish() {
            const result = confirm("Biztosan befejezed a kvízt?");
            if (result) {
                // User confirmed, finish the quiz
                finishQuiz();
            } else {
                // User canceled, continue with the quiz
                // You may add additional logic here if needed
            }
        }

        function finishQuiz() {
            // Optionally, you can perform any actions before finishing the quiz

            // Calculate and set total points
            const totalPointsText = `Elért pontok: ${totalPoints}`;
            document.getElementById('totalPointsText').innerText = totalPointsText;

            // Display the completion div
            displayCompletionDiv();

            // Hide the confirmation div
            hideConfirmationDiv();

            // Optionally, you can redirect to another page or perform another action

            // Reset total points for a new quiz session
            totalPoints = 0;
        }

        // Function to hide the completion div
        function hideCompletionDiv() {
            document.getElementById('completionDiv').style.display = 'none';
        }
        function hideConfirmationDiv() {
            document.getElementById('confirmationDiv').style.display = 'none';
        }
        function displayCompletionDiv() {
            document.getElementById('completionDiv').style.display = 'flex';
        }
        function confirmFinish() {
            // Display the confirmation div
            document.getElementById('confirmationDiv').style.display = 'flex';
        }
        function cancelFinish() {
            // Hide the confirmation div
            hideConfirmationDiv();
        }
            // Initial load
            document.addEventListener('DOMContentLoaded', loadQuestions);
</script>

</head>
<body style="margin: 0; background-color: #CDD2D8;">
    <!--<div id="header"> 
        <a href="../index.php"><button id="back" class="login">⬅</button></a>
        <button class="login"><a href="profil.php"><?php echo $_SESSION["Name"];?></a></button>
        <button class="login" ><a href="logout.php">Kijelentkezés</a></button>
    </div>-->

    <div class="big-container">
        <div class="quiz-container" style="background-color: transparent; backdrop-filter: blur(20px);">
            <div class="question"></div>
            <div class="answer-container">
                <div class="answer1" onclick="checkAnswer(1)"></div>
                <div class="answer2" onclick="checkAnswer(2)"></div>
            </div>
            <div class="answer-container">
                <div class="answer3" onclick="checkAnswer(3)"></div>
                <div class="answer4" onclick="checkAnswer(4)"></div>
            </div>
            <div class="buttons-container">
                <button onclick="confirmFinish()">Befejezés</button>
            </div>
        </div>
    </div>
    <div id="completionDiv">
        <div class="completion-content">
            <p>You finished the quiz</p>
            <p id="totalPointsText"></p>
            <a href="../kitoltes/kitoltes.php"><button id="done">Kilépés</button></a>
			<script>
				$(document).ready(function() {
					$("#done").click(function(){
						let pont = document.getElementById("totalPointsText").textContent.slice(14);
						console.log(pont);
						$.ajax({
							type: "POST",
							url: "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>",
							data: { pont: pont},
							success: function(response) {
								console.log(response);
							}
						});
					});
				});
			</script>
        </div>
    </div>
    <div id="confirmationDiv">
        <div class="confirmation-content">
            <div>
                <p>Biztosan befejezed a kvíz kitöltését?</p>
            </div>
            <div>
                <button onclick="finishQuiz()">Igen</button>
                <button onclick="cancelFinish()">Nem</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php endif; ?>
<?php
		if (isset($_POST["pont"])){
			$_SESSION["Pont"] = $_POST["pont"];
					
			try{
				$sqlKeres = "select * from statistic where playerId={$_SESSION["Id"]};";
				$queryKeres = $connDB->prepare($sqlKeres);
				$queryKeres->execute();
					
				if($queryKeres->rowCount() == 0){
					$sqlBeszur = "INSERT INTO statistic (playedQuiz, points, playerId, maxPoints) VALUES('1','{$_SESSION['Pont']}','{$_SESSION['Id']}','{$_SESSION["maxPoints"]}');";
					$queryBeszur = $connDB->prepare($sqlBeszur);
					$queryBeszur->execute();
				}
				else{
					foreach($queryKeres as $q){
						$sqlUpdate = "UPDATE statistic SET points={$q["points"]}+{$_SESSION["Pont"]}, playedQuiz = {$q["playedQuiz"]}+1, maxPoints={$q["maxPoints"]}+{$_SESSION["maxPoints"]} WHERE playerId={$_SESSION["Id"]};";
						$queryUpdate = $connDB->prepare($sqlUpdate);
						$queryUpdate->execute();
					}
				}		 
			}catch(PDOException $e){
				echo "<p class=\"error\">Adatbázis hiba: {$e->getMessage()}</p>\n";
			}
		}
	?>

<?php
// Close the connection after all queries
//$connDB->close();
?>