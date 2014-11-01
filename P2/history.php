<?php require_once $_SERVER['DOCUMENT_ROOT'].'/base/header.php'; ?>


<div class="body-container" ng-controller="historyController">
	<aside class="menu">
	</aside>

	<div class="scroller">
		<h3>Pedidos</h3>
		<div class="main-container">
			<?php
				require_once 'php/history.php';

				$purchases = getHistory("users/".$_SESSION['email']);

				foreach ($purchases as $purchase) 
				{
					$price = 0;
					$movieCount = 0;

					foreach ($purchase['movies'] as $movie) 
					{
						$price += $movie['quantity'] * $movie['price'];
						$movieCount += $movie['quantity'];
					} 
			?>
			<div class="purchase" ng-init="showItems<?php echo $movie['id']; ?> = false">
				<div class="purchase-info">
					<span class="purchase-count">
						<a href="" ng-click="showItems<?php echo $movie['id']; ?> = !showItems<?php echo $movie['id']; ?>;">
							<?php echo $movieCount; ?> películas
						</a>
					</span>
					<span class="purchase-price"><?php echo $price; ?> €</span>
				</div>

				<div class="purchase-items" ng-show="showItems<?php echo $movie['id']; ?>">
					<?php foreach ($purchase['movies'] as $movie) { ?>
					<div class="purchase-item">
						<img alt="" src="<?php echo $movie['image']; ?>" class="purchase-img" />
						<div class="purchase-title"><?php echo $movie['title']; ?></div>

						<div class="purchase-item-price">
							<?php echo $movie['quantity']; ?> × <?php echo $movie['price']; ?> €
						</div>
					</div>
					<?php } ?>
				</div>

				<div class="purchase-date"><?php 
					$date = DateTime::createFromFormat(DATE_ATOM, $purchase['date']);
					echo $date->format('d F Y, H:i');
				?></div>
			</div>	
			<?php } ?>
		</div>
	</div>
</div>


<?php require_once 'base/footer.php'; ?>