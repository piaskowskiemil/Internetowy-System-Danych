<?php
session_start();

if (!isset($_SESSION['loggedin'])) 
{
	header('Location: ../index.php');
	exit;
}

array_map('unlink', glob( $_SESSION['storage_id'] . '/*.*' ));
header('Location: user_uploads.php');
exit;
?>