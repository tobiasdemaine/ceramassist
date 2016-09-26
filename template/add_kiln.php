<?php
$view->set_section('firings');

if($view->url["add_kiln"] != "new"){
	$kiln = $view->get_kiln($view->url["add_kiln"]);
	$fire = $kiln;
}else{
	$fire["id"] = 0;
	$fire["name"] = "";
	$fire["type"] = "";
	$fire["description"] = "";
	$fire["notes"] = "";
	$fire["programmable"] = "0";
}
?>
<h4>Kiln <?php 
	if(isset($kiln)){?><a href="javascript:add_document();" class='btn btn-info btn-sm pull-right' style="font-weight:300;">Add Documents</a><?php } ?></h4>
<div style="width:20%; display:table-cell; vertical-align:top; ">
	<div>Name</div>
	<input type="text" id="name" value="<?=$fire["name"]?>">
	<div>Type</div>
	<input type="hidden" id="id" value="<?=$fire["id"]?>">
	<input type="text" id="type" value="<?=$fire["type"]?>">

	<div>Description</div>
	<textarea id="description"><?=$fire["description"]?></textarea>
	<div>Notes</div>
	<textarea id="notes"><?=$fire["notes"]?></textarea>
	<div>Computer Controlled &nbsp;&nbsp;<input type="checkbox" id="programmable" <?php if($fire["programmable"]==1){?>checked<?php } ?>></div>
	<br>
	<br>
	<a href="javascript:save_firing()" class="btn">Save</a>
</div>


<div style="width:80%; display:table-cell;" id="firedocs ">
<?php 
	if(isset($kiln)){
		$parent_id = $kiln['id'];
		$parent_table = "kilns";
		include('template/select_document.php');
	}
?>
</div>

<script>
function save_firing(){
	if($("#name").val()==""){
		alert("A Kiln Name is required!");
	}else{
		if($("#type").val()==""){
			alert("Type is required!");
		}else{
			url = "?update_kiln="+$("#id").val()+"&name="+escape($("#name").val())+"&type="+escape($("#type").val())+"&description="+escape($("#description").val())+"&notes="+escape($("#notes").val())+"&programmable="+escape($("#programmable").is(":checked"));
			$.ajax(url).done(function(data){
				//console.log(data);
				document.location.href = "?firings=true";
			});
		}
	}
}
</script>