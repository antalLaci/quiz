<!DOCTYPE HTML>
<html>
	<?php
		session_start();
		
		include("server.php");
		
		//Ha nem létezik a session visszadob a loginhoz
		if(!isset($_SESSION["Name"])){
			header("Location: login.php");
		}
	?>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
        <title>Profil módosítás</title>
	</head>
	<body>
		
	<div id="header"> 
        <button class="login"><a href="profil.php"><?php echo $_SESSION["Name"];?></a></button>
        <button class="login" ><a href="logout.php">Kijelentkezés</a></button>
    </div>
    <h1 id="profilCim">Profil adatainak módosítása</h1>
    <div class="container">
    
        <form action="update.php" method="post">
			<label>Felhasználónév:</label>
            <input type="text" name="username" value="<?php echo $_SESSION["Username"];?>"></input>
			<label>Teljes név:</label>
            <input type="text" name="name" value="<?php echo $_SESSION["Name"];?>"></input>
            <label>Régi jelszó:</label>
            <input type="password" name="password" ></input>
            <label>Új jelszó:</label>
            <input type="password" name="new_password" ></input>
            <input type="submit" name="submit" value="Mentés"></input>
        </form>
    </div>
    <?php
        if(isset($_POST["submit"])){
            try{
                $username = $_POST["username"];
                $password = $_POST["password"];
                $new_password = $_POST["new_password"];
				
                $queryLeker = getUsers($connDB);

                $id = $_SESSION['Id'];

                if(empty($new_password) || empty($password)){
                    throw new Exception("Hiba: A jelszómezők nem lehetnek üresek!");
                }

                if($new_password === $password){
                    throw new Exception("Hiba: A régi jelszó nem lehet azonos az új jelszóval!");
                }

                $queryUserUpdate = updateUsers($connDB, $new_password, $id);
                
            }
            catch(Exception $e){
                echo "".$e->getMessage()."";
            }
            
        };
    ?>
	</body>
</html>