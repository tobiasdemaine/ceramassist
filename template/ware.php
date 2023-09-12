<?php
$view->set_section('ware');
?>


<script>
	function new_category(){
		// show new cat drop down
		$('#catid').val(0);
		$('#name').val('');
		$('#parent').val('');
		$('#cataddbutton').html('Add');
		$('#new_edit').dialog('open');
	}
	
	function add_link_cat(){
		if($('#catid').val()!=0){
			id = $('#catid').val();
			parent = $("#parent").val();
			name = $("#name").val();
			url = "?add_ware_cat="+id+"&parent="+parent+"&name="+encodeURIComponent(name);
			$.ajax(url).done(function(data){
				$('#new_edit').dialog('close');
				$("#data_return").html(data);
			});
		}else{
	
			parent = $("#parent").val();
			name = $("#name").val();
			url = "?add_ware_cat=true&parent="+parent+"&name="+encodeURIComponent(name);
			$.ajax(url).done(function(data){
				$('#new_edit').dialog('close');
				$("#data_return").html(data);
			});
		}
	}

	
	$(function(){
		$('#new_edit').dialog({
		  autoOpen: false
		});
		$('#new_ware').dialog({
		  autoOpen: false, width:1000, modal: true
		});
		
		$('#add_document').dialog({
		  autoOpen: false, width: 900, modal: true
		});
		
		$('#add_recipe').dialog({
		  autoOpen: false, width: 900, modal: true
		});

		$('#add_firing').dialog({
		  autoOpen: false, width: 900, modal: true
		});
	
		view_ware_cat_contents(0);
	});
	
	
	function iload_ware(id){
		url = "?load_ware="+id;
		$.ajax(url).done(function(data){
			console.log(data);
			x = $.parseJSON(data);
			
			$('#new_ware').dialog('open');
			$('#ware_id').val(x['id']);
			$('#iname').val(x['name']);
			$('#description').val(x['description']);
			$('#short_description').val(x['short_description']);
			$('#artiststatment').val(x['artiststatment']);
			$('#x_mm').val(x['x_mm']);
			$('#y_mm').val(x['y_mm']);
			$('#z_mm').val(x['z_mm']);
			$('#grams').val(x['grams']);
			$('#qty').val(x['qty']);
			$('#price').val(x['price']);
			$("#next").hide();
			$("#update").show();
			$("#attachments").show();
			// Documents
			
			load_linked_items(id, 'ware', 'documents', 'link_docs');
			load_linked_items(id, 'ware', 'warecats', 'link_cats');
			load_linked_items(id, 'ware', 'recipe', 'link_recipes');
			load_linked_items(id, 'ware', 'firings', 'link_firings');
			
			$("#ui-id-2").html("EDIT WARE");
			// load recipes
			
			// load firings
			
			
		});
		
	}
	
	function add_document(){
		$('#newdoc').hide();
		$('#existdoc').hide();
		$('#docquestion').show();
		$('#add_document').dialog('open');
	}
	
	function add_recipe(){
		$('#add_recipe').dialog('open');
	}

	function add_firing(){
		$('#add_firing').dialog('open');
	}

	var current_cat=0;
	function view_ware_cat_contents(id){
		$("#ware_list").load("?view_ware_list="+id);
		current_cat = id;
		$(".catz").removeClass('selected')
		$("#cat_"+id).addClass('selected');
		
	}
	
	function view_cat_contents(id){
		$("#document_list").load("?preview_docs="+id);
	}
	
	function remove_cat(id){
		if(confirm("Are you sure? Orphan documents will be placed in documents root folder")){
			url = "?delete_ware_cat="+id;
			$.ajax(url).done(function(data){
				document.location.href = "?ware=true"; 
			});
		}
	}
	
	function new_ware(){
		$('#new_ware').dialog('open');
		// clear the form
		//$("#ware_start_form").clear();
		$("#ware_id").val('0');
		$("#ui-id-2").html("NEW WARE");
		$('#iname').val('');
		$('#description').val('');
		$('#short_description').val('');
		$('#artiststatment').val('');
		$('#qty').val('');
		$('#price').val('');
		$('#x_mm').val('');
		$('#y_mm').val('');
		$('#z_mm').val('');
		$('#grams').val('');
		$("#next").show();
		$("#update").hide();
		$("#attachments").hide();
	}
	
	function delete_ware(id){
		if(confirm("Are you sure?")){
			url = "?delete_ware="+id;
			$.ajax(url).done(function(data){
				$("#ware_list").load("?view_ware_list="+current_cat);
			});
		}
	}
	
	function edit_cat(id, parent, name){
		$('#catid').val(id);
		$('#name').val(name);
		$('#parent').val(parent);
		$('#cataddbutton').html('update');
		$('#new_edit').dialog('open');
	}
	
	function add_ware_first(){
		if($("#iname").val() == ""){
			alert("A name is needed to continue.");
		}else{
			data = $("#ware_start_form").serialize();
			url = "?add_new_ware=true&" + data;
			$.ajax(url).done(function(data){
				$("#ware_id").val(data);
				$("#next").hide();
				$("#update").show();
				$("#attachments").show();
				load_linked_items(data, 'ware', 'documents', 'link_docs');
				load_linked_items(data, 'ware', 'warecats', 'link_cats');
				load_linked_items(data, 'ware', 'recipe', 'link_recipes');
				load_linked_items(data, 'ware', 'firings', 'link_firings');
			//	#("#add_ware").attr("title", "Edit Ware");	
			});
		}
	}
	function update(){
		if($("#ware_id")!=0){
			// we can save
			data = $("#ware_start_form").serialize();
			url = "?update_ware=true&" + data;
			console.log(url);
			$.ajax(url).done(function(data){

				console.log(data)
			});
		}
	}
	
	function remove_link(id){
		url = "?remove_link_item=" + id ;
		$.ajax(url).done(function(data){
			load_linked_items($("#ware_id").val(), 'ware', 'documents', 'link_docs');
		});
	}
	
	function remove_warecat_link(id){
		url = "?remove_link_item=" + id ;
		$.ajax(url).done(function(data){
			load_linked_items($("#ware_id").val(), 'ware', 'warecats', 'link_cats');
		});
	}
	
	function remove_recipe_link(id){
		url = "?remove_link_item=" + id ;
		$.ajax(url).done(function(data){
			load_linked_items($("#ware_id").val(), 'ware', 'recipe', 'link_recipes');
		});
	}
	
	function remove_firing_link(id){
		url = "?remove_link_item=" + id ;
		$.ajax(url).done(function(data){
			load_linked_items($("#ware_id").val(), 'ware', 'firings', 'link_firings');
		});
	}
	
	function link_document(id){
	//$parent_table, $parent_id, $item_table, $item_id
		url = "?link_item=" + id + "&item_table=documents&parent_table=ware&parent_id="+$("#ware_id").val();
		$.ajax(url).done(function(data){
			$('#add_document').dialog('close');
			//load_linked_document_list();
			load_linked_items($("#ware_id").val(), 'ware', 'documents', 'link_docs');
			
		});
	}
	
	function link_recipe(id){
	//$parent_table, $parent_id, $item_table, $item_id
		url = "?link_item=" + id + "&item_table=recipe&parent_table=ware&parent_id="+$("#ware_id").val();
		$.ajax(url).done(function(data){
			$('#add_recipe').dialog('close');
			//load_linked_document_list();
			load_linked_items($("#ware_id").val(), 'ware', 'recipe', 'link_recipes');
			
		});
	}
	
	function link_firing(id){
	//$parent_table, $parent_id, $item_table, $item_id
		url = "?link_item=" + id + "&item_table=firings&parent_table=ware&parent_id="+$("#ware_id").val();
		$.ajax(url).done(function(data){
			$('#add_firing').dialog('close');
			//load_linked_document_list();
			load_linked_items($("#ware_id").val(), 'ware', 'firings', 'link_firings');
			
		});
	}
	
	
	function load_linked_items(parent_id, parent_table, item_table, id){
	
		url = "?get_link_items=true&parent_id="+parent_id+"&parent_table="+parent_table+"&item_table=" + item_table;
		
		$.ajax(url).done(function(data){
			$("#" + id).html(data);
		})
		
	};
	
	
	function add_cat(){
		url = "?link_item=" + $("#cat_sel").val() + "&item_table=warecats&parent_table=ware&parent_id="+$("#ware_id").val();
		console.log(url);
		$.ajax(url).done(function(data){
			$('#add_document').dialog('close');
			load_linked_items($("#ware_id").val(), 'ware', 'warecats', 'link_cats');
			$('#cat_add').hide();
		});

	}
