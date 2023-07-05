<!DOCTYPE html>
<html>

	<head>
	
		<meta charset="UTF-8">
		<meta name="description" content="">
		<meta name="keywords" content="">
		<meta name="author" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<title>Engine Test</title>
	
	</head>

	<body>

		<form action = "index.php" method = "POST">		
			<input type = "text" name = "login" placeholder = "Login"/>
			<input type = "password" name = "password" placeholder = "Password"/>
			<input type = "submit" name = "submit" value = "Submit"/>
		</form>

		<?php 

			if (@$_POST['login'] != null) {

				$dbhost = "localhost"; 
				$dbuser = "web"; 
				$dbpass = "q"; 
				$dbname = "web_engine_test";
				
				$usertable = "users";
					
				$Connect = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);
				
				@$username = $_POST['login'];
				@$password = $_POST['password'];
				
				$username = stripslashes($username);
				$password = stripslashes($password);
					
				$username = mysqli_real_escape_string($Connect,$username);
				$password = mysqli_real_escape_string($Connect,$password);

				$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

				$LogInQuery = "SELECT * FROM $usertable WHERE login = ?";
				$stmt = $Connect->prepare($LogInQuery);
				$stmt->bind_param("s", $username);
				
				$stmt->execute();
				$LogInResult = $stmt->get_result();

				if ($LogInResult->num_rows === 1) {
					$LogInData = $LogInResult->fetch_assoc();
					$hashedPassword = $LogInData['password'];

					var_dump($LogInData);

					if (password_verify($password, $hashedPassword)) {
						// Password is correct, user is authenticated
						// Continue with the login process

						session_start();
						$_SESSION['loggedin'] = true;
						$_SESSION['username'] = $username; 
						$_SESSION['permission'] = $LogInData['permission_level']; 
					 	header("refresh:5;url=/WebEngineTest/Admin_Panel/index.php");

						echo "success";
					} else {
						// Password is wrong
						echo "password";
					}
				} else {
					echo "user";
					// Username is wrong
				}
				
				$stmt->close();
			}
		?>
			
		</main>


	</body>
	
</html>