<script>
	$(function(){
		
		
		$('#add_document').dialog({
		  autoOpen: false, width: 900
		});
		view_cat_contents(0);
		load_linked_document_list();
	});
	
	function add_document(){
		$('#newdoc').hide();
		$('#existdoc').hide();
		$('#docquestion').show();
		$('#add_document').dialog('open');
	}
	
	function view_cat_contents(id){
		$("#document_list").load("?preview_docs="+id);
		$(".catz").removeClass('selected')
		$("#cat_"+id).addClass('selected');
	}
	function load_linked_document_list(){
		url = "?get_link_items=true&parent_id=<?=$parent_id?>&parent_table=<?=$parent_table?>&item_table=documents";
		$.ajax(url).done(function(data){
				
			$("#linked_document_list").html(data);
		});
	}	

	function link_document(id){
	//$parent_table, $parent_id, $item_table, $item_id
		url = "?link_item=" + id + "&item_table=documents&parent_table=<?=$parent_table?>&parent_id=<?=$parent_id?>";
		$.ajax(url).done(function(data){
			$('#add_document').dialog('close');
			load_linked_document_list();
		});
	}

	function remove_link(id){
		url = "?remove_link_item=" + id ;
		$.ajax(url).done(function(data){
			load_linked_document_list();
		});
	}

</script>

<style>
.selected{
	background-color: #0088cc;
	color: #fff!important;
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
		<div style="width:70%;" class="pull-right" id="document_list">
   			Document list window
		</div>
		<div style="width:30%;" class="pull-right">
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

