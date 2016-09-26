<script>
	$(function(){
		$('#add_formula').dialog({
		  autoOpen: false, width: 900
		});
		load_linked_formula_list();
	});
	
	function add_formula(){
		$('#add_formula').dialog('open');
	}
	
	function link_formula(id){
		url = "?link_item=" + id + "&item_table=formula&parent_table=<?=$parent_table?>&parent_id=<?=$parent_id?>";
		$.ajax(url).done(function(data){
			$('#add_formula').dialog('close');
			load_linked_test_list();
		});
	}

	function load_linked_formula_list(){
		url = "?get_link_items=true&parent_id=<?=$parent_id?>&parent_table=<?=$parent_table?>&item_table=formula";
		$.ajax(url).done(function(data){
			$("#linked_formula_list").html(data);
		});
	}

	function remove_formula_link(id){
		url = "?remove_link_item=" + id ;
		$.ajax(url).done(function(data){
			load_linked_test_list();
		});
	}
</script>	

<div id="linked_formula_list">
		
</div>
	
<div id="add_formula" title="Link a Test">
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
		echo "<tr>";
		echo "<td>".$sample["Name"]."</td>";
		echo "<td>".$sample["Comments"]."</td>";
		echo "<td width='10%' nowrap='nowrap'>  <a href=\"javascript:void(0);\" onclick=\"link_formula(".$sample["id"].")\";  class='btn btn-info btn-sm'>Link</a></td>";
		echo "</tr>";
	}
	?>
	</table>
</div>