<?php
$view->set_section('material');
$sample = $view->get_sample($view->url["sample"]);

?>
<script src="https://maps.googleapis.com/maps/api/js"></script>
<script>
<?php
if($sample['latitude'] != ''){
	if($sample['longitude'] !=''){
	?>
      function initialize() {
        var mapCanvas = document.getElementById('map');
        
        var myLatlng = new google.maps.LatLng(<?=$sample['latitude']?>, <?=$sample['longitude']?>);

        
        var mapOptions = {
          center: myLatlng,
          zoom: 12,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        var map = new google.maps.Map(mapCanvas, mapOptions)
        
        var marker = new google.maps.Marker({
    		position: myLatlng,
    		title:"Hello World!"
		});

		marker.setMap(map);
      }
      google.maps.event.addDomListener(window, 'load', initialize);
      <?php
	}
}

?>
function dsample(id){
      	if(confirm('Are you sure you want to delete this permanently')){
      		document.location.href='?dsample='+id;	
      	}
      }
    </script>
	
<div style="width:20%;" class="pull-right">
<a href="javascript:dsample(<?=$view->url["sample"]?>);" class='btn btn-info btn-sm pull-right'>Delete</a>
<a href="?esample=<?=$view->url["sample"]?>" class='btn btn-info btn-sm pull-left'>Edit</a>
<table class="table table-striped" >
	<thead>
		<tr>
			<th colspan=2>Raw Data</th>
		</tr>
	</thead>
		<?php
		
		foreach($sample as $key => $value){
			if($value==""){
				$value='0.00';
			}
			echo "<tr><td>$key</td><td style='text-align:right;'>$value</td></tr>";
		}
		?>
</table>	
</div>
<div style="width:80%;" class="pull-right">
	<div style="margin-right:20px;  margin-bottom:30px; height:400px; background-color:#efefef;" id="map">
	
	</div>
	<div style="margin-right:20px; margin-bottom:30px;">
	<?php
		$seger = $view->convert_to_seger($sample["id"]);
	?>
	
	<div class="pull-right" style="padding:8px;">Formula Weight <strong><?=round($seger["formula_weight"], 4) ?></strong>  |  Silica / Alumina ratio : <strong><?=round(($seger['RO2']['SiO2']/$seger['R2O3']['Al2O3']), 4)?></strong></div><div style="padding:8px; font-weight:bold;">Seger</div>
	<div style="border-top:1px solid #dedede; padding:20px; background-color:#efefef;">
		<table cellpadding="10" align=center>
		<tr>
		<td valign=middle style="padding-right:40px;">
		<?php
		foreach($seger['RO'] as $key =>$value){
			if($value != 0){
				echo "<span style='font-weight:bold; width:100px; display:inline-block'>". $key ."</span> ". round($value , 4)."<br>";
			}
		}
		?>
		</td>
		<td style="padding-right:40px;">
		<?php
		foreach($seger['R2O3'] as $key =>$value){
			if($value != 0){
				echo "<span style='font-weight:bold; width:100px; display:inline-block'>". $key ."</span> ". round($value , 4)."<br>";
			}
		}
		?>
		</td>
		<td>
		<?php
		foreach($seger['RO2'] as $key =>$value){
			if($value != 0){
				echo "<span style='font-weight:bold; width:100px; display:inline-block'>". $key ."</span> ". round($value , 4)."<br>";
			}
		}
		?>
		</td>
		</tr>
	</table>
	
	</div>
	
	</div>
	<a href="javascript:add_document();" class='btn btn-info btn-sm' style="font-weight:300;">Add Documents</a>
	<div id="tabs-2">
		<?php 
		$parent_id = $sample['id'];
		$parent_table = "samples";
		include('template/select_document.php');
		?>
	</div>
	
</div>