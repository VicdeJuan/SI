<?php require_once $_SERVER['DOCUMENT_ROOT'].'/php/common.php'; ?>

<!DOCTYPE html>
<html lang="en" xmlns:ng="http://angularjs.org" data-ng-app="mainApp">
<head>
<link rel="stylesheet" type="text/css" href="style/main.css" />
<meta charset="utf-8" />
<title>Ola k ase</title>
<script type="text/javascript">
	var serverRoot = "<?php echo $applicationBaseDir; ?>";
</script>
<script src="<?php echo $applicationBaseDir; ?>lib/angular.min.js" type="text/javascript"> 
</script>
<script src="<?php echo $applicationBaseDir; ?>lib/angular-animate.min.js" type="text/javascript">
</script>
<script src="<?php echo $applicationBaseDir; ?>js/controllers.js" type="text/javascript">
</script>
<script src="<?php echo $applicationBaseDir; ?>js/filterDirective.js" type="text/javascript">
</script>
</head>