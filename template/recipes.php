<?
$view->set_section('recipes');


$samples = $view->get_all_recipes();
$types = array();
$data_sets = array();
foreach($samples as $sample){
	if(!in_array($sample['type'], $types)){
		array_push($types, $sample['type']);
	}
	if(!in_array($sample['data_set'], $data_sets)){
		array_push($data_sets, $sample['data_set']);
	}
}

?>
<a href="?new_recipe=true"  class='btn btn-info btn-sm pull-right'>New Recipe</a>
<!-- <a href="?recipes=true"   class='btn btn-info btn-sm'>All</a> <a href="?recipes=true&type=Clay Body"   class='btn btn-info btn-sm'>Clay Body</a> <a href="?recipes=true&type=slip"   class='btn btn-info btn-sm'>Slip</a> <a href="?recipes=true&type=glaze"   class='btn btn-info btn-sm'>Glaze</a> -->

<select id="allsets" style="" onchange="document.location.href = '?recipes=true&data_set='+$(this).val()+'&type='+$('#alltype').val();">
		<option value="">All</option>
<?
foreach ($data_sets as $type){
	$selected = "";
	if(isset($view->url["data_set"])){
		if($view->url["data_set"] == $type){
			$selected = "selected";
		}
	}
	?><option value="<?=$type?>" <?=$selected?>><?=$type?></option><?
}
?>
</select>
<select id="alltype" style="width:120px;" onchange="document.location.href = '?recipes=true&type='+$(this).val()+'&data_set='+$('#allsets').val();">
		<option value="">All</option>
<?
foreach ($types as $type){
	$selected = "";
	if(isset($view->url["type"])){
		if($view->url["type"] == $type){
			$selected = "selected";
		}
	}
	?><option value="<?=$type?>" <?=$selected?>><?=$type?></option><?
}
?>
</select>


<table class="table table-striped">
	<thead>
		<tr>
		<th>Set</th>
		<th>Type</th>
		<th>Name</th>
		<th>Description</th>
		<th>Action</th>
		</tr>
	</thead>
	<?php
	$samples = $view->get_all_recipes();

	foreach($samples as $sample){
	$show = 0;
	if(isset($view->url["type"])){
		if(strtolower($sample["type"]) == strtolower($view->url["type"])){
			if($sample["data_set"] == $view->url["data_set"]){	
				$show = 1;
			}
			if($view->url["data_set"] == ""){
				$show = 1;
			}
		}else{
			if($view->url["type"] == ""){
				if($sample["data_set"] == $view->url["data_set"]){	
				$show = 1;
			}
			if($view->url["data_set"] == ""){
				$show = 1;
			}
			}
		}
	}else{
		$show = 1;
	}	
	if($show==1){
		echo "<tr>";
		echo "<td width='10%' nowrap='nowrap'>".$sample["data_set"]."</td>";
		echo "<td width='10%' nowrap='nowrap'>".$sample["type"]."</td>";
		echo "<td width='10%' nowrap='nowrap'>".$sample["name"]."</td>";
		echo "<td>".$sample["description"]."</td>";
		echo "<td width='10%' nowrap='nowrap'><a href='?recipe=".$sample["id"]."'  class='btn btn-info btn-sm'>View</a> <a href='javascript:delete_recipe(".$sample["id"].");'  class='btn btn-info btn-sm'>Delete</a></td>";
		echo "</tr>";
	}
}
?>
</table>
<script>
function delete_recipe(id){
	x = confirm('are you sure?');
	if(x == true){
		document.location.href="?delete_recipe="+id;
	}
}
</script>