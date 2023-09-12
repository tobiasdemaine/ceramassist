<?php
$view->set_section('distribution');

if($view->url["add_webstore"] != "new"){
	$shop = $view->get_webstore($view->url["add_webstore"]);
	$_shop = $shop;
	if($_shop["type"]=="woocommerce"){
		$ljson = json_decode($_shop["linkage_json"], true);
		$woo = [];
		$woo["api_url"] = $ljson["api_url"];
		$woo["consumer_key"] = $ljson["consumer_key"];
		$woo["consumer_secret"] = $ljson["consumer_secret"];
		
		
		require_once 'assets/lib/woocommerce-api.php';
		$options = array(
 		   'ssl_verify'      => false,
		);

		try {

 		   $client = new WC_API_Client( $woo["api_url"], $woo["consumer_key"], $woo["consumer_secret"], $options );
 		   
 		    if(isset($view->url["upload_woo_product"])){
 		    
 		    
 		    	$p = array();
 		    	$p["title"]= $view->url["woo_name"];
				$p["type"]= "simple";
				$p["status"]= "publish";
				$p["sku"]= $view->url["woo_id"];
				$p["price"]= $view->url["woo_price"];
				$p["regular_price"]= $view->url["woo_price"];
				$p["stock_quantity"] = $view->url["woo_qty"]; //SET FROM
				$p["in_stock"] = true;
				$p["visible"]= true;
				$p["weight"]= ($view->url["woo_grams"]/1000); // kg
				$p["dimensions"]= array( "length"=> $view->url["woo_z_mm"], "width"=> $view->url["woo_x_mm"], "height"=> $view->url["woo_y_mm"], "unit"=> "mm" );
				$p["description"]=$view->url["woo_description"];
				$p["short_description"] = $view->url["woo_short_description"];

				$cats = json_decode($view->url["cats"], true);
				$p["categories"] = array();
				foreach($cats as $ca){
					array_push($p["categories"], $ca);
				}
				
				
				$featured = "";
				$p["images"] = array();
				$imgs = json_decode($view->url["img"], true);
				$c=1;
				foreach($imgs as $img){
					// upload image via curl and retrieve url
					$file_name_with_full_path = realpath($img["src"]);
					$src = explode("/", $img["src"]);
					$s = array_pop($src);
					$post = array('key' => $woo["consumer_secret"],'file-upload' => new CurlFile($file_name_with_full_path, 'text/plain' /* MIME-Type */, $s));
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $woo["api_url"]."/image-upload/");
					curl_setopt($ch, CURLOPT_POST,1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

					$result = curl_exec ($ch);
					curl_close ($ch);
					$f = explode("[file:", $result);
					$fi = explode("]", $f[1]);
					
					
					$im = array();
					$im["src"] = $fi[0]; // http
					if($img["feature"]== true){
						$im["position"] = 0;
					}else{
						$im["position"] = $c;
					}
					$c++;
					array_push($p["images"], $im);
					
				}
				$p["featured_src"]= $featured;
				$client->products->create($p);
				exit;
 		    }
 		   
 		   if(isset($view->url["woo-update-order-status"])){
 		   		if(isset($view->url["woo-order-id"])){
 		   			$client->orders->update_status($view->url["woo-order-id"], $view->url["woo-update-order-status"]);
 		   		}
 		   		
 		   }
 		   
 		   
 		   if(isset($view->url["woo-update-product-status"])){
 		    	if($view->url["woo-update-product-status"] == "delete"){
 		    		if(isset($view->url["woo-product-id"])){
 		    			$client->products->delete( $view->url["woo-product-id"], true );
 		    		}
 		    	}else{
 		    	if(isset($view->url["woo-product-id"])){
 		   			$product = $client->products->get( $view->url["woo-product-id"]);
 		   			$p = $product->product;
 		   			$x = eval("$"."p->".$view->url["woo-update-product-status"].";");
 		   			foreach($p as $key => $value){
 		   				if($key == $view->url["woo-update-product-status"]){
 		   					$x = $value;
 		   				}
 		   			}
 		   			if($x == true){
 		   				$toggle = false;
 		   			}else{
 		   				$toggle = true;
 		   			}
 		   			$arr = array( $view->url["woo-update-product-status"] => $toggle );
 		   			$x = $client->products->update( $view->url["woo-product-id"], $arr );
 		   			
 		   		}
 		   		}
 		   }

 		
 		   			// echo "$"."product->product->".$view->url["woo-update-product-status"].";";

 
		} catch ( WC_API_Client_Exception $e ) {

		    echo $e->getMessage() . PHP_EOL;
		    echo $e->getCode() . PHP_EOL;

  			if ( $e instanceof WC_API_Client_HTTP_Exception ) {

    	    	print_r( $e->get_request() );
    	    	print_r( $e->get_response() );
    		}
		}
		
		 
	}
	if($_shop["type"]=="etsy"){
		$etsy = [];
		$etsy["api_url"] = "";
		$etsy["api_key"] = "";
	}
}else{
	$_shop["id"] = 0;
	$_shop["name"] = "";
	$_shop["type"] = "woocommerce";
	$_shop["url"] = "";
	$_shop["description"] = "";
	$_shop["linkage_json"] = "";
	$woo = [];
	$woo["api_url"] = "";
	$woo["consumer_key"] = "";
	$woo["consumer_secret"] = "";
	$etsy = [];
	$etsy["api_url"] = "";
	$etsy["api_key"] = "";
}
?>
<h4>Webstore Details<?php 
	if(isset($shop)){?> <a href="javascript:add_document();" class='btn  btn-sm pull-right' style="margin-left:10px; font-weight:300;">Add Documents +</a> <a href="javascript:add_product();" class='btn  btn-sm pull-right' style="font-weight:300;">Add Ware +</a><a href="<?=$woo["api_url"];?>/wp-admin/post-new.php?post_type=shop_order" style="margin-right:10px; font-weight:300;" class='btn pull-right' target='_blank'>New Order +</a><?php } ?></h4>
