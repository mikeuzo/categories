  <?php
	require_once 'core/init.php';
	include 'includes/head.php';
	include 'includes/navigation.php';
	include 'includes/headerpartial.php';
	include 'includes/leftbar.php';

if(isset($_GET['cat'])){
	$cat_id = sanitize($_GET['cat']);
}else{
	$cat_id = '';
}

$sql = "SELECT * FROM services WHERE categories = '$cat_id'";
$serviceQ = $db->query($sql);
$category = get_category($cat_id);
?>
 <!-- Main Content -->
	<div class="col-md-8">
	 	<div class="row">
	 		<h2 class="text-center"><?=$category['parent'].' '.$category['child'];?></h2>
	 		<?php while($service = mysqli_fetch_assoc($serviceQ)) : ?>
	 			<!-- <?php var_dump($service); ?> --> 
	 		<div class="col-md-3 text-center">
	 			<h4><?= $service['title']; ?></h4>
	 			<img src="<?= $service['image']; ?>" alt="<?= $service['title']; ?>" class="img-thumb"/>
	 			<p class="list-price text-danger">List Price: <s><?= $service['list_price']; ?></s></p>
	 			<p class="price">Our Price:<?= $service['price']; ?></p>
	 			<button type="button" class="btn btn-sm btn-success" onclick="detailsmodal(<?=$service['id']; ?>)">Details</button>
	 		</div>
	 	<?php endwhile; ?>
	 	</div>
	</div>

<style>
		.img-thumb{
			width: 190px;
			height: 200px;
		}
		
</style>

<?php
	include 'includes/rightbar.php';
	include 'includes/footer.php';
?>


