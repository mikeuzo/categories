</div>


<footer class="text-center" id="footer">&copy; Copyright 2018-2020 Mike's Logistics</footer>

<style>
	#footer{
		margin-top: 50px;
	}
</style>


<script>
	jQuery(window).scroll(function (){
		var vscroll = jQuery(this).scrollTop();
		jQuery('#logotext').css({
			"transform" : "translate(0px, "+vscroll/2+"px)"
		});

		var vscroll = jQuery(this).scrollTop();
		jQuery('#back-flower').css({
			"transform" : "translate(0px, -"+vscroll/12+"px)"
		});

		var vscroll = jQuery(this).scrollTop();
		jQuery('#for-flower').css({
			"transform" : "translate(0px, -"+vscroll/2+"px)"
		});
	});

	function detailsmodal(id){
		// alert(id);
		var data = {"id" : id};
		jQuery.ajax({
		url : '/projectpgd/includes/detailsmodal.php',
		method : "post",
		data : data,
		success: function(data){
			jQuery('body').append(data);
			jQuery('#details-modal').modal('toggle');
		},
		error: function(){
			alert("Something went wrong!");
		},
	  });
	}

	function update_cart(mode,edit_id,edit_size){
		var data = {"mode" : mode, "edit_id" : edit_id, "edit_size" : edit_size};
		jQuery.ajax({
			url : '/projectpgd/admin/parsers/update_cart.php',
			method : 'post',
			data : data,
			success : function(){location.reload();},
			error : function(){alert("Something went wrong")},
		});
	}

	function add_to_cart(){
		jQuery('#modal_errors').html("");
		var size = jQuery('#size').val();
		var quantity = jQuery('#quantity').val();
		var available = jQuery('#available').val();
		var error = '';
		var data = jQuery('#add_service_form').serialize();
		if(size == '' || quantity == '' || quantity == 0){
			error += '<p class="text-danger text-center">You must choose a size and quantity.</p>';  
			jQuery('#modal_errors').html(error);
			return;
		}else if(quantity > available){
			error += '<p class="text-danger text-center">There are only '+available+' available.</p>'; 
			jQuery('#modal_errors').html(error);  
			return; 
		 }else{      
			jQuery.ajax({
				url : '/projectpgd/admin/parsers/add_cart.php',
				method : 'post',
				data : data,
				success : function(){
					location.reload();
				},
				error : function(){alert("Something went wrong");}
			});
		}
	}
</script>
</body>
</html>