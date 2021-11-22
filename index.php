<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Internetowy System Danych</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link rel="stylesheet" href="./css/style.css" type="text/css" />
    <link rel="icon" href="./favicon.ico" type="image/ico">

</head>
<body>
    <div class="login">
        <h1>Internetowy system danych</h1>
        
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <label for="username">
                <i class="fas fa-id-card"></i>
            </label>
            <input type="text" name="username" placeholder="Nazwa użytkownika" id="username" required>
            <label for="password">
                <i class="fas fa-shield-alt"></i>
            </label>
            <input type="password" name="password" placeholder="Hasło" id="password" required>
            <h2> <a id="registertxt" href="register.php"> Nie masz konta? Zarejestruj się! </a> </h2>
            <input type="submit" name="submit" value="Zaloguj się">
        </form>		
    </div>

   <p class="echo">
<?php
$Benchmark = microtime(true);

if(isset($_POST['submit']))
{	
	session_start();
	$DATABASE_HOST = '';
	$DATABASE_USER = '';
	$DATABASE_PASS = '';
	$DATABASE_NAME = '';
	
	$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
	if ( mysqli_connect_errno() ) 
	{
		
		exit('Nastąpił problem z połączeniem SQL: ' . mysqli_connect_error());
	}

	
	if ( !isset($_POST['username'], $_POST['password']) ) 
	{		
		exit('Proszę podać nazwę użytkownika i hasło!');
	}


	if ($stmt = $con->prepare('SELECT id, password_db, mail_auth, storage_id FROM isd_users WHERE nickname = ? AND mail_auth = "activated"')) 
	{
		
		$stmt->bind_param('s', $_POST['username']);
		$stmt->execute();		
		$stmt->store_result();
		
		if ($stmt->num_rows > 0) 
		{
		$stmt->bind_result($id, $password, $user_auth, $db_storage_id);
		$stmt->fetch();
			 														
			if (password_verify($_POST['password'], $password)) 
			{									
				session_regenerate_id();
				$_SESSION['loggedin'] = TRUE;
				$_SESSION['name'] = $_POST['username'];
				$_SESSION['id'] = $id;
				$_SESSION['storage_id'] = $db_storage_id;
				$_SESSION['Benchmark_result'] = round( microtime(true) - $Benchmark, 2);
				header('Location: main_upload.php');
				exit;
			} 
			else 
			{				
				echo 'Podano błędne hasło!';
			}
			
		}
		else
		{
			echo 'Użytkownik nie istnieje lub nie został aktywowany!';
		}
		$stmt->close();
	}
}
?>
	</p>

    <footer>
        <p>
            Internetowy System Danych<br />
            Emil Piaskowski
        </p>
    </footer>
</body>
</html>
