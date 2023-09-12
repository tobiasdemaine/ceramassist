<?php
$view->set_section('recipes');
if(isset($view->url["id"])){
$recipe = $view->get_recipe($view->url["id"]);
?>
<input type="hidden" id="id" value="<?=$view->url["id"];?>" style="width:100%;">
<table  class="table table-striped">
	<thead>
		<tr>
			<td colspan=2><h2>Edit Recipe<a href="javascript:update();" class='btn btn-info btn-sm pull-right'>Update</a></h2> </td>
		</tr>
    </thead>

<?php
}else{
?>
<table  class="table table-striped">
	<thead>
		<tr>
			<td colspan=2><h2>Add a recipe<a href="javascript:save();" class='btn btn-info btn-sm pull-right'>Save</a></h2> </td>
		</tr>
    </thead>

<?php
}
?>
    <tr>
		<td width="10%">Type</td>
		<td>
			<select id="type">
				<option value="Clay Body">Clay Body</option>
				<option value="Slip">Slip</option>
				<option value="Glaze">Glaze</option>
			</select>
		</td>
	</tr>

	<tr>
		<td width="10%">Data Set</td>
		<td>
			<select id="data_set">
				<?php 
				$samples = $view->get_all_recipes();
				$sets = array();
				foreach($samples as $_sample){
					if(!in_array(strtolower($_sample['data_set']), $sets)){
						array_push($sets, strtolower($_sample['data_set']));
						?><option <?php if(isset($recipe["data_set"])){ if($recipe["data_set"]==$_sample["data_set"]){ echo 'selected';}} ?> value="<?=$_sample["data_set"]?>"><?=$_sample["data_set"]?></option><?php
					}
				}
				?>
			</select>
			
			&nbsp; or New Set &nbsp; 
			
			<input type="text" id="newset">
		</td>
	</tr>
	
    <tr>
		<td width="10%">Name</td>
		<td><input type="text" id="name" style="width:100%;"></td>
	</tr>
	<tr>
		<td>Description</td>
		<td><textarea id="description" style="width:100%;"></textarea></td>
	</tr>
     <tr>
		<td width="10%">Orton Cone</td>
		<td><input type="text" id="cone" style="width:100%;"></td>
	</tr>
    <thead id="append_after">
		<tr>
			
			<td>amount</td>
			<td>Material</td>
		</tr>
    </thead>
    <tbody id="parts">
    
   
	<tr id="part_parent">
		<td><input type="text" id="part_percent" style="width:100%;"></td>
		<td>
		<select id="part" style="width:100%;">
			<option value="0">----</option>
			<?php
			$samples = $view->get_all_samples();
            foreach($samples as $sample){
            	?>
            	<option value="<?=$sample["id"]?>:<?=$sample["type"]?> - <?=$sample["Unit"]?> - <?=$sample["Comments"]?>"><?=$sample["id"]?>:<?=$sample["type"]?> - <?=$sample["Unit"]?> - <?=$sample["Comments"]?></option>
            	<?php
            }
			?>
		</select>
		<a href="javascript:add_material();" class='btn btn-info btn-sm pull-right'>Add Material</a>
		</td>
	</tr>
	<tr >
		
		<td colspan=2></td>
		
	</tr>
	 </tbody>
