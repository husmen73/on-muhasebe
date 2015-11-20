<?php
if(isset($_GET['barcode']))
{
	$barcode = $_GET['barcode'];
	$print = $_GET['print'];
}
else
{
	exit;	
}
?>
<img src="barcode.php?barcode=<?php echo $barcode; ?>" />

<!-- TASK -->
<?php
$task = '';
if($print == true)
{
	$task = 'window.print(); parent.close(); ';	
}
?>
<body onLoad="<?php echo $task; ?>">
<!-- / TASK -->