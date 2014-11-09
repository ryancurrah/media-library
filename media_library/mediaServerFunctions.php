<?php

 // Function Name:	folderFillNewDatabase
 //       Purpose:  fills the database with the folder names and current date
 //       Accepts:  String Array
 //       Returns:  nothing
function fillNewDatabase($fillArray, $mediaTable, $mediaName, $mediaDate)
{
	for($index=0; $index < count($fillArray); $index++) 
	{
		$fillArray[$index] = str_replace("'","\'", $fillArray[$index]);
		print ($fillArray[$index].'<br />');
		mysql_query("INSERT INTO $mediaTable($mediaName, $mediaDate) values('$fillArray[$index]', CURDATE())");
	}
}

 // Function Name:	make_mysql_connection
 //       Purpose:  to create a connection with the mysql server 
 //       Accepts:  mysql_connect command and database name
 //       Returns:  error if no connection to database is made
function make_mysql_connection($con, $database)// Format for $con variable - mysql_connect("localhost","USERNAME","PASSWORD");
{
	if(!($con))
	{
		die('Could not connect: ' . mysql_error());
	}
	mysql_select_db($database, $con) or die("Unable to select database");
}

 // Function Name:	getMediaTable_from_mysql
 //       Purpose:  get database table
 //       Accepts:  table name
 //       Returns:  Array with two values
function getMediaTable_from_mysql($mediaTable)
{
	// GET $mediaTable DATA FROM MYSQL 
	$selectMediaTable="SELECT * FROM $mediaTable";
	$getMediaTable["result"]=mysql_query($selectMediaTable);
	$getMediaTable["num"]=mysql_numrows($getMediaTable["result"]);
	
	return $getMediaTable;
}

 // Function Name:	getMediaTableNew_from_mysql
 //       Purpose:  get database table
 //       Accepts:  table name
 //       Returns:  Array with two values
function getMediaTableNew_from_mysql($mediaTableNew)
{
	// GET $mediaTableNew DATA FROM MYSQL
	$selectMediaTableNew="SELECT * FROM $mediaTableNew";
	$getMediaTableNew["result"]=mysql_query($selectMediaTableNew);
	$getMediaTableNew["num"]=mysql_numrows($getMediaTableNew["result"]);
	
	return $getMediaTableNew;
}

// Function Name:  mediaFileToArray
//       Purpose:  gets all file names in the specified location and returns them
//       Accepts:  path to where media is located, true||false
//       Returns:  Array with the file names
function mediaFileToArray($mediaPath, $recursive) {
						  // $mediaPath is set in the _variables.php file
	$array_items = array();
	if ($handle = opendir($mediaPath)) {
		while (false !== ($file = readdir($handle))) {
			if (!($file == 'ehthumbs_vista.db' || $file == 'Thumbs.db' || $file == 'ntldr' || $file == 'NTDETECT.COM' || $file == '.' || $file == '..')) {
				if (is_dir($mediaPath. "/" . $file)) {
					if($recursive) {
						$array_items = array_merge($array_items, mediaFileToArray($mediaPath. "/" . $file, $recursive));
					}
					//$array_items[] = preg_replace("/\/\//si", "/", $file);
				} else {
//					$array_items[] = preg_replace("/\/\//si", "/", $file);
					$array_items[] = $file;
				}
			}
		}
		closedir($handle);
	}
	return $array_items;
}

// Function Name:  mediaDirToArray
//       Purpose:  gets all folder names in the specified location and returns them 
//       Accepts:  path to where media is located
//       Returns:  Array with the folder names
function mediaDirToArray($mediaPath)
{
	$mediaDirectory = opendir($mediaPath); // $mediaPath is set in the _variables.php file
	// Get each entry on shared drive
	while($file = readdir($mediaDirectory))
	{
		if (!($file == 'ehthumbs_vista.db' || $file == 'Thumbs.db' || $file == 'ntldr' || $file == 'NTDETECT.COM' || $file == '.' || $file == '..')) //if readdir not any of these than add to dirArray
		{
			$dirArray[] = $file; // Fill the array 
		}
	}
	closedir($mediaDirectory);
	return $dirArray;
}