</script>
<style>
.selected{
	background-color: #0088cc;
	color: #fff;
}
.catlist li{
	margin:0px;
	padding:0px;
	
}
ul{
list-style: none;
}
.catlist li a{
	padding:3px;
	white-space: nowrap;
	border-radius:5px;
}
.catlist{
	margin-left: 0px;
}

</style>
<div style="overflow:hidden; text-align:right; margin-bottom:10px;"><a href="javascript:void(0)" onclick="new_category();" class='btn btn-info btn-sm' style="font-weight:300;">New Category</a> <a href="javascript:new_ware();" style="font-weight:300;" class='btn  btn-info btn-sm'>Add New Ware</a></span>
</div>
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
	$view->get_ware_cat_html(0, $html);
	echo $html;
	?>
	</li>
	</ul>
</td></tr></table>
</div>

<div id="new_ware" title="NEW WARE">
	<div id="attachments" style="width:550px; float:right; display:none;">
		
		<div style="overflow:hidden;">
			<a href="javascript:$('#cat_add').toggle();" style="color:#0088cc; float:right;">+ Categories</a>
			<div style="clear:both; text-align: center; border-radius:3px; background:#efefef; padding-top:10px; display:none;" id="cat_add" >
			<select id="cat_sel" onchange="">
			<option value="0" selected>/</option>
			<?php
			$html = "";
			$view->get_ware_cat_options(0, $html, "/");
			echo $html;
			?>
			?>
			</select>
			<a href="javascript:add_cat();" class='btn  btn-info btn-sm' style="color:white; margin-bottom:10px;">Add cat</a>
			</div>
			<div id="link_cats" style="margin-bottom:30px;">
		
			</div>
		</div>
		
		<div>
		<a href="javascript:add_document();" style="color:#0088cc; float:right;">+ Documents</a>
		<div id="link_docs">
		
		</div>
		
		<iframe id="hiddeniframe" name="hiddeniframe" style="display:none;"></iframe>
		<div id="linked_document_list">
		
		</div>

		<div id="add_document" title="Add a document">
			<center id="docquestion"><a href="javascript:$('#newdoc').show();$('#docquestion').hide();" class='btn  btn-info btn-sm'>New</a> or <a href="javascript:$('#existdoc').show();$('#docquestion').hide();" class='btn  btn-info btn-sm'>Existing</a></center>
	
			<form action="?upload_document_module=true&parent_table=<?=$parent_table?>&parent_id=<?=$parent_id?>" method="post" target="hiddeniframe" id="newdoc" style="display:none;" enctype="multipart/form-data">
	
				<div><input type="file" id="uploadfile" name="uploadfile"></div>
				<div>Parent</div>
				<div>
					<select id="parent" name="parent" onchange="">
					<option value="0" selected>/</option>
					<?php
					$html = "";
					$view->get_document_cat_options(0, $html, "/");
					echo $html;
					?>
					</select>
				</div>
				<div>description</div>
				<div><textarea type="text" name="description"></textarea></div>
				<div>
					<input type="submit" class='btn  btn-info btn-sm' value='Add Document'>
				</div>
			</form>
			<div id="existdoc" style="display:none">
				<div id="data_return"></div>
				<div style="width:80%;" class="pull-right" id="document_list">
   				Document list window
				</div>
					<div style="width:20%;" class="pull-right">
						<table class="table table-striped" width="100%"><thead><tr><th>Folders</th></tr></thead><tr><td>
							<ul class="catlist" style=" overflow:scroll">
							<li><a href="javascript:view_cat_contents(0)" id="cat_0" class="catz">Documents</a>	<?php
							$html = "";
							$view->get_document_cat_html_lite(0, $html);
							echo $html;
							?>
							</li>
							</ul>
						</td></tr></table>
					</div>
				</div>
			</div>
		</div>
		
		<div>
		<a href="javascript:add_recipe();" style="color:#0088cc; float:right;">+ Recipes</a>
		<div id="link_recipes"  style="clear:both;">
		
		</div>
		
		<div id="add_recipe" title="Link Recipe">
			<div  class="pull-right" id="recipe_list">
   								<table class="table table-striped">
				<thead>
					<tr>
					<th>Type</th>
					<th>Name</th>
					<th>Description</th>
					</tr>
				</thead>
				<tbody id='rlist'>
			<?php
			$samples = $view->get_all_recipes();

			foreach($samples as $sample){
				
				echo "<tr >";
				echo "<td width='10%' nowrap='nowrap'>".$sample["type"]."</td>";
				echo "<td width='10%' nowrap='nowrap'>".$sample["name"]."</td>";
				echo "<td>".$sample["description"]."</td>";
				echo "<td width='10%' nowrap='nowrap'><a href='javascript:link_recipe(".$sample["id"].");'>Link</a></td>";
				echo "</tr>";
				
			}
			?>
			</tbody>
			</table>		
   				
			</div>
		</div>
		</div>
		
		<div>
		<a href="javascript:add_firing();" style="color:#0088cc; float:right;">+ Firings</a>
		<div id="link_firings"  style="clear:both;">
		
		</div>
		
		<div id="add_firing" title="Link Firing">
			<div  class="pull-right" id="firing_list">
   								<table class="table table-striped">
				<thead>
		<tr>
		<th>date</th>
		<th>kiln</th>
		<th>firing type</th>
		<th>atmosphere</th>
		<th>Action</th>
		</tr>
	</thead>
				<tbody id='rlist'>
			<?php
			$samples = $view->get_all_firings();

			foreach($samples as $sample){
				
				echo "<tr>";
				echo "<td width='10%' nowrap='nowrap'>".$sample["date"]."</td>";
				echo "<td  nowrap='nowrap'>".$sample["kiln"]."</td>";
				echo "<td>".$sample["firing_type"]."</td>";
				echo "<td>".$sample["atmosphere"]."</td>";
				echo "<td width='10%' nowrap='nowrap'><a href='javascript:link_firing(".$sample["id"].");'>Link</a></td>";
				echo "</tr>";
				
			}
			?>
			</tbody>
			</table>		
   				
			</div>
		</div>
		</div>
		
	</div>
	<div style="width:400px; float:left;" id="ware_start">
	<form id="ware_start_form">
	<input type="hidden" id="ware_id" name="id" value="0">
	<div>Name</div>
	<div><input type="text" id="iname" name="name" style="width:400px;"></div>
	<table cellpadding=0 cellspacing=0>
		<tr>
			<td>
				<div>Price</div>
				<div><input type="text" id="price" name="price" style="width:190px;"></div>
			</td>
			<td style='padding-left:10px;'>
				<div>Qty</div>
				<div><input type="text" id="qty" name="qty" style="width:190px;"></div>
			</td>
		</tr>
		<tr>
			<td>
				<div>X mm</div>
				<div><input type="text" id="x_mm" name="x_mm" style="width:190px;"></div>
			</td>
			<td style='padding-left:10px;'>
				<div>Y mm</div>
				<div><input type="text" id="y_mm" name="y_mm" style="width:190px;"></div>
			</td>
		</tr>
		<tr>
			<td>
				<div>Z mm</div>
				<div><input type="text" id="z_mm" name="z_mm" style="width:190px;"></div>
			</td>
			<td style='padding-left:10px;'>
				<div>Grams</div>
				<div><input type="text" id="grams" name="grams" style="width:190px;"></div>
			</td>
		</tr>
	</table>
	
	
	
	
	
	
	<div>Short Description</div>
	<div><textarea type="text" id="short_description" name="short_description" style="width:400px; height:30px;"></textarea></div>
	<div>Description</div>
	<div><textarea type="text" name="description"  id="description" style="width:400px; height:100px;"></textarea></div>
	<div>Artist Statement</div>
	<div><textarea type="text" id="artiststatement"  name="artiststatement" style="width:400px; height:100px;"></textarea></div>
	<div><a href="javascript:add_ware_first()" class='btn btn-info btn-sm' id="next">Next</a>
	<a href="javascript:update();" class='btn btn-info btn-sm' id="update" style="display:none;">Update</a>
	</form>
	</div>
	</div>
	
</div>

<div id="new_edit" title="Category Manager">
	<div>Name</div>
	<div><input type="text" id="name"><input type="hidden" id="catid"></div>
	<div>Parent</div>
	<div>
		<select id="parent" onchange="">
			<option value="0" selected>/</option>
			<?php
			$html = "";
			$view->get_ware_cat_options(0, $html, "/");
			echo $html;
			?>
			?>
		</select>
	</div>
	<div>
	<a href="javascript:add_link_cat();" ID="cataddbutton" class='btn  btn-info btn-sm'>Add</a>
	</div>
	
</div>
