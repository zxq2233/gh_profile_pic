
<?php

require('../vendor/autoload.php');
ini_set('post_max_size', '20M');
ini_set('upload_max_filesize', '20M');
if(isset($_POST["submit"])){
try 
{ 
	
	$target_dir = dirname(__FILE__); 
	$target_dir = $target_dir . '/'; 
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
       // echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
	
	
	/*
// Check if file already exists
if (file_exists($target_file)) {
   echo "Sorry, file already exists.".$imageFileType ;
   $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
 Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG,PNG & GIF files are allowed.";
    $uploadOk = 0;
}
	*/
	
	
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
	
	
	
    // Let's check whether we can perform the magick. 
    if (TRUE !== extension_loaded('imagick')) 
    { 
        throw new Exception('Imagick extension is not loaded.'); 
    } 

    // This check is an alternative to the previous one. 
    // Use the one that suits you better. 
    if (TRUE !== class_exists('Imagick')) 
    { 
        throw new Exception('Imagick class does not exist.'); 
    } 

    // Let's find out where we are. 
    $dir = dirname(__FILE__); 

    // Let's read the images. 
    $glass = new Imagick(); 
    if (FALSE === $glass->readImage($dir . '/dp.png')) 
    { 
        throw new Exception(); 
    } 
	
	$g_width=$glass->getImageWidth();
	$g_height=$glass->getImageHeight();

    $face = new Imagick(); 
    if (FALSE === $face->readImage($target_file)) 
    //if (FALSE === $face->readImage($dir."/a.jpg")) 
    { 
        throw new Exception(); 
    } 

	$width=$face->getImageWidth();
	$height=$face->getImageHeight();
	echo ($width." ".$height);
	
	if($width>$height)	{
		echo " one";
		thumbnail($face, $height);
	}
	else if($height>$width){
		echo "two";
		thumbnail($face, $width);
	}
	$width=$face->getImageWidth();
	echo $width;
	if($width>$g_width){
		$face->resizeImage($g_width,$g_width,Imagick::FILTER_CATROM,1);
	}
	else if($g_width>$width){
		$glass->resizeImage($width,$width,Imagick::FILTER_CATROM,1);
	}
	
	
		
    // Let's put the glasses on (10 pixels from left, 20 pixels from top of face). 
    $face->compositeImage($glass, Imagick::COMPOSITE_DEFAULT, 0, 0); 

    // Let's merge all layers (it is not mandatory). 
    $face->flattenImages(); 
	//echo "flattenerd";

    // Let's write the image. 
    if  (FALSE == $face->writeImage($target_file)) 
    { 
        throw new Exception(); 
    } 
			if (file_exists($target_file)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=gh_'.basename($target_file));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($target_file));
			ob_clean();
			flush();
			readfile($target_file);
				unlink($target_file);
		
		}
	
	
}

catch (Exception $e) 
{ 
    echo 'Caught exception: ' . $e->getMessage() . "\n"; 
} 





}

function thumbnail($image, $n)
{
    $w = $image->getImageWidth();
    $h = $image->getImageHeight();
            $image->cropImage($n, $n, ($w - $n) / 2, ($h - $n) / 2);   
}

?> 


<!DOCTYPE html>

<html><head>
  <title>Geo Horizon Dp</title>
	
	<style type ="text/css" >
   .footer{ 
       position: fixed;     
       text-align: center;    
       bottom: 0px; 
       width: 100%;
   }  
</style>
<center><h1  style="font-size:50px;" ><u>Geohorizon 17 dp create page</u></h1></center>
	
	</head>
	
<body>


		
	<h1>Upload a photo and Click Submit</h1>

	<form method="post" action="./" enctype="multipart/form-data">
		<input type="file" name="fileToUpload" id="fileToUpload" style="width: 3000px;font-size: 30px;" size="100">
		<br><br>
		<input type="submit" name="submit" style="font-size: 30px;" value="Upload Image">

	</form>

 <div class="footer">Author:Idiot</div>

</body>

</html><?php ?>