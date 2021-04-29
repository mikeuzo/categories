<?php 
	require_once $_SERVER['DOCUMENT_ROOT'].'/projectpgd/core/init.php';
	if(!is_logged_in()){
	   login_error_redirect();
	}
	include 'includes/head.php';
	include 'includes/navigation.php';
?>

<h2 class="text-center">Get an Estimation</h2><hr>
<form class="form" action="quotations.php" method="post">
	<div class="col-md-3 form-group">
		<label for="first name">FIRST NAME*:</label>
		<input type="text" class="form-control" name="title" value="<?=((isset($_POST['title']))? sanitize($_POST['title']) : '' );?>">
	</div>
	<div class="col-md-3 form-group">
		<label for="first name">LAST NAME*:</label>
		<input type="text" class="form-control" name="title" value="<?=((isset($_POST['title']))? sanitize($_POST['title']) : '' );?>">
	</div>
	<div class="col-md-3 form-group">
		<label for="first name">EMAIL ADDRESS*:</label>
		<input type="text" class="form-control" name="title" value="<?=((isset($_POST['title']))? sanitize($_POST['title']) : '' );?>">
	</div>
	
</form>

<?php include 'includes/footer.php';