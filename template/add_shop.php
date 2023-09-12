<?php
$view->set_section('distribution');

if($view->url["add_shop"] != "new"){
	$shop = $view->get_shop($view->url["add_shop"]);
	$_shop = $shop;
}else{
	$_shop["id"] = 0;
	$_shop["name"] = "";
	$_shop["address"] = "";
	$_shop["contact"] = "";
	$_shop["phone1"] = "";
	$_shop["phone2"] = "";
	$_shop["email"] = "";
	$_shop["url"] = "";
	$_shop["description"] = "";
	$_shop["notes"] = "";
	
}
?>
<h4>Shop <?php 
	if(isset($shop)){?><a href="javascript:add_document();" class='btn  btn-sm pull-right' style="margin-left:10px; font-weight:300;">Add Documents</a> <a href="javascript:add_product();" class='btn  btn-sm pull-right' style="font-weight:300;">Add Ware</a><?php } ?></h4>
<div style="width:20%; display:table-cell; vertical-align:top; ">
	<form id="shop_data">
	<input type="hidden" name="id" id="shop_id" value="<?=$_shop["id"]?>">
	<div>Name</div>
	<input type="text" name="name" id="name" value="<?=$_shop["name"]?>">
	
	<div>Address</div>
	<input type="text" name="address" value="<?=$_shop["address"]?>">

	<div>Contact</div>
	<input type="text" name="contact" value="<?=$_shop["contact"]?>">
	
	<div>Phone 1</div>
	<input type="text" name="phone1" value="<?=$_shop["phone1"]?>">
	
	<div>Phone 2</div>
	<input type="text" name="phone2" value="<?=$_shop["phone2"]?>">

	<div>email</div>
	<input type="text" name="email" value="<?=$_shop["email"]?>">
	
	<div>url</div>
	<input type="text" name="url" value="<?=$_shop["url"]?>">


	<div>Description</div>
	<textarea id="description"><?=$_shop["description"]?></textarea>
	
	
	
	<div>Notes</div>
	<textarea id="notes"><?=$_shop["notes"]?></textarea>
	
	
	<br>
	<br>
	<a href="javascript:save_shop()" class="btn">Save</a>
	</form>
</div>
<?php if(isset($shop)){ ?>
<div style="width:70%; display:table-cell; ">
<div id="tabs">
  <ul>
    <li><a href="#tabs-1">Products</a></li>
    
    <li><a href="#tabs-3">Documents</a></li>
    
   </ul>
  	
  	<div id="tabs-1">
  	<?php
  	 echo $view->get_shop_ware_list($shop['id']);
  	?>		
  	
  	</div>
  	
  	<div id="tabs-3">
		<div style="width:100%;" id="shopdocs ">
		<?php 
		if(isset($shop)){
			$parent_id = $shop['id'];
			$parent_table = "shops";
			include('template/select_document.php');
		}
		?>
		</div>
	</div>
</div>
</div>

<div id="link_ware" title="Ware Details">
<input id="link_ware_id" type="hidden">
<div>Price </div>
<input type="text" id="link_ware_price" value="">
<div>Consignment Qty</div>
<input type="text" id="link_ware_qty" value="">
<div>Qty Sold</div>
<input type="text" id="link_ware_sold" value="">
<div>Notes</div>
<textarea id="link_ware_notes"></textarea>
<div>
<a href="javascript:link_ware_to_db()" class="btn">Link</a>
</div>
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
<a href="javascript:update_link_ware_to_db()" class="btn">Link</a>
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
	<li><a href="javascript:view_ware_cat_contents(0)" id="cat_0" class="catz">Ware</a>	<?php
	$html = "";
	$view->get_ware_cat_html_no_edit(0, $html);
	echo $html;
	?>
	</li>
	</ul>
</td></tr></table>
</div>


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
	<div>X mm</div>
	<div class="stda" id="x_mm" name="x_mm"></div>
	<div>Y mm</div>
	<div class="stda" id="y_mm" name="y_mm"></div>
	<div>Z mm</div>
	<div class="stda" id="z_mm" name="z_mm"></div>
	<div>Grams</div>
	<div class="stda" id="grams" name="grams"></div>
	
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
	url = "?link_ware_to_shop="+shop_id+"&ware_id="+ware_id+"&ware_price="+ware_price+"&ware_sold="+ware_sold+"&ware_qty="+ware_qty+"&ware_notes="+ware_notes;
	console.log(url);
	$.ajax(url).done(function(data){
		document.location.href="?add_shop=" + $('#shop_id').val();
		$('#link_ware').dialog('close');
	});
}


function delete_ware_from_shop(linkid){
	if(confirm("Are you sure?")){
		url = "?delete_ware_from_shop="+linkid;
	
		$.ajax(url).done(function(data){
			document.location.href="?add_shop=" + $('#shop_id').val();
		});
		
		
	}
}

function update_link_ware_to_db(){
	link_id = $('#shop_link_ware_id').val();
	ware_qty = $('#update_link_ware_qty').val();
	ware_price = $('#update_link_ware_price').val();
	ware_sold = $('#update_link_ware_sold').val();
	ware_notes = $('#update_link_ware_notes').val();
	url = "?update_link_ware_to_shop="+link_id+"&ware_price="+ware_price+"&ware_sold="+ware_sold+"&ware_qty="+ware_qty+"&ware_notes="+ware_notes;
	$.ajax(url).done(function(data){
		document.location.href="?add_shop=" + $('#shop_id').val();
		//$('#update_link_ware').dialog('close');
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
	url = "?load_ware="+id;
	$.ajax(url).done(function(data){
		x = $.parseJSON(data);
		$('#link_ware_price').val(x['price']);
		$('#link_ware_qty').val(1);
		$('#link_ware_sold').val(0);
	});
}

function iload_ware(id){
		url = "?load_ware="+id;
		$.ajax(url).done(function(data){
			console.log(data);
			x = $.parseJSON(data);
			$('#new_ware').dialog('open');
			$('#ware_id').html(x['id']);
			$('#iname').html(x['name']);
			$('#description').html(x['description']);
			$('#short_description').html(x['short_description']);
			$('#artiststatment').html(x['artiststatment']);
			$('#qty').html(x['qty']);
			$('#x_mm').html(x['x_mm']);
			$('#y_mm').html(x['y_mm']);
			$('#z_mm').html(x['z_mm']);
			$('#grams').html(x['grams']);
			$('#price').html(x['price']);
			$("#next").hide();
			$("#update").show();
			$("#attachments").show();
			// Documents
			
			load_linked_items(id, 'ware', 'documents', 'link_docs');
			load_linked_items(id, 'ware', 'warecats', 'link_cats');
			load_linked_items(id, 'ware', 'recipe', 'link_recipes');
			load_linked_items(id, 'ware', 'firings', 'link_firings');
			
			
			// load firings
			
			
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
			d = $("#shop_data").serialize();
			url = "?update_shop=true&"+d;
			console.log(url);
			$.ajax(url).done(function(data){
				console.log(data);
				document.location.href = "?add_shop="+data;
			});
		
	}
	
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
		  autoOpen: false
	});
	$('#select_ware').dialog({
		  autoOpen: false,
		  width: 950
	});
	$('#new_ware').dialog({
		  autoOpen: false, width:1000, height:600, modal: true
		});

$( "#tabs" ).tabs();
</script>