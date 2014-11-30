<?php require_once dirname(__FILE__).'/../php/common.php'; ?>

<!DOCTYPE html>
<html lang="en" data-ng-app="mainApp">
<head>
<link rel="stylesheet" type="text/css" href="<?php echo $applicationBaseDir; ?>style/main.css" />
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
