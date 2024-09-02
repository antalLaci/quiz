<html lang="en">
<?php
		session_start();
		
		include("server.php");
		
		//Ha nem létezik a session visszadob a loginhoz
		if(!isset($_SESSION["Name"])){
			header("Location: login.php");
		}
	?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="kviz.css">
    <title>Document</title>
</head>
<body>
    
    <div id="header">
        <a href="profil.php"><button id="back" class="login">⬅</button></a>
        
        <!--<button class="login"><?php echo $_SESSION["Name"];?></button>-->
    </div>
    <div class="container">
		<?php
		try {
								
			$sqlKerdesLeker = "SELECT * FROM questions where id={$_SESSION["kerdesId"]};";
			$queryKerdesLeker = $connDB->prepare($sqlKerdesLeker);
			$queryKerdesLeker->execute();
					
			while ($row = $queryKerdesLeker->fetch(PDO::FETCH_ASSOC)){
		
				echo "<form action='update_question.php' method='post'>
					<input type='text' name='ujKerdes' id='ujKerdes' value='{$row["question"]}'>
					<div class='kerdesFajta'>
						<p>
							<input type='radio' name='fajta' value='felet'>
							<label>Felet választós</label>
						</p>
						
						<p >
							<input type='radio' name='fajta' value='önálló'>
							<label>Önálló válasz</label>
						</p>
					</div>
					
					<div class='radio-grid'>
						
							<input class='radioStyle' type='radio' name='radio' value='1'>
							<input type='text' class='valasz' value='{$row["answer1"]}' name='valasz1'>

							<input class='radioStyle' type='radio' name='radio' value='2' >
							<input type='text' class='valasz' value='{$row["answer2"]}' name='valasz2'><br>
						
							<input class='radioStyle' type='radio' name='radio' value='3'>
							<input type='text' class='valasz' value='{$row["answer3"]}' name='valasz3'>

							<input class='radioStyle' type='radio' name='radio' value='4'>
							<input type='text' class='valasz' value='{$row["answer4"]}' name='valasz4'>
						
					</div>
					<div class='footer'>
						<p>
							<input class='next' type='submit' value='Mentés' name='updateKerdes'>
						</p>
					</div>
					</form>";
			}
		}
		catch (PDOException $e){
		  echo "<p class=\"error\">Adatbázis lekérdezési hiba: {$e->getMessage()}</p>\n";
		} 						
		catch (Throwable $e){
		  echo "<p class=\"error\">Ismeretlen hiba: {$e->getMessage()}</p>\n";
		}
		?>
        
    </div>
    <?php
    try {
        $connDB = new PDO("mysql:host=localhost;dbname=project_testing","root","");
        $connDB->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e){
        echo "<p class=\"error\">Adatbázis kapcsolódási hiba: {$e->getMessage()}</p>\n";
        die();
    } catch (Throwable $e){
        echo "<p class=\"error\">Ismeretlen hiba: {$e->getMessage()}</p>\n";
        die();
    }
    if (isset($_POST["updateKerdes"])){
        $ujKerdes = trim($_POST["ujKerdes"]);
        $helyes = $_POST["radio"];
        $valasz1 = trim($_POST["valasz1"]);
        $valasz2 = trim($_POST["valasz2"]);
        $valasz3 = trim($_POST["valasz3"]);
        $valasz4 = trim($_POST["valasz4"]);

        //összetettebb if feltétel
        //!empty($ujKerdes) && !empty($radio) && !empty($valasz1) && !empty($valasz2) && !empty($valasz3) && !empty($valasz4) && !empty($pont)
        if (!empty($ujKerdes)){
            try{
                $sql = "UPDATE questions SET question='{$ujKerdes}', correct='{$helyes}', answer1='{$valasz1}', answer2='{$valasz2}',
				answer3='{$valasz3}', answer4='{$valasz4}' where id={$_SESSION["kerdesId"]};";         
                $connDB->exec($sql); 
            }catch(PDOException $e){
                echo "<p class=\"error\">Adatbázis hiba: {$e->getMessage()}</p>\n";
            }
        }
    }
    ?>
</body>
</html>