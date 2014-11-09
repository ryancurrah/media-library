<html>
<head>
<title>TEST</title>
</head>
<body>

<?php

$array1 = array('red', 'blue', 'green', 'octarine');
$array2 = array('red', 'yellow', 'green');
$diff = array_diff($array1, $array2);

print_r($diff); //Array ( [1] => blue [3] => octarine )

?>
<br />
<br />

<?php

print_r(array_merge($diff)) //Array ( [0] => blue [1] => octarine )

?>

<br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
<h1>Script Finished</h1>
</body>
</html>