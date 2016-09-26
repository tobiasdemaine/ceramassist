<?php
$view->set_section('recipes');
$recipe = $view->get_recipe($view->url["recipe"]);

?>

<div style="overflow:hidden;">
<a href="?new_recipe=true&id=<?=$view->url["recipe"]?>" class='btn btn-info btn-sm pull-right'>Edit Recipe</a>
<h2><?=$recipe["name"]?><br><small  style="margin-right:20px;"><?=$recipe["type"]?> <?php if($recipe["cone"] !=""){?>: Orton Cone <?=$recipe["cone"];?><?php }?></small></h2>
<p><?=$recipe["description"]?></p>
</div>

<div style="width:50%; float:right; margin-bottom:30px;">
<div style="padding-top:8px; padding-left:20px; font-weight:bold;">Alumina Silica Limits</div>
<div id="placeholder"  style="height:200px; margin-left:10px;"></div>

</div>
<div style="width:50%; margin-bottom:30px;  ">
	<?php
		$seger = $view->convert_recipe_to_seger($recipe["id"]);
	?>
	<div class="pull-right" style="padding:8px;">Formula Weight <strong><?=round($seger["formula_weight"], 4) ?></strong>  |  Silica / Alumina ratio : <strong><?=round(($seger['RO2']['SiO2']/$seger['R2O3']['Al2O3']), 4)?>:1</strong></div><div style="padding:8px; font-weight:bold;">Seger</div>
	<div style="border-top:1px solid #dedede;  background-color:#efefef;">
		<table cellpadding="10" align=center class="table table-striped">
		<tr>
		<td valign=middle style="padding-right:40px;">
		<?php
		$kno = 0;
		$ROlist = "";
		$KROlist = "";
		foreach($seger['RO'] as $key =>$value){
			if($value != 0){
				$k = false;
				if($key == "K2O"){
					$kno += $value;
					$k = true;
				}
				if($key == "Na2O"){
					$kno += $value;
					$k = true;
				}
				if($k==true){
					$KROlist .= "<small style='color:#666;'><span style='font-weight:bold; width:50px; display:inline-block'>". $key ."</span> ". round($value , 4)."<br></small>";
				}else{
					$ROlist .= "<span style='font-weight:bold; width:50px; display:inline-block'>". $key ."</span> ". round($value , 4)."<br>";
				}
			}
		}
		echo "<span style='font-weight:bold; width:50px; display:inline-block'>KNO</span> ". round($kno , 4)."<br>".$ROlist.$KROlist;
		?>
		
		</td>
		<td style="padding-right:40px;">
		<?php
		foreach($seger['R2O3'] as $key =>$value){
			if($value != 0){
				echo "<span style='font-weight:bold; width:50px; display:inline-block'>". $key ."</span> ". round($value , 4)."<br>";
			}
		}
		?>
		</td>
		<td>
		<?php
		foreach($seger['RO2'] as $key =>$value){
			if($value != 0){
				echo "<span style='font-weight:bold; width:50px; display:inline-block'>". $key ."</span> ". round($value , 4)."<br>";
			}
		}
		?>
		</td>
		</tr>
	</table>
	
	</div>
</div>

<table class="table table-striped" style="margin-top:50px;">
	<thead >
		<tr>
			<td>Amount</td>
			<td>Type</td>
			<td>Material</td>
			<td nowrap style="width:5%;"><input id='whole_amount' type='text' style='text-align:right; width:50px; margin-bottom:0px;' onKeyUp='adjust_parts()' value='10'> <small>grams</small></td>
		</tr>
    </thead>
    <tbody id="parts">
    <?php
    $parts = json_decode($recipe["parts"], true);
    
    foreach($parts as $part){
    	$part = explode(",", $part);
    	$sample = $view->get_sample($part[1]);
        ?>
    	<tr>
    		<td class='whole_part'>
    		<?=$part[0];?>	
    		</td>
    		<td>
    		<?=$sample["type"]?>
    		</td>
    		<td>
    		<?=$sample["Comments"]?> <a  href="?sample=<?=$sample["id"]?>">view</a>
    		</td>
    		<td><input class='sub_part' type='text' style='width:50px; text-align:right;' > <small>grams</small></td>
    	</tr>
    <?php
    
    }
    ?>
    </tbody>
</table>
	
<div id="tabs">
  <ul>
    <li><a href="#tabs-2">Documents</a></li>
    <li><a href="#tabs-3">Tests</a></li>
     <li><a href="#tabs-4" class="formula_title">Formula</a></li>
  </ul>
  	<div id="tabs-2">
		<?php 
		$parent_id = $view->url["recipe"];
		$parent_table = "recipe";
		include('template/select_document.php');
		?>
	</div>
	<div id="tabs-3">
		<?php 
		$parent_id = $view->url["recipe"];
		$parent_table = "recipe";
		include('template/select_test.php');
		?>
	</div>
	<div id="tabs-4">
		<?php 
		$parent_id = $view->url["recipe"];
		$parent_table = "recipe";
		include('template/select_formula.php');
		?>
	</div>
</div>	
<script>
function adjust_parts(){
	// get the value of all whole parts
	var whole = 0;
	$(".whole_part").each(function(){
		whole = (whole + Number($(this).text()));
	});
	
	divider = whole / $("#whole_amount").val();
	
	$(".whole_part").each(function(){
		$(this).parent().find("td input").val($(this).text() / divider);
		 
	});
}

$( "#tabs" ).tabs();
adjust_parts();

	$(function() {
		var data =  [[<?=round($seger['RO2']['SiO2'], 2)?>, <?=round($seger['R2O3']['Al2O3'], 2)?>]];

		var options = {
			series: {
				points: {
					show: true,
					radius: 3
				}
			},
			xaxis: {
				ticks: 14,
				min: 0,
				max: 13
			},
			yaxis: {
				ticks: 14,
				min: 0.0,
				max: 1.4
			}
		};

			$.plot("#placeholder", [data], options);
		
		// Add the Flot version string to the footer

		
	});

</script>