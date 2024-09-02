<!DOCTYPE HTML>
<html>
	<?php
		session_start();
		
		include("server.php");
		
		//Ha nem létezik a session visszadob a loginhoz
		if(!isset($_SESSION["Name"])){
			header("Location: login.php");
		}
		
		//require_once('server.php');
	?>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
		<link rel="stylesheet" type="text/css" href="profilStyle.css">
		<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
		<title>Profil</title>
	</head>
	<body>
		<!--Kiírja a sessionben tárolt nevet-->
		
	<div id="header"> 
        <button class="login"><a href="index.php">Index</a></button>
        <button class="login" ><a href="logout.php">Kijelentkezés</a></button>
    </div>
    <div>
		
		<?php
			if($_SESSION["Admin"] == 1){
				try {
					$queryKvizLeker = getQuizes($connDB);					
					$queryKerdesLeker = getQuestionsNew($connDB);
					
					echo "<h1>Kérdések / kérdéssorok módosítása</h1>";
					echo "<table id='kerdessorok'>\n";
					echo "\t<tr>\n\t\t<th>Lejátszott kvízek</th>\n\t\t<th>Megszerzett pont</th>\n\t\t<th>Törlés</th>\nt\t<th>Módosítás</th></tr>\n";
					while ($row = $queryKvizLeker->fetch(PDO::FETCH_ASSOC)){
						echo "\t<tr>\n\t\t<td>{$row["name"]}</td><td>{$row["points"]}</td><td><a href='profil.php'><p class='deleteKerdesSor' name={$row["id"]}>❌</p></a></td>
						<td><a href='update_quiz.php'><p class='updateKerdesSor' name={$row["id"]}>✏️</p></a></td></tr>\n";
					}
					echo "</table>\n";
					
					echo "<table id='kerdesek'>\n";
					echo "\t<tr>\n\t\t<th>Lejátszott kvízek</th>\n\t\t<th>Megszerzett pont</th>\n\t\t<th>Módosítás</th>\n</tr>\n";
					while ($row = $queryKerdesLeker->fetch(PDO::FETCH_ASSOC)){
						echo "\t<tr>\n\t\t<td>{$row["question"]}</td><td>{$row["point"]}</td><td><a href='update_question.php'><p class='update' name={$row["id"]}>✏️</p></a></td></tr>\n";
					}
					echo "</table>\n";
				} 
				catch (PDOException $e){
				  echo "<p class=\"error\">Adatbázis lekérdezési hiba: {$e->getMessage()}</p>\n";
				} 
				catch (Throwable $e){
				  echo "<p class=\"error\">Ismeretlen hiba: {$e->getMessage()}</p>\n";
				}
			}
			?>
			<script>
				$(document).ready(function() {
					$(".deleteKerdesSor").click(function(){
						let kerdesSorId = $(this).attr("name");
						$.ajax({
							type: "POST",
							url: "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>",
							data: { kerdesSorId: kerdesSorId},
							success: function(response) {
								console.log(response);
							}
						});
					});
					$(".update").click(function(){
						let kerdesId = $(this).attr("name");
						$.ajax({
							type: "POST",
							url: "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>",
							data: { kerdesId: kerdesId},
							success: function(response) {
								console.log(response);
							}
						});
					});
					$(".updateKerdesSor").click(function(){
						let kerdesSorId = $(this).attr("name");
						$.ajax({
							type: "POST",
							url: "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>",
							data: { updateKerdesSorId: kerdesSorId},
							success: function(response) {
								console.log(response);
							}
						});
					});
				});
				<?php
					if(isset($_POST["kerdesSorId"])){						
						try {							
							$sqlLeker = "DELETE FROM quizes WHERE id={$_POST["kerdesSorId"]};";
							$queryLeker = $connDB->prepare($sqlLeker);
							$queryLeker->execute();
						}
						catch (PDOException $e){
						  echo "<p class=\"error\">Adatbázis lekérdezési hiba: {$e->getMessage()}</p>\n";
						} 						
						catch (Throwable $e){
						  echo "<p class=\"error\">Ismeretlen hiba: {$e->getMessage()}</p>\n";
						}
					}
					if(isset($_POST["kerdesId"])){
						$_SESSION["kerdesId"] =$_POST["kerdesId"];
					}
					if(isset($_POST["updateKerdesSorId"])){
						$_SESSION["updateKerdesSorId"] =$_POST["updateKerdesSorId"];
						//$_SESSION["kerdesId"] = $
					}
				?>
			</script>
			<h1>Statisztika</h1>
					
			<?php
			try {	
				$sqlLeker = "SELECT * FROM statistic where playerId={$_SESSION["Id"]};";
				$queryLeker = $connDB->prepare($sqlLeker);
				$queryLeker->execute();
				echo "<table id='stat'>\n";
				echo "\t<tr>\n\t\t<th>Lejátszott kvízek</th>\n\t\t<th>Megszerzett pont</th>\n\t\t<th>Százalék</th>\n</tr>\n";
				while ($row = $queryLeker->fetch(PDO::FETCH_ASSOC)){
					$percent = ($row["points"]/$row["maxPoints"])*100;
					echo "\t<tr>\n\t\t<td>{$row["playedQuiz"]}</td><td>{$row["points"]}</td><td>{$percent}%</td></tr>\n";
				}
				echo "</table>\n";
				} catch (PDOException $e){
				  echo "<p class=\"error\">Adatbázis lekérdezési hiba: {$e->getMessage()}</p>\n";
			} catch (Throwable $e){
			  echo "<p class=\"error\">Ismeretlen hiba: {$e->getMessage()}</p>\n";
			}
		?>
	</div>
	</body>
</html>