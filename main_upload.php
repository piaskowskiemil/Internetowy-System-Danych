<?php
session_start();

if (!isset($_SESSION['loggedin'])) 
{
	header('Location: index.php');
	exit;
}
?>


<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Internetowy System Danych</title>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<link rel="stylesheet" href="css/style.css" type="text/css" />
		<link rel="icon" href="favicon.ico" type="image/ico">

		<script>
		function AjaxUpload()
		{
			var data = document.getElementById("fileToUpload").files[0];	//target the file to be uploaded, refers to fileToUpload id from input type file then access first element of files array (files[0])		
			var formUpload = new FormData(); //easily sent key values pairs of data in the ajax request to the parsing file
			formUpload.append("fileToUpload", data); //sent to the ajax request
			var jquery = new XMLHttpRequest(); //build ajax request
			//now taking jquery object instance and adding event listeners to it
			jquery.upload.addEventListener("progress", PassageHandler, false); //PassageHandler is called every time the new progress of upload to the server, everytime more bytes are uploaded to the server this event will be called to run
			jquery.addEventListener("load", FinishHandler, false);
			jquery.addEventListener("error", ErrorHandler, false);
			jquery.addEventListener("abort", CancelHandler, false);
			jquery.open("POST", "<?php echo $_SERVER['PHP_SELF']; ?>"); //runs open method to send a file to the server with "POST" method
			jquery.send(formUpload); //execute the whole ajax request
		}

		function ErrorHandler(event)
		{
			document.getElementById("HTMLMessage").innerHTML = "Przesyłanie nie powiodło się!";
		}

		function CancelHandler(event)
		{
			document.getElementById("HTMLMessage").innerHTML = "Przesyłanie zostało przerwane!";
		}

		function PassageHandler(event) //event reference in the argument area of the function, most important function
		{
			document.getElementById("LoadedBytes").innerHTML = "Wysłano "+event.loaded+" bajtów z "+event.total;			
			document.getElementById("ProgressLevel").value = Math.round( (event.loaded / event.total) * 100 ); //multiply percentage by a hundred value to not get the decimal value but to make it appear in %
			document.getElementById("HTMLMessage").innerHTML = Math.round( (event.loaded / event.total) * 100 )+"% Przesłanych danych...";
		}

		function FinishHandler(event)
		{
			document.getElementById("HTMLMessage").innerHTML = event.target.responseText; //actual text that is echoed back from PHP file
			document.getElementById("ProgressLevel").value = 0; //make value 0 after file upload is complete, function fires of after file is uploaded and php echos back about it
		}
		</script>

	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1> Cześć, <?=$_SESSION['name']?>! </h1>
				<a href="main_upload.php"><i class="fas fa-cloud-upload-alt"></i>Wyślij plik</a>
				<a href="myfiles/user_uploads.php"><i class="fas fa-folder-open"></i>Moje pliki</a>
				<a href="logout.php"><i class="fas fa-door-open"></i>Wyloguj się</a>
			</div>
		</nav>
		<div class="content">
			<center> <h2>Internetowy System Danych</h2> </center>
			
		</div>

    <div class="upload">
        <h1>Kliknij poniżej, aby wybrać pliki</h1>
        <form id="FileForm" method="post" enctype="multipart/form-data">
		<input type="file" name="fileToUpload" id="fileToUpload">
		<input type="submit" name="submit" value="Wyślij!" onclick="AjaxUpload()">

		<progress id="ProgressLevel" value="0" max="100"

		style="width:299px;"> 
		
		</progress>
		<h3	id="HTMLMessage"> </h3>
		<p	id="LoadedBytes"> </p>
        </form>
    </div>

	<p class="echo2">

<?php
if(isset($_POST['submit'])) 
{
	
	chdir('../../user_uploads/');
	if ( !is_dir($_SESSION['storage_id']) ) 
	{	
		mkdir($_SESSION['storage_id'], 0777);       
	}


	$target_dir = $_SESSION['storage_id']; 
	$target_file = $target_dir . '/' . basename($_FILES["fileToUpload"]["name"]);	
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

	if (!empty($_FILES['fileToUpload']["name"])) //if (isset($_FILES['fileToUpload']["name"]))
	{
						
		if (file_exists($target_file)) 
		{
			echo "Plik już istnieje!";			
		}
 
		else 
		{
			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) 
			{
				echo "Plik ". basename( $_FILES["fileToUpload"]["name"]). " został przesłany pomyślnie!";		
			} 

			else 
			{
				echo "Wystąpił błąd podczas przesyłania pliku!";
			}
		}
	}

	else 
	{
		echo "Proszę wybrać plik!";
	}
}

?>
	</p>
	<footer>
        <p>
            Internetowy System Danych<br />
            Emil Piaskowski <br />
			Czas logowania to: <?=$_SESSION['Benchmark_result']?> sekund
        </p>
    </footer>

	</body>
</html>

