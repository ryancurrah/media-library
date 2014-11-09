<? include 'movie_variables.php';
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

/////DIRECTORY ACCESS START/////
$dirArray = mediaDirToArray($mediaPath);

/////DO NOT UNCOMMENT UNLESS FILLING DATABASE FOR THE FIRST TIME/////
//folderFillNewDatabase($dirArray);

/////GET MYSQL DATA/////
$getMediaTable = getMediaTable_from_mysql($mediaTable);
$getMediaTableNew = getMediaTableNew_from_mysql($mediaTableNew);

/////MEDIA OLDER THAN 30 DAYS GETS MOVED TO THE OLD LIST/////
// Check for media in the new lists age and if over 30 days move to old list and return the new list result
$mediaArrayNew = newMediaAgeCheck($getMediaTableNew, $mediaTable, $mediaIDNew, $mediaName, $mediaDate);

print ('<br /><br /><br />');

//////COMPARE TABLES TO SEE IF NEW MEDIA HAS BEEN ADDED/////
// Put $mediaTable from mysql into an array
for($index=0; $index < $getMediaTable['num']; $index++) 
	{
		$mediaArray[]=mysql_result($getMediaTable['result'],$index,"$mediaName");
	}	

/////GET LIST/////
// Check hardrive for new movies compare to moviesList 
$mediaCheck = array_diff($dirArray, $mediaArray); // Compare the movie directory to current database and only show the new files
$mediaCheck = array_merge($mediaCheck);  // Change element numbers from(EG: [1],[234] to [1],[2])
// Check to see if the new media found is already in the movieListNew Database
if(empty($mediaArrayNew))
{
	// Add each new media into the $mediaTableNew database
	for($index=0; $index < count($mediaCheck); $index++) 
	{
		$mediaCheck[$index] = str_replace("'","\'", $mediaCheck[$index]);
	    print ($mediaCheck[$index].'<br />');
		mysql_query("INSERT INTO $mediaTableNew($mediaName, $mediaDate) values('$mediaCheck[$index]', CURDATE())");
	}
}
else
{
print ('New Movies: <br/>');
$newMediaCheck = array_diff($mediaCheck, $mediaArrayNew);
$newMediaCheck = array_merge($newMediaCheck);
//print_r ($newMediaCheck);
	// Add each new media into the $mediaTableNew database
	for($index=0; $index < count($newMediaCheck); $index++) 
	{
		$newMediaCheck[$index] = str_replace("'","\'", $newMediaCheck[$index]);
		print ($newMediaCheck[$index].'<br />');
		mysql_query("INSERT INTO $mediaTableNew($mediaName, $mediaDate) values('$newMediaCheck[$index]', CURDATE())");
	}
}

// Close mysql connection
mysql_close($con);
?>

<br /><br />
<h1>Script Finished</h1>
</body>
</html>
