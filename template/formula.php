<?php
$view->set_section('formula');
?><div style="text-align:right;">
<div style="inline-block; float:left">
	<a href="?eformula=true" class='btn btn-info btn-sm' style="margin-bottom:10px;">New Formula</a>
	 	
</div>


</div>
<table class="table table-striped">
	<thead>
		<tr>
		<th>Name</th>
		<th>Comments</th>
		<th>Action</th>
		</tr>
	</thead>

<?php

$samples = $view->get_all_formula();
foreach($samples as $sample){
	$show = 0;
	if(isset($view->url["type"])){
	    if($sample["type"] == $view->url["type"]){
			$show = 1;
		}
	}else{
			$show = 1;
		
	}	
	if($show==1){
		echo "<tr>";
		echo "<td>".$sample["Name"]."</td>";
		echo "<td>".$sample["Comments"]."</td>";
		echo "<td width=10% nowrap><a href='?viewformula=".$sample["id"]."'  class='btn btn-info btn-sm'>View</a> <a href='?eformula=".$sample["id"]."'  class='btn btn-info btn-sm'>Edit</a></td>";
		echo "</tr>";
	}
}
?>
</table>	



