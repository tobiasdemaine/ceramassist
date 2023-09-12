<?php
$view->set_section('material');
if(isset($view->url["esample"])){
	if($view->url["esample"] != 'true'){
		$sample = $view->get_sample($view->url["esample"]);
	}
}


?>
<form action="?aesample=true" method='post'>
<table><tr><td valign=top>	
<table class="table table-striped" style="width:300px;" >
	<thead>
		<tr>
			<th colspan=2>
			<?php 
			if(isset($sample["id"])){
				$button = "Update Material";
				?><input type="hidden" value="<?=$sample["id"]?>" name='id'><h4>Edit Material : <?=$sample["id"]?></h4><?php
			}else{
				$button = "Add New Material";
				?><h4>Add New Material</h4><?php
			}
			?>
			
			
			</th>
		</tr>
	</thead>
<tr><td>SAMPNO</td><td style='text-align:right;'><input type='text' name='SAMPNO' value='<?php if(isset($sample["SAMPNO"])){echo $sample["SAMPNO"];} ?>'></td></tr>
<tr><td>Unit</td><td style='text-align:right;'><input type='text' name='Unit' value='<?php if(isset($sample["Unit"])){echo $sample["Unit"];} ?>'></td></tr>
<tr><td>Comments/identifier</td><td style='text-align:right;'><input type='text' name='Comments' value='<?php if(isset($sample["Comments"])){echo $sample["Comments"];} ?>'></td></tr>
<tr><td>latitude</td><td style='text-align:right;'><input type='text' name='latitude' value='<?php if(isset($sample["latitude"])){echo $sample["latitude"];} ?>'></td></tr>
<tr><td>longitude</td><td style='text-align:right;'><input type='text' name='longitude' value='<?php if(isset($sample["longitude"])){echo $sample["longitude"];} ?>'></td></tr>
<tr><td>Method</td><td style='text-align:right;'><input type='text' name='Method' value='<?php if(isset($sample["Method"])){echo $sample["Method"];} ?>'></td></tr>
<tr><td>type</td><td style='text-align:right;'>
<select name="type" >
<?php
$samples = $view->get_all_samples();
$types = array();
foreach($samples as $_sample){
	if(!in_array(strtolower($_sample['type']), $types)){
		array_push($types, strtolower($_sample['type']));
		?><option <?php if(isset($sample["type"])){ if($sample["type"]==$_sample["type"]){ echo 'selected';}} ?> value="<?=$_sample["type"]?>"><?=$_sample["type"]?></option><?php
	}
}
?>
		
		
	<select>
	Or New Type
	<input type="text" name="newtype">
</td></tr>
<tr><td>Data Set</td><td style='text-align:right;'>
<select name="data_set" >
<?php
$samples = $view->get_all_samples();
$sets = array();
foreach($samples as $_sample){
	if(!in_array(strtolower($_sample['data_set']), $sets)){
		array_push($sets, strtolower($_sample['data_set']));
		?><option <?php if(isset($sample["data_set"])){ if($sample["data_set"]==$_sample["data_set"]){ echo 'selected';}} ?> value="<?=$_sample["data_set"]?>"><?=$_sample["data_set"]?></option><?php
	}
}
?>
		
		
	<select>
	Or New Set
	<input type="text" name="newset">
</td></tr>
</table>
<input type='submit' class='btn btn-info btn-sm' value='<?=$button?>'>	
</td><td>
<table class="table table-striped" style="width:300px;" >
<thead>
		<tr>
			<th colspan=2>
			<h4>Composition</h4>
			</th>
		</tr>	
