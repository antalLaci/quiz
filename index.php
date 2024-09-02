<!DOCTYPE HTML>
<html>
	<?php
		session_start();
		
		//Ha nem létezik a session visszadob a loginhoz
		if(!isset($_SESSION["Name"])){
			header("Location: login.php");
		}
	?>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
        <title>Főoldal</title>
	</head>
	<body>
		<!--Kiírja a sessionben tárolt nevet-->
		
	<div id="header"> 
        <button class="login"><a href="profil.php"><?php echo $_SESSION["Name"];?></a></button>
        <button class="login" ><a href="logout.php">Kijelentkezés</a></button>
    </div>
    <div class="container">
        <div class="left">
            <a href="ujKerdes.php">
            <div class="small-box" id="plusKerdes">
            
                <svg fill="#FFF" width="80px" height="80px" viewBox="0 0 24 24" id="plus" data-name="Line Color" class="icon line-color">
                    <path id="primary" d="M5,12H19M12,5V19" style="fill: none; stroke: #FFF; stroke-linecap: round; stroke-width: 2;"/>
                </svg>
            
            </div>
            </a>
			<a href="update.php">
            <div class="small-box" id="update">
                <svg fill="#FFF" width="80px" height="80px" viewBox="0 0 3 3" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0.25 2.625h0.75a0.125 0.125 0 0 0 0 -0.25H0.384A0.876 0.876 0 0 1 1.25 1.625a0.63 0.63 0 1 0 -0.422 -0.167A1.126 1.126 0 0 0 0.125 2.5a0.125 0.125 0 0 0 0.125 0.125Zm1 -2a0.375 0.375 0 1 1 -0.375 0.375 0.375 0.375 0 0 1 0.375 -0.375Zm1.276 0.537a0.125 0.125 0 0 0 -0.177 0l-0.781 0.781a0.126 0.126 0 0 0 -0.03 0.049l-0.156 0.469A0.125 0.125 0 0 0 1.5 2.625a0.127 0.127 0 0 0 0.04 -0.006l0.469 -0.156a0.125 0.125 0 0 0 0.049 -0.03l0.781 -0.781a0.125 0.125 0 0 0 0 -0.177Zm-0.625 1.073 -0.204 0.068 0.068 -0.204 0.672 -0.672 0.136 0.136Z"/>
                </svg>
                  
            </div>
			</a>
        </div>
        <div class="right">
            <a href="kerdessor.php">
            <div class="big-box">
                <svg width="150px" height="140px" viewBox="-0.113 0 5.625 5.625" fill="none">
                    <path d="M1.473 2.813h3.411" stroke="#FFF" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" stroke-width="0.225"/>
                    <path d="M1.473 1.463h3.411" stroke="#FFF" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" stroke-width="0.225"/>
                    <path d="M1.473 4.163h3.411" stroke="#FFF" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" stroke-width="0.225"/>
                    <path d="M0.741 1.688a0.225 0.225 0 1 0 0 -0.45 0.225 0.225 0 0 0 0 0.45Z" fill="#FFF"/>
                    <path d="M0.741 3.038a0.225 0.225 0 1 0 0 -0.45 0.225 0.225 0 0 0 0 0.45Z" fill="#FFF"/>
                    <path d="M0.741 4.388a0.225 0.225 0 1 0 0 -0.45 0.225 0.225 0 0 0 0 0.45Z" fill="#FFF"/>
                  </svg>
               <p>Új kérdéssor</p> 
            </div>
            </a>
            <a href="kitoltes/kitoltes.php">
            <div class="big-box">
                <svg fill="#FFF" width="170px" height="140px" viewBox="0 0 12.6 12.6">
                    <path d="m9.741 3.808 0.533 -0.522c0.269 -0.269 0.279 -0.559 0.042 -0.802l-0.179 -0.179c-0.237 -0.237 -0.527 -0.211 -0.796 0.047l-0.533 0.527ZM3.524 10.01l5.753 -5.748 -0.923 -0.928 -5.759 5.753L2.095 10.258c-0.047 0.126 0.084 0.274 0.211 0.221Z"/>
                  </svg>
                <p>Kitöltés</p>
            </a>
            </div>
        </div>
    </div>
	</body>
</html>