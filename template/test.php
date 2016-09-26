<?php
$view->set_section('tests');
$blend = $view->get_blend($view->url['test']);
//var_dump($blend);



?>
<h2><?=$blend["name"]?> <a href="javascript:add_document();" class='btn btn-info btn-sm pull-right' style="font-weight:300;">Add Documents</a></h2>
<p><?=$blend["description"]?></p>
<script>
var __parts;
function select_tile(id){
	// populate
	$seger_html = "";
	$('#tile').dialog({title: "Blend "+id});
	$('#tile').dialog('open');
	$seger_html = $seger_html + "<div style='display:inline-block; width:33%;'>";
	for (var key in tiles[id]['seger']['RO']) {
		if (tiles[id]['seger']['RO'].hasOwnProperty(key)) {
			if(tiles[id]['seger']['RO'][key] > 0){
  			
  			 	$seger_html = $seger_html + key + " : " + Math.round(tiles[id]['seger']['RO'][key]*1000)/1000+"<br>";
  			 }
  		}
	}
	$seger_html = $seger_html + "</div>";
	$seger_html = $seger_html + "<div style='display:inline-block; width:33%;'>";
	for (var key in tiles[id]['seger']['R2O3']) {
		if (tiles[id]['seger']['R2O3'].hasOwnProperty(key)) {
  			if(tiles[id]['seger']['R2O3'][key] > 0){
  			 $seger_html = $seger_html + key + " : " + Math.round(tiles[id]['seger']['R2O3'][key]*1000)/1000+"<br>";
  			}
  		}
	}
	$seger_html = $seger_html + "</div>";
	$seger_html = $seger_html + "<div style='display:inline-block; width:33%;'>";
	for (var key in tiles[id]['seger']['RO2']) {
		if (tiles[id]['seger']['RO2'].hasOwnProperty(key)) {
			if(tiles[id]['seger']['RO2'][key] > 0){
  			 $seger_html = $seger_html + key + " : " + Math.round(tiles[id]['seger']['RO2'][key]*1000)/1000+"<br>";
  			}
  		}
	}
	$seger_html = $seger_html + "</div>";
	$seger_html = $seger_html + "<h5>RECIPE</h5><div style='display:inline-block; width:100%; background:#efefef; border:#ddd; border-radius:4px;'><table class='table table-striped'><thead><tr><th>Amount</th><th>Material</th><th nowrap><input id='whole_amount' type='text' style='text-align:right; width:50px; margin-bottom:0px;' onKeyUp='adjust_parts()' value='10'> <small>grams</small></th></tr></thead>";
	__parts = "[";
	last = "";
	for (var key in tiles[id]['parts']) {
		if (tiles[id]['parts'].hasOwnProperty(key)) {
			if(tiles[id]['parts'][key] > 0){
  				s = key.split(":") 
  			 	$seger_html = $seger_html + "<tr><td class='whole_part'>" + tiles[id]['parts'][key] + "</td><td><a href='?sample=" + s[0] + "' target='_blank'>" + s[1] +"</td><td><input class='sub_part' type='text' style='width:50px; text-align:right;' ></td></tr>";
  			 	__parts = __parts + last + '"'+tiles[id]['parts'][key]+','+s[0]+'"';
  			 	last = ","
  			 }
  		}
	}
	__parts = __parts + "]";
	$seger_html = $seger_html + "</table></div>";
	$('#add_material').show();
	$('#tile_paint').html("<h5>SEGER</h5>"+$seger_html);
	$('#add_recipe').hide();
	$('#name').val('');
	$('#description').val('');
	adjust_parts();
}


function adjust_parts(){
	// get the value of all whole parts
	var whole = 0;
	$(".whole_part").each(function(){
		whole = (whole + Number($(this).text()));
	});
	console.log(whole);
	
	divider = whole / $("#whole_amount").val();
	console.log(divider);
	$(".whole_part").each(function(){
		$(this).parent().find("td input").val($(this).text() / divider);
		 
	});
}



$(function(){
		$('#tile').dialog({
		  autoOpen: false,
		  width: 500
		});
		$( "#tabs" ).tabs();
	});
	
	
	function save(){
	     $type = $("#type").val();
	     $description = $("#description").val();
	     $name = $("#name").val();
	     url = "?add_recipe=true&type=" + encodeURIComponent($type) + "&description=" + encodeURIComponent($description) + "&name=" + encodeURIComponent($name) + "&parts=" + encodeURIComponent(__parts);
	     $("#script_load").load(url);
	}
</script>
<div id="script_load"></div>
<div id="tile" title="Blend Data" style="width:400px;">
<div id="tile_paint" style="width:100%;">Ingredients</div>
<div id="add_recipe">
	<table  class="table table-striped">
	<thead>
		<tr>
			<td colspan=2>Add a recipe<a href="javascript:save();" class='btn btn-info btn-sm pull-right'>Save</a> </td>
		</tr>
    </thead>
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
		<td width="10%">Name</td>
		<td><input type="text" id="name" style="width:100%;"></td>
	</tr>
	<tr>
		<td>Description</td>
		<td><textarea id="description" style="width:100%;"></textarea></td>
	</tr>
	
	</table>
</div>

<a href="javascript:void(0);" onclick="$(this).hide();$('#add_recipe').show();" id="add_material" class='btn btn-info btn-sm pull-right'>Create recipe from test</a>
</div>