<div style="width:20%; display:table-cell; vertical-align:top; ">
	<form id="shop_data">
	<input type="hidden" name="id" id="shop_id" value="<?=$_shop["id"]?>">
	<div>Name</div>
	<input type="text" id="name" name="name" value="<?=$_shop["name"]?>">
	
	<div>Type</div>
	<select id="select_type" onchange="$('._details').hide();$('#'+$(this).val()+'_details').show();$('#type').val($(this).val());">
		<option value="woocommerce">Word Press / Woo Commerce</option>
		<!-- <option value="etsy">Etsy</option> -->
	</select>
	<input type="hidden" name="type" id="type" value="<?=$_shop["type"]?>">

	<div>Url</div>
	<input type="text" name="url" value="<?=$_shop["url"]?>">
	
	<div>Description</div>
	<textarea id="description"><?=$_shop["description"]?></textarea>
	
	
	<input type="hidden" name="linkage_json" id="linkage_json" value="<?=$_shop["id"]?>">
	</form>
	<div id="woocommerce_details" class='_details'>
		<h4>Woo Commerce Details</h4>
		<div>API URL</div>
		<input type="text" id="api_url" value="<?=$woo["api_url"]?>">
		<div>Consumer Key</div>
		<input type="text" id="consumer_key" value="<?=$woo["consumer_key"]?>">
		<div>Consumer Secret</div>
		<input type="text" id="consumer_secret" value="<?=$woo["consumer_secret"]?>">
	</div>
	<div id="etsy_details" style="display:none;" class='_details' >
		<h4>Etsy Details</h4>
		<div>API URL</div>
		<input type="text" id="etsy_api_url" value="<?=$etsy["api_url"]?>">
		<div>Api Key</div>
		<input type="text" id="etsy_api_key" value="<?=$etsy["api_key"]?>">
		<!-- <div>Consumer Secret</div>
		<input type="text" id="consumer_secret" value=""> -->
	</div>
	<a href="javascript:save_shop()" class="btn">Save</a>
