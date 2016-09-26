<?php
$view->set_section('recipes');
$recipes = $view->build_similar_recipes($view->url["build_recipe"]);

?>
	<h2>Most similar recipes</h2>
	
<?php
	foreach($recipes as $recipe){
		//var_dump($recipe).'<br><br><br>';
		//for
		//echo $recipe["path"];
		//$parts = explode(",", $recipe["path"]);
		?>
		<table class="table table-striped">
			<thead >
				<tr>
					<td>Amount</td>
					<td>Type</td>
					<td>Material</td>
				</tr>
 		   </thead>
  		  <tbody id="parts">	
  		  <?php 
  		  foreach($recipe["path"] as $part){
   		 	//$part = explode(":", $part);
   		 	$sample = $view->get_sample($part['id']);
   		     ?>
   		 	<tr>
   		 		<td>
   		 		<?=round(($part['id']*$part['moles']), 2);?>	
   		 		</td>
   		 		<td>
   		 		<?=$sample["type"]?>
   		 		</td>
   		 		<td>
   		 		<?=$sample["Comments"]?> <a  href="?sample=<?=$sample["id"]?>">view</a>
   		 		</td>
   		 	</tr>
 		   <?php
 		   $last = $part;
   		 }
   		 var_dump($last['remains']);
  		  ?>
  		  
  		  </tbody>	
		</table>
		
		<?php
		
	}

?>

