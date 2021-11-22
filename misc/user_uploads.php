<?php

session_start();

if (!isset($_SESSION['loggedin'])) 
{
	header('Location: ../index.php');
	exit;
}

if ( !is_dir($_SESSION['storage_id']) ) 
{	
    mkdir($_SESSION['storage_id'], 0777);       
}
		
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title> Moje pliki </title>
		<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0,maximum-scale=1.0, viewport-fit=cover"> 
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">		
		<link rel="stylesheet" href="../css/user_uploads.css" type="text/css" /> 
		<link rel="icon" href="../favicon.ico" type="image/ico">

		<script>
		function FileDeletePrompt()
		{			
			if ( confirm("Czy chcesz trwale usunąć wszystkie swoje pliki?") )
			{				
				window.location.href = 'delete_files.php';
				return false;
			}
		}
		</script>



	</head>
	<body class="loggedin"> 
		<nav class="navtop">
			<div>
				<h1> Cześć, <?=$_SESSION['name']?>! </h1>
				<a href="../main_upload.php"><i class="fas fa-cloud-upload-alt"></i>Wyślij plik</a>
				<a href="user_uploads.php"><i class="fas fa-folder-open"></i>Moje pliki</a>
				<a href="../logout.php"><i class="fas fa-door-open"></i>Wyloguj się</a>
			</div>
		</nav>

		<div class="userfiles">
			<h2> Moje pliki </h2>

<?php
function GetFileExtension($FileName) 
{
	return substr ( strrchr($FileName,'.'), 1 );
}

function GetFileSize ($bytes, $precision = 2) 
{
   $power = 0;
   $units = " B";

   if ($bytes > 0 && $bytes < 1024) 
   {
      $power = 0;
      return round($bytes, $precision) . '<span class="FontOneEM bold"> ' . $units . "</span>";
   }

   if ($bytes >= 1024 && $bytes < 1024 ** 2) 
   {
      $power = 1;
      $units = "KB";
   }

   if ($bytes >= 1024 ** 2 && $bytes < 1024 ** 3) 
   {
     $power = 2;
     $units = "MB";
   }

   if ($bytes >= 1024 ** 3 && $bytes < 1024 ** 4) 
   {
     $power = 3;
     $units = "GB";
   }

   if ($bytes >= 1024 ** 4 && $bytes < 1024 ** 5) 
   {
     $power = 4;
     $units = "TB";
   }
  
  $bytes = $bytes / 1024 ** $power;
  return round($bytes, $precision) . '<span class="FontOneEM bold"> ' . $units . "</span>";
}


function ShowFileBlocks( $file )
{
	$ForceFileDownload = true;	
	$FileExtension = GetFileExtension($file);	
	$FileDownload = ($ForceFileDownload) ? " download='" . basename($file) . "'" : "";
	
	echo "<div class=\"CSSFileBlocks\">";
	echo "<a href=\"$file\" class=\"$FileExtension\"{$FileDownload}>";
	echo "	<div class=\"blocks $FileExtension\"></div>";
	echo "	<div class=\"FileName\">";
	
	echo "		<div class=\"File FontWeight500\">" . basename($file) . "</div>";
	echo "		<div class=\"FileProperties BigText Rozmiar TextSize1\"><span class=\"bold\">Rozmiar:</span> " . GetFileSize(filesize($file)) . "</div>";
	echo "		<div class=\"FileProperties BigText DataModyfikacji TextSize1\"><span class=\"bold\">Zmodyfikowany:</span> " .  date("D, d-m-Y - H:i", filemtime($file)) . "</div>";	
	
	echo "	</div>";
	echo "	</a>";
	echo "</div>";	
}


function CreateFileBlocks( $Files, $Folder )
{		
	$FilesArray = array();
			
	foreach($Files as $File)
	{
		if( $File == ".." OR $File == ".")
		{
			continue;
		}
		
		if( $Folder && $File ) 
		{
			$File = "$Folder/$File"; 
		}
		
		if( $File )
		{
			$FilesArray[ date("U", filemtime($File)) . "-" . $File ] = $File; 
		}
	}
			
	natsort( $FilesArray );
	
	foreach($FilesArray as $file)
	{		
		echo ShowFileBlocks( $file );
	}
}


$Files = scandir( $_SESSION['storage_id'] ); 
CreateFileBlocks( $Files, $_SESSION['storage_id'] ); 
?>

<div class = "delete">

<a href = "#" onclick="FileDeletePrompt(); return false;"> Usuń swoje pliki </a>

</div>
		</div>

	<footer>
        <p>
            Internetowy System Danych<br />
            Emil Piaskowski
        </p>
    </footer>
	</body>
</html>