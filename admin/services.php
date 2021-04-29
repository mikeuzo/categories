<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/projectpgd/core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';

//Delete Services 
if(isset($_GET['delete'])){
	$id = sanitize($_GET['delete']);
	$db->query("UPDATE services SET deleted = 1 WHERE id = '$id'");
	header('Location: services.php');
}

$dbpath = '';
if (isset($_GET['add']) || isset($_GET['edit'])){
$parentQuery = $db->query("SELECT * FROM categories WHERE parent = 0 ORDER BY category");
$title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):'');
$parent = ((isset($_POST['parent']) && $_POST['parent'] != '')?sanitize($_POST['parent']):'');
$category = ((isset($_POST['child']) && $_POST['child'] != '')?sanitize($_POST['child']):'');
$price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):'');
$list_price = ((isset($_POST['list_price']) && $_POST['list_price'] != '')?sanitize($_POST['list_price']):'');
$description = ((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']):'');
$sizes = ((isset($_POST['sizes']) && $_POST['sizes'] != '')?sanitize($_POST['sizes']):'');
$sizes = rtrim($sizes,',');
$saved_image = '';

if(isset($_GET['edit'])){
	$edit_id = (int)$_GET['edit'];
	$serviceResults = $db->query("SELECT * FROM services WHERE id = '$edit_id'");
	$service = mysqli_fetch_assoc($serviceResults);
	if(isset($_GET['delete_image'])){
		$image_url = $_SERVER['DOCUMENT_ROOT'].$service['image'];echo $image_url;
		unlink($image_url);
		$db->query("UPDATE services SET image = '' WHERE id = '$edit_id'");
		header('Location: services.php?edit='.$edit_id);
	}
	$category = ((isset($_POST['child']) && $_POST['child'] != '')?sanitize($_POST['child']):$service['categories']);
	$title = ((isset($_POST['title']) && !empty($_POST['title']))?sanitize($_POST['title']):$service['title']);
	$parentQ = $db->query("SELECT * FROM categories WHERE id = '$category'");
	$parentResult = mysqli_fetch_assoc($parentQ);
	$parent = ((isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']):$parentResult['parent']);
	$price = ((isset($_POST['price']) && !empty($_POST['price']))?sanitize($_POST['price']):$service['price']);
	$list_price = ((isset($_POST['list_price']) && !empty($_POST['list_price']))?sanitize($_POST['list_price']):$service['list_price']);
	$description = ((isset($_POST['description']) && !empty($_POST['description']))?sanitize($_POST['description']):$service['description']);
	$sizes = ((isset($_POST['sizes']) && !empty($_POST['sizes']))?sanitize($_POST['sizes']):$service['sizes']);
	$sizes = rtrim($sizes,',');
	$saved_image = (($service['image'] != '')?$service['image']:'');
	$dbpath = $saved_image;
}

if (!empty($sizes)){
		$sizeString = sanitize($sizes);
		$sizeString = rtrim($sizeString,',');//echo $sizeString;
		$sizesArray = explode(',',$sizeString);
		$sArray = array();
		$qArray = array();
		foreach($sizesArray as $ss){
			$s = explode(':', $ss);
			$sArray[] = $s[0];
			$qArray[] = $s[1];
		}
	}else{$sizesArray = array();}

// Form Validation
if($_POST) {
	// $categories = sanitize($_POST['child']);
	// $price = sanitize($_POST['price']);
	// $list_price = sanitize($_POST['list_price']);
	// $sizes = sanitize($_POST['sizes']);
	// $description = sanitize($_POST['description']);
	$errors = array();
	$required = array('title','price','parent','child','sizes');
	foreach ($required as $field){
		if($_POST[$field] == ''){
			$errors[] = 'All Fields With an Astrisk are required.'; break;
		}	
	}
	if(!empty($_FILES)){
		 //var_dump($_FILES);
		 $photo = $_FILES['photo'];
		 $name = $photo['name'];
		 $nameArray = explode('.',$name);
		 $fileName = $nameArray[0];
		 $fileExt = $nameArray[1];
		 $mime = explode('/',$photo['type']);
		 $mimeType = $mime[0];
		 $mimeExt = $mime[1];
		 $tmpLoc = $photo['tmp_name'];
		 $fileSize = $photo['size'];
		 $allowed = array('png','jpg','jpeg','gif');
		 $uploadName = md5(microtime()).'.'.$fileExt;
		 $uploadPath = BASEURL.'images/services'.$uploadName;
		 $dbpath = '/projectpgd/images/services'.$uploadName;
		 if($mimeType != 'image'){
		 	$errors[] = 'The file must be an image.';
		 }
		 if (!in_array($fileExt, $allowed)){
		 		$errors[] = 'The photo file extension must be a png, jpg, jpeg, or gif.';
		 }
		 if($fileSize > 1000000){
		 	$errors[] = 'The files size must be under 25MB.';
		 }
		 if($fileExt != $mimeExt && ($mimeExt == 'jpeg' && $fileExt != 'jpg')){ 
		 	$errors[] = 'The file extension does not match the file.';
		 }
	}

	if(!empty($errors)){
		echo display_errors($errors);
	}else{
		//upload file and insert into database
		if (!empty($_FILES)) {
			move_uploaded_file($tmpLoc,$uploadPath);
		}
		$insertSql = "INSERT INTO services (`title`,`price`,`list_price`,`categories`,`sizes`,`image`,`description`) VALUES ('$title','$price','$list_price','$category','$sizes','$dbpath','$description')";
		if(isset($_GET['edit'])){
			$insertSql = "UPDATE services SET title = '$title', price = '$price', list_price = '$list_price', categories = '$category', sizes = '$sizes', image = '$dbpath', description = '$description' WHERE id = '$edit_id'";
		}

		$db->query($insertSql);
		header('Location: services.php');
	}
}
?>
	<h2 class="text-center"><?=((isset($_GET['edit']))?'Edit':'Add A New');?> Service</h2><hr>
	<form action="services.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1');?>" method="POST" enctype="multipart/form-data">
		<div class="form-group col-md-3">
			<label for="title">Title*:</label>
			<input type="text" name="title" class="form-control" id="title" value="<?=$title;?>">
		</div>
		<div class="form-group col-md-3">
			<label for="parent">Parent Category*:</label>
			<select class="form-control" id="parent" name="parent">
			<option value=""<?=(($parent == '')?'selected':'');?>></option>	
			<?php while($p = mysqli_fetch_assoc($parentQuery)): ?>
				<option value="<?=$p['id'];?>"<?=(($parent == $p['id'])?'selected' :'');?>><?=$p['category'];?></option>
			<?php endwhile; ?>	
			</select>	
		</div>
		<div class="form-group col-md-3">
			<label for="child">Child Category*:</label>
			<select id="child" name="child" class="form-control">
			</select>
		</div>
		<div class="form-group col-md-3">
			<label for="price">Price*:</label>
			<input type="text" name="price" id="price" class="form-control" value="<?=$price;?>">
		</div>
		<div class="form-group col-md-3">
			<label for="list_price">List Price*:</label>
			<input type="text" name="list_price" id="list_price" class="form-control" value="<?=$list_price;?>">
		</div>
		<div class="form-group col-md-3">
			<label>Quantity & Sizes*:</label>
			<button class="btn btn-default form-control" onclick="jQuery('#sizesModal').modal('toggle');return false;">Quantity & Sizes</button>
		</div>
		<div class="form-group col-md-3">
			<label for="sizes">Sizes & Qty Preview</label>
			<input type="text" class="form-control" name="sizes" id="sizes" value="<?=$sizes;?>" readonly>
		</div>
		<div class="form-group col-md-6">
			<?php if($saved_image != ''): ?>
				<div class="saved_image"><img src="<?=$saved_image;?>" alt="saved_image"/><br>
				<a href="services.php?delete_image=1&edit=<?=$edit_id;?>" class="text-danger">Delete Image</a>	
				</div>
			<?php else: ?>
				<label for="photo">Service Photo</label>
				<input type="file" name="photo" id="photo" class="form-control">
			<?php endif; ?>

			<style>
				.saved_image img{
					width:200px;
					height: auto;
				}
			</style>


		</div>
		<div class="form-group col-md-6">
			<label for="description">Description</label>
			<textarea id="description" name="description" class="form-control" rows="6"><?=$description;?></textarea>
		</div>
		<div class="form-group pull-right">
			<a href="services.php" class="btn btn-default">Cancel</a>
			<input type="submit" value="<?=((isset($_GET['edit']))?'Edit':'Add');?> Service" class="btn btn-sm btn-success">
		</div><div class="clearfix"></div>
	</form> 

	<!-- Modal -->
<div class="modal fade" id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel" aria-hidden="true">    
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times</span></button>
				<h4 class="modal-title" id="sizesModalLabel">Size & Quantity</h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
				<?php for($i=1;$i <= 12;$i++): ?>
					<div class="form-group col-md-4">
						<label for="size<?=$i;?>">Size:</label>
						<input type="text" name="size<?=$i;?>" id="size<?=$i;?>" value="<?=((!empty($sArray[$i-1]))?$sArray[$i-1]:'');?>" class="form-control">
					</div>
					<div class="form-group col-md-2">
						<label for="qty<?=$i;?>">Quantity:</label>
						<input type="number" name="qty<?=$i;?>" id="qty<?=$i;?>" value="<?=((!empty($qArray[$i-1]))?$qArray[$i-1]:'');?>" min="0" class="form-control">
					</div>
				<?php endfor; ?> 
			</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" onclick="updateSizes();jQuery('#sizesModal').modal('toggle');return false;">Save changes</button>
			</div>
		</div>
	</div>
</div>
<?php }else{
$sql = "SELECT * FROM services WHERE deleted = 0";
$sresults = $db->query($sql);
if (isset($_GET['featured'])) {
	$id = (int)$_GET['id'];
	$featured = (int)$_GET['featured'];
	$featuredSql = "UPDATE services SET featured = '$featured' WHERE id = '$id'";
	$db->query($featuredSql);
	header('Location: services.php');
}
?>
<h2 class="text-center">Services</h2>
<a href="services.php?add=1" class="btn btn-success pull-right" id="add-product-btn">Add Services</a><div class="clearfix"></div>
<hr>
<table class="table table-bordered table-condensed table-striped">
	<thead><th></th><th>Services</th><th>Price</th><th>Category</th><th>Featured</th><th>Sold</th></thead>
	<tbody>
		<?php while($service = mysqli_fetch_assoc($sresults)): 
			$childID = $service['categories'];
			$catSql = "SELECT * FROM categories WHERE id = '$childID'"; 
			$result = $db->query($catSql);
			$child = mysqli_fetch_assoc($result); 
			$parentID = $child['parent'];
			$pSql = "SELECT * FROM categories WHERE id = '$parentID'";
			$presult = $db->query($pSql);
			$parent = mysqli_fetch_assoc($presult);
			$category = $parent['category'].'~'.$child['category'];
			?>
			<tr>
				<td>
					<a href="services.php?edit=<?=$service['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
					<a href="services.php?delete=<?=$service['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span></a>
				</td>
				<td><?=$service['title'];?></td>
				<td><?=money($service['price']);?></td>
				<td><?=$category;?></td>
				<td><a href="services.php?featured=<?=(($service['featured'] == 0)? '1':'0');?>&id=<?=$service['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-<?=(($service['featured'] == 1)?'minus':'plus');?>"></span></a>&nbsp <?=(($service['featured'] ==1)?'Featured Service':'');?></td>
				<td>0</td>
			</tr>
		<?php endwhile; ?>
	</tbody>
</table>

<?php } include 'includes/footer.php'; ?>
<script>
	jQuery('document').ready(function(){
		get_child_options('<?=$category;?>');
	});
</script>