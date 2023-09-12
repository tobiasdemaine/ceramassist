<?php

$view->set_section('material');
$samples = $view->get_all_samples();
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

<div style="text-align:right;">
<div style="inline-block; float:left">
	<a href="?esample=true" class='btn btn-info btn-sm' style="margin-bottom:10px;">New Sample</a> 	&nbsp; &nbsp;


	
	<select id="search_for"  style="width:80px;">
		<?php 
			foreach($view->mw as $key => $value){
				?>
				<option value="<?=$key?>"><?=$key?></option>
				<?php
			}
			
		?>
	</select>
	<select id="operator" style="width:80px;">
		<option value="<"><</option>
		<option value="<="><=</option>
		<option value="==">==</option>
		<option value=">=">>=</option>
		<option value=">">></option>
	<select>
	<select id="amount" style="width:80px;">
		<option value="0.5">0.5 %</option>
		<option value="1">1 %</option>
		<option value="1.5">1.5 %</option>
		<option value="2">2 %</option>
		<option value="2.5">2.5 %</option>
		<option value="3">3 %</option>
		<option value="3.5">3.5 %</option>
		<option value="4">4 %</option>
		<option value="4.5">4.5 %</option>
	</select> 
	<select id="mtype" style="width:120px;">
		<option value="all">All</option>
		<?php
		foreach ($types as $type){
			?><option value="<?=$type?>"><?=$type?></option><?php
		}
		
		?>
		
	<select>
	<a href="javascript:search_for_sample()"   class='btn btn-info btn-sm' style="margin-bottom:10px;">search</a> 	
</div>
<script>
	function search_for_sample(){
		document.location.href = "?search_for=" + $("#search_for").val() + "&amount=" + $("#amount").val()  + "&operator=" + $("#operator").val() + "&mtype=" + $("#mtype").val();
	}

</script>




<select id="allsets" style="" onchange="document.location.href = '?samples=true&data_set='+$(this).val()+'&type='+$('#alltype').val();">
		<option value="">All</option>
<?php
foreach ($data_sets as $type){
	$selected = "";
	if(isset($view->url["data_set"])){
		if($view->url["data_set"] == $type){
			$selected = "selected";
		}
	}
	?><option value="<?=$type?>" <?=$selected?>><?=$type?></option><?php
}
?>
</select>
<select id="alltype" style="width:120px;" onchange="document.location.href = '?samples=true&type='+$(this).val()+'&data_set='+$('#allsets').val();">
		<option value="">All</option>
<?php
foreach ($types as $type){
	$selected = "";
	if(isset($view->url["type"])){
		if($view->url["type"] == $type){
			$selected = "selected";
		}
	}
	?><option value="<?=$type?>" <?=$selected?>><?=$type?></option><?php
}
?>
</select>

</div>
<table class="table table-striped">
	<thead>
		<tr>
		<th>Set</th>
		<th>Type</th>
		<th>Sample No</th>
		<th>unit</th>
		<th>Comments</th>
		<th>Method</th>
		<th>Action</th>
		</tr>
	</thead>

<?php
function compare($expr1, $operator, $expr2)
{
   switch(strtolower($operator)) {
      case '>':
         return $expr1 > $expr2;
      case '<':
         return $expr1 < $expr2;
      case '==':
         return $expr1 == $expr2;
      case '>=':
         return $expr1 >= $expr2;
      case '<=':
         return $expr1 <= $expr2;
      case '!=':
         return $expr1 != $expr2;
      case '&&':
      case 'and':
         return $expr1 && $expr2;
      case '||':
      case 'or':
         return $expr1 || $expr2;
      default:
         throw new Exception("Invalid operator '$operator'");
   }
} 


foreach($samples as $sample){
	$show = 0;
	if(isset($view->url["type"])){
		
	    if($sample["type"] == $view->url["type"]){
			if($sample["data_set"] == $view->url["data_set"]){	
				$show = 1;
			}
			if($view->url["data_set"] == ""){
				$show = 1;
			}
		}
		if($view->url["type"] == ""){
			if($sample["data_set"] == $view->url["data_set"]){	
				$show = 1;
			}
			if($view->url["data_set"] == ""){
				$show = 1;
			}
		}

	}else{
		if(isset($view->url["search_for"])){
			if($sample[$view->url["search_for"]] != ""){
				
				//if(eval( $sample[$view->url["search_for"]].'  '.$view->url["operator"].' '. $view->url["amount"])){
				
				if(compare($sample[$view->url["search_for"]], $view->url["operator"], $view->url["amount"]) == 1){
					if($sample["type"] == $view->url["mtype"]){
						$show = 1;
					}
					if('all' == $view->url["mtype"]){
						$show = 1;
					}
				}
			}
		}else{
			$show = 1;
		}
	}	
	if($show==1){
		echo "<tr>";
		echo "<td nowrap>".$sample["data_set"]."</td>";
		echo "<td>".$sample["type"]."</td>";
		echo "<td>".$sample["SAMPNO"]."</td>";
		echo "<td>".$sample["Unit"]."</td>";
		echo "<td>".$sample["Comments"]."</td>";
		echo "<td>".$sample["Method"]."</td>";
		echo "<td><a href='?sample=".$sample["id"]."'  class='btn btn-info btn-sm'>View</a></td>";
		echo "</tr>";
	}
}
?>
</table>	