// Function Name:  newMediaAgeCheck
//       Purpose:  checks the age of all the media in the new list and if older than 30 days move to old list and delete from old list
//       Accepts:  array, table name, table prmary key name, media column name, date column name
//       Returns:  Array with the media names
function newMediaAgeCheck($getMediaTableNew, $mediaTable, $mediaIDNew, $mediaName, $mediaDate)
{
echo "<h2>All New media will be moved to the Regular media $mediaTable after 30 days.</h2><br /><br />";

for($index=0; $index < $getMediaTableNew['num']; $index++) 
	{
		$idField[]=mysql_result($getMediaTableNew['result'],$index,"$mediaIDNew");
		$mediaArrayNew[]=mysql_result($getMediaTableNew['result'],$index,"$mediaName");
		$mediaDateNew[]=mysql_result($getMediaTableNew['result'],$index,"$mediaDate");
		$month_query = mysql_query("SELECT DATEDIFF(NOW(), '$mediaDateNew[$index]')");
	    $month = mysql_result($month_query, 0);
		echo " $mediaArrayNew[$index] is... $month day(s) old.<br />";
		echo "<br />";
		if($month > 30) 
		{
			mysql_query("INSERT INTO $mediaTable($mediaName, $mediaDate) values('$mediaArrayNew[$index]', '$mediaDateNew[$index]')");
			echo "<h2>The media $mediaArrayNew[$index] is over 30 days old and has been moved to the $mediaTable.</h2><br />";
			mysql_query("DELETE FROM $mediaTableNew WHERE $mediaIDNew = $idField[$index]");
		}
	}
	return $mediaArrayNew;
}

// Function Name:  addNewMedia
//       Purpose:  adds the new media to the new media list
//       Accepts:  old list array, new list array, media table name, media column name, date column name
//       Returns:  nothing
function addNewMedia($mediaArray, $scanArray, $mediaArrayNew, $mediaTableNew, $mediaName, $mediaDate, $skipNew)
{
	// Compare and return the difference of the current list of movies on the hardrive to the list on the database
	$mediaCheck = array_diff($scanArray, $mediaArray); // Compare the movie directory to current database and only show the new files
	$mediaCheck = array_merge($mediaCheck);  // Change element numbers from(EG: [1],[234] to [1],[2])
if($skipNew == true)
{
		// Add each new media into the $mediaTableNew database
		for($index=0; $index < count($mediaCheck); $index++) 
		{
			$mediaCheck[$index] = str_replace("'","\'", $mediaCheck[$index]);
			print ('<b>'.$mediaCheck[$index].'</b><br />');
			mysql_query("INSERT INTO $mediaTableNew($mediaName, $mediaDate) values('$mediaCheck[$index]', CURDATE())");
		}
}

	if(empty($mediaArrayNew))
	{
		// Add each new media into the $mediaTableNew database
		for($index=0; $index < count($mediaCheck); $index++) 
		{
			$mediaCheck[$index] = str_replace("'","\'", $mediaCheck[$index]);
			print ('<b>'.$mediaCheck[$index].'</b><br />');
			mysql_query("INSERT INTO $mediaTableNew($mediaName, $mediaDate) values('$mediaCheck[$index]', CURDATE())");
		}
	}
	else
	{
		$newMediaCheck = array_diff($mediaCheck, $mediaArrayNew);
		$newMediaCheck = array_merge($newMediaCheck);
		// Add each new media into the $mediaTableNew database
		for($index=0; $index < count($newMediaCheck); $index++) 
		{
			$newMediaCheck[$index] = str_replace("'","\'", $newMediaCheck[$index]);
			print ('<b>'.$newMediaCheck[$index].'</b><br />');
			mysql_query("INSERT INTO $mediaTableNew($mediaName, $mediaDate) values('$newMediaCheck[$index]', CURDATE())");
		}
	}
}
?>