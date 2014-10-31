<?php require_once 'base/header.php'; ?>


<div class="body-container">
	<aside class="menu">
		
	</aside>

	<div class="scroller">
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
			<div class="purchase">
				<div class="purchase-info">
					<span class="purchase-count"><?php echo $movieCount; ?> películas</span>
					<span class="purchase-price"><?php echo $price; ?> €</span>
				</div>

				<div class="purchase-items">
					<?php foreach ($purchase['movies'] as $movie) { ?>
					<div class="purchase-item">
						<img src="<?php echo $movie['image']; ?>" class="purchase-img" />
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