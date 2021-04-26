<?php

if(isset($_POST['upload'])){


$targetFolder = "prod_images";
  $errorMsg = array();
  $successMsg = array();

  foreach($_FILES as $file => $fileArray)
  {
      
      if(!empty($fileArray['name']) && $fileArray['error'] == 0)
      {
          $getFileExtension = pathinfo($fileArray['name'], PATHINFO_EXTENSION);;

          if(($getFileExtension =='jpg') || ($getFileExtension =='jpeg') || ($getFileExtension =='png') || ($getFileExtension =='gif'))
          {
              if ($fileArray["size"] <= 5000000) 
              {
                $breakImgName = explode(".",$fileArray['name']);
                $imageOldNameWithOutExt = $breakImgName[0];
                $imageOldExt = $breakImgName[1];
                $newFileName = strtotime("now")."-".str_replace(" ","-",strtolower($imageOldNameWithOutExt)).".".$imageOldExt;
                $targetPath = $targetFolder."/".$newFileName;

// change file name ends
 
  // Valid extension
  $valid_ext = array('png','jpeg','jpg');

  // Location
//   $location = "images/".$filename;

  // file extension
  $file_extension = pathinfo($targetPath, PATHINFO_EXTENSION);
  $file_extension = strtolower($file_extension);

  // Check extension
  if(in_array($file_extension,$valid_ext)){

    // Compress Image
   compressImage($fileArray['tmp_name'],$targetPath,60);
   
							// echo $img_id;

							//kjh

							
$qry ="INSERT INTO `product_images`(`src`) VALUES ('".$targetPath."')";


$rs  = mysqli_query($con, $qry);

if($rs)
{
	$successMsg[$file] = "Image is uploaded successfully<button><a href='$targetPath'download>Download</a></button>";
}
else
{
	$errorMsg[$file] = "Unable to save ".$file." files ";
}

  }else
  {
      $errorMsg[$file] = "Unable to save ".$file." file ";		
  }
}else
{
    $errorMsg[$file] = "Image size is too large in ".$file;
}
          }else
          {
              $errorMsg[$file] = 'Only image file required in '.$file.' position';
          }
        }
    }
}
// Compress image
function compressImage($source, $destination, $quality) {

  $info = getimagesize($source);

  if ($info['mime'] == 'image/jpeg') {
    $image = imagecreatefromjpeg($source);
  }
    elseif ($info['mime'] == 'image/gif') 
    {$image = imagecreatefromgif($source);}

  elseif ($info['mime'] == 'image/png') 
    {$image = imagecreatefrompng($source);}

 imagejpeg($image, $destination , 60);
    
 
						
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>how to upload multiple images in php and store in mysql database</title>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
	<div class="form-container">

	<?php 
		if(isset($successMsg) && !empty($successMsg))
		{
			echo "<div class='success-msg'>";
			foreach($successMsg as $sMsg)
			{
				echo $sMsg."<br>";
			}
			echo "</div>";
		}
	?>


	<?php 
		if(isset($errorMsg) && !empty($errorMsg))
		{
			echo "<div class='error-msg'>";
			foreach($errorMsg as $eMsg)
			{
				echo $eMsg."<br>";
			}

			echo "</div>";
		}
	?>

	<div class="add-more-cont"><a id="moreImg"><button style="font-size:24px">More Image <i class="material-icons">add_to_photos</i></button></a></div>
	<form name="uploadFile" action="" method="post" enctype="multipart/form-data" id="upload-form">
		<div class="input-files">
		<input type="file" name="image_upload-1">
		</div>
		<input type="submit" name="upload" value="submit">
	</form>
	
	</div>
	
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script>
		$(document).ready(function(){
			var id = 1;
			$("#moreImg").click(function(){
				var showId = ++id;
				if(showId <=10)
				{
					$(".input-files").append('<input type="file" name="image_upload-'+showId+'">');
				}
			});
		});
	</script>
</body>
</html>
