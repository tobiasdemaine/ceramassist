<?php
$view->set_section('formula');
		$sample = $view->get_formula($view->url["viewformula"]);

?>
<form action="?aeformula=true" method='post'>

<h2><?php if($sample["Name"]!=""){echo $sample["Name"];} ?> <a href="javascript:add_document();" class='btn pull-right btn-info btn-sm' style="font-weight:300;">Add Documents</a>
	</h2>
<p><?php if($sample["Comments"]!=""){echo $sample["Comments"];} ?></p>

<div id="tabs">
	<ul>
	<li><a href="#tabs-1">Formula</a></li>
    <li><a href="#tabs-2">Documents</a></li>
	</ul>
	<div id="tabs-1">

<p>Silica / Alumina ratio : <strong><?=round(($sample['SiO2']/$sample['Al2O3']), 4)?></strong></p>
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
					<?php if($sample["K2O"] != ""){?><tr><td>K2O</td><td style='text-align:right;'><?php echo $sample["K2O"]?> </td></tr><?php } ?>
					<?php if($sample["Na2O"] != ""){?><tr><td>Na2O</td><td style='text-align:right;'><?php echo $sample["Na2O"]?> </td></tr><?php } ?>
					<?php if($sample["CaO"] != ""){?><tr><td>CaO</td><td style='text-align:right;'><?php echo $sample["CaO"]?> </td></tr><?php } ?>
					<?php if($sample["MnO"] != ""){?><tr><td>MnO</td><td style='text-align:right;'><?php echo $sample["MnO"]?> </td></tr><?php } ?>
					<?php if($sample["MgO"] != ""){?><tr><td>MgO</td><td style='text-align:right;'><?php echo $sample["MgO"]?> </td></tr><?php } ?>
					<?php if($sample["BaO"] != ""){?><tr><td>BaO</td><td style='text-align:right;'><?php echo $sample["BaO"]?> </td></tr><?php } ?>
					<?php if($sample["ZnO"] != ""){?><tr><td>ZnO</td><td style='text-align:right;'><?php echo $sample["ZnO"]?> </td></tr><?php } ?>
					<?php if($sample["PbO"] != ""){?><tr><td>PbO</td><td style='text-align:right;'><?php echo $sample["PbO"]?> </td></tr><?php } ?>
					<?php if($sample["Li2O"] != ""){?><tr><td>Li2O</td><td style='text-align:right;'><?php echo $sample["Li2O"]?> </td></tr><?php } ?>
					<?php if($sample["FeO"] != ""){?><tr><td>FeO</td><td style='text-align:right;'><?php echo $sample["FeO"]?> </td></tr><?php } ?>
					<?php if($sample["SrO"] != ""){?><tr><td>SrO</td><td style='text-align:right;'><?php echo $sample["SrO"]?> </td></tr><?php } ?>
					<?php if($sample["H2O"] != ""){?><tr><td>H2O</td><td style='text-align:right;'><?php echo $sample["H2O"]?> </td></tr><?php } ?>
					
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
					<?php if($sample["Al2O3"] != ""){?><tr><td>Al2O3</td><td style='text-align:right;'><?php echo $sample["Al2O3"]?> </td></tr><?php } ?>
					<?php if($sample["Fe2O3"] != ""){?><tr><td>Fe2O3</td><td style='text-align:right;'><?php echo $sample["Fe2O3"]?> </td></tr><?php } ?>
					<?php if($sample["P2O5"] != ""){?><tr><td>P2O5</td><td style='text-align:right;'><?php echo $sample["P2O5"]?> </td></tr><?php } ?>
					<?php if($sample["B2O3"] != ""){?><tr><td>B2O3</td><td style='text-align:right;'><?php echo $sample["B2O3"]?> </td></tr><?php } ?>
					
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
					<?php if($sample["SiO2"] != ""){?><tr><td>SiO2</td><td style='text-align:right;'><?php echo $sample["SiO2"]?> </td></tr><?php } ?>
					<?php if($sample["TiO2"] != ""){?><tr><td>TiO2</td><td style='text-align:right;'><?php echo $sample["TiO2"]?> </td></tr><?php } ?>
					<?php if($sample["CO2"] != ""){?><tr><td>CO2</td><td style='text-align:right;'><?php echo $sample["CO2"]?> </td></tr><?php } ?>
					<?php if($sample["ZrO2"] != ""){?><tr><td>ZrO2</td><td style='text-align:right;'><?php echo $sample["ZrO2"]?> </td></tr><?php } ?>
					<?php if($sample["SO3"] != ""){?><tr><td>SO3</td><td style='text-align:right;'><?php echo $sample["SO3"]?> </td></tr><?php } ?>
					
				</tbody>
			</table>
		</td>
	</tr>
	
</table>



</form>

</div>
	<div id="tabs-2">
	
	
	<?php 
		$parent_id = $sample['id'];
		$parent_table = "formula";
		include('template/select_document.php');
		?>
</div>	

<script>
$( "#tabs" ).tabs();

</script>

