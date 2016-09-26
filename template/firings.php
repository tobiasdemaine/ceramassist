<?
$view->set_section('firings');
?>
<div id="tabs">
  <ul>
    <li><a href="#tabs-1">Firings</a></li>
    <li><a href="#tabs-2">Schedules</a></li>
    <li><a href="#tabs-3">Kilns</a></li>
   </ul>
  	<div id="tabs-1">
		<a href="?firing=new" style="margin-top:-50px;" class='btn btn-info btn-sm pull-right'>New Firing</a>
	<table class="table table-striped">
	<thead>
		<tr>
		<th>date</th>
		<th>Firing</th>
		<th>kiln</th>
		<th>kiln type</th>
		<th>atmosphere</th>
		<th>Action</th>
		</tr>
	</thead>
	<?php
	$samples = $view->get_all_firings();

	foreach($samples as $sample){
	$show = 0;
	if(isset($view->url["type"])){
		if(strtolower($sample["type"]) == strtolower($view->url["type"])){
			$show = 1;
		}
	}else{
		$show = 1;
	}	
	if($show==1){
		$kiln = $view->get_kiln($sample['kiln']);
		echo "<tr>";
		echo "<td width='10%' nowrap='nowrap'>".$sample["date"]."</td>";
		echo "<td  nowrap='nowrap'>".$sample["firing_type"]."</td>";
		echo "<td  nowrap='nowrap'>".$kiln["name"]."</td>";
		echo "<td>".$kiln["type"]."</td>";
		echo "<td>".$sample["atmosphere"]."</td>";
		echo "<td width='10%' nowrap='nowrap'><a href='?firing=".$sample["id"]."'  class='btn btn-info btn-sm'>View</a> <a href='javascript:delete_firing(".$sample["id"].");'  class='btn btn-info btn-sm'>Delete</a></td>";
		echo "</tr>";
	}
}
?>
</table>
</div>
<div id="tabs-2">
<a href="?add_schedule=new" style="margin-top:-50px;" class='btn btn-info btn-sm pull-right'>Add Schedule</a>

<table class="table table-striped">
	<thead>
		<tr>
		<th>type</th>
		<th>description</th>
		<th>atmosphere</th>
		<th>action</th>
		</tr>
	</thead>
	<?php
	$samples = $view->get_all_schedules();

	foreach($samples as $sample){
		echo "<tr>";
		echo "<td width='10%' nowrap='nowrap'>".$sample["type"]."</td>";
		echo "<td  >".$sample["description"]."</td>";
		echo "<td>".$sample["atmosphere"]."</td>";
		echo "<td width='10%' nowrap='nowrap'><a href='?add_schedule=".$sample["id"]."'  class='btn btn-info btn-sm'>View</a> <a href='javascript:delete_fschedule(".$sample["id"].");'  class='btn btn-info btn-sm'>Delete</a></td>";
		echo "</tr>";
	}

?>
</table>

</div>
<div id="tabs-3">
<a href="?add_kiln=new" style="margin-top:-50px;" class='btn btn-info btn-sm pull-right'>New Kiln</a>
<table class="table table-striped">
	<thead>
		<tr>
		<th>name</th>
		<th>type</th>
		<th>description</th>
		<th>action</th>
		</tr>
	</thead>
	<?php
	$samples = $view->get_all_kilns();

	foreach($samples as $sample){
		echo "<tr>";
		echo "<td  nowrap='nowrap'>".$sample["name"]."</td>";
		echo "<td  nowrap='nowrap'>".$sample["type"]."</td>";
		echo "<td>".$sample["description"]."</td>";
		echo "<td  nowrap='nowrap' width='10%'><a href='?add_kiln=".$sample["id"]."'  class='btn btn-info btn-sm'>View</a> <a href='javascript:delete_kiln(".$sample["id"].");'  class='btn btn-info btn-sm'>Delete</a></td>";
		echo "</tr>";
	}

?>
</table>
</div>
</div>
<script>
function delete_firing(id){
	x = confirm('are you sure?');
	if(x == true){
		document.location.href="?delete_firing="+id;
	}
}

function delete_kiln(id){
	x = confirm('are you sure?');
	if(x == true){
		document.location.href="?delete_kiln="+id;
	}
}

function delete_fschedule(id){
	x = confirm('are you sure?');
	if(x == true){
		document.location.href="?delete_schedule="+id;
	}
}
$( "#tabs" ).tabs();
</script>