</thead>
<tr><td>SiO2</td><td style='text-align:right;'><input type='text' name='SiO2' value='<?php if(isset($sample["SiO2"])){echo $sample["SiO2"];} ?>'></td></tr>
<tr><td>TiO2</td><td style='text-align:right;'><input type='text' name='TiO2' value='<?php if(isset($sample["TiO2"])){echo $sample["TiO2"];} ?>'></td></tr>
<tr><td>Al2O3</td><td style='text-align:right;'><input type='text' name='Al2O3' value='<?php if(isset($sample["Al2O3"])){echo $sample["Al2O3"];} ?>'></td></tr>
<tr><td>Fe2O3T</td><td style='text-align:right;'><input type='text' name='Fe2O3T' value='<?php if(isset($sample["Fe2O3T"])){echo $sample["Fe2O3T"];} ?>'></td></tr>
<tr><td>Fe2O3</td><td style='text-align:right;'><input type='text' name='Fe2O3' value='<?php if(isset($sample["Fe2O3"])){echo $sample["Fe2O3"];} ?>'></td></tr>
<tr><td>FeO</td><td style='text-align:right;'><input type='text' name='FeO' value='<?php if(isset($sample["FeO"])){echo $sample["FeO"];} ?>'></td></tr>
<tr><td>MnO</td><td style='text-align:right;'><input type='text' name='MnO' value='<?php if(isset($sample["MnO"])){echo $sample["MnO"];} ?>'></td></tr>
<tr><td>MgO</td><td style='text-align:right;'><input type='text' name='MgO' value='<?php if(isset($sample["MgO"])){echo $sample["MgO"];} ?>'></td></tr>
<tr><td>CaO</td><td style='text-align:right;'><input type='text' name='CaO' value='<?php if(isset($sample["CaO"])){echo $sample["CaO"];} ?>'></td></tr>
<tr><td>Na2O</td><td style='text-align:right;'><input type='text' name='Na2O' value='<?php if(isset($sample["Na2O"])){echo $sample["Na2O"];} ?>'></td></tr>
<tr><td>K2O</td><td style='text-align:right;'><input type='text' name='K2O' value='<?php if(isset($sample["K2O"])){echo $sample["K2O"];} ?>'></td></tr>
<tr><td>P2O5</td><td style='text-align:right;'><input type='text' name='P2O5' value='<?php if(isset($sample["P2O5"])){echo $sample["P2O5"];} ?>'></td></tr>
<tr><td>BaO</td><td style='text-align:right;'><input type='text' name='BaO' value='<?php if(isset($sample["BaO"])){echo $sample["BaO"];} ?>'></td></tr>
<tr><td>ZnO</td><td style='text-align:right;'><input type='text' name='ZnO' value='<?php if(isset($sample["ZnO"])){echo $sample["ZnO"];} ?>'></td></tr>
<tr><td>PbO</td><td style='text-align:right;'><input type='text' name='PbO' value='<?php if(isset($sample["PbO"])){echo $sample["PbO"];} ?>'></td></tr>
<tr><td>Li2O</td><td style='text-align:right;'><input type='text' name='Li2O' value='<?php if(isset($sample["Li2O"])){echo $sample["Li2O"];} ?>'></td></tr>
<tr><td>SrO</td><td style='text-align:right;'><input type='text' name='SrO' value='<?php if(isset($sample["SrO"])){echo $sample["SrO"];} ?>'></td></tr>
<tr><td>B2O3</td><td style='text-align:right;'><input type='text' name='B2O3' value='<?php if(isset($sample["B2O3"])){echo $sample["B2O3"];} ?>'></td></tr>
<tr><td>ZrO2</td><td style='text-align:right;'><input type='text' name='ZrO2' value='<?php if(isset($sample["ZrO2"])){echo $sample["ZrO2"];} ?>'></td></tr>
<tr><td>SO3</td><td style='text-align:right;'><input type='text' name='SO3' value='<?php if(isset($sample["SO3"])){echo $sample["SO3"];} ?>'></td></tr>
<tr><td>CO2</td><td style='text-align:right;'><input type='text' name='CO2' value='<?php if(isset($sample["CO2"])){echo $sample["CO2"];} ?>'></td></tr>
<tr><td>H2O</td><td style='text-align:right;'><input type='text' name='H2O' value='<?php if(isset($sample["H2O"])){echo $sample["H2O"];} ?>'></td></tr>
<tr><td>LOI</td><td style='text-align:right;'><input type='text' name='LOI' value='<?php if(isset($sample["LOI"])){echo $sample["LOI"];} ?>'></td></tr>
<tr><td>total</td><td style='text-align:right;'><input type='text' name='total' value='<?php if(isset($sample["total"])){echo $sample["total"];} ?>'></td></tr>
</table>


</td>
<td>


</td>
</tr>
</table>


</form>
		