<div id="tabs">
	<ul>
	<li><a href="#tabs-1">Test</a></li>
    <li><a href="#tabs-2">Documents</a></li>
	</ul>
	<div id="tabs-1">

	

<?php
	$mixes = array();
	if($blend['blend_type'] == 'line'){
		?>
		<div style="text-align:center;">
			
			<?php
			$a = 0;
			$b = 100;
			$i = 1;
			$dim = $blend["dimension"];
			$part = round(100/($dim-1), 2);
			for($x=$blend["dimension"];$x>0;$x--){
				$c = 0;
					?>
					<div style="width:100px;height:100px; padding:10px; margin:5px; box-shadow:0px 0px 4px #aaa; border-radius:4px; display:inline-block;" class='btn btn-sm ' href="javascript:void(0);" onclick="select_tile(<?=$i;?>)"><?=($i)?><br>
						A : <?=$a?><br>
						B : <?=$b?><br>
					</div>
				<?php	
				$mixes[$i] = array($a, $b);
				$a = $a+$part;	
				$b = $b-$part;
				$i++;
			}	
			?>
			
		</div>
		<?php
	}else
	if($blend['blend_type'] == 'triaxial'){
		?>
		<div style="text-align:center;">
			
			<?php
			$a = 0;
			$b = 100;
			$c = 0;
			$i = 1;
			$dim = $blend["dimension"];
			$part = round(100/($dim-1), 2);
			for($x=$blend["dimension"];$x>0;$x--){
				echo "<div>";
				$c = 0;
				for($d=$dim;$d>0;$d--){
					?>
					<div style="width:100px;height:100px; padding:10px; margin:5px; box-shadow:0px 0px 4px #aaa; border-radius:4px; display:inline-block;" class='btn btn-sm ' href="javascript:void(0);" onclick="select_tile(<?=$i;?>)"><?=($i)?><br>
						A : <?=$a?><br>
						B : <?=$b?><br>
						C : <?=$c?><br>
					</div>
					
					<?php
						$mixes[$i] = array($a, $b, $c);
						$b = round($b - $part, 2);
						$c = $c + $part;
						$i++;
				}
				$a = 100 - round(($x-2) * $part, 2);
				$b = round(($x-2) * $part, 2);
				echo "</div>";
				$dim--;
			}	
				
			?>
		</div>
		<?php
		
	}else if($blend['blend_type'] == 'quadraxial'){
		?>
		<div style="text-align:center;">
			
			<?php
			$a = 100;
			$b = 0;
			$c = 0;
			$d = 0;
			$i = 1;
			$dim = $blend["dimension"];
			$part = round(100/($dim-1), 2);
			$a_part = $part;
			$c_part = $part;
			for($x=$blend["dimension"];$x>0;$x--){
				echo "<div>";
				for($e=$dim;$e>0;$e--){
					
					?>
					<div style="width:100px;height:100px; padding:10px; margin:5px; box-shadow:0px 0px 4px #aaa; border-radius:4px; display:inline-block;" class='btn btn-sm ' href="javascript:void(0);" onclick="select_tile(<?=$i;?>)"><?=($i)?><br>
						A : <?=$a?><br>
						B : <?=$b?><br>
						C : <?=$c?><br>
						D : <?=$d?><br>
					</div>
					<?php
						$mixes[$i] = array($a, $b, $c, $d);
						$a = $a-$a_part;
						$b = $b+$a_part;
						if($c>0){
						$c = $c-$c_part;
						$d = $d+$c_part;
						}
						//$b = ($dim - ($e-1)) * $part;
						$i++;
				}
				//$a = ($dim - $x)* $part;
				//$b = ($dim - ($x-1));
				$a = 100 - (($dim - ($x-1)) * $part);
				$b = 0;
				$c = (($dim - ($x-1)) * $part);
				$d = 0;
				$a_part = round($a/($dim-1), 2);
				$c_part = round($c/($dim-1), 2);
				echo "</div>";
				//$dim--;
			}	
				
			?>
		</div>
		<?
		
	}
	
		$tile = array();
		foreach($mixes as $key => $val){
			$tile[$key] = array();
			$tile[$key]['parts'] = array();
			$i=1;
			foreach($val as $percent){
				$tile[$key]['parts'][$i]= array($blend['corner_'.$i], $percent);
				$i++;
			
			}
			$tile[$key]['seger'] = $view->test_to_seger($tile[$key]['parts']); 
			$tile[$key]['parts'] = $view->test_to_recipe($tile[$key]['parts']); 
			$partz = array();
			foreach($tile[$key]['parts'] as $keya => $part){
				$sample = $view->get_sample($keya);
				$partz[($sample["id"].":".$sample["type"]." - ".$sample["Unit"]." - ".$sample["Comments"])] = $part;
			}
			$tile[$key]['parts'] = $partz;
			
			$tiles = json_encode($tile);
		}
		echo "<script>var tiles = $.parseJSON('".$tiles."');</script>";
		//var_dump($mixes);
		
		
?>
</div>
	<div id="tabs-2">
		<div style="width:100%; display:table-cell;" id="testdocs ">
		<?php 
			$parent_id = $view->url['test'];
			$parent_table = "tests";
			include('template/select_document.php');
		?>
		</div>
	</div>
</div>
