<?php
$view->set_section('formula');
if(isset($view->url["eformula"])){
	if($view->url["eformula"] != 'true'){
		$sample = $view->get_formula($view->url["eformula"]);
	}
}


?>
<form action="?aeformula=true" method='post'>
<table class="table table-striped" style="width:100%" >
	<thead>
		<tr>
			<th colspan=2>
			<?php 
			if(isset($sample["id"])){
				$button = "Update Formula";
				?><input type="hidden" value="<?=$sample["id"]?>" name='id'><input  type='submit' class='btn btn-info pull-right btn-sm' value='<?=$button?>'>	<h4>Edit Formula : <?=$sample["id"]?></h4><?php
			}else{
				$button = "Add New Formula";
				?><input type='submit' class='btn btn-info pull-right btn-sm' value='<?=$button?>'>	<h4>Add New Formula</h4><?php
			}
			?>
			
			
			</th>
		</tr>
	</thead>
<tr><td width="100">Name</td><td '><input type="text" name='name' style="width:100%;" value='<?php if(isset($sample["Name"])){echo $sample["Name"];} ?>'></td></tr>
<tr><td>Comments</td><td ><input type="text" name='Comments' style="width:100%;" value='<?php if(isset($sample["Comments"])){echo $sample["Comments"];} ?>'></td></tr>
</table>


<table align=center>
	<tr>
		<td>
			<table class="table table-striped" style="width:100%;" >
				<thead>
					<tr>
					<th colspan=2></th>
					</tr>
				</thead>
				<tbody>
					<tr><td>K2O</td><td style='text-align:right;'><input type="text" name='K2O' value='<?php if(isset($sample["K2O"])){echo $sample["K2O"];} ?>'></td></tr>
					<tr><td>Na2O</td><td style='text-align:right;'><input type="text" name='Na2O' value='<?php if(isset($sample["Na2O"])){echo $sample["Na2O"];} ?>'></td></tr>
					<tr><td>CaO</td><td style='text-align:right;'><input type="text" name='CaO' value='<?php if(isset($sample["CaO"])){echo $sample["CaO"];} ?>'></td></tr>
					<tr><td>MnO</td><td style='text-align:right;'><input type="text" name='MnO' value='<?php if(isset($sample["MnO"])){echo $sample["MnO"];} ?>'></td></tr>
					<tr><td>MgO</td><td style='text-align:right;'><input type="text" name='MgO' value='<?php if(isset($sample["MgO"])){echo $sample["MgO"];} ?>'></td></tr>
					<tr><td>BaO</td><td style='text-align:right;'><input type="text" name='BaO' value='<?php if(isset($sample["BaO"])){echo $sample["BaO"];} ?>'></td></tr>
					<tr><td>ZnO</td><td style='text-align:right;'><input type="text" name='ZnO' value='<?php if(isset($sample["ZnO"])){echo $sample["ZnO"];} ?>'></td></tr>
					<tr><td>PbO</td><td style='text-align:right;'><input type="text" name='PbO' value='<?php if(isset($sample["PbO"])){echo $sample["PbO"];} ?>'></td></tr>
					<tr><td>Li2O</td><td style='text-align:right;'><input type="text" name='Li2O' value='<?php if(isset($sample["Li2O"])){echo $sample["Li2O"];} ?>'></td></tr>
					<tr><td>FeO</td><td style='text-align:right;'><input type="text" name='FeO' value='<?php if(isset($sample["FeO"])){echo $sample["FeO"];} ?>'></td></tr>
					<tr><td>SrO</td><td style='text-align:right;'><input type="text" name='SrO' value='<?php if(isset($sample["SrO"])){echo $sample["SrO"];} ?>'></td></tr>
					<tr><td>H2O</td><td style='text-align:right;'><input type="text" name='H2O' value='<?php if(isset($sample["H2O"])){echo $sample["H2O"];} ?>'></td></tr>
				</tbody>
			</table>
		</td>
		<td>
			<table class="table table-striped"  style="width:100%;" >
				<thead>
					<tr>
					<th colspan=2></th>
					</tr>
				</thead>
				<tbody>
					<tr><td>Al2O3</td><td style='text-align:right;'><input type="text" name='Al2O3' value='<?php if(isset($sample["Al2O3"])){echo $sample["Al2O3"];} ?>'></td></tr>
					<tr><td>Fe2O3</td><td style='text-align:right;'><input type="text" name='Fe2O3' value='<?php if(isset($sample["Fe2O3"])){echo $sample["Fe2O3"];} ?>'></td></tr>
					<tr><td>P2O5</td><td style='text-align:right;'><input type="text" name='P2O5' value='<?php if(isset($sample["P2O5"])){echo $sample["P2O5"];} ?>'></td></tr>
					<tr><td>B2O3</td><td style='text-align:right;'><input type="text" name='B2O3' value='<?php if(isset($sample["B2O3"])){echo $sample["B2O3"];} ?>'></td></tr>
				</tbody>
			</table>
		</td>
		<td>
			<table class="table table-striped"  style="width:100%;" >
				<thead>
					<tr>
					<th colspan=2></th>
					</tr>
				</thead>
				<tbody>
					<tr><td>SiO2</td><td style='text-align:right;'><input type="text" name='SiO2' value='<?php if(isset($sample["SiO2"])){echo $sample["SiO2"];} ?>'></td></tr>
					<tr><td>TiO2</td><td style='text-align:right;'><input type="text" name='TiO2' value='<?php if(isset($sample["TiO2"])){echo $sample["TiO2"];} ?>'></td></tr>
					<tr><td>CO2</td><td style='text-align:right;'><input type="text" name='CO2' value='<?php if(isset($sample["CO2"])){echo $sample["CO2"];} ?>'></td></tr>
					<tr><td>ZrO2</td><td style='text-align:right;'><input type="text" name='ZrO2' value='<?php if(isset($sample["ZrO2"])){echo $sample["ZrO2"];} ?>'></td></tr>
					
					<tr><td>SO3</td><td style='text-align:right;'><input type="text" name='SO3' value='<?php if(isset($sample["SO3"])){echo $sample["SO3"];} ?>'></td></tr>
				</tbody>
			</table>
		</td>
	</tr>
	
</table>













</td></tr>
</table>

</form>
		
		
</table>	