</table>
<?php if(isset($view->url["id"])){ ?>
<div style="text-align:right;"><a href="javascript:add_formula();" class='btn btn-info  btn-sm' style="font-weight:300;">Link Formula</a> <a href="javascript:add_test();" class='btn btn-info  btn-sm' style="font-weight:300;">Link Tests</a>
<a href="javascript:add_document();" class='btn btn-info  btn-sm' style="font-weight:300;">Link Documents</a></div>

<div id="tabs">
  <ul>
    <li><a href="#tabs-2">Documents</a></li>
    <li><a href="#tabs-3">Tests</a></li>
     <li><a href="#tabs-4" class="formula_title">Formula</a></li>
  </ul>
  	<div id="tabs-2">
		<?php 
		$parent_id = $view->url["id"];
		$parent_table = "recipe";
		include('template/select_document.php');
		?>
	</div>
	<div id="tabs-3">
		<?php 
		$parent_id = $view->url["id"];
		$parent_table = "recipe";
		include('template/select_test.php');
		?>
	</div>
	<div id="tabs-4">
		<?php 
		$parent_id = $view->url["id"];
		$parent_table = "recipe";
		include('template/select_formula.php');
		?>
	</div>
</div>
<?php } ?>
<div id="script_load"></div>
<script>

	function add_material(){
		a = $("#part_percent").val();   
		b = $("#part").val();   
	    b1 = b.split(":");
	    if(a != ''){
	    	if(b != '0' ){
	    		$("#parts").html($("#parts").html()+"<tr class='parts' data-part='"+a+","+b1[0]+"'><td><input  type='text' value='"+a+"' onChange='mcha(this)'></td><td>"+b1[1]+"<a class='btn btn-info btn-sm pull-right' href='javascript:void(0)' onclick='remove_part(this)'>remove</a></td></tr>");
	    	}
	    }
		
	}
	
	function load_material(a, b){
		b1 = b.split(":");
	    if(a != ''){
	    	if(b != '0' ){
	    		$("#parts").html($("#parts").html()+"<tr class='parts' data-part='"+a+","+b1[0]+"'><td><input  type='text' value='"+a+"' onChange='mcha(this)'></td><td>"+b1[1]+"<a class='btn btn-info btn-sm pull-right' href='javascript:void(0)' onclick='remove_part(this)'>remove</a></td></tr>");
	    	}
	    }
		
	}
	
	function mcha(id){
		$(id).parent().parent().attr('data-part', ($(id).val()+","+$(id).parent().parent().attr('data-part').split(",")[1]));
		console.log($(id).parent().parent().attr('data-part'));
		
	}
	
	function save(){
	     $type = $("#type").val();
	     $description = $("#description").val();
	     $name = $("#name").val();
	     $cone = $("#cone").val();
	     if($("#newset").val().trim() != ""){
	     	$data_set = $("#newset").val().trim();
	     }else{
	     	$data_set = $("#data_set").val()
	     }
	     parts = [];
	     $(".parts").each(function(){
	     	parts.push($(this).attr('data-part'));
	     });
	     url = "?add_recipe=true&type=" + encodeURIComponent($type) + "&data_set=" + encodeURIComponent($data_set) + "&description=" + encodeURIComponent($description) + "&name=" + encodeURIComponent($name) + "&cone=" + encodeURIComponent($cone) + "&parts=" + encodeURIComponent(JSON.stringify(parts));
	     $("#script_load").load(url);
	}
	
	function update(){
	     $type = $("#type").val();
	     $description = $("#description").val();
	     $name = $("#name").val();
	     $cone = $("#cone").val();
	     if($("#newset").val().trim() != ""){
	     	$data_set = $("#newset").val().trim();
	     }else{
	     	$data_set = $("#data_set").val()
	     }
	     parts = [];
	     $(".parts").each(function(){
	     	parts.push($(this).attr('data-part'));
	     });
	     url = "?update_recipe=" + $("#id").val() + "&data_set=" + encodeURIComponent($data_set) + "&type=" + encodeURIComponent($type) + "&description=" + encodeURIComponent($description) + "&name=" + encodeURIComponent($name) + "&cone=" + encodeURIComponent($cone) + "&parts=" + encodeURIComponent(JSON.stringify(parts));
	     console.log(url);
	     $("#script_load").load(url);
	}
	
	function remove_part(obj){
		$(obj).parent().parent().remove();
	}
	<?php
	if(isset($view->url["id"])){
		?>
		$("#type").val('<?=$recipe["type"]?>');
		$("#name").val('<?=$recipe["name"]?>');
		$("#description").val('<?=$recipe["description"]?>');
		$("#cone").val('<?=$recipe["cone"]?>');
		 $( "#tabs" ).tabs();
		<?php
		$parts = json_decode($recipe["parts"], true);
    
    	foreach($parts as $part){
    		$part = explode(",", $part);
    		$sample = $view->get_sample($part[1]);
        	?>
        	load_material('<?=$part[0];?>' , "<?=$sample["id"]?>:<?=$sample["type"]?> - <?=$sample["Unit"]?> - <?=$sample["Comments"]?>");
        	<?php
        }
		
	}
	?>
</script>