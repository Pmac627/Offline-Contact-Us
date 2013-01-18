<?php /* header.php */ ?>
<!DOCTYPE html>
<html lang="en" manifest="cache-manifest.mf">
<head>
	<meta charset="UTF-8" />
	<title><?php echo $title; ?>Offline Contact App</title>
	<link type="text/css" href="css/stylesheet.css" rel="stylesheet" media="screen" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<script type="text/javascript" src="js/jquery-min.js"></script>
</head>
<body onload="checkLocalStorage();">
