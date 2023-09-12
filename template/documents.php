<?php
$view->set_section('documents');
?>
<script>
	function new_category(){
		// show new cat drop down
		$('#catid').val(0);
		$('#name').val('');
		$('#parent').val('');
		$('#parent').val('');
		$('#cataddbutton').html('Add');
		$('#new_edit').dialog('open');
	}
	
	function new_document(){
		$('#upload').dialog('open');
	}
	
	function add_cat(){
		if($('#catid').val()!=0){
			id = $('#catid').val();
			parent = $("#parent").val();
			name = $("#name").val();
			url = "?add_doc_cat="+id+"&parent="+parent+"&name="+encodeURIComponent(name);
			$.ajax(url).done(function(data){
				$('#new_edit').dialog('close');
				$("#data_return").html(data);
			});
		}else{
	
			parent = $("#parent").val();
			name = $("#name").val();
			url = "?add_doc_cat=true&parent="+parent+"&name="+encodeURIComponent(name);
			$.ajax(url).done(function(data){
				$('#new_edit').dialog('close');
				$("#data_return").html(data);
			});
		}
	}
	function delete_document(id, parent){
		//
		if(confirm("Are you sure?")){
			url = "?delete_document="+id;
			$.ajax(url).done(function(data){
				view_cat_contents(parent);
			});
		}
	}

	$(function(){
		$('#new_edit').dialog({
		  autoOpen: false
		});
		$('#upload').dialog({
		  autoOpen: false
		});
		/*$("#uploadfile").customFileInput({
        	button_position : 'right'
   		 });*/
		view_cat_contents(0);
	});
	
	function view_cat_contents(id){
		$("#document_list").load("?view_docs="+id);
		$(".catz").removeClass('selected')
		$("#cat_"+id).addClass('selected');
	}
	
	function remove_cat(id){
		if(confirm("Are you sure? Orphan documents will be placed in documents root folder")){
			url = "?delete_document_cat="+id;
			$.ajax(url).done(function(data){
				document.location.href = "?documents=true"; 
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
<div style="overflow:hidden; text-align:right; margin-bottom:10px;"><a href="javascript:void(0)" onclick="new_category();" class='btn btn-info btn-sm' style="font-weight:300;">New Category</a> <a href="javascript:new_document();" style="font-weight:300;" class='btn  btn-info btn-sm'>New Document</a></span>
</div>
<div id="data_return"></div>
<div style="width:70%;" class="pull-right" id="document_list">
   Document list window
</div>
<div style="width:30%;" class="pull-right">
<table class="table table-striped" width="100%"><thead><tr><th>Folders</th></tr></thead><tr><td>
	<ul class="catlist" style=" overflow:scroll">
	<li><a href="javascript:view_cat_contents(0)" id="cat_0" class="catz">Documents</a>	<?php
	$html = "";
	$view->get_document_cat_html(0, $html);
	echo $html;
	?>
	</li>
	</ul>
</td></tr></table>
</div>

<div id="upload" title="Add File">
	<form action="?upload_document=true" method="post" enctype="multipart/form-data">
	
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
			?>
		</select>
	</div>
	<div>description</div>
	<div><textarea type="text" name="description"></textarea></div>
	<div>
	<input type="submit" class='btn  btn-info btn-sm' value='Add Document'>
	</div>
	</form>
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
			$view->get_document_cat_options(0, $html, "/");
			echo $html;
			?>
			?>
		</select>
	</div>
	<div>
	<a href="javascript:add_cat();" ID="cataddbutton" class='btn  btn-info btn-sm'>Add</a>
	</div>
	
</div>

