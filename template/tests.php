<?php
$view->set_section('tests');
if(isset($view->url['save'])){
	$blend = json_decode(urldecode($view->url['save']));
	if($blend->id != 0){
		$view->update_blend($blend);
	}else{
		$view->add_blend($blend);
	}
}
if(isset($view->url['delete'])){
	$view->delete_blend($view->url['delete']);
}

$samples = $view->get_all_tests();
$types = array();
$data_sets = array();
foreach($samples as $sample){
	if(!in_array($sample['blend_type'], $types)){
		array_push($types, $sample['blend_type']);
	}
	if(!in_array($sample['data_set'], $data_sets)){
		array_push($data_sets, $sample['data_set']);
	}
}
?>
<div >

			<div style="padding-top:10px;">
<select id="allsets" style="" onchange="document.location.href = '?tests=true&data_set='+$(this).val()+'&type='+$('#alltype').val();">
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
<select id="alltype" style="width:120px;" onchange="document.location.href = '?tests=true&type='+$(this).val()+'&data_set='+$('#allsets').val();">
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


				<a href="javascript:new_blend();"  class='btn pull-right btn-info btn-sm'>New Test</a>
				
        	</div>
			
        </div>
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
	$samples = $view->get_all_tests();

	foreach($samples as $sample){
	$show = 0;
	if(isset($view->url["type"])){
		if(strtolower($sample["blend_type"]) == strtolower($view->url["type"])){
			if($sample["data_set"] == $view->url["data_set"]){	
				$show = 1;
			}
			if($view->url["data_set"] == ""){
				$show = 1;
			}
		}else if($view->url["type"] == ""){
			if($sample["data_set"] == $view->url["data_set"]){	
				$show = 1;
			}
			if($view->url["data_set"] == ""){
				$show = 1;
			}
		}
	}else{
		$show = 1;
	}	
	if($show==1){
		echo "<tr>";
		echo "<td width='10%' nowrap='nowrap'>".$sample["data_set"]."</td>";
		echo "<td width='10%' nowrap='nowrap'>".$sample["blend_type"]."</td>";
		echo "<td width='10%' nowrap='nowrap'>".$sample["name"]."</td>";
		echo "<td>".$sample["description"]."</td>";
		echo "<td width='10%' nowrap='nowrap'><a href='?test=".$sample["id"]."'  class='btn btn-info btn-sm'>View</a>  <a href=\"javascript:void(0);\" onclick=\"edit_blend('". urlencode( json_encode( $sample)) ."')\";  class='btn btn-info btn-sm'>Edit</a> <a href='javascript:delete_blend(".$sample["id"].");'  class='btn btn-info btn-sm'>Delete</a></td>";
		echo "</tr>";
	}
}
?>
</table>
<div id="new_edit" title="Blend Maker">
	<div>Name</div>
	<div><input type="text" id="name"><input type="hidden" id="id" value="0"></div>
	<div>Description</div>
	<div><input type="text" id="description"></div>
	<div style="border-left: 1px solid pink; margin-left:-7px; padding-left:7px;">
	<div>Data Set</div>
	<div>
		<select id="data_set">
			<? 
				$samples = $view->get_all_tests();
				$sets = array();
				foreach($samples as $_sample){
					if(!in_array(strtolower($_sample['data_set']), $sets)){
						array_push($sets, strtolower($_sample['data_set']));
						?><option value="<?=$_sample["data_set"]?>"><?=$_sample["data_set"]?></option><?
					}
				}
				?>
		</select>
		<br>
		or New Set 
		
		<input type="text" id="newset">
	</div>
	</div>
	<div>Blend Type</div>
	<div>
		<select id="blend_type" onchange="show_corners(this)">
			<option value="line">Line</option>
			<option value="triaxial">Triaxial</option>
			<option value="quadraxial">Quadraxial</option>
		</select>
	</div>
	<div>Dimension</div>
	<div>
		<select id="dimension">
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>
			<option value="9">9</option>
			<option value="10">10</option>
		</select>
	</div>
	<?php
	$samples = $view->get_all_recipes();
	
	?>
	<div id="corner_1_master">
	<div>Corner 1</div>
	<div>
		<select id="corner_1">
			<option value="0"> --- </option>
			<?php
			foreach($samples as $sample){
				?>
				<option value="<?=$sample["id"]?>"><?=$sample["name"]?></option>
				<?php
			}
			?>
		</select>
	</div>
	</div>
	<div id="corner_2_master">
	<div>Corner 2</div>
	<div>
		<select id="corner_2">
			<option value="0"> --- </option>
			<?php
			foreach($samples as $sample){
				?>
				<option value="<?=$sample["id"]?>"><?=$sample["name"]?></option>
				<?php
			}
			?>
		</select>
	</div>
	</div>
	<div id="corner_3_master">
	<div>Corner 3</div>
	<div>
		<select id="corner_3">
			<option value="0"> --- </option>
			<?php
			foreach($samples as $sample){
				?>
				<option value="<?=$sample["id"]?>"><?=$sample["name"]?></option>
				<?php
			}
			?>
		</select>
	</div>
	</div>
	<div id="corner_4_master">
	<div>Corner 4</div>
	<div>
		<select id="corner_4">
			<option value="0"> --- </option>
			<?php
			foreach($samples as $sample){
				?>
				<option value="<?=$sample["id"]?>"><?=$sample["name"]?></option>
				<?php
			}
			?>
		</select>
	</div>
	</div>
	<div><a href="javascript:save_blend()" class='btn btn-info btn-sm'>Save Blend</a></div>