</div>
<?php if(isset($shop)){ ?>
<div style="width:70%; display:table-cell; ">
<div id="tabs">
  <ul>
   
    <?php
    if(isset($client)){
    	?>
    	 <li><a href="#tabs-2">Orders</a></li>
    	 <li><a href="#tabs-4">Store Products</a></li>
    	<?php
    }
    ?>
    <li><a href="#tabs-3">Documents</a></li>
    
   </ul>
  	
  	
  	 <?php
    if(isset($client)){
    	$orders = $client->orders->get();
    	function get_orders($status, $_orders, $woo){
    		?>
    		<table class="table table-striped"><thead><tr><th>Order id</th><th>Date</th><th>Customer</th><th>Purchased</th><th>Total</th><th>Pay type</th><th>Actions</th></tr></thead>
    			<?php
    			$count = 0;
    			foreach($_orders->orders as $order){
  					if($order->status == $status){
  						$count++;
  						?>
  						<tr>
  							<td><?=$order->id?></td>
  							<td><?=$order->created_at?></td>
  							<td><?=$order->customer->first_name?> <?=$order->customer->last_name?></td>
  							<td><?=$order->total_line_items_quantity?> item/s</td>
  							<td><?=$order->total?></td>
  							<td><?=$order->payment_details->method_title?></td>
  							<td style="width:15%;" nowrap data-order='<?=json_encode($order)?>'>
  								<a href="<?=$woo["api_url"];?>/wp-admin/post.php?post=<?=$order->id?>&action=edit" target="_blank" class='btn' >View</a> or <select onchange="woo_execute(this)" style="width:120px; margin-bottom:0px;" >
  									<option value=''>Select Action</option>
  									<option value='pending'>Payment Pending</option>
  									<option value='processing'>Processing</option>
  									<option value='on-hold'>On Hold</option>
  									<option value='complete'>Complete</option>
  									<option value='cancelled'>Cancelled</option>
  									<option value='refund'>Refund</option>
  								</select>
  							</td>
  						</tr>
  						<?php
  					}
  				}
  				if($count==0){
  					$count='';
  				}else{
  					$count = '['.$count.']';
  				}
  				?>
  			</table>
  			<script>$('#<?=$status?>-count').html('<?=$count?>');</script>
    		<?php
    	}
    	?>
    	<div id="tabs-2">
    		
    		<div id="order-tabs">
    			<ul>
    				<li><a href="#order-pending">Pending Payment <span id="pending-count"></span></a></li>
    				<li><a href="#order-processing">Processing <span id="processing-count"></span></a></li>
    				<li><a href="#order-on-hold">On Hold <span id="on-hold-count"></span></a></li>
    				<li><a href="#order-complete">Complete <span id="complete-count"></span></a></li>
    				<li><a href="#order-cancelled">Cancelled <span id="cancelled-count"></span></a></li>
    				<li><a href="#order-refund">Refunded <span id="refund-count"></span></a></li>
    				<li><a href="#order-failed">Failed <span id="failed-count"></span></a></li>
    			</ul>
    			<div id="order-pending">
    				 <?=get_orders('pending', $orders, $woo)?>
    			</div>
    			<div id="order-processing">
    				<?=get_orders('processing', $orders, $woo)?>
    			</div>
    			<div id="order-on-hold">
    				<?=get_orders('on-hold', $orders, $woo)?>
    			</div>
    			<div id="order-complete">
    				<?=get_orders('complete', $orders, $woo)?>
    			</div>
    			<div id="order-cancelled">
    				<?=get_orders('cancelled', $orders, $woo)?>
    			</div>
    			<div id="order-refund">
    				<?=get_orders('refund', $orders, $woo)?>
    			</div>
    			<div id="order-failed">
    				<?=get_orders('failed', $orders, $woo)?>
    			</div>
    			
    			<script>
  					$( "#order-tabs" ).tabs();
  					
  					  					
  					function woo_execute(obj){
  						order = $.parseJSON($(obj).parent().attr('data-order'));
  						if($(obj).val() == ''){
  							
  						}else{
  							url = '?add_webstore=<?=$view->url['add_webstore']?>&woo-update-order-status='+$(obj).val()+'&woo-order-id='+order.id;
  							document.location.href = url;
  						}
  					}
  				</script>
  		</div>
  		
  		</div>
  		<div id="tabs-4">
  		<table class="table table-striped"><thead><tr><th>image</th><th>product</th><th>price</th><th>qty</th><th>on sale</th><th>visible</th><th>linked to</th><th>Actions</th></tr></thead>
  		<?php
  		$products = $client->products->get();
  		
  		?>
  	
  		<?php
  		foreach($products->products as $product){
  		
  			?>
  			<tr>
  				<td><img src='<?=$product->featured_src?>' style="height:100px;"></td>
  				<td><?=$product->title;?></td>
  				<td><?=$product->price;?></td>
  				<td><?=$product->stock_quantity;?></td>
  				<td><?php if($product->on_sale==1){echo "on sale";} ?></td>
  				<td><?php if($product->visible==1){echo "visible";} ?></td>
  				<td>
  				<?php 
  				$x = $view->get_link_from_webstore($view->url["add_webstore"], $product->id);
  				if($x !== false){
  					$w = json_decode($view->load_ware($x['wareid']), true);
  					?>
  					<a href="javascript:iload_ware(<?=$x['wareid']?>)"><?=$w["name"]?></a>
  					<?php
  				}
  				?>
  				</td>
  				<td>
  				<a href="<?=$product->permalink?>" target="_blank" class='btn' >View</a> or 
  				<select onchange="select_action_product(this, '<?=$product->id;?>')"  style="width:120px; margin-bottom:0px;" >
  					<option value=''>Select Action</option>
  					<!-- <option value="visible">Visibile</option>
  					<option value="on_sale">On Sale</option> -->
  					<?php
  					if($x !== false){
  						?>
  						<option value="unlink">Unlink</option>
  						<?php
  					}
  					?>
  					<option value="delete">Delete</option>
  				</select>
  				</td>
  			</tr>	
  			<?php
  		}
  		?>
  		</table>
  		
  		
  		</div>
    	<?php
    }
    ?>
  	
  	
  	<div id="tabs-3">
		<div style="width:100%;" id="shopdocs ">
		<?php 
		if(isset($shop)){
			$parent_id = $shop['id'];
			$parent_table = "webstore";
			include('template/select_document.php');
		}
		?>
		</div>
	</div>
</div>
</div>

<div id="link_ware" title="Ware Details">
<input id="link_ware_id" type="hidden">
	<div class="pull-right">or &nbsp; &nbsp;<a class='btn' href="javascript:add_new_woo_product($('#link_ware_id').val())">add new ware to shop</a></div>
	<table class="table table-striped"><thead><tr><th>image</th><th>product</th><th>price</th><th>qty</th><th>on sale</th><th>visible</th><th>Actions</th></tr></thead>
  		<?php
  		if(isset($client)){
  			$products = $client->products->get();
  			foreach($products->products as $product){
  		
  			?>
  			<tr>
  				<td><img src='<?=$product->featured_src?>' style="height:100px;"></td>
  				<td><?=$product->title;?></td>
  				<td><?=$product->price;?></td>
  				<td><?=$product->stock_quantity;?></td>
  				<td><?php if($product->on_sale==1){echo "on sale";} ?></td>
  				<td><?php if($product->visible==1){echo "visible";} ?></td>
  				<td>
  				<a href="javascript:link_ware_to_woo(<?=$product->id?>)" class="btn">Link</a>
  				</td>
  			</tr>	
  			<?php
  			}
  		}
  		?>
  	</table>


</div>


<div id="update_link_ware" title="Ware Details">
<input id="shop_link_ware_id" type="hidden">
<div>Price </div>
<input type="text" id="update_link_ware_price" value="">
<div>Consignment Qty</div>
<input type="text" id="update_link_ware_qty" value="">
<div>Qty Sold</div>
<input type="text" id="update_link_ware_sold" value="">
<div>Notes</div>
<textarea id="update_link_ware_notes"></textarea>
<div>
<a href="javascript:next_link_ware()" class="btn">Next</a>
</div>
</div>



<div id="select_ware" title="Select ware to add to shop">
<div id="data_return"></div>
<div style="width:80%;" class="pull-right" id="ware_list">
  <?php
  
  ?>
</div>
<div style="width:20%;" class="pull-right">
<table class="table table-striped" width="100%"><thead><tr><th>Folders</th></tr></thead><tr><td>
	<ul class="catlist" style=" overflow:scroll">
	<li><a href="javascript:view_ware_cat_contents(0)" id="cat_0" class="catz">Ware</a>	
	<?php
	$html = "";
	$view->get_ware_cat_html_no_edit(0, $html);
	echo $html;
	?>
	</li>
	</ul>
</td></tr></table>
</div>


</div>

<div id="add_ware_to_woo" title="Add ware to Web Shop">
	<form id='add_ware_list'>
		<div>SKU</div>
		<div><input type="text"  id="woo_id" name="woo_id" style="width: 401px;"></div>
		<div>Name</div>
		<div><input type="text"  id="woo_name" name="woo_name" style="width: 401px;"></div>
		<div>Short Description</div>
		<div><textarea name="woo_short_description" id="woo_short_description" style="margin: 0px 0px 10px; width: 401px; height: 54px;"></textarea></div>
		<div>Description</div>
		<div><textarea name="woo_description" id="woo_description" style="margin: 0px 0px 10px; width: 401px; height: 144px;"></textarea></div>
		<table cellpadding=0 cellspacing=0>
		<tr>
			<td>
				<div>Price</div>
				<div><input type="text" id="woo_price" name="woo_price" style="width:190px;"></div>
			</td>
			<td style='padding-left:10px;'>
				<div>Qty</div>
				<div><input type="text" id="woo_qty" name="woo_qty" style="width:190px;"></div>
			</td>
		</tr>
		<tr>
			<td>
				<div>X mm</div>
				<div><input type="text" id="woo_x_mm" name="woo_x_mm" style="width:190px;"></div>
			</td>
			<td style='padding-left:10px;'>
				<div>Y mm</div>
				<div><input type="text" id="woo_y_mm" name="woo_y_mm" style="width:190px;"></div>
			</td>
		</tr>
		<tr>
			<td>
				<div>Z mm</div>
				<div><input type="text" id="woo_z_mm" name="woo_z_mm" style="width:190px;"></div>
			</td>
			<td style='padding-left:10px;'>
				<div>Grams</div>
				<div><input type="text" id="woo_grams" name="woo_grams" style="width:190px;"></div>
			</td>
		</tr>
		</table>
		<div>
			<div>Categories</div>
			<div>
				<?php
				$pc = $client->products->get_categories();
				foreach($pc->product_categories as $cat){
					echo "<div style='padding-bottom:5px;'><input type='checkbox' class='cats' style='margin-bottom:5px;' value='".$cat->id."'> " . $cat->name . "</div>";
				}
				?>
			</div>
		</div>
		
		
		<div>
			<p>Select Images</p>
			<div id="image_list">
				
			</div>
		</div>
	</form>
	<a href="javascript:upload_product();" class="btn pull-right">Add Product to Webstore</a>
</div>


<div id="new_ware" title="VIEW WARE">
	<div id="attachments" style="width:550px; float:right; display:none;">
		
		<div style="overflow:hidden;">
			Categories
			<div id="link_cats" style="margin-bottom:30px;">
		
			</div>
		</div>
		
		<div>
		Documents
		<div id="link_docs">
		
		</div>
		
		<div id="linked_document_list">
		
		</div>

		
		Recipes
		<div id="link_recipes"  style="clear:both;">
		
		</div>
		
		Firings
		<div id="link_firings"  style="clear:both;">
		
		</div>
	
		
		
	</div>
	
	
	</div>
	
	<div style="width:400px; float:left;" id="ware_start">
	<input type="hidden" id="ware_id" name="id" value="0">
	<div>Name</div>
	<div class="stda" id="iname" name="name"></div>
	<div>Price</div>
	<div class="stda" id="price" name="price"></div>
	<div>Qty</div>
	<div class="stda" id="qty" name="qty"></div>
	<div>Short Description</div>
	<div class="stda"  id="short_description" ></div>
	<div>Description</div>
	<div class="stda" id="description" ></div>
	<div>Artist Statement</div>
	<div class="stda" id="artiststatement"></div>
	</div>
	
</div>
<style>
.stda{
	font-size: larger;
	background-color: #efefef;
	padding:5px;
	margin-bottom:5px;
}
.img_select{
	width:198px;
	margin-right:10px;
	margin-bottom: 10px;
	float:left;
}
.img_select select{
	width:198px;
	margin-top: 5px;
}
</style>


<?php } ?>
<script>
function link_ware_to_db(){
	ware_id = $('#link_ware_id').val();
	ware_qty = $('#link_ware_qty').val();
	ware_price = $('#link_ware_price').val();
	ware_sold = $('#link_ware_sold').val();
	ware_notes = $('#link_ware_notes').val();
	shop_id = $('#shop_id').val();
	url = "?link_ware_to_webstore="+shop_id+"&ware_id="+ware_id+"&ware_price="+ware_price+"&ware_sold="+ware_sold+"&ware_qty="+ware_qty+"&ware_notes="+ware_notes;
	console.log(url);
	$.ajax(url).done(function(data){
		document.location.href="?add_webstore=" + $('#shop_id').val();
		$('#link_ware').dialog('close');
	});
}



