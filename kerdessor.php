	<?php
		session_start();
		
		include("server.php");
		
		//Ha nem létezik a session visszadob a loginhoz
		if(!isset($_SESSION["Name"])){
			header("Location: login.php");
		}
	?>

<html lang="en">
<head>
    <meta charset="UTF-8">
   	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kérdéssor összeállítás</title>
	<link rel="stylesheet" type="text/css" href="kerdessorStyle.css">
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<script>
		$(function(){
    		$("#kerdesek").on("click", ".bankKerdesek", function(e){
        		let text = $(this).text().replace("➜","");

				let id = $(this).attr('id');
				let li = "<li class='sorKerdesek' id="+id+"><p class='ujKerdesText'>"+text+"</p><span>✖</span></li>";
				$("#ujKerdesek").append(li);
				$(this).slideUp( function(){$(this).remove()});
			});

			$("#ujKerdesek").on("click", ".sorKerdesek", function(e){
				let text = $(this).text().replace("✖","");
				let id = $(this).attr('id');
				let li = "<li class='bankKerdesek' id="+id+"><p class='kerdesText'>"+text+"</p><span>➜</span></li>";
				$("#kerdesek").append(li);
				$(this).slideUp( function(){$(this).remove()});
			});	
		});		
	</script>
	
</head>
<body>
	<script>
		$(document).ready(function() {
			$("#done").click(function(){
				console.log("lefutxd");
				let elements = $(".ujKerdesText");
				if(elements.length > 0){
					let idsArray = [];
					for(let i = 0; i < elements.length; i++){
						idsArray.push(elements[i].parentElement.id);
					}
					let ids  = idsArray.toString();
					let name = document.getElementById("nev").value;
					console.log(ids);
					$.ajax({
						type: "POST",
						url: "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>",
						data: { ids: ids, name: name},
						success: function(response) {
							console.log(response);
						}
					});
				}
				else{
					let hiba = "<p class='error'>Üres kérdéssor</p>\n";
					$("#hiba").append(hiba);
				}
				
			});
			
		});
	</script>
	<div id="header"> 
		<a href="index.php"><button id="back" class="login">⬅</button></a>
        <button class="login"><a href="profil.php"><?php echo $_SESSION["Name"];?></a></button>
        <button class="login" ><a href="logout.php">Kijelentkezés</a></button>
    </div>
	<div id="hiba" style="margin:0;"></div>
	<form action="kerdessor.php" method="post">
		<input id="nev" name="nev" type="text" placeholder="Új kérdéssor">
		<input id="send" name="send" type="submit" value="Véglegesítés">
	</form>
		<div id="container">
			<div id="kerdesbank">
				<h2>Kérdésbank</h2>
				<ul id="kerdesek">
					<?php
						try{
							$queryKerdesLeker = getQuestionsNew($connDB);
							while ($row = $queryKerdesLeker->fetch(PDO::FETCH_ASSOC)){
								echo "<li class='bankKerdesek' id='{$row["id"]}'><p class='kerdesText'>{$row["question"]}</p><span>➜</span></li>";
							}
						} catch(PDOException $e){
							echo "<p class=\"error\">Adatbázis lekérdezési hiba: {$e->getMessage()}</p>\n";
						}
					?>
				</ul>
			</div>
			<div id="kerdessor">
				<h2>Kérdéssor</h2>
				<ul id="ujKerdesek">
						
				</ul>

			</div>
			
		</div>
		<button id="done"><a href="index.php">Mentés</a></button>	
	</form>
	<?php
		
		if (isset($_POST["ids"]) && $_SERVER["REQUEST_METHOD"] == "POST"){	
			$ids = $_POST["ids"];
			$name = $_POST["name"];
			echo "Received from JavaScript: " . $ids;
			if(strlen($ids) > 0){
				feltolt($connDB, $ids, $name);
			}
			else{
				echo "<p class='error'>Üres kérdéssor</p>\n";
			}						
			exit();
		}
		function feltolt($connDB, $ids, $name){
			
				
				$idArray = explode(",",$ids);
				$maxPoint = 0;
				echo $ids;

				try{						
					$queryLeker = getQuestionsNew($connDB);
					while ($row = $queryLeker->fetch(PDO::FETCH_ASSOC)){
						foreach($idArray as $id){
							if($row["id"] == $id){
								$maxPoint += (int)$row["point"];
							}
						}
					}
					
				} catch(PDOException $e){
					echo "<p class=\"error\">Adatbázis lekérdezési hiba: {$e->getMessage()}</p>\n";
				}

				try{
					$sqlBeir = "INSERT INTO quizes (name, questionIds, points)
								VALUES ('$name', '$ids', '$maxPoint')";
					$connDB->exec($sqlBeir);
				} catch(PDOException $e){
					echo "<p class=\"error\">Adatbázis beírási hiba: {$e->getMessage()}</p>\n";
				}
		}
	?>
	
</body>
</html>