</div>

<script>
	$(function(){
		$('#new_edit').dialog({
		  autoOpen: false
		});
	});
	
	function delete_blend(id){
		x = confirm('are you sure?');
		if(x == true){
			document.location.href="?tests=true&delete="+id;
		}
	}
	
	function show_corners(that){
		if($(that).val() == "line"){
			$("#corner_4_master").hide();
			$("#corner_3_master").hide();
		}
		if($(that).val() == "triaxial"){
			$("#corner_4_master").hide();
			$("#corner_3_master").show();
		}
		if($(that).val() == "quadraxial"){
			$("#corner_4_master").show();
			$("#corner_3_master").show();
		}
	}
	
	function new_blend(){
		// reset from
		
		$("#corner_4_master").hide();
		$("#corner_3_master").hide();
		$("#corner_1").val(0);
		$("#corner_2").val(0);
		$("#corner_3").val(0);
		$("#corner_4").val(0);
		$("#id").val(0);
		$("#newset").val('');
		$("#data_set").val();
		$("#blend_type").val('line');
		$("#name").val('');
		$("#description").val('');
		$('#new_edit').dialog('open');
	}
	
	function edit_blend(blend){
		blend = JSON.parse(decodeURIComponent(blend))
		console.log(blend);
		$("#corner_1").val(blend.corner_1);
		$("#corner_2").val(blend.corner_2);
		$("#corner_3").val(blend.corner_3);
		$("#corner_4").val(blend.corner_4);
		$("#id").val(blend.id);
		$("#newset").val('');
		$("#data_set").val(blend.data_set)
		$("#dimension").val(blend.dimension);
		
		$("#blend_type").val(blend.blend_type);
		show_corners($("#blend_type"));
		
		
		
		$("#name").val(blend.name);
		$("#description").val(blend.description);
		$('#new_edit').dialog('open');
	}
	
	function save_blend(){
		// reset from
		blend = {}
		blend.id = $('#id').val();
		blend.name = $('#name').val();
		blend.description = $('#description').val();
		blend.dimension = $('#dimension').val();
		blend.blend_type = $('#blend_type').val();
		blend.corner_1 = $('#corner_1').val();
		blend.corner_2 = $('#corner_2').val();
		blend.corner_3 = $('#corner_3').val();
		blend.corner_4 = $('#corner_4').val();
		if($("#newset").val().trim() != ""){
	     	blend.data_set = $("#newset").val().trim();
	    }else{
	     	blend.data_set = $("#data_set").val()
	    }
		
		console.log(encodeURIComponent(JSON.stringify(blend)));
		url = "index.php?tests=true&save="+encodeURIComponent(JSON.stringify(blend));
		if(blend.name==""){
			alert('Give the blend a name');
			
		}else{
			document.location.href = url;
		}
		
		
	}
</script>