function select_action_product(obj, id){
	val = $(obj).val();
	
	if(val == "delete"){
		if(confirm('Are you sure you want to delete this?')){
			url = "?add_webstore=" + $('#shop_id').val() + "&woo-update-product-status=" + val + "&woo-product-id=" + id ;
			$.ajax(url).done(function(data){
				document.location.href="?add_webstore=" + $('#shop_id').val();
			});
		}
	}
	if(val == "visible"){
		url = "?add_webstore=" + $('#shop_id').val() + "&woo-update-product-status=" + val + "&woo-product-id=" + id ;
		document.location.href=url;
	}
	
	if(val == "on_sale"){
		url = "?add_webstore=" + $('#shop_id').val() + "&woo-update-product-status=" + val + "&woo-product-id=" + id ;
		document.location.href=url;
	}
	
	if(val == "unlink"){
		if(confirm('Are you sure you want to unlink this?')){
			url = "?delete_ware_from_webstore_by_wooid=" + id;
			$.ajax(url).done(function(data){
				document.location.href="?add_webstore=" + $('#shop_id').val();
			});
		}
	}
}

function add_new_woo_product(id){
	url = "?load_ware="+id;
	$.ajax(url).done(function(data){
		console.log(data);
		x = $.parseJSON(data);
		$('#add_ware_to_woo').dialog('open');
		$('#link_ware').dialog('close');
		for(key in x){
			console.log(key);
			$('#woo_' + key).val(x[key]);
		}
		load_linked_items_for_woo_insert(id, 'ware', 'documents', 'link_docs');
	});			
}

