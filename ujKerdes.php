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
    <link rel="stylesheet" href="ujKerdes.css">
    <title>Új kérdés felvétele</title>
</head>
<body>
    
    <div id="header">
        <a href="index.php"><button id="back" class="login">⬅</button></a>
        
        
        <!--<button class="login"><a href="profil.php"><?php echo $_SESSION["Name"];?></a></button>-->
		<!--<button class="login" ><a href="logout.php">Kijelentkezés</a></button>-->
    </div>
    <h1>Új kérdés felvétele</h1>
    <div class="container">
        
        <form action="ujKerdes.php" method="post" enctype="multipart/form-data">
            
            <input type="text" name="ujKerdes" id="ujKerdes" placeholder="Új kérdés...">        
            <div class="radio-grid">
                
                    <input class="radioStyle" type="radio" name="radio" value="1">
                    <input type="text" class="valasz" name="valasz1">

                    <input class="radioStyle" type="radio" name="radio" value="2">
                    <input type="text" class="valasz" name="valasz2"><br>
                
                    <input class="radioStyle" type="radio" name="radio" value="3">
                    <input type="text" class="valasz" name="valasz3">

                    <input class="radioStyle" type="radio" name="radio" value="4">
                    <input type="text" class="valasz" name="valasz4">
                
            </div>
            <div class="footer">
                <p>
                    <input class="pont" type="text" name="pont" id="pont" value="">
                    <label for="pont">Pont</label>
                </p>
                <p>
                    <input class="next" type="submit" value="Következő→" name="next">
                </p>
                
            </div>
                <h2>Kérdések feltöltése fájlból:</h2>
                <input name="import" type="file" id="import" class="login"  accept=".json"><br>
                <input class="next" type="submit" value="Feltöltés" name="upload" id="upload">
        </form>
    </div>
    <?php
    if (isset($_POST["next"])){
        
            
            if(!empty($_POST["radio"]) && !empty($_POST["ujKerdes"]) && !empty($_POST["valasz1"]) && !empty($_POST["valasz2"])&& !empty($_POST["valasz3"])&& !empty($_POST["valasz4"])&& !empty($_POST["pont"])){
                $ujKerdes = trim($_POST["ujKerdes"]);
                $helyes = (int)$_POST["radio"]; 
                $valasz1 = trim($_POST["valasz1"]);
                $valasz2 = trim($_POST["valasz2"]);
                $valasz3 = trim($_POST["valasz3"]);
                $valasz4 = trim($_POST["valasz4"]);
                $pont = (int)$_POST["pont"]; 
                if (!empty($ujKerdes && !empty($helyes) && !empty($valasz1) && !empty($valasz2) && !empty($valasz3) && !empty($valasz4) && !empty($pont)) ){
                    try{
                        $sql = "INSERT INTO questions (question, correct, answer1, answer2, answer3, answer4, point)
                            VALUES ('$ujKerdes', '$helyes', '$valasz1', '$valasz2', '$valasz3', '$valasz4', '$pont')";         
                        $connDB->exec($sql);
                        echo "<p id='success'>Új kérdés felvéve</p>"; 
                    }catch(PDOException $e){
                        echo "<p class=\"error\">Adatbázis hiba: {$e->getMessage()}</p>\n";
                    }
            
                }
            }else{
                echo "<p class=\"error\">Minden adatot ki kell tölteni!</p>\n";
            }
    }
    //if ($_FILES["import"]["error"] == UPLOAD_ERR_OK) {   
    if (isset($_POST["upload"])) {       
        try {
            $jsonContent = file_get_contents($_FILES["import"]["tmp_name"]);
            $jsonData = json_decode($jsonContent);
            foreach($jsonData as $item) {
                $kerdes = $item->kerdes;
                $helyes = (int)$item->helyes;
                $valasz1 = $item->valasz1;
                $valasz2 = $item->valasz2;    
                $valasz3 = $item->valasz3;
                $valasz4 = $item->valasz4;
                $pont = (int)$item->pont;
                $sql = "INSERT INTO questions (question, correct, answer1, answer2, answer3, answer4, point)
                    VALUES ('$kerdes', '$helyes', '$valasz1', '$valasz2', '$valasz3', '$valasz4', '$pont')";         
                $connDB->exec($sql);
            }
            echo "<p id='success'>Kérdés felvéve</p>"; 
        } catch(PDOException $e) {
            echo "<p class=\"error\">Adatbázis hiba: {$e->getMessage()}</p>\n";
        } catch(Error $err){
            echo "<p class=\"error\">Nem adott meg file-t</p>\n";
        }      
    }

    ?>

</body>
</html>