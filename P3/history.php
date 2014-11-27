<?php require_once $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/base/header.php'; ?>


<div class="body-container" data-ng-controller="historyController">
	<aside class="menu" >
	</aside>

	<div class="scroller" id="scroller-purchase">
		<h3>Pedidos</h3>
		<div class="main-container">
			<?php
			require_once 'php/history.php';

				$purchases = getHistory();
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
			<div class="purchase" data-ng-init="showItems<?php echo $movie['id']; ?> = false">
				<div class="purchase-info">
					<span class="purchase-count">
						<a href="" data-ng-click="showItems<?php echo $movie['id']; ?> = !showItems<?php echo $movie['id']; ?>;">
							<?php echo $movieCount; ?> películas
						</a>
					</span>
					<span class="purchase-price"><?php echo $price; ?> €</span>
				</div>

				<div class="purchase-items" data-ng-show="showItems<?php echo $movie['id']; ?>">
					<?php foreach ($purchase['movies'] as $movie) { ?>
					<div class="purchase-item">
						<img alt="Imagen de la película." src="<?php echo $movie['image']; ?>" class="purchase-img" />
						<div class="purchase-title"><?php echo $movie['title']; ?></div>

						<div class="purchase-item-price">
							<?php echo $movie['quantity']; ?> × <?php echo $movie['price']; ?> €
						</div>
					</div>
					<?php } ?>
				</div>

				<div class="purchase-date"><?php 
					echo $purchase['date'];
				?></div>
			</div>	
			<?php } ?>
		</div>
	</div>
</div>


<?php require_once 'base/footer.php'; ?>