function load_linked_items_for_woo_insert(parent_id, parent_table, item_table, id){
	
		url = "?get_link_items_json=true&isview=true&parent_id="+parent_id+"&parent_table="+parent_table+"&item_table=" + item_table;
		
		$.ajax(url).done(function(data){
			console.log(data);
			data = JSON.parse(data);
			html = ""
			for(i=0; i<data.length;i++){
				console.log(data[i].filename);
				html = html + "<div class='img_select' id='img_attach_"+data[i].id+"'><img src='"+data[i].filename+"'><div><select><option value='0'>not attached</option><option value='feature'>Feature Image</option><option value='other'>Other Image</option></select></div></div>";
			}
			$("#image_list").html(html);
		});
		
};

function delete_ware_from_shop(linkid){
	if(confirm("Are you sure?")){
		url = "?delete_ware_from_shop="+linkid;
	
		$.ajax(url).done(function(data){
			document.location.href="?add_webstore=" + $('#shop_id').val();
		});
		
		
	}
}



function update_link_ware_to_db(){
	link_id = $('#shop_link_ware_id').val();
	ware_qty = $('#update_link_ware_qty').val();
	ware_price = $('#update_link_ware_price').val();
	ware_sold = $('#update_link_ware_sold').val();
	ware_notes = $('#update_link_ware_notes').val();
	url = "?update_link_ware_to_webstore="+link_id+"&ware_price="+ware_price+"&ware_sold="+ware_sold+"&ware_qty="+ware_qty+"&ware_notes="+ware_notes;
	console.log(url);
	$.ajax(url).done(function(data){
		document.location.href="?add_shop=" + $('#shop_id').val();
		$('#update_link_ware').dialog('close');
	});
}



