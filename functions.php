<?php

	//alustan sessiooni, et saaks kasutada $_SESSION muutujaid
	session_start();

	//****************
	//*****SIGNUP*****
	//****************
	
	$database = "if16_karoku";
	
	function signup ($email, $password, $username, $gender) {
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	
		$stmt = $mysqli->prepare("INSERT INTO user_sample (email, password) VALUES (?, ?)");
		echo $mysqli->error;
		
		$stmt->bind_param("ss", $email, $password);
		
		if ($stmt->execute()) {
			echo "salvestamine �nnestus";
		} else {
			echo "ERROR".$stmt->error;
		}
	}

	
	function login($email, $password) {
		
		$error = "";
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	
		$stmt = $mysqli->prepare("SELECT id, email, password, created FROM user_sample WHERE email = ?");
		
		echo $mysqli->error;
		
		//asendan k�sim�rgi
		$stmt->bind_param("s", $email);
		
		//m��ran tulpadele muutujad
		$stmt->bind_result($id, $emailFromDatabase, $passwordFromDatabase, $created);
		$stmt->execute();
		
		//k�sin rea andmeid
		if($stmt->fetch()) {
			//oli rida
			
			//v�rdlen paroole
			$hash = hash ("sha512", $password);
			if($hash == $passwordFromDatabase) {
				echo "kasutaja ".$id." logis sisse";
				
				$_SESSION["userId"] = $id;
				$_SESSION["email"] = $emailFromDatabase;
				
				//suunaks uuele lehele
				header("Location: data.php");
				
			} else {
				$error = "Parool on vale";
			}
			
		} else {
			//ei olnud
			$error = "Sellise emailiga ".$email."kasutajat ei olnud";
			
		}
		
		return $error;
		
		
	}
	
	
	
	
	
	
	
	
	
	
	
	/*function sum ($x, $y) {
		return $x + $y;
		}

	echo sum(375,555);
	echo "<br>";
	$answer = sum(10,15);
	echo $answer;
	echo "<br>";
	
	function hello ($firstname, $lastname) {
		return "Tere tulemast ".$firstname." ".$lastname."!";
	}
	
	echo sum(346,644);
	echo "<br>";
	$answer = sum(10,15);
	echo $answer;
	echo "<br>";
	echo hello ("Karoliina", "K"); */
?>

