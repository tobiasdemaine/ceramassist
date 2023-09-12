<?php
function get_IP_address()
{
    foreach (array('HTTP_CLIENT_IP',
                   'HTTP_X_FORWARDED_FOR',
                   'HTTP_X_FORWARDED',
                   'HTTP_X_CLUSTER_CLIENT_IP',
                   'HTTP_FORWARDED_FOR',
                   'HTTP_FORWARDED',
                   'REMOTE_ADDR') as $key){
        if (array_key_exists($key, $_SERVER) === true){
            foreach (explode(',', $_SERVER[$key]) as $IPaddress){
                $IPaddress = trim($IPaddress); // Just to be safe

                if (filter_var($IPaddress,
                               FILTER_VALIDATE_IP,
                               FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)
                    !== false) {

                    return $IPaddress;
                }
            }
        }
    }
}

$settings = $view->get_all_settings();
foreach($settings as $setting){
	if($setting['key'] == "update_server"){
		$filepath = $setting["value"] . "software/version.php";
		$file = file_get_contents($filepath);
		$update = json_decode($file, true);
	}
	if($setting['key'] == "version"){
		$version = $setting["value"];
	}
}


?>
<div class='pull-right bg-info' style="width:30%;">
	<h4>Settings</h4>
	<table class="table table-striped">
		<tr>
			<td>
			db
			</td>
			<td>
			<a href="assets/db/phpliteadmin.php" target="_blank">phpliteadmin</a>
			</td>
		</tr>
		<tr>
			<td>
			server
			</td>
			<td>
			<?="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?><br>
			<?="http://".get_IP_address()."/".$_SERVER['REQUEST_URI'];?>;
			</td>
		</tr>
		
	<?php
	$settings = $view->get_all_settings();
	foreach($settings as $setting){
	?>
		<tr>
			<td>
			<?=$setting['key'];?>	
			</td>
			<td>
			<?=$setting['value'];?>
			</td>
		</tr>
	<?php
	}
	?>
	</table>
	
	
	
	
	
</div>
<div class='pull-left bg-info' style="width:70%; height:300px;">
	<?php
	if(isset($update)){
		if($version < $update["version"]){
			?>
			<div style="margin-bottom:40px;">
				<h2>A new update is available <a href="#" class="btn pull-right" style="margin-right:10px;">UPDATE NOW</a></h2>
				<ul>
					<?php 
					foreach($update["version_contents"] as $content){
						echo "<li>".$content."</li>";
					}
					?>
				</ul>
			</div>
			<?php
		}
	
		foreach($update["info"] as $info){
			?>
			<div class="info_box">
				<div>
					<?=$info?>
				</div>
			</div>
			<?php
		}
	}else{
	
	}
	?>
</div>

<style>
.info_box{
	width:100%;
	float:left;
}
.info_box div{
	margin-right:5px;
	margin-bottom:5px;
	min-height: 200px;
	background: #efefef;
	padding-left:10px;
	
}
</style>