function edit_link_ware_from_shop(id, qty, sold, price, notes){
	$('#update_link_ware').dialog('open');
	$('#shop_link_ware_id').val(id);
	$('#update_link_ware_price').val(price);
	$('#update_link_ware_qty').val(qty);
	$('#update_link_ware_sold').val(sold);
	$('#update_link_ware_notes').val(notes);
}

function link_ware(id){
	$('#select_ware').dialog('close');
	$('#link_ware').dialog('open');
	$('#link_ware_id').val(id);
}

function link_ware_to_woo(id){
	url = '?link_ware_to_webstore=<?=$view->url["add_webstore"];?>&ware_id=' + $('#link_ware_id').val() + '&woo_id=' + id;
	$.ajax(url).done(function(data){
		console.log(data)
	    document.location.href="?add_webstore=" + $('#shop_id').val();
	});
}

function iload_ware(id){
		url = "?load_ware="+id;
		$.ajax(url).done(function(data){
			
			x = $.parseJSON(data);
			$('#new_ware').dialog('open');
			$('#ware_id').html(x['id']);
			$('#iname').html(x['name']);
			$('#description').html(x['description']);
			$('#short_description').html(x['short_description']);
			$('#artiststatment').html(x['artiststatment']);
			$('#qty').html(x['qty']);
			$('#price').html(x['price']);
			$("#next").hide();
			$("#update").show();
			$("#attachments").show();
			// Documents
			
			load_linked_items(id, 'ware', 'documents', 'link_docs');
			load_linked_items(id, 'ware', 'warecats', 'link_cats');
			load_linked_items(id, 'ware', 'recipe', 'link_recipes');
			load_linked_items(id, 'ware', 'firings', 'link_firings');
			
		});
		
}



