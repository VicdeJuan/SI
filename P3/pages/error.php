<?php require dirname(__FILE__).'/../base/header.php'; ?>


<body>
<div class="body-container" data-ng-controller="movieListController">
	<aside class="menu">
	</aside>
	<div class="scroller">
		<div class="scroller-top">
			<?php
				session_start();
				echo "Lo sentimos. "."\n"."Se ha producido el siguiente error: ";
				if (isset($_SESSION['error'])) {
					echo $_SESSION['error'];
				}else
					echo "Desconodico";
				?>
		</div>
	</div>
</div>



<?php require dirname(__FILE__).'/../base/footer.php'; ?>
