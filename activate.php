<?php
$DATABASE_HOST = '';
$DATABASE_USER = '';
$DATABASE_PASS = '';
$DATABASE_NAME = '';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) 
{
	
	exit('Nastąpił problem z połączeniem SQL: ' . mysqli_connect_error());
}

if (isset($_GET['email'], $_GET['code'])) 
{
	if ($stmt = $con->prepare('SELECT * FROM isd_users WHERE email = ? AND mail_auth = ?')) 
	{
		$stmt->bind_param('ss', $_GET['email'], $_GET['code']);
		$stmt->execute();
		
		$stmt->store_result();
		if ($stmt->num_rows > 0) 
		{
			
			if ($stmt = $con->prepare('UPDATE isd_users SET mail_auth = ? WHERE email = ? AND mail_auth = ?')) 
			{
				
				$mailcode = 'activated';
				$stmt->bind_param('sss', $mailcode, $_GET['email'], $_GET['code']);
				$stmt->execute();
				echo 'Twoje konto zostało aktywowane!<br>';
				echo 'Możesz się teraz zalogować <a href="index.php">tutaj</a>!';
			}
		} 
		else 
		{
			echo 'Konto użytkownika zostało już aktywowane lub nie istnieje!';
		}
	}
}
?>