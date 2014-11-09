<? include 'tvshow_variables.php';
   include 'mediaServerFunctions.php';
   include 'global_variables.php';
 ?>
<html>
<head>
<title>Update <?echo "$media";?> Databse Script</title>
</head>
<body>
<h1> SCANNING DIRECTORY FOR CHANGES...<br /></h1>
<?php
$curDate = date(" d-m-Y  H:i");
echo "Page loaded on $curDate. <br /> <br />";

make_mysql_connection($con, $database);

// Get all file names in directory
$fileArray = mediaFileToArray($mediaPath, true);
// Get all folder names in specified directory
$dirArray = mediaDirToArray($mediaPath);
/////DO NOT UNCOMMENT UNLESS FILLING DATABASE FOR THE FIRST TIME/////
//fillNewDatabase($fileArray, $mediaTableFile, $mediaFileName, $mediaDate)

/////GET MYSQL DATA/////
//FILE DATA
$getMediaTable = getMediaTable_from_mysql($mediaTableFile);
$getMediaTableNew = getMediaTableNew_from_mysql($mediaTableNewFile);
//FOLDER DATA
$getMediaTableFolder = getMediaTable_from_mysql($mediaTableFolder);
$getMediaTableFolderNew = getMediaTableNew_from_mysql($mediaTableNewFolder);
/////MEDIA OLDER THAN 30 DAYS GETS MOVED TO THE OLD LIST/////
// Check for media in the new lists age and if over 30 days move to old list and return the new list result
$mediaArrayNew = newMediaAgeCheck($getMediaTableNew, $mediaTableNewFile, $mediaIDNewFile, $mediaFileName, $mediaDate);
//$mediaArrayFolderNew = newMediaAgeCheck($getMediaTableFolderNew, $mediaTableNewFolder, $mediaIDNewFolder, $mediaDirName, $mediaDate);

print ('<br /><br /><br />');

// Put $mediaTable from mysql into an array
for($index=0; $index < $getMediaTable['num']; $index++) 
	{
		$mediaArray[]=mysql_result($getMediaTable['result'],$index,"$mediaFileName");
	}	
for($index=0; $index < $getMediaTableFolder['num']; $index++) 
	{
		$mediaFolderArray[]=mysql_result($getMediaTableFolder['result'],$index,"$mediaDirName");
	}	
	//print_r ('<br/>TEST:<br/>'.$fileArray.'<br/>');
/////ADD NEW MEDIA TO NEW LIST/////
print ('New TV Episodes:<br/>');
addNewMedia($mediaArray, $fileArray, $mediaArrayNew, $mediaTableNewFile, $mediaFileName, $mediaDate);
print ('<br/>New TV Shows:<br/>');
addNewMedia($mediaFolderArray, $dirArray, $mediaArrayFolder, $mediaTableFolder, $mediaDirName, $mediaDate, $skipNew==true);

// Close mysql connection
mysql_close($con);

?>
<br/><br/>
<h1>Script Finished</h1>
</body>
</html>