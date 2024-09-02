<!DOCTYPE HTML>

<?php

	//Adatbázis adatai
	/*$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "project_testing";*/

	//Session elindítása
	session_start();
	
	include("server.php");

	// Connection string
	//$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Ha nem jött létre a kapcsolat hibát dob
	/*if (!$connDB) {
		die("Connection failed: " . mysqli_connect_error());
	}*/
	
	if(isset($_SESSION["Name"])){
		header("Location: index.php");
	}
	
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés</title>
	<style>
		body, html {
			background-color: rgb(214, 214, 214);
			background-image: url('wallpaper.jpg');
			background-size: cover;
			height: 100%;
			margin: 0;
			padding: 0;
			font-family: arial;
		}

		.login{
			background-color: rgba(128, 128, 128, 0.3);
			color: white;
			font-size: large;
			width: 150px;
			height: 50px;
			margin-right: 20px;
			margin-top: 20px;
			border-radius: 30px;
			border: none;
			cursor:pointer;
		}
		.login:hover{
			background-color: rgba(128, 128, 128, 0.6);
		}

		#header {
			display: flex;
			justify-content: end;
			height: 60px;
		}

		#loginOverlay, #registerOverlay {
			position: fixed;
			bottom: 0;
			left: 0;
			width: 100%;
			height: 0;
			background: rgba(0, 0, 0, 0.5);
			overflow: hidden;
			transition: height 0.5s ease;
		}

		.overlay-content {
			background-color: transparent;
			border-radius: 5px;
			width: 500px;
			margin: auto;
			border: 4px solid white;
			color: white;
		}


		h2 {
			text-align: center;
			margin-bottom: 50px;
		}

		.overlay-content label {
			margin-bottom: 10px;
			display: block;
		}
		.overlay-content form {
			display: flex;
			flex-direction: column;
			align-items: center;

		}

		.overlay-content input {
			margin: 8px;
			border: 2px solid #ccc;
			border-radius: 4px;
			font-size: 14px;
			width: 200px;
			height: 35px;
			text-align: center;
		}
		.overlay-content button{
			margin-top: 5px;
			margin-bottom: 10px;
			margin-right: 0;
		}


		.overlay.active {
			height: 100%;
		}
	</style>
</head>
<body>
    <div id="header"> 
        <button class="login" id="bejel">Bejelentkezés</button>
        <button class="login" id="reg">Regisztráció</button>
    </div>
    <div id="loginOverlay">
        <div class="overlay-content" >
          <h2>Bejelentkezés</h2>
		  <form action="login.php" method="POST">
			  <label for="username">Felhasználónév:</label>
			  <input type="text" id="username" name="username">
			  <label for="password">Jelszó:</label>
			  <input type="password" id="password" name="password">
			  <input type="submit" name="logsubmit" class="login">
			  <button type="button" onclick="closeOverlay('loginOverlay')" class="login">Bezárás</button>
		  </form>
        </div>
    </div>
    
    <div id="registerOverlay">
		<div class="overlay-content">
				<h2>Regisztráció</h2>
			<form action="login.php" method="POST" class="form">
				<label for="newUsername">Felhasználónév:</label>
				<input type="text" id="newUsername" name="newUsername">
				<label for="newPassword">Jelszó:</label>
				<input type="password" id="newPassword" name="newPassword">
				<label for="newName">Teljes név:</label>
				<input type="text" id="newName" name="newName">
				<input type="submit" name="regsubmit" class="login">
				<button type="button" onclick="closeOverlay('registerOverlay')" class="login">Bezárás</button>
			</form>
		</div>
    </div>
      
      <script>
      document.getElementById('bejel').addEventListener('click', function() {
          toggleOverlay('loginOverlay');
      });
  
      document.getElementById('reg').addEventListener('click', function() {
          toggleOverlay('registerOverlay');
      });
  
      function toggleOverlay(overlayId) {
          var overlay = document.getElementById(overlayId);
          overlay.style.display = 'flex';
          setTimeout(function() {
              overlay.style.height = '100%';
          }, 10);
      }
  
      function closeOverlay(overlayId) {
          var overlay = document.getElementById(overlayId);
          overlay.style.height = '0';
          setTimeout(function() {
              overlay.style.display = 'none';
          }, 500);
      }
  </script>
	  
	  <?php
		$result = getUsers($connDB);
		
		//Ha meg lett nyomva a küldés gomb
		if(isset($_POST["logsubmit"])){
			//Lekéri a két mező értékét
			$usn = $_POST["username"];
			$psw = $_POST["password"];
			
			//Ha a lekérdezésben vannak elemek
			if ($result->rowCount() > 0) {
				//Egy while ciklussal végigmegyünk a lekérdezés eredményén, a $row jelöli a sorokat
				while($row = $result->fetch(PDO::FETCH_ASSOC)) {
					//Ha a lekért username és password megegyezik bármelyik lekért adattal, akkor beírjuk a sessionbe az adatokat
					if($usn == $row["Username"] && $psw == $row["Password"]){
						$_SESSION['Username'] = $usn;
						$_SESSION['Name'] = $row["Name"];
						$_SESSION['Id'] = $row["Id"];
						$_SESSION['Admin'] = $row["admin"];
						$_SESSION['Pont'] = 0;
						//Átdob az index oldalra
						header("Location: index.php");
					}
				}
			}
		}
		
		if(isset($_POST["regsubmit"])){
			$newUsn = $_POST["newUsername"];
			$newPsw = $_POST["newPassword"];
			$newName = $_POST["newName"];
			
			$sql = "INSERT INTO Users (Username, Password, Name, Admin)
					VALUES ('".$newUsn."', '".$newPsw."', '".$newName."', '0')";
					
			$queryUserBeszur = $connDB->prepare($sql);
			$queryUserBeszur->execute();
		}		
		
		//Lezárja a kapcsolatot
		//mysqli_close($connDB);
		
	  ?>
	 
</body>
</html>