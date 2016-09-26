<script>
	$(function(){
		$('#add_test').dialog({
		  autoOpen: false, width: 900
		});
		load_linked_test_list();
	});
	
	function add_test(){
		$('#add_test').dialog('open');
	}
	
	function link_test(id){
		url = "?link_item=" + id + "&item_table=blends&parent_table=<?=$parent_table?>&parent_id=<?=$parent_id?>";
		$.ajax(url).done(function(data){
			$('#add_test').dialog('close');
			load_linked_test_list();
		});
	}

	function load_linked_test_list(){
		url = "?get_link_items=true&parent_id=<?=$parent_id?>&parent_table=<?=$parent_table?>&item_table=blends";
		$.ajax(url).done(function(data){
			$("#linked_test_list").html(data);
		});
	}

	function remove_test_link(id){
		url = "?remove_link_item=" + id ;
		$.ajax(url).done(function(data){
			load_linked_test_list();
		});
	}
</script>	

<div id="linked_test_list">
		
</div>
	
<div id="add_test" title="Link a Test">
	<table class="table table-striped">
	<thead>
		<tr>
		<th>Type</th>
		<th>Name</th>
		<th>Description</th>
		<th>Action</th>
		</tr>
	</thead>
	<?php
	$samples = $view->get_all_tests();

	foreach($samples as $sample){
		echo "<tr>";
		echo "<td width='10%' nowrap='nowrap'>".$sample["blend_type"]."</td>";
		echo "<td width='10%' nowrap='nowrap'>".$sample["name"]."</td>";
		echo "<td>".$sample["description"]."</td>";
		echo "<td width='10%' nowrap='nowrap'>  <a href=\"javascript:void(0);\" onclick=\"link_test(".$sample["id"].")\";  class='btn btn-info btn-sm'>Link</a></td>";
		echo "</tr>";
	}
	?>
	</table>
</div>