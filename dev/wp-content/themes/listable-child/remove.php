<?php
// get correct file path
$fileName = $_GET['name'];
$filePath = $_SERVER['DOCUMENT_ROOT']."/dev/wp-content/themes/listable-child/uploads/".date
('Y').'/'.date(m).'/'.$fileName;
echo $filePath;
	unlink($filePath);
	header('Location: http://mealblvd.com/dev/registration/');


?>