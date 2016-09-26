<?
$view->set_section('distribution');


?>
<div id="tabs-2" >
	<h3>Web Stores <a href="?add_webstore=new" class="btn pull-right">Add Web Store</a>
	</h3>
	<table class="table table-striped">
	<thead>
		<tr>
		<th>Name</th>
		<th>Type</th>
		<!-- <th>InStock Qty</th>
		<th>InStock $</th>
		<th>Sold Qty</th>
		<th>Sold $</th>-->
		<th>action</th>
		</tr>
	</thead>
	<?php
	$shops= $view->get_all_webstores();

	foreach($shops as $shop){
		echo "<tr>";
		echo "<td width='10%' nowrap='nowrap'>".$shop["name"]."</td>";
		echo "<td>".$shop["type"]."</td>";
		/*
		echo "<td>".$view->webstore_get_instock_qty($shop["id"])."</td>";
		echo "<td>".$view->webstore_get_instock_value($shop["id"])."</td>";
		echo "<td>".$view->webstore_get_sold_qty($shop["id"])."</td>";
		echo "<td>".$view->webstore_get_sold_value($shop["id"])."</td>";
		*/
		echo "<td width='10%' nowrap='nowrap'><a href='?add_webstore=".$shop["id"]."'  class='btn btn-xs'>view</a> <a href='javascript:delete_webstore(".$shop["id"].")'  class='btn btn-xs'>delete</a></td>";
		echo "</tr>";
	}

?>
	</table>
</div>	


<div id="tabs-1" style="margin-top:50px;">
	<h3>Shops - Galleries
	<a href="?add_shop=new" class="btn pull-right">Add Shop</a>
	</h3>
	<table class="table table-striped">
	<thead>
		<tr>
		<th>Name</th>
		<th>InStock Qty</th>
		<th>InStock $</th>
		<th>Sold Qty</th>
		<th>Sold $</th>
		<th>Contact</th>
		<th>Phone</th>
		<th>action</th>
		</tr>
	</thead>
	<?php
	$shops= $view->get_all_shops();

	foreach($shops as $shop){
		echo "<tr>";
		echo "<td width='10%' nowrap='nowrap'>".$shop["name"]."</td>";
		echo "<td>".$view->shop_get_instock_qty($shop["id"])."</td>";
		echo "<td>".$view->shop_get_instock_value($shop["id"])."</td>";
		echo "<td>".$view->shop_get_sold_qty($shop["id"])."</td>";
		echo "<td>".$view->shop_get_sold_value($shop["id"])."</td>";
		echo "<td>".$shop["contact"]."</td>";
		echo "<td>".$shop["phone1"]."</td>";
		echo "<td width='10%' nowrap='nowrap'><a href='?add_shop=".$shop["id"]."'  class='btn btn-xs'>view</a> <a href='javascript:delete_shop(".$shop["id"].")'  class='btn btn-xs'>delete</a></td>";
		echo "</tr>";
	}

?>
	</table>
</div>



<div  style="margin-top:50px;">
	<h3>Exhibitions<a href="?add_exhibition=new" class="btn pull-right">Add Exhibition</a></h3>
	<table class="table table-striped">
	<thead>
		<tr>
		<th nowrap>Exhibition Name</th>
		<th>Gallery Name</th>
		<th>Opening</th>
		<th>Start</th>
		<th>Close</th>
		<th>Sold</th>
		<th>Action</th>
		</tr>
	</thead>
	<?php
	$shops= $view->get_all_exhibitions();

	foreach($shops as $shop){
		echo "<tr>";
		echo "<td width='10%' nowrap='nowrap'>".$shop["name"]."</td>";
		echo "<td>".$shop["gallery_name"]."</td>";
		echo "<td>".$shop["opening_datetime"]."</td>";
		echo "<td>".$shop["start_datetime"]."</td>";
		echo "<td>".$shop["end_datetime"]."</td>";
		echo "<td align='right'>$".$view->exhibition_get_sold_value($shop["id"])."</td>";
		echo "<td width='10%' nowrap='nowrap'><a href='?add_exhibition=".$shop["id"]."'  class='btn btn-xs'>view</a> <a href='javascript:delete_exhibition(".$shop["id"].")'  class='btn btn-xs'>delete</a></td>";
		echo "</tr>";
	}

?>
	</table>

</div>


<script>
	function delete_shop(id){
		if(confirm("Are you sure? This not reversible")){
			document.location.href = "?delete_shop=" + id
		}
	}
	function delete_webstore(id){
		if(confirm("Are you sure? This not reversible")){
			document.location.href = "?delete_webstore=" + id
		}
	}
	function delete_exhibition(id){
		if(confirm("Are you sure? This not reversible")){
			document.location.href = "?delete_exhibition=" + id
		}
	}
</script>