<? include 'movie_variables.php'; 
   include 'mediaServerFunctions.php';
   include 'global_variables.php';
?>
<html>

	<head>
 		<title>Ryan's <?echo "$media";?> List</title>
		<link rel="stylesheet" type="text/css" href="css/style.css"/>
	</head>
<body>
<?php
// SQL CONNECTION
make_mysql_connection($con, $database);

/////GET MYSQL DATA/////
$getMediaTable = getMediaTable_from_mysql($mediaTable);
$getMediaTableNew = getMediaTableNew_from_mysql($mediaTableNew);

switch($_GET['byNew']){
  case $mediaName:
  case $mediaDate:
    $byNew = $_GET['byNew'];
  break;
  default:
    $byNew = $mediaName;
  break;
}
switch($_GET['by']){
  case $mediaName:
  case $mediaDate:
    $by = $_GET['by'];
  break;
  default:
    $by = $mediaName;
  break;
}
$orderNew = ($_GET['orderNew']=='DESC')?'DESC':'ASC';
$order = ($_GET['order']=='DESC')?'DESC':'ASC';
// then order the query
$result = mysql_query("SELECT * FROM $mediaTable ORDER BY $by $order");
$resultNew = mysql_query("SELECT * FROM $mediaTableNew ORDER BY $byNew $orderNew");
 
////DISPLAY TITLE////
?>
<br />
	<center>
		<h1>Ryan's <?echo "$media";?> List</h1>
		<img src="images/moviereel.png" alt="Ryan's <?echo "$media";?>" />
<br />		 					
		<p><font color="#ffffff" face="Tahoma"><small>Works best with Internet Explorer so you can open the <?echo "$media";?> links to the shared directory.</small>
<br />
<?$mediaTotal = $getMediaTableNew['num'] + $getMediaTable['num']; // Get total media?>
		[<b>There are currently (<?php echo "$mediaTotal"; ?>) <?echo "$media";?>s in the shared folder.</b>]</font></p>
	</center>
<br />	
<br />
<!--  ////DISPLAY DATA//// -->

<!-- This is the jump to list for new media -->
<div id="leftcolumn">
<p align=center><font size="2.5"> 
<center>
<fieldset class='orange'>
<legend>Skip To:</legend>
<a href="#Anew">[A</a>
<a href="#Bnew">-B</a>
<a href="#Cnew">-C</a>
<a href="#Dnew">-D</a>
<a href="#Enew">-E</a>
<a href="#Fnew">-F</a>
<a href="#Gnew">-G</a>
<a href="#Hnew">-H</a>
<a href="#Inew">-I</a>
<a href="#Jnew">-J</a>
<a href="#Knew">-K</a>
<a href="#Lnew">-L</a>
<a href="#Mnew">-M</a>
<a href="#Nnew">-N</a>
<a href="#Onew">-O</a>
<a href="#Pnew">-P</a>
<a href="#Qnew">-Q</a>
<a href="#Rnew">-R</a>
<a href="#Snew">-S</a>
<a href="#Tnew">-T</a>
<a href="#Unew">-U</a>
<a href="#Vnew">-V</a>
<a href="#Wnew">-W</a>
<a href="#Xnew">-X</a>
<a href="#Ynew">-Y</a>
<a href="#Znew">-Z]</a>
<br /><br />
</center>
</fieldset>	
</font></p>
<?
// then add sorting options
echo "<table class='orange'><tr>";
if($byNew == $mediaName && $orderNew == 'DESC')
  echo '<th><a href="?byNew=$mediaName&amp;orderNew=ASC">Newly Added '. $media .'</th>';
else
  echo '<th><a href="?byNew=$mediaName&amp;orderNew=DESC">Newly Added '. $media .'</th>';
if($byNew == $mediaDate && $orderNew == 'ASC')
  echo '<th><a href="?byNew=$mediaDate&amp;orderNew=DESC">Date Added<br />(yyyy/mm/dd)</th>';
else
  echo '<th><a href="?byNew=$mediaDate&amp;orderNew=ASC">Date Added<br />(yyyy/mm/dd)</th>';
  echo '<th>IMDB</th>';

echo '</tr>';
while($rowNew = mysql_fetch_array($resultNew))
{
  echo "<tr>";
  echo "<td><A href='file:$networkLocation/$rowNew[$mediaName]' name='" . $rowNew[$mediaName][0] . "new'>$rowNew[$mediaName]</A></td>";
  echo "<td><center>" . $rowNew[$mediaDate] . "</center></td>";
  echo "<td><center><a href='http://www.imdb.com/find?s=all&q=" . str_replace(" ", "+", "$rowNew[$mediaName]") . "' target='_blank'><IMG SRC='images/imdb_small.gif' border=0></IMG></a></center></td>";
  echo "</tr>";
}
echo "</table>";
?>
	</table class='orange'>
</div id="leftcolumn">

<!-- This is the jump to list for media -->
<div id='rightcolumn'>
<p align=center><font size="2.5">
<center>
<fieldset class='green'>
<legend>Skip To:</legend>
<a href="#A">[A</a>
<a href="#B">-B</a>
<a href="#C">-C</a>
<a href="#D">-D</a>
<a href="#E">-E</a>
<a href="#F">-F</a>
<a href="#G">-G</a>
<a href="#H">-H</a>
<a href="#I">-I</a>
<a href="#J">-J</a>
<a href="#K">-K</a>
<a href="#L">-L</a>
<a href="#M">-M</a>
<a href="#N">-N</a>
<a href="#O">-O</a>
<a href="#P">-P</a>
<a href="#Q">-Q</a>
<a href="#R">-R</a>
<a href="#S">-S</a>
<a href="#T">-T</a>
<a href="#U">-U</a>
<a href="#V">-V</a>
<a href="#W">-W</a>
<a href="#X">-X</a>
<a href="#Y">-Y</a>
<a href="#Z">-Z]</a>
<br /><br />
</center>
</fieldset>	
</font></p>
<?
echo "<table class='green' align='center'><tr>";
// then add sorting options
if($by == $mediaName && $order == 'DESC')
  echo '<th><a href="?by=$mediaName&amp;order=ASC">'.$media.'</th>';
else
  echo '<th><a href="?by=$mediaName&amp;order=DESC">'.$media.'</th>';
if($by == $mediaDate && $order == 'ASC')
  echo '<th><a href="?by=$mediaDate&amp;order=DESC">Date Added<br />(yyyy/mm/dd)</th>';
else
  echo '<th><a href="?by=$mediaDate&amp;order=ASC">Date Added<br />(yyyy/mm/dd)</th>';
  echo '<th>IMDB</th>';
// all done now.
echo '</tr>';
while($row = mysql_fetch_array($result))
{
  echo "<tr>";
  echo "<td><A href='file:$networkLocation/$row[$mediaName]' name='" . $row[$mediaName][0] . "'>$row[$mediaName]</A></td>";
  echo "<td><center>" . $row[$mediaDate] . "</center></td>";
  echo "<td><center><a href='http://www.imdb.com/find?s=all&q=" . str_replace(" ", "+", "$row[$mediaName]") . "' target='_blank'><IMG SRC='images/imdb_small.gif' border=0></IMG></a></center></td>";
  echo "</tr>";
}
echo "</table>";
?>
	</table class='green'>
</div id='rightcolumn'>
</body>
</html>