function view_ware_cat_contents(id){
		$("#ware_list").load("?view_ware_list="+id+"&linker=true");
		current_cat = id;
		$(".catz").removeClass('selected')
		$("#cat_"+id).addClass('selected');
		
	}

function add_product(){
	view_ware_cat_contents(0);
	$('#select_ware').dialog('open');
}

function save_shop(){
	if($("#name").val()==""){
		alert("A shop Name is required!");
	}else{
			// do the Json
			if($('#type').val()=="woocommerce"){
				linkage_json = {};
				linkage_json.api_url = $("#api_url").val();
				linkage_json.consumer_key = $("#consumer_key").val();
				linkage_json.consumer_secret = $("#consumer_secret").val();
				$("#linkage_json").val(JSON.stringify(linkage_json));
			}
			
			d = $("#shop_data").serialize();
			
			url = "?update_webstore=true&"+d;
			console.log(url);
			$.ajax(url).done(function(data){
				console.log(data);
				document.location.href = "?add_webstore="+data;
			});
		
	}
	
}

function upload_product(){
	x = $("#add_ware_list").serialize();
	//
	cats = [];
	$(".cats").each(function(){
		if($(this).is(":checked")){
			cats.push($(this).val());
		}
	});
	
	image_select = [];
	$(".img_select").each(function(){
		if($(this).find('select').val() == "feature"){
			ob = {};
			ob.src = $(this).find('img').attr('src');
			ob.feature = true;
			image_select.push(ob);
		}
		if($(this).find('select').val() == "other"){
			ob = {};
			ob.src = $(this).find('img').attr('src');
			ob.feature = false;
			image_select.push(ob);
		}
		
	});
	url = "?upload_woo_product=true&add_webstore=<?=$view->url["add_webstore"];?>&" + x + "&img=" + JSON.stringify(image_select) + "&cats=" + JSON.stringify(cats);
	show_loader();
	$.ajax(url).done(function(data){
		hide_loader();
		document.location.href = "?add_webstore=<?=$view->url["add_webstore"];?>";
	});	
}


function load_linked_items(parent_id, parent_table, item_table, id){
	
		url = "?get_link_items=true&isview=true&parent_id="+parent_id+"&parent_table="+parent_table+"&item_table=" + item_table;
		$.ajax(url).done(function(data){
			$("#" + id).html(data);
		})
		
	};
	$('#update_link_ware').dialog({
		  autoOpen: false
	});

	$('#link_ware').dialog({
		  autoOpen: false, width:1000, height:600, modal: true
	});
	$('#select_ware').dialog({
		  autoOpen: false, width:1000, height:600, modal: true
	});
	$('#new_ware').dialog({
		  autoOpen: false, width:1000, height:600, modal: true
	});
	$('#add_ware_to_woo').dialog({
		  autoOpen: false, width:450, height:600, modal: true
	});
	$("#select_type").val($('#type').val());
	$('._details').hide();$('#'+$('#type').val()+'_details').show();

$( "#tabs" ).tabs();
</script>
