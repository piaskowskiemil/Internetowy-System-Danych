<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Register</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link rel="stylesheet" href="./css/style.css" type="text/css" />
    <link rel="icon" href="./favicon.ico" type="image/ico">
</head>
<body>
    <div class="register">
        <h1>Rejestracja</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" autocomplete="off">
            <label for="username">
                <i class="fas fa-users"></i>
            </label>
            <input type="text" name="username" placeholder="Nazwa użytkownika" id="username" required>
            <label for="password">
                <i class="fas fa-lock"></i>
            </label>
            <input type="password" name="password" placeholder="Hasło" id="password" required>
            <label for="email">
                <i class="fas fa-envelope-open"></i>
            </label>
            <input type="email" name="email" placeholder="Adres e-mail" id="email" required>
            <input type="submit" name="submit" value="Zarejestruj się!">
        </form>
    </div>
    
	<p class="echo">
<?php
$Benchmark1 = microtime(true);

if(isset($_POST['submit']))
{
	$DATABASE_HOST = '';
	$DATABASE_USER = '';
	$DATABASE_PASS = '';
	$DATABASE_NAME = '';

	$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

	if (mysqli_connect_errno()) 
	{	
		exit('Nastąpił problem z połączeniem SQL: ' . mysqli_connect_error());
	}

	
	if (!isset($_POST['username'], $_POST['password'], $_POST['email'])) 
	{
		
		exit('Proszę o wypełnienie formularza rejestracyjnego!');
	}

	
	if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) 
	{
		
		exit('Proszę o wypełnienie formularza rejestracyjnego!');
	}

	if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
	{
		exit('Adres e-mail jest niepoprawny!');
	}

	if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['username']) == 0) 
	{
		exit('Nazwa użytkownika jest niepoprawna!');
	}

	if (strlen($_POST['password']) > 30 || strlen($_POST['password']) < 8) 
	{
		exit('Hasło musi mieć długość od 8 do 30 znaków');
	}

	
	if ($stmt = $con->prepare('SELECT id, password_db FROM isd_users WHERE nickname = ?')) 
	{
		
		$stmt->bind_param('s', $_POST['username']);
		$stmt->execute();
		$stmt->store_result();
		
		if ($stmt->num_rows > 0) 
		{
			
			echo 'Użytkownik o wybranej nazwie istnieje, proszę wybrać inną nazwę użytkownika!';
		} 
		else 
		{	
			if ($stmt = $con->prepare('INSERT INTO isd_users (nickname, password_db, email, mail_auth, storage_id) VALUES (?, ?, ?, ?, ?)'))  
			{
				
				$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
				$uniqid = uniqid();
				$storage_id = uniqid('', true); //more entrophy
				$stmt->bind_param('sssss', $_POST['username'], $password, $_POST['email'], $uniqid, $storage_id);
				$stmt->execute();

				$Benchmark2 = microtime(true);
				$from    = 'yourusername@yahoo.com';
				$subject = 'Internetowy System Danych - aktywacja konta';
				$headers = 'From: ' . $from . "\r\n" . 'Reply-To: ' . $from . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n" . 'MIME-Version: 1.0' . "\r\n" . 'Content-Type: text/html; charset=UTF-8' . "\r\n";			
				$activate_link = 'http://yourwebsite.com/fms/activate.php?email=' . $_POST['email'] . '&code=' . $uniqid;
				$message = '<p>Kliknij w ten link, aby aktywować swoje konto: <br><a href="' . $activate_link . '">' . $activate_link . '</a></p>';
				mail($_POST['email'], $subject, $message, $headers);

				echo 'Wysłano link aktywacyjny na podany adres e-mail!<br>';
				echo 'Link powinien dotrzeć w ciągu kilku minut<br>';
				echo 'Jeśli wiadomość nie dotarła do skrzynki odbiorczej, proszę sprawdzić folder "spam"<br>';
				echo 'Czas walidacji danych: ' . round( $Benchmark2 - $Benchmark1, 2) . ' sekund<br>'; 
				echo 'E-mail wysłano w ciągu: ' . round( microtime(true) - $Benchmark2, 2) . ' sekund';  
			} 
			else 
			{
				
				echo 'Nastąpił błąd w konstrukcji zapytania SQL!';
			}
		}
		
		$stmt->close();
	}

	else 
	{
		
		echo 'Nastąpił błąd w konstrukcji zapytania SQL!';
	}
	$con->close();
}
?>

	</p>


</body>
</html>