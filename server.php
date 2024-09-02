<?php
	$dbname = "project_testing";
	
	try{
		$connDB = new PDO("mysql:host=localhost;dbname={$dbname}","root","");
		$connDB->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		
	}catch(PDOException $e){
		echo "<p class=\"error\">Adatbázis hiba: {$e->getMessage()}</p>\n";
	}
	
	function getQuestions($connDB, $questionIds=NULL){
		if (!isset($questionIds)) {
			
			return [];
		}

		$questionIdsArray = explode(',', $questionIds);
		$questions = [];

		foreach ($questionIdsArray as $questionId) {
			
			$sql = "SELECT * FROM questions WHERE id = $questionId";
			$result = $connDB->query($sql);

			if ($result->rowCount() > 0) {
				$row = $result->fetch(PDO::FETCH_ASSOC);
				$questions[] = $row;
			}
		}

		return $questions;
	}
	
	function getQuestionsNew($connDB, $id=null){
			try{
				if(!isset($id)){
					$sqlKerdesLeker = "SELECT * FROM questions;";	
					$queryKerdesLeker = $connDB->prepare($sqlKerdesLeker);	
					$queryKerdesLeker->execute();
					
					return $queryKerdesLeker;
				}
				
				$sqlKerdesLeker = "SELECT * FROM questions where id={$id};";
				$queryKerdesLeker = $connDB->prepare($sqlKerdesLeker);
				$queryKerdesLeker->execute();
		
				return $queryKerdesLeker;
			}
			catch (PDOException $e){
				echo "<p class=\"error\">Adatbázis lekérdezési hiba: {$e->getMessage()}</p>\n";
			} 
			catch (Throwable $e){
				echo "<p class=\"error\">Ismeretlen hiba: {$e->getMessage()}</p>\n";
			}
	}
	
	function getQuizes($connDB, $id=null){
		try{
				if(!isset($id)){
					$sqlKvizLeker = "SELECT * FROM quizes;";
					$queryKvizLeker = $connDB->prepare($sqlKvizLeker);
					$queryKvizLeker->execute();
					
					return $queryKvizLeker;
				}
				$sqlKvizLeker = "SELECT * FROM quizes where id={$id};";
				$queryKvizLeker = $connDB->prepare($sqlKvizLeker);
				$queryKvizLeker->execute();
					
				return $queryKvizLeker;
		}
		catch (PDOException $e){
			echo "<p class=\"error\">Adatbázis lekérdezési hiba: {$e->getMessage()}</p>\n";
		} 
		catch (Throwable $e){
			echo "<p class=\"error\">Ismeretlen hiba: {$e->getMessage()}</p>\n";
		}
	}
	
	function getUsers($connDB){
		try{
			$sqlUserLeker = "SELECT * FROM users;";
			$queryUserLeker = $connDB->prepare($sqlUserLeker);
			$queryUserLeker->execute();
			
			return $queryUserLeker;
		}
		catch (PDOException $e){
			echo "<p class=\"error\">Adatbázis lekérdezési hiba: {$e->getMessage()}</p>\n";
		} 
		catch (Throwable $e){
			echo "<p class=\"error\">Ismeretlen hiba: {$e->getMessage()}</p>\n";
		}
	}
	
	function updateUsers($connDB, $new_password, $id){
		try{
			$sqlUserUpdate = "UPDATE users SET  Password = '$new_password' WHERE Id = $id;";
            $queryUserUpdate = $connDB->prepare($sqlUserUpdate);
            $queryUserUpdate->execute();
			
			return $queryUserUpdate;
		}
		catch (PDOException $e){
			echo "<p class=\"error\">Adatbázis lekérdezési hiba: {$e->getMessage()}</p>\n";
		} 
		catch (Throwable $e){
			echo "<p class=\"error\">Ismeretlen hiba: {$e->getMessage()}</p>\n";
		}
	}
?>
