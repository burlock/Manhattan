<?php session_start(); ?>

<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	
	<title>Home</title>

	<!-- Custom styles for this template -->
	<link href="../common/css/design.css" rel="stylesheet">

	<!-- Using the same favicon from perspectiva-alemania.com site -->
	<link rel="shortcut icon" href="http://www.perspectiva-alemania.com/wp-content/themes/perspectiva2013/bilder/favicon.png">
	<!-- Using the favicon for touch-devices shortcut -->
	<link rel="apple-touch-icon" href="../common/img/apple-touch-icon.png">

</head>

<body>
	<div class="top-alert-container">
		<?php if(!$_SESSION['loglogin']) { ?>
		<div class="alert alert-danger alert-error top-alert fade in">
			<a href="#" class="close" data-dismiss="alert">&times;</a>
			<strong>Opssss!</strong> An error occured while closing session.
		</div>
		<?php
	}
	else{
		require_once($_SERVER['DOCUMENT_ROOT'] . '/common/library/functions.php');
		session_destroy();
		?>
		<div class="alert alert-success top-alert fade in">
			<a href="#" class="close" data-dismiss="alert">&times;</a>
			<strong>Success!</strong> Session closed successfully.
		</div>
		<?php
	}
	?>
	</div> <!-- Container -->

<!-- Footer bar & info
	================================================== -->
	<div id="footer" >
		<div class="container">
			<p class="text-muted">&copy; Perspectiva Alemania, S.L.</p>
		</div>
	</div>

<!-- Scripts. Placed at the end of the document so the pages load faster.
	================================================== -->
	<!-- Bootstrap core JavaScript -->
	<script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
	<script src="https://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>

	<!-- Site own functions -->
	<script src="../common/js/functions.js"></script>
	<script src="../common/js/application.js"></script>
	<script src="../common/js/docs.min.js"></script>

	<!-- Own document functions -->
	<!-- Go to index.html when alert closed -->
	<script type="text/javascript">
		$(document).ready(function(){
			// Close the alert after 2 seconds.
			window.setTimeout(function() { $(".alert").alert('close'); }, 3000);
			$(".alert").bind('closed.bs.alert', function(){
				window.location.href='index.html';
			});
		});  
	</script>

</body>
</html>