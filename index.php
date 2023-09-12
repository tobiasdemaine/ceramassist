<?php
set_time_limit(10000);
ini_set('post_max_size', '5000M');
ini_set('upload_max_filesize', '5000M');


require_once('assets/lib/yocto_api.php');
require_once('assets/lib/yocto_temperature.php');

class ceramassist{
	public $url = array();
   	public $user;
   	private $return_data;
   	private $db;
   	public $iserror = false;
   	public $render_type = false;
   	public $supress_render = false;
   	public $alert = false;
   	public $oxides = array('Na2O', 'K2O', 'CaO', 'MnO', 'MgO', 'BaO', 'ZnO', 'PbO', 'Li2O', 'SrO', 'Al2O3', 'P2O5', 'B2O3', 'Fe2O3', 'SiO2', 'TiO2', 'ZrO2');
				
   	// define molecular weights
   	public $mw = array();
    public $section = "";
	

 	function __construct() {
 		// Molecular Oxide Weights
 	
 		$this->mw["Na2O"] = 61.982;
		$this->mw["K2O"] = 94.20;
 		$this->mw["Al2O3"] = 101.94;
 		$this->mw["MnO"] = 70.94;
		$this->mw["MgO"] = 40.32;
		$this->mw["CaO"] = 56.08;
 	
 		$this->mw["FeO"] = 71.85;
		
		
		$this->mw["H2O"] = 18.016;
		$this->mw["SiO2"] = 60.09;
		$this->mw["TiO2"] = 79.90;
		
		
		$this->mw["Fe2O3T"] = 159.70;
		$this->mw["Fe2O3"] = 159.70;
		$this->mw["SO3"] = 80.066;
		$this->mw["P2O5"] = 141.95;
		$this->mw["BaO"] = 153.36;
		$this->mw["ZnO"] = 81.38;
		$this->mw["PbO"] = 223.21;
		$this->mw["Li2O"] = 29.88;
		$this->mw["SrO"] = 103.63;
		$this->mw["B2O3"] = 69.64;
		$this->mw["ZrO2"] = 123.22;
		$this->mw["CO2"] = 44.010;
 	
 	
			
		$this->db = new PDO('sqlite:assets/db/ceramassist');
    	// Set errormode to exceptions
    	$this->db->setAttribute(PDO::ATTR_ERRMODE, 
                            PDO::ERRMODE_EXCEPTION);
          
		session_start();
		$this->prepare_requests();
		if($this->iserror == false){
			$this->process_requests();
			if($this->iserror == false){
				if(!$this->supress_render){
					$this->render();
				}
			}else{
				echo $this->iserror;
				die;
			}
		}else{
			echo $this->iserror;
			die;
		}
		
				
		
	}
	
	private function prepare_requests(){
	    foreach($_GET as $key => $value){
			$this->url[$key] = $value;
		}
		foreach($_POST as $key => $value){
			$this->url[$key] = $value;
		}
	}
	
	private function process_requests(){
	
	
		if(isset($this->url["sample"])){
			 $this->render_type = "template/sample.php";
		}
		if(isset($this->url["samples"])){
			 $this->render_type = "template/samples.php";
		}
		if(isset($this->url["formula"])){
			 $this->render_type = "template/formula.php";
		}
		if(isset($this->url["documents"])){
			 $this->render_type = "template/documents.php";
		}
		if(isset($this->url["firings"])){
			 $this->render_type = "template/firings.php";
		}
		if(isset($this->url["orders"])){
			 $this->render_type = "template/orders.php";
		}
		if(isset($this->url["add_kiln"])){
			 $this->render_type = "template/add_kiln.php";
			 
		}
		if(isset($this->url["add_shop"])){
			 $this->render_type = "template/add_shop.php";
			 
		}
		
		if(isset($this->url["add_webstore"])){
			 $this->render_type = "template/add_webstore.php";
		}
		
		if(isset($this->url["add_exhibition"])){
			 $this->render_type = "template/add_exhibition.php";
		}
		
		if(isset($this->url["update_kiln"])){
			 echo $this->add_kiln();
			exit();
			 
		}
		
		if(isset($this->url["update_exhibition"])){
			 echo $this->add_exhibition();
			exit();
		}

		if(isset($this->url["update_shop"])){
			 echo $this->add_shop();
			exit();
		}
		
		if(isset($this->url["delete_webstore"])){
			$this->delete_webstore($this->url["delete_webstore"]);
			 echo "<script> document.location.href='?distribution=true';</script>";
		}

		if(isset($this->url["delete_shop"])){
			$this->delete_shop($this->url["delete_shop"]);
			 echo "<script> document.location.href='?distribution=true';</script>";
		}

				
		if(isset($this->url["update_webstore"])){
			 echo $this->add_webstore();
			exit();
		}

		if(isset($this->url["link_ware_to_shop"])){
			 echo $this->link_ware_to_shop();
			exit();
		}

		if(isset($this->url["update_link_ware_to_shop"])){
			 echo $this->update_link_ware_to_shop();
			exit();
		}
		
		if(isset($this->url["update_link_ware_to_exhibition"])){
			 echo $this->update_link_ware_to_exhibition();
			exit();
		}
		
		if(isset($this->url["link_ware_to_exhibition"])){
			 echo $this->link_ware_to_exhibition();
			exit();
		}
		
		if(isset($this->url["link_ware_to_webstore"])){
			 echo $this->link_ware_to_webstore();
			exit();
		}

		if(isset($this->url["update_link_ware_to_webstore"])){
			 echo $this->update_link_ware_to_webstore();
			exit();
		}


		if(isset($this->url["delete_ware_from_shop"])){
			 echo $this->delete_ware_from_shop($this->url["delete_ware_from_shop"]);
			 exit();
		}
		
		if(isset($this->url["delete_ware_from_webstore_by_wooid"])){
			 echo $this->delete_ware_from_webstore_by_wooid($this->url["delete_ware_from_webstore_by_wooid"]);
			 exit();
		}
		
		
		if(isset($this->url["add_schedule"])){
			 $this->render_type = "template/add_schedule.php";
			 
		}
		if(isset($this->url["update_schedule"])){
			echo $this->add_schedule($this->url["update_schedule"]);
			exit();
		}
		
		if(isset($this->url["delete_schedule"])){
			$this->delete_schedule($this->url["delete_schedule"]);
			$this->render_type = "template/firings.php";
		}
		
		if(isset($this->url["clear_firing_data"])){
			 $this->clear_firing_data();
			 $this->url["firing"] = $this->url["clear_firing_data"];
			 echo "<script> document.location.href='?firing={$this->url["clear_firing_data"]}';</script>";
			 exit();
		}
		if(isset($this->url["firing"])){
			 $this->render_type = "template/firing.php";
		}
		if(isset($this->url["add_firing"])){
			 echo $this->add_firing();
			 exit();
		}
		if(isset($this->url["add_firing_item"])){
			 echo $this->add_firing_item();
			 exit();
		}
		if(isset($this->url["get_firing_comments_as_html"])){
			 echo $this->get_firing_comments_as_html($this->url["get_firing_comments_as_html"]);
			 exit();
		}
		
		if(isset($this->url["delete_firing_item"])){
			echo $this->delete_firing_item($this->url["delete_firing_item"]);
			exit;
		}		
		
		if(isset($this->url["upload_document"])){
			$this->upload_document();
			$this->render_type = "template/documents.php";
		}
		
		if(isset($this->url["upload_document_module"])){
			$id = $this->upload_document();
			if($id != "error"){
				$this->link_item_to_section($this->url["parent_table"], $this->url["parent_id"], 'documents', $id);
			}
			echo "<script>parent.load_linked_document_list();add_document.dialog('close')</script>";
			exit;
		}
		
		if(isset($this->url["ware"])){
			 $this->render_type = "template/ware.php";
		}
		if(isset($this->url["add_new_ware"])){
			 echo $this->add_new_ware();
			 exit();
		}
		
		if(isset($this->url["update_ware"])){
			 echo $this->update_ware();
			 exit();
		}
		
		//

		if(isset($this->url["firing_attach"])){
			 echo json_encode($this->firing_attach());
			 exit();
		}
		
		if(isset($this->url["get_schedule"])){
			 echo json_encode($this->get_schedule($this->url["get_schedule"]));
			 exit();
		}
		
		//
		
		if(isset($this->url["delete_ware"])){
			 echo $this->delete_ware($this->url["delete_ware"]);
			 exit();
		}
		
		if(isset($this->url["delete_kiln"])){
			$this->delete_kiln($this->url["delete_kiln"]);
			 echo "<script> document.location.href='?firings=true';</script>";
		}
		
		
		if(isset($this->url["view_ware_list"])){
			 echo $this->get_ware_list($this->url["view_ware_list"]);
			 exit();
		}
		if(isset($this->url["load_ware"])){
			 echo $this->load_ware($this->url["load_ware"]);
			 exit();
		}
		
		if(isset($this->url["tests"])){
			 $this->render_type = "template/tests.php";
		}
		if(isset($this->url["test"])){
			 $this->render_type = "template/test.php";
		}
		if(isset($this->url["set_thermo"])){
			 echo $this->set_thermo();
			 exit();
		}
		if(isset($this->url["get_thermo"])){
			 echo $this->get_thermo();
			 exit();
		}
		if(isset($this->url["recipes"])){
			 $this->render_type = "template/recipes.php";
		}
		if(isset($this->url["distribution"])){
			 $this->render_type = "template/distribution.php";
		}
		
		if(isset($this->url["recipe"])){
			 $this->render_type = "template/recipe.php";
		}
		if(isset($this->url["new_recipe"])){
			 $this->render_type = "template/add_recipe.php";
		}
		
		if(isset($this->url["build_recipe"])){
			 $this->render_type = "template/build_recipe.php";
		}
		if(isset($this->url["esample"])){
			 $this->render_type = "template/editsample.php";
		}
		
		if(isset($this->url["eformula"])){
			 $this->render_type = "template/editformula.php";
		}
		
		if(isset($this->url["viewformula"])){
			 $this->render_type = "template/viewformula.php";
		}
		
		if(isset($this->url["dsample"])){
			$this->dsample();
			echo "<script> document.location.href='?';</script>";
			exit();
		}
		
		
		
		if(isset($this->url["aesample"])){
			 echo "<script> document.location.href='?sample=".$this->add_sample()."';</script>";
			 exit();
		}
		
		if(isset($this->url["aeformula"])){
			 echo "<script> document.location.href='?formula=".$this->add_formula()."';</script>";
			 exit();
		}
		
		if(isset($this->url["add_ware_cat"])){
			$this->add_ware_cat();
			echo "<script> document.location.href='?ware=true';</script>";
			 exit();
		}
		
		
		if(isset($this->url["add_doc_cat"])){
			$this->add_doc_cat();
			echo "<script> document.location.href='?documents=true';</script>";
			 exit();
		}
		
		if(isset($this->url["add_recipe"])){
			 echo "<script> document.location.href='?recipe=".$this->add_recipe()."';</script>";
			 exit();
		}
		if(isset($this->url["update_recipe"])){
			 echo "<script> document.location.href='?recipe=".$this->update_recipe()."';</script>";
			 exit();
		}
		if(isset($this->url["delete_recipe"])){
			 $this->delete_recipe();
			 echo "<script> document.location.href='?recipes=true';</script>";
			 exit();
		}
		if(isset($this->url["delete_firing"])){
			 $this->delete_firing();
			 echo "<script> document.location.href='?firings=true';</script>";
			 exit();
		}
		if(isset($this->url["delete_document"])){
			 $this->delete_document($this->url["delete_document"]);
			 exit();
		}
		if(isset($this->url["delete_document_cat"])){
			 $this->delete_document_cat($this->url["delete_document_cat"]);
			 exit();
		}
		if(isset($this->url["view_docs"])){
			echo $this->get_document_list($this->url["view_docs"]);
			exit;
		}
		if(isset($this->url["preview_docs"])){
			echo $this->get_link_document_list($this->url["preview_docs"]);
			exit;
		}
		
		if(isset($this->url["link_item"])){
			$this->link_item_to_section($this->url["parent_table"], $this->url["parent_id"], $this->url["item_table"], $this->url["link_item"]);
			exit;
		}
		
		
		if(isset($this->url["remove_link_item"])){
			
			$this->remove_link_item($this->url["remove_link_item"]);
			exit;
		}
		
		
		if(isset($this->url["get_link_items"])){
			echo $this->get_link_item_list($this->url["parent_table"], $this->url["parent_id"], $this->url["item_table"]);
			exit;
		}
		
		
		if(isset($this->url["get_link_items_json"])){
			echo $this->get_link_item_json($this->url["parent_table"], $this->url["parent_id"], $this->url["item_table"]);
			exit;
		}

	}
	
	
		
	
	private function delete_document_cat($id){
		$sql = "update `documents` set `parent`='0' where `parent`='{$id}';";
		$result = $this->db->query($sql);
		$sql = "delete from `doccats` where `id`='{$id}';";
		$result = $this->db->query($sql);
	}
	
	
	private function edit_document_cat($id, $parent, $name){
		$sql = "update `documents` set `parent`='{$parent}', `name`='{$name}' where `parent`='{$id}';";
		$result = $this->db->query($sql);
	}
	
	private function upload_document(){
		
		$file_name = "assets/files/";
		$path_info = pathinfo($_FILES['uploadfile']['name']);
		if(isset($path_info['extension'])){
			$file_name = $file_name . strtoupper(dechex((microtime(true)*10000))).".".$path_info['extension'];
		}else{
			$file_name = $file_name . strtoupper(dechex((microtime(true)*10000)));
		}	
		if(!move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file_name)){
			$file_name = "error";	
			return "error";
		}else{
			$sql = "insert into documents (`filename`, `parent`, `original_file_name`, `description`) values ('{$file_name}','".$this->url['parent']."','".$_FILES['uploadfile']['name']."','".$this->url["description"]."')";
			$result = $this->db->query($sql);
			return $this->db->lastInsertId();
		
		}
		 
		
	}
	
	private function setup_thermo_connect(){
	  if(yRegisterHub('http://127.0.0.1:4444/',$errmsg) != YAPI_SUCCESS) {
          die("Cannot contact VirtualHub on 127.0.0.1");
  	  }
	  $temp = yFirstTemperature();
      if(is_null($temp)) {
          die("No module connected (check USB cable)");
      } else {
          $serial = $temp->module()->get_serialnumber();
      }
      
      $this->temp1 = yFindTemperature($serial.".temperature1");
      $this->temp2 = yFindTemperature($serial.".temperature2");
	}
	
	public function set_thermo(){
	  $this->setup_thermo_connect();
      $pyro = json_decode($this->url["set_thermo"]);
      $t1 = $this->temp1->set_sensorType($pyro->temperature1);
      $t2 = $this->temp2->set_sensorType($pyro->temperature2);
  	  $t = "{".$t1.":".$t2."}";
  	  return $t;
  	}
	public function get_thermo(){
	  $this->setup_thermo_connect();
	  $pyro = array();
	  $pyro["temperature1"] = $this->temp1->get_currentValue();
	  $pyro["temperature2"] = $this->temp2->get_currentValue();
	  $pyro["timestamp"] = time(); 
	  if($this->url["get_thermo"]=="log"){
		if(isset($this->url["fid"])){
			// insert  
			$sql = "INSERT INTO `firing_temperature`(`fid`, `ttimestamp`, `thermocouple`, `temperature`) VALUES ('".$this->url["fid"]."', '".$pyro["timestamp"]."', '1', '".$pyro["temperature1"]."')";
			if($result = $this->db->query($sql)){
				//return true;
			}else{
			//	echo $this->db->errorInfo();
			}
			
			$sql = "INSERT INTO `firing_temperature`(`fid`, `ttimestamp`, `thermocouple`, `temperature`) VALUES ('".$this->url["fid"]."', '".$pyro["timestamp"]."', '2', '".$pyro["temperature2"]."')";
			if($result = $this->db->query($sql)){
				//return true;
			}else{
				//echo $this->db->errorInfo();
			}
			
		}
	  }
	  return json_encode($pyro);
	}


	public function firing_attach(){
		if($this->url["schedule"]==0){
			$sql = "UPDATE `firings` set `schedule`='' where `id`='".$this->url["firing_attach"]."';";
		}else{
			$sql = "UPDATE `firings` set `schedule`='".$this->url["schedule"]."' where `id`='".$this->url["firing_attach"]."';";
		}
		$result = $this->db->query($sql);
		return $this->get_schedule($this->url["schedule"]);
	}

	
	public function add_firing_item(){
		if(trim($this->url["comment"])!=""){
			
			$sql = "INSERT INTO `firing_temperature`(`fid`, `ttimestamp`, `thermocouple`, `temperature`, `comment`) VALUES ('".$this->url["fid"]."', '".strtotime($this->url["datetime"])."', '', '', '".$this->url["comment"]."')";
			if($result = $this->db->query($sql)){
				//return true;
			}else{
				//echo $this->db->errorInfo();
			}
		}
		if(trim($this->url["temp1"])!=""){
			$sql = "INSERT INTO `firing_temperature`(`fid`, `ttimestamp`, `thermocouple`, `temperature`) VALUES ('".$this->url["fid"]."', '".strtotime($this->url["datetime"])."', '1', '".$this->url["temp1"]."')";
			if($result = $this->db->query($sql)){
				//return true;
			}else{
				//echo $this->db->errorInfo();
			}
		}	
		if(trim($this->url["temp2"])!=""){
			$sql = "INSERT INTO `firing_temperature`(`fid`, `ttimestamp`, `thermocouple`, `temperature`) VALUES ('".$this->url["fid"]."', '".strtotime($this->url["datetime"])."', '2', '".$this->url["temp2"]."')";
			if($result = $this->db->query($sql)){
				//return true;
			}else{
				//echo $this->db->errorInfo();
			}
		}
		$data = $this->get_firing_data($this->url["fid"]);
		if(!isset($data['thermo1'])){
			$thermo1 = "";
		}else{
			$thermo1 = implode(",",$data['thermo1']);
		}
		if(!isset($data['thermo2'])){
			$thermo2 = "";
		}else{
			$thermo2 = implode(",",$data['thermo2']);
		}
		
		
			
		return "<script> var thermo1 = [".$thermo1."]; var thermo2 = [".$thermo2."]; redraw_graph(); </script>";
		

	}
	
	public function delete_schedule($id){
		if(isset($id)){
			$sql = 'delete from `firing_schedule` where `id`="'.$id.'";';
			if($result = $this->db->query($sql)){
				return true;
			}else{
				//echo $this->db->errorInfo();
			}
		}
	}

	
	public function dsample(){
		if(isset($this->url['dsample'])){
			$sql = 'delete from `samples` where `id`="'.$this->url['dsample'].'";';
			if($result = $this->db->query($sql)){
				return true;
			}else{
				//echo $this->db->errorInfo();
			}
		}
	}
	
	public function delete_firing(){
		if(isset($this->url['delete_firing'])){
			$sql = 'delete from `firings` where `id`="'.$this->url['delete_firing'].'";';
			if($result = $this->db->query($sql)){
				return true;
			}else{
				//echo $this->db->errorInfo();
			}
		}
	}
	
	public function clear_firing_data(){
		$sql = 'delete from `firing_temperature` where `fid`="'.$this->url['clear_firing_data'].'";';
			if($result = $this->db->query($sql)){
				return true;
			}else{
				//echo $this->db->errorInfo();
			}
	}
	
	public function get_firing($id){
		$sql = "select * from `firings` where id=$id;";
		if($result = $this->db->query($sql)){
			$row = $result->fetch(PDO::FETCH_ASSOC);
			return $row;
		}
	}
	
	public function get_kiln($id){
		$sql = "select * from `kilns` where id=$id;";
			if($result = $this->db->query($sql)){
			$row = $result->fetch(PDO::FETCH_ASSOC);
			return $row;
		}
	}
	
	public function get_shop($id){
		$sql = "select * from `shops` where id=$id";
		if($result = $this->db->query($sql)){
			$row = $result->fetch(PDO::FETCH_ASSOC);
			return $row;
		}
	}
	public function get_exhibition($id){
		$sql = "select * from `exhibitions` where id=$id";
		if($result = $this->db->query($sql)){
			$row = $result->fetch(PDO::FETCH_ASSOC);
			return $row;
		}
	}
	
	public function get_webstore($id){
		$sql = "select * from `webstore` where id=$id";
		if($result = $this->db->query($sql)){
			$row = $result->fetch(PDO::FETCH_ASSOC);
			return $row;
		}
	}
	
	public function get_schedule($id){
		$sql = "select * from `firing_schedule` where id=$id;";
		if($result = $this->db->query($sql)){
			$row = $result->fetch(PDO::FETCH_ASSOC);
			return $row;
		}
	}
	
	public function sqlite_escape_string($str){
		return str_replace("'", "''", $str);
	}
	
	public function add_kiln(){
		if($this->url["programmable"]=='true'){
			$this->url["programmable"] = 1;
		}else{
			$this->url["programmable"] = 0;
		}
		if($this->url['update_kiln']=='0'){
			//insert
			
			$sql = "insert into `kilns` (`type`,  `name`, `description`, `notes`, `programmable` ) values ('".$this->sqlite_escape_string($this->url["type"])."','".$this->sqlite_escape_string($this->url["name"])."','".$this->sqlite_escape_string($this->url["description"])."','".$this->sqlite_escape_string($this->url["notes"])."','{$this->url["programmable"]}')";
			$result = $this->db->query($sql);
			return $this->db->lastInsertId();
		}else{
			$sql = "update `kilns` set `type`='".$this->sqlite_escape_string($this->url["type"])."', `programmable`='{$this->url["programmable"]}', `description`='".$this->sqlite_escape_string($this->url["description"])."', `name`='".$this->sqlite_escape_string($this->url["name"])."',  `notes`='".$this->sqlite_escape_string($this->url["notes"])."' where `id`='{$this->url['update_kiln']}';";
			$result = $this->db->query($sql);
			return $this->url['update_kiln'];
		}
	}
	
	public function link_ware_to_shop(){
		$sql = "insert into `shop_links` ( `shopid`, `wareid`, `qty`, `sold`, `notes`, `price`) values ('".$this->sqlite_escape_string($this->url["link_ware_to_shop"])."', '".$this->sqlite_escape_string($this->url["ware_id"])."', '".$this->sqlite_escape_string($this->url["ware_qty"])."', '".$this->sqlite_escape_string($this->url["ware_sold"])."', '".$this->sqlite_escape_string($this->url["ware_notes"])."', '".$this->sqlite_escape_string($this->url["ware_price"])."');";
		$result = $this->db->query($sql);
		return $this->db->lastInsertId();
	}


	public function link_ware_to_exhibition(){
		$sql = "insert into `exhibition_links` ( `shopid`, `wareid`, `qty`, `sold`, `notes`, `price`) values ('".$this->sqlite_escape_string($this->url["link_ware_to_exhibition"])."', '".$this->sqlite_escape_string($this->url["ware_id"])."', '".$this->sqlite_escape_string($this->url["ware_qty"])."', '".$this->sqlite_escape_string($this->url["ware_sold"])."', '".$this->sqlite_escape_string($this->url["ware_notes"])."', '".$this->sqlite_escape_string($this->url["ware_price"])."');";
		$result = $this->db->query($sql);
		return $this->db->lastInsertId();
	}
	
	
	
	public function update_link_ware_to_shop(){
		
		$sql = "update `shop_links` set `qty`='".$this->sqlite_escape_string($this->url["ware_qty"])."', `sold`='".$this->sqlite_escape_string($this->url["ware_sold"])."', `notes`='".$this->sqlite_escape_string($this->url["ware_notes"])."', `price`='".$this->sqlite_escape_string($this->url["ware_price"])."' where `id`='".$this->sqlite_escape_string($this->url["update_link_ware_to_shop"])."';";
		
		$result = $this->db->query($sql);
		return $this->url["update_link_ware_to_shop"];
	}
	
	public function update_link_ware_to_exhibition(){
		
		$sql = "update `exhibition_links` set `qty`='".$this->sqlite_escape_string($this->url["ware_qty"])."', `sold`='".$this->sqlite_escape_string($this->url["ware_sold"])."', `notes`='".$this->sqlite_escape_string($this->url["ware_notes"])."', `price`='".$this->sqlite_escape_string($this->url["ware_price"])."' where `id`='".$this->sqlite_escape_string($this->url["update_link_ware_to_exhibition"])."';";
		
		$result = $this->db->query($sql);
		return $this->url["update_link_ware_to_exhibition"];
	}
	
	
	public function link_ware_to_webstore(){
		$sql = "insert into `webstore_links` ( `storeid`, `wareid`, `wooid`) values ('".$this->sqlite_escape_string($this->url["link_ware_to_webstore"])."', '".$this->sqlite_escape_string($this->url["ware_id"])."', '".$this->sqlite_escape_string($this->url["woo_id"])."');";
		echo $sql;
		$result = $this->db->query($sql);
		return $this->db->lastInsertId();
	}
	
	public function update_link_ware_to_webstore(){
		//$sql = "update `shop_links` set `qty`='".$this->sqlite_escape_string($this->url["ware_qty"])."', `sold`='".$this->sqlite_escape_string($this->url["ware_sold"])."', `notes`='".$this->sqlite_escape_string($this->url["ware_notes"])."', `price`='".$this->sqlite_escape_string($this->url["ware_price"])."' where id='".$this->sqlite_escape_string($this->url["link_ware_to_webstore"])."';";
		
		$result = $this->db->query($sql);
		return $this->db->lastInsertId();
	}
	
	
	public function get_link_from_webstore($webstore_id, $woo_id){
		$sql = "select * from `webstore_links` where `storeid`='$webstore_id' and `wooid`='$woo_id';";
		$result = $this->db->query($sql);
		$row = $result->fetch(PDO::FETCH_ASSOC);
		return $row;
	} 
	
	
	public function add_shop(){
		
		if($this->url['id']=='0'){
			$sql = "insert into `shops` ( `name`) values ('".$this->sqlite_escape_string($this->url["name"])."');";
			$result = $this->db->query($sql);
			$this->url['id'] = $this->db->lastInsertId();
			
		}
		
		foreach($this->url as $key => $value){
			if($key != "id"){
				if($key != "update_shop"){
					$sql = 'update `shops` set `'.$key.'`="'.$value.'" where `id` = "'.$this->url['id'].'";';
					$result = $this->db->query($sql);
					//echo $this->db->errorInfo();
				}
			}
		}
		
		return $this->url['id'];
		
	}
	
	public function add_exhibition(){
		
		if($this->url['id']=='0'){
			$sql = "insert into `exhibitions` ( `name`) values ('".$this->sqlite_escape_string($this->url["name"])."');";
			$result = $this->db->query($sql);
			$this->url['id'] = $this->db->lastInsertId();
			
		}
		
		foreach($this->url as $key => $value){
			if($key != "id"){
				if($key != "update_exhibition"){
					$sql = 'update `exhibitions` set `'.$key.'`="'.$value.'" where `id` = "'.$this->url['id'].'";';
					$result = $this->db->query($sql);
					//echo $this->db->errorInfo();
				}
			}
		}
		
		return $this->url['id'];
		
	}
	
	public function add_webstore(){
		
		if($this->url['id']=='0'){
			$sql = "insert into `webstore` ( `name`) values ('".$this->sqlite_escape_string($this->url["name"])."');";
			$result = $this->db->query($sql);
			$this->url['id'] = $this->db->lastInsertId();
			
		}
		
		foreach($this->url as $key => $value){
			if($key != "id"){
				if($key != "update_webstore"){
					$sql = 'update `webstore` set `'.$key.'`=\''.$value.'\' where `id` = "'.$this->url['id'].'";';
					$result = $this->db->query($sql);
					//echo $this->db->errorInfo();
				}
			}
		}
		
		return $this->url['id'];
		
	}
	
	
	public function add_schedule($schedule){
		$schedule = json_decode($schedule, true);
		
		if($schedule['id']=='0'){
			//insert
			$sql = "insert into `firing_schedule` (`type`, `description`, `atmosphere`, `points` ) values ('{$schedule["type"]}','{$schedule["description"]}','{$schedule["atmosphere"]}','".json_encode($schedule["points"])."')";
			
			$result = $this->db->query($sql);
			
			return $this->db->lastInsertId();
		}else{
			$sql = "update `firing_schedule` set `type`='{$schedule["type"]}', `description`='{$schedule["description"]}', `atmosphere`='{$schedule["atmosphere"]}', `points`='".json_encode($schedule["points"])."' where `id`='{$schedule['id']}';";
			$result = $this->db->query($sql);
			return $schedule['id'];
		}
	}
	
	
	public function delete_firing_item($id){
		$sql = "select * from `firing_temperature` where id=$id;";
		if($result = $this->db->query($sql)){
			$row = $result->fetch(PDO::FETCH_ASSOC);
			$sql = 'delete from `firing_temperature` where `ttimestamp`="'.$row["ttimestamp"].'" and`fid`="'.$row["fid"].'";';
			if($result = $this->db->query($sql)){
				$data = $this->get_firing_data($row["fid"]);
				if(!isset($data['thermo1'])){
					$thermo1 = "";
				}else{
					$thermo1 = implode(",",$data['thermo1']);
				}
				if(!isset($data['thermo2'])){
					$thermo2 = "";
				}else{
					$thermo2 = implode(",",$data['thermo2']);
				}
				return "<script> var thermo1 = [".$thermo1."]; var thermo2 = [".$thermo2."]; redraw_graph(); </script>";
			}else{
				//echo $this->db->errorInfo();
			}
		}
	}
	
	
	public function get_firing_comments_as_html($id){
		$sql = "select * from `firing_temperature` where `fid`='$id' and `comment`<>'' order by `ttimestamp` DESC;";
		if($result = $this->db->query($sql)){
			$data = "<table  class=\"table table-striped\"><thead><tr><th>Time</th><th>Probe 1</th><th>Probe 2</th><th>Comment</th><th>Action</th></tr>";
			while($row = $result->fetch(PDO::FETCH_ASSOC)){
				// sort date here
				
				$t1 = $this->get_temp_from_time($id, $row['ttimestamp'], 1);
				$t2 = $this->get_temp_from_time($id, $row['ttimestamp'], 2);
				$data .= "<tr id='en_".$id."'><td width=10% nowrap>". gmdate("Y-m-d H:i:s", ($row['ttimestamp']+(60*60*2))) ."</td><td width='10%'>".$t1."</td><td width='10%'>".$t2."</td><td>".$row["comment"]."</td><td width=10%><a href='javascript:void(0);' onclick='del(".$row['id'].")' >delete</a></td><tr>";
			}
			return $data . "</table>";
		}
	}
	
	public function get_temp_from_time($id, $ts, $temp){
		$sql = "select * from `firing_temperature` where `fid`='$id' and `ttimestamp`='$ts' and `thermocouple` = '$temp'";
		if($result = $this->db->query($sql)){
			$row = $result->fetch(PDO::FETCH_ASSOC);
			return $row['temperature'];
		}else{
			return '-';
		}
	}
	
	public function get_firing_data($id){
		$sql = "select * from `firing_temperature` where `fid`=$id  order by `ttimestamp` DESC;";
		if($result = $this->db->query($sql)){
			$data = array();
			while($row = $result->fetch(PDO::FETCH_ASSOC)){
				if($row['thermocouple']==1){
					if(!isset($data['thermo1'])){
						$data['thermo1'] = array();
					}
					$data['thermo1'][] = "[".(($row['ttimestamp']+(60*60*2))*1000).",".$row['temperature']."]";
				}
				if($row['thermocouple']==2){
					if(!isset($data['thermo2'])){
						$data['thermo2'] = array();
					}
					$data['thermo2'][] = "[".(($row['ttimestamp']+(60*60*2))*1000).",".$row['temperature']."]";
				}	
			}
			return $data;
		}
	}
	
	public function add_firing(){
		$firing = json_decode($this->url["add_firing"], true);
		if($firing['id']==0){
			$sql = "INSERT INTO `firings`(`date`) VALUES ('".$firing['date']."')";
			if($result = $this->db->query($sql)){
				$firing["id"] = $this->db->lastInsertId();
			}
		}
		if(isset($firing["id"])){
			$this->update_firing($firing);
		}
		return $firing["id"];
	}
	
	
	public function update_firing($firing){
		foreach($firing as $key => $value){
			if($key != "id"){
				$sql = 'update `firings` set `'.$key.'`="'.$value.'" where `id` = "'.$firing['id'].'";';
				$result = $this->db->query($sql);
				//echo $this->db->errorInfo();
			}
		}
	}
	
	public function add_sample(){
		$sample = array();
		//var_dump($this->url);
		foreach($this->url as $key => $value){
			if($key != "aesample"){
				if($key == "newtype"){
					if($value != ""){
						$sample["type"] = $value;
						unset($sample[$key]);

					}
				}else{
					$sample[$key] = $value;
				}
				
				
				if($key == "newset"){
					if($value != ""){
						$sample["data_set"] = $value;
						
					}
					//echo $sample[$key] . " death";
					//die;
				}else{
					$sample[$key] = $value;
				}
				
			}	
			
		}
		unset($sample["newset"]);
		unset($sample["newtype"]);
		if(!isset($this->url['id'])){
			$sql = "INSERT INTO `samples`(`type`) VALUES ('".$sample['type']."')";
			if($result = $this->db->query($sql)){
				$sample["id"] = $this->db->lastInsertId();
			}
		}
		if(isset($sample["id"])){
			$this->update_sample($sample);
		}
		return $sample["id"];
	}
	
	
	public function update_sample($sample){
		foreach($sample as $key => $value){
			if($key != "id"){
				$sql = 'update `samples` set `'.$key.'`="'.$value.'" where `id` = "'.$sample['id'].'";';
				$result = $this->db->query($sql);
				//echo $this->db->errorInfo();
			}
		}
	}
	
	public function add_formula(){
		$sample = array();
		//var_dump($this->url);
		foreach($this->url as $key => $value){
			if($key != "aeformula"){
				$sample[$key] = $value;
			}	
		}
		
		if(!isset($this->url['id'])){
			$sql = "INSERT INTO `formula`(`type`) VALUES ('".$sample['type']."')";
			if($result = $this->db->query($sql)){
				$sample["id"] = $this->db->lastInsertId();
			}
		}
		if(isset($sample["id"])){
			$this->update_formula($sample);
		}
		return $sample["id"];
	}
	
	
	public function update_formula($sample){
		foreach($sample as $key => $value){
			if($key != "id"){
				$sql = 'update `formula` set `'.$key.'`="'.$value.'" where `id` = "'.$sample['id'].'";';
				$result = $this->db->query($sql);
				//echo $this->db->errorInfo();
			}
		}
	}
	
	
	public function get_blend($id){
		$sql = "select * from `blends` where id=$id;";
		if($result = $this->db->query($sql)){
			$row = $result->fetch(PDO::FETCH_ASSOC);
			return $row;
		}
	}
		
	
	public function get_sample($id){
		$sql = "select * from `samples` where id=$id;";
		if($result = $this->db->query($sql)){
			$row = $result->fetch(PDO::FETCH_ASSOC);
			return $row;
		}
	}
	
	public function get_formula($id){
		$sql = "select * from `formula` where id=$id;";
		if($result = $this->db->query($sql)){
			$row = $result->fetch(PDO::FETCH_ASSOC);
			return $row;
		}
	}
	
	
	
	public function convert_to_seger($id){
		$seger = "";
		
		
		
		$sample = $this->get_sample($id);
		$RO = array();
		 
		
		
		
		$RO["K2O"] = floatval($sample["K2O"]) / $this->mw["K2O"];
		$RO["Na2O"] = floatval($sample["Na2O"]) / $this->mw["Na2O"];
		$RO["CaO"] = floatval($sample["CaO"]) / $this->mw["CaO"];
		$RO["MnO"] = floatval($sample["MnO"]) / $this->mw["MnO"];
		$RO["MgO"] = floatval($sample["MgO"]) / $this->mw["MgO"];
		$RO["BaO"] = floatval($sample["BaO"]) / $this->mw["BaO"];
		$RO["ZnO"] = floatval($sample["ZnO"]) / $this->mw["ZnO"];
		$RO["PbO"] = floatval($sample["PbO"]) / $this->mw["PbO"];
		$RO["Li2O"] = floatval($sample["Li2O"]) / $this->mw["Li2O"];
		$RO["SrO"] = floatval($sample["SrO"]) / $this->mw["SrO"];
		//$RO["FeO"] = floatval($sample["FeO"]) / $this->mw["FeO"];
		//$RO["H2O"] = floatval($sample["H2O"]) / $this->mw["H2O"];
		
		$R2O3 = array();
		
		$R2O3["Al2O3"] = floatval($sample["Al2O3"]) / $this->mw["Al2O3"];
		//$R2O3["SO3"] = floatval($sample["SO3"] / $this->mw["SO3"];
		$R2O3["P2O5"] = floatval($sample["P2O5"]) / $this->mw["P2O5"];	
		//$R2O3["Fe2O3T"] = floatval($sample["Fe2O3T"] / $this->mw["Fe2O3T"];
		$R2O3["B2O3"] = floatval($sample["B2O3"]) / $this->mw["B2O3"];
		$R2O3["Fe2O3"] = (floatval($sample["FeO"]) + floatval($sample["Fe2O3"])) / $this->mw["Fe2O3"];
		

		
		$RO2 = array();
		
		$RO2["SiO2"] = floatval($sample["SiO2"]) / $this->mw["SiO2"];
		$RO2["TiO2"] = floatval($sample["TiO2"]) / $this->mw["TiO2"];
		$RO2["ZrO2"] = floatval($sample["ZrO2"]) / $this->mw["ZrO2"];
		//$RO2["CO2"] = floatval($sample["CO2"] / $this->mw["CO2"];
	
		$RO_unity = 0;
		foreach($RO as $key => $value){
			$RO_unity += $value;
		}
		if($RO_unity == 0){
			foreach($R2O3 as $key => $value){
				$RO_unity += $value;
			}
		}
		if($RO_unity == 0){
			foreach($RO2 as $key => $value){
				$RO_unity += $value;
			}
		}
		
		
		$RO_fu = array();
		foreach($RO as $key => $value){
			
		
			$RO_fu[$key] = $RO[$key] / $RO_unity;
		}
		
		$R2O3_fu = array();
		foreach($R2O3 as $key => $value){
			$R2O3_fu[$key] = $R2O3[$key] / $RO_unity;
		}
		
		$RO2_fu = array();
		foreach($RO2 as $key => $value){
			$RO2_fu[$key] = $RO2[$key] / $RO_unity;
		}
		
		$formula_weight = 0;
		foreach($RO as $key => $value){
			$formula_weight += ($RO_fu[$key] * $this->mw[$key]);
		}
		
		foreach($R2O3 as $key => $value){
			$formula_weight += ($R2O3_fu[$key] * $this->mw[$key]);
		}
		
		foreach($RO2 as $key => $value){
			$formula_weight += ($RO2_fu[$key] * $this->mw[$key]);
		}
		$seger = array();
		$seger["formula_weight"] = $formula_weight;
		$seger["id"] = $id;
		$seger["RO"] = $RO_fu;
		$seger["R2O3"] = $R2O3_fu;
		$seger["RO2"] = $RO2_fu;
		
		return $seger;
	}
	
	
	public function test_to_seger($test){
		 
		
		$parts = $this->test_to_recipe($test);
		$samples = array();
   		$c = 0;
   		
   		$recipe = array();
   		$recipe['RO'] = array();
   		$recipe['R2O3'] = array();
   		$recipe['RO2'] = array();
   		
   		
   		$recipe['RO']["K2O"] = 0;
		$recipe['RO']["Na2O"] = 0;
		$recipe['RO']["CaO"] = 0;
		$recipe['RO']["MnO"] = 0;
		$recipe['RO']["MgO"] = 0;
		$recipe['RO']["BaO"] = 0;
		$recipe['RO']["ZnO"] = 0;
		$recipe['RO']["PbO"] = 0;
		$recipe['RO']["Li2O"] = 0;
		$recipe['RO']["SrO"] = 0;
		$recipe['RO']["FeO"] = 0;
		//$RO["H2O"] = $sample["H2O"] / $this->mw["H2O"];
		
		$recipe['R2O3']["Al2O3"] = 0;
		$recipe['R2O3']["P2O5"] = 0;	
		$recipe['R2O3']["B2O3"] = 0;
		$recipe['R2O3']["Fe2O3"] = 0;
		

		
		$recipe['RO2']["SiO2"] = 0;
		$recipe['RO2']["TiO2"] = 0;
		$recipe['RO2']["ZrO2"] = 0;
   		
   		foreach($parts as $key => $part){
    		//$part = explode(",", $part);
    		$samples[$c] = array();
    	    $samples[$c]['sample'] = $this->get_sample($key);
    	    $samples[$c]['id'] = $key; 
    	    $samples[$c]['part'] = $part; 
    	    $samples[$c]['seger'] = $this->convert_to_seger($samples[$c]['id']);
    	    $samples[$c]['molecular_equivalent'] = $samples[$c]['part']/$samples[$c]['seger']["formula_weight"];
    	        	    
    	    foreach($samples[$c]['seger']['RO'] as $oxide => $value){
    	    	/*if(!isset($recipe['RO'][$oxide])){
    	    		$recipe['RO'][$oxide] = 0;
    	    	}*/
    	    	$recipe['RO'][$oxide] += ($samples[$c]['molecular_equivalent'] * $value);
    	    }
    	    foreach($samples[$c]['seger']['R2O3'] as $oxide => $value){
    	    	/*if(!isset($recipe['RO'][$oxide])){
    	    		$recipe['R2O3'][$oxide] = 0;
    	    	}*/
    	    	$recipe['R2O3'][$oxide] += ($samples[$c]['molecular_equivalent'] * $value);
    	    }
    	    foreach($samples[$c]['seger']['RO2'] as $oxide => $value){
    	    	/*if(!isset($recipe['RO'][$oxide])){
    	    		echo $oxide.";";
    	    		$recipe['RO2'][$oxide] = 0;
    	    	}*/
    	    	
    	    	$recipe['RO2'][$oxide] = $recipe['RO2'][$oxide] + ($samples[$c]['molecular_equivalent'] * $value);
    	    }
    	    
    	    $c++;
    	}
    	$RO_unity = 0;
		foreach($recipe['RO'] as $key => $value){
			$RO_unity += $value;
		}
		
		
		$RO_fu = array();
		foreach($recipe['RO'] as $key => $value){
			$recipe['RO'][$key] = $value / $RO_unity;
		}
		
		
		$R2O3_fu = array();
		foreach($recipe['R2O3'] as $key => $value){
			$recipe['R2O3'][$key] = $value / $RO_unity;
		}
		
		$RO2_fu = array();
		foreach($recipe['RO2'] as $key => $value){
			$recipe['RO2'][$key] = $value / $RO_unity;
		}
    	
    	
    	$formula_weight = 0;
    	foreach($recipe['RO'] as $key => $value){
			$formula_weight += $value * $this->mw[$key];
		}
		
		foreach($recipe['R2O3'] as $key => $value){
			$formula_weight += $value * $this->mw[$key];
		}
		
		foreach($recipe['RO2'] as $key => $value){
			$formula_weight += $value * $this->mw[$key];
		}

    	$recipe['formula_weight'] = $formula_weight;
    	return $recipe;
	}
	
	public function test_to_recipe($test){
		$in = 100;
		$_parts = array();
		foreach($test as $corner ){
			$recipe = $this->get_recipe($corner[0]);
			$parts = json_decode($recipe["parts"], true);
			$total = 0;
   			foreach($parts as $part){
    			$part = explode(",", $part);
    			if(isset($_parts[$part[1]])){
    				$_parts[$part[1]] = $_parts[$part[1]] + (($part[0]/100)*$corner[1]);
    			}else{
    				$_parts[$part[1]] = (($part[0]/100)*$corner[1]);
    			}
    			$total = $total + $_parts[$part[1]];
    		}
    	}
		return $_parts;
		
    		
    		
	}
	
	
	public function convert_recipe_to_seger($id){
   		// loop through each part and get the seger of each
   		$_recipe = $this->get_recipe($id);
   		$parts = json_decode($_recipe["parts"], true);
   		$samples = array();
   		$c = 0;
   		
   		$recipe = array();
   		$recipe['RO'] = array();
   		$recipe['R2O3'] = array();
   		$recipe['RO2'] = array();
   		
   		
   		$recipe['RO']["K2O"] = 0;
		$recipe['RO']["Na2O"] = 0;
		$recipe['RO']["CaO"] = 0;
		$recipe['RO']["MnO"] = 0;
		$recipe['RO']["MgO"] = 0;
		$recipe['RO']["BaO"] = 0;
		$recipe['RO']["ZnO"] = 0;
		$recipe['RO']["PbO"] = 0;
		$recipe['RO']["Li2O"] = 0;
		$recipe['RO']["SrO"] = 0;
		$recipe['RO']["FeO"] = 0;
		//$RO["H2O"] = $sample["H2O"] / $this->mw["H2O"];
		
		$recipe['R2O3']["Al2O3"] = 0;
		$recipe['R2O3']["P2O5"] = 0;	
		$recipe['R2O3']["B2O3"] = 0;
		$recipe['R2O3']["Fe2O3"] = 0;
		

		
		$recipe['RO2']["SiO2"] = 0;
		$recipe['RO2']["TiO2"] = 0;
		$recipe['RO2']["ZrO2"] = 0;
   		
   		foreach($parts as $part){
    		$part = explode(",", $part);
    		$samples[$c] = array();
    	    $samples[$c]['sample'] = $this->get_sample($part[1]);
    	    $samples[$c]['id'] = $part[1]; 
    	    $samples[$c]['part'] = $part[0]; 
    	    $samples[$c]['seger'] = $this->convert_to_seger($samples[$c]['id']);
    	    $samples[$c]['molecular_equivalent'] = $samples[$c]['part']/$samples[$c]['seger']["formula_weight"];
    	        	    
    	    foreach($samples[$c]['seger']['RO'] as $oxide => $value){
    	    	/*if(!isset($recipe['RO'][$oxide])){
    	    		$recipe['RO'][$oxide] = 0;
    	    	}*/
    	    	$recipe['RO'][$oxide] += ($samples[$c]['molecular_equivalent'] * $value);
    	    }
    	    foreach($samples[$c]['seger']['R2O3'] as $oxide => $value){
    	    	/*if(!isset($recipe['RO'][$oxide])){
    	    		$recipe['R2O3'][$oxide] = 0;
    	    	}*/
    	    	$recipe['R2O3'][$oxide] += ($samples[$c]['molecular_equivalent'] * $value);
    	    }
    	    foreach($samples[$c]['seger']['RO2'] as $oxide => $value){
    	    	/*if(!isset($recipe['RO'][$oxide])){
    	    		echo $oxide.";";
    	    		$recipe['RO2'][$oxide] = 0;
    	    	}*/
    	    	
    	    	$recipe['RO2'][$oxide] = $recipe['RO2'][$oxide] + ($samples[$c]['molecular_equivalent'] * $value);
    	    }
    	    
    	    $c++;
    	}
    	$RO_unity = 0;
		foreach($recipe['RO'] as $key => $value){
			$RO_unity += $value;
		}
		
		
		$RO_fu = array();
		foreach($recipe['RO'] as $key => $value){
			$recipe['RO'][$key] = $value / $RO_unity;
		}
		
		$R2O3_fu = array();
		foreach($recipe['R2O3'] as $key => $value){
			$recipe['R2O3'][$key] = $value / $RO_unity;
		}
		
		$RO2_fu = array();
		foreach($recipe['RO2'] as $key => $value){
			$recipe['RO2'][$key] = $value / $RO_unity;
		}
    	
    	
    	$formula_weight = 0;
    	foreach($recipe['RO'] as $key => $value){
			$formula_weight += $value * $this->mw[$key];
		}
		
		foreach($recipe['R2O3'] as $key => $value){
			$formula_weight += $value * $this->mw[$key];
		}
		
		foreach($recipe['RO2'] as $key => $value){
			$formula_weight += $value * $this->mw[$key];
		}

    	$recipe['formula_weight'] = $formula_weight;
    	return $recipe;
    	
   		
    }
	
	
	private function flatten_seger($seger){
		$flat = array();
		foreach($seger as $key => $value){
			if(is_array($value)){
				foreach($value as $k => $v){
					$flat[$k] = $v;
				}
			}else{
				$flat[$key] = $value;
			}
		
		}
		return $flat;
	}
	
	
	private function test_oxide(&$parent, $base, $recipe, $oxide){
		$matches = array();
		foreach($base as $sample){
			// best match Na20 K20
			$sample_seger = $this->flatten_seger($this->convert_to_seger($sample['id']));
			
			if($sample_seger[$oxide] != 0){
				$not = array('formula_weight', 'id', 'moles', 'used', 'remains', 'sub_parts', 'path');
				$moles_part = $recipe[$oxide]/$sample_seger[$oxide];
				$sample_seger['moles'] = $moles_part; 
				$sample_seger['used'] = array();
				$sample_seger['remains'] = array();
				$match = false;
				foreach($sample_seger as $key => $value){	
					if(!in_array($key, $not)){
						if(isset($recipe[$key])){
							if($sample_seger[$key] > 0){
								
								
								//	echo $key.":".$recipe[$key]."<br>";
								//	echo $key . ":" . $recipe[$key]."<br>";
									if(($sample_seger[$key] * $sample_seger['moles']) <= $recipe[$key]){
								
										$match = true;
						 			
						 				$sample_seger['used'][$key] = round($sample_seger[$key] * $sample_seger['moles'], 4);
						 				$sample_seger['remains'][$key] = round($recipe[$key] - ($sample_seger[$key] * $sample_seger['moles']), 4);
									}else{
										//$match = false;
										//break;
									}
								}else{
									/*if(isset($recipe['remains'])){
										$sample_seger['remains'][$key] = $recipe['remains'][$key];
										$match = true;
										break;
									}*/
								}
						}else{
							$match = false;
							break;	//$match = false;
								//	break;
						}
						//}
					}
				}
				if($match == true){
				 	$parent[$sample_seger['id']] = $sample_seger;
				}
			}
		}
	}
	
	private function test_oxides(&$mixes, $base){
		if(is_array($mixes)){
			foreach($mixes as $key => $value){
				foreach($this->oxides as $oxide){
					if(isset($mixes[$key]['remains'][$oxide])){
						if($mixes[$key]['remains'][$oxide] > 0.001){
							$this->test_oxide($mixes[$key]['sub_mix'], $base, $mixes[$key]['remains'], $oxide);
							$this->test_oxides($mixes[$key]['sub_mix'], $base);
						}
					}
				}
			
			}
		}
	}
	
	private function tally_remains(&$base_mix, $parent_id, $mixes){
		foreach($mixes as $key => $mix){
			if(isset($mixes[$key]['sub_mix'])){
				if(is_array($mixes[$key]['sub_mix'])){
					$base_mix[$parent_id]['path'][] = $mix;
					$this->tally_remains($base_mix, $parent_id, $mixes[$key]['sub_mix']);
			
				}else{
					// we are at an end point
					$c = 0;
					foreach($this->oxides as $oxide){
						if(isset($mixes[$key]['remains'][$oxide])){
							$c = ($mixes[$key]['remains'][$oxide] + $c);
						}
					}
					$base_mix[$parent_id]['remainder'] = $c;
				}
			}
		}
	}
	
	
	
	
	public function build_similar_recipes($id){
		$base = $this->get_all_samples();
		$flat_seger = $this->flatten_seger($this->convert_recipe_to_seger($id));
		$mixes = array();
		$this->test_oxide($mixes, $base, $flat_seger, 'Na2O');
		$this->test_oxides($mixes, $base);
		foreach($mixes as $key => $value){
			//echo $key;
			$mixes[$key]['path'][] = $value;
			$this->tally_remains($mixes, $key, $mixes);
			//echo $key . " path : " . $mixes[$key]['path'] . " :: " . $mixes[$key]['remainder']."<BR>";
		}
			
		return $mixes; //$recipes;
	}
	

	
	
	public function convert_to_calculated_analysis($id){
		$ua = array();
		$ua["console"] = "";
		$sample = $this->get_sample($id);
		$K2O = $sample["K2O"] * 5.32; 
		$K2O_Al2O3 = $K2O * 0.183;
		$K2O_SiO2 = $K2O * 0.647;
		
		$Na2O = $sample["Na2O"] * 5.32; 
		$Na2O_Al2O3 = $Na2O * 0.183;
		$Na2O_SiO2 = $Na2O * 0.647;
		
		$remaining_Al2O3 = $sample["Al2O3"] - ($K2O_Al2O3 + $Na2O_Al2O3); 
		$remaining_SiO2 = $sample["SiO2"] - ($K2O_SiO2 + $Na2O_SiO2); 
		
		$ua['console'] .= "K2O $K2O<br>";
		$ua['console'] .= "K2O_Al2O3 $K2O_Al2O3<br>";
		$ua['console'] .= "K2O_SiO2 $K2O_SiO2<br>";
		$ua['console'] .= "Na2O $Na2O<br>";
		$ua['console'] .= "Na2O_Al2O3  $Na2O_Al2O3<br>";
		$ua['console'] .= "Na2O_SiO2 $Na2O_SiO2<br>";
		$ua['console'] .= "Remaining Al2O3 ".$remaining_Al2O3."<br>";
		$ua['console'] .= "Remaining SiO2 ".$remaining_SiO2."<br>";
		
		
		$anothrite = $sample["CaO"] * 4.964;
		$anthorite_Al2O3 = $anothrite * 0.3669;
		$anthorite_SiO2 = $anothrite * 0.4317;
		$remaining_Al2O3_after_anothrite = $remaining_Al2O3 - $anthorite_Al2O3; 
		$remaining_SiO2_after_anothrite = $remaining_SiO2 - $anthorite_SiO2; 
		
		
		$ua['console'] .= "<hr>Anthorite $anothrite<br>";
		$ua['console'] .= "Anthorite Al2O3  $anthorite_Al2O3<br>";
		$ua['console'] .= "Anthorite SiO2 $anthorite_SiO2<br>";
		$ua['console'] .= "Remaining Al2O3 ".$remaining_Al2O3_after_anothrite."<br>";
		$ua['console'] .= "Remaining SiO2 ".$remaining_SiO2_after_anothrite."<br>";
		
		
		if($sample["CO2"] > 0 || $sample["LOI"] != ""){ // loss of ignition // CO2
			$calcite = $sample["CaO"] * 1.786;
			$calcite_C02 = $calcite * 0.44;
			$ua['console'] .= "<hr>Calcite $calcite<br>";
			$ua['console'] .= "Calcite C02 $calcite_C02<br>";
			
		}
			
		
		if($sample["P2O5"] != ""){ // 
			$apatite = $sample["P2O5"] * 2.366;
			$apatite_CaO = $apatite * 0.56;
			$apatite_remaining_CaO = $sample["CaO"] - $apatite_CaO;
			
			$ua['console'] .= "<hr>Apatite $apatite<br>";
			$ua['console'] .= "Apatite CaO $apatite_CaO<br>";
			$ua['console'] .= "Apatite remaining CaO $apatite_remaining_CaO<br>";
			
			$sphene = $apatite_remaining_CaO * 3.43;
			//$sphene_TiO2 = $sphene * 0.5;
			//$sphene_SiO2;
			$ua['console'] .= "Sphene $sphene";
			
		}
		
		echo $ua["console"];
		
	}
	
	
	public function add_blend($blend){
		$sql = "INSERT INTO `blends`(`name`, `description`, `corner_1`, `corner_2`, `corner_3`, `corner_4`, `blend_type`, `dimension`, `data_set`) VALUES ('".$blend->name."','".$blend->description."','".$blend->corner_1."','".$blend->corner_2."','".$blend->corner_3."','".$blend->corner_4."','".$blend->blend_type."', '".$blend->dimension."', '".$blend->data_set."')";
		if($result = $this->db->query($sql)){
			return $this->db->lastInsertId();

		}
	}
	
	public function update_blend($blend){
		$sql = "UPDATE `blends` SET `name`='".$blend->name."',`description`='".$blend->description."',`corner_1`='".$blend->corner_1."',`corner_2`='".$blend->corner_2."',`corner_3`='".$blend->corner_3."',`corner_4`='".$blend->corner_4."',`blend_type`='".$blend->blend_type."',`dimension`='".$blend->dimension."',`data_set`='".$blend->data_set."' WHERE `id`='".$blend->id."';";
		if($result = $this->db->query($sql)){
			return true;

		}
	}
	
	public function delete_blend(){
		$sql = "delete from `blends` where `id`='".$this->url['delete']."';";
		if($result = $this->db->query($sql)){
			return true;

		}
	}
	
	public function add_recipe(){
		$sql = "INSERT INTO `recipe`(`data_set`, `type`, `name`, `parts`, `description`, `cone`) VALUES ('".$this->url['data_set']."','".$this->url['type']."','".$this->url['name']."','".$this->url['parts']."','".$this->url['description']."','".$this->url['cone']."')";
		if($result = $this->db->query($sql)){
			return $this->db->lastInsertId();

		}
	}
	
	public function update_recipe(){
		$sql = "update `recipe` set `data_set`='".$this->url['data_set']."', `type`='".$this->url['type']."', `name`='".$this->url['name']."', `parts`='".$this->url['parts']."', `description`='".$this->url['description']."', `cone`='".$this->url['cone']."' where `id`='".$this->url['update_recipe']."';";
		echo $sql;
		if($result = $this->db->query($sql)){
			return $this->url['update_recipe'];

		}
	}
	
	public function delete_recipe(){
		$sql = "delete from `recipe` where `id`='".$this->url['delete_recipe']."';";
		if($result = $this->db->query($sql)){
			return true;

		}
	}
	
	public function get_recipe($id){
   		$sql = "select * from `recipe` where `id`='".$id."';";
   		if($result = $this->db->query($sql)){
   			$row = $result->fetch(PDO::FETCH_ASSOC);
   				$item = array();
   				foreach ($row as $key => $value){
					$item[$key] = $value;
				}
			
   			return $item;
   		}else{
   		   // echo $this->db->errorInfo();
   			return false;
   		}
   		
   }
   
   public function add_doc_cat(){
   		if($this->url["add_doc_cat"] !='true'){
   			$sql = "update `doccats` set `name`='{$this->url['name']}', `parent`= '{$this->url['parent']}' where `id`='{$this->url["add_doc_cat"]}'";
   			$result = $this->db->query($sql);
   		}else{
   			$sql = "INSERT INTO `doccats` (`name`, `parent`) VALUES ( '{$this->url['name']}', '{$this->url['parent']}');";
   			$result = $this->db->query($sql);
   		}
   } 
   
   public function get_document_cat_children($parent){
		$sql = "select * from doccats where parent='$parent'; ";
		if($result = $this->db->query($sql)){
   			$children = array();
   			while($row = $result->fetch(PDO::FETCH_ASSOC)){
				$children[] = $value;
			}
		}
		return $children;
	}
	
	private function delete_document($id){
		$sql = "select * from documents where id='{$id}';";
		if($result = $this->db->query($sql)){
   			$row = $result->fetch(PDO::FETCH_ASSOC);
   			unlink($row['filename']);
   			$sql = "delete from documents where id='{$id}';";	
   			if($result = $this->db->query($sql)){
   				return true;
   			}
   		}
	}
	
	
	private function delete_ware_from_shop($id){
		$sql = "delete from shop_links where id='{$id}';";	
   		if($result = $this->db->query($sql)){
   			return true;
   		}
   	}


	private function delete_ware_from_webstore_by_wooid($id){
		$sql = "delete from webstore_links where wooid='{$id}';";	
   		if($result = $this->db->query($sql)){
   			return true;
   		}
   	}
   	
   	
   	public function shop_get_instock_qty($shopid){
   		$sql = "select * from `shop_links` where shopid='$shopid';";
   		if($result = $this->db->query($sql)){
   			$qty = 0;
   			while($row = $result->fetch(PDO::FETCH_ASSOC)){
				$qty = $qty + ($row["qty"]-$row["sold"]);
			}
			return $qty;
		}
   	}
   	
   	public function shop_get_instock_value($shopid){
   		$sql = "select * from `shop_links` where shopid='$shopid';";
   		if($result = $this->db->query($sql)){
   			$qty = 0;
   			while($row = $result->fetch(PDO::FETCH_ASSOC)){
				$qty = $qty + (($row["qty"]-$row["sold"])*$row["price"]);
			}
			return $qty;
		}
   	}
   	
   	public function shop_get_sold_qty($shopid){
   		$sql = "select * from `shop_links` where shopid='$shopid';";
   		if($result = $this->db->query($sql)){
   			$qty = 0;
   			while($row = $result->fetch(PDO::FETCH_ASSOC)){
				$qty = $qty + $row["sold"];
			}
			return $qty;
		}
   	}
   	
   	public function shop_get_sold_value($shopid){
   		$sql = "select * from `shop_links` where shopid='$shopid';";
   		if($result = $this->db->query($sql)){
   			$qty = 0;
   			while($row = $result->fetch(PDO::FETCH_ASSOC)){
				$qty = $qty + ($row["sold"]*$row["price"]);
			}
			return $qty;
		}
   	}


	public function exhibition_get_sold_value($shopid){
   		$sql = "select * from `exhibition_links` where shopid='$shopid';";
   		if($result = $this->db->query($sql)){
   			$qty = 0;
   			while($row = $result->fetch(PDO::FETCH_ASSOC)){
				$qty = $qty + ($row["sold"]*$row["price"]);
			}
			return $qty;
		}
   	}



	public function webstore_get_instock_qty($shopid){
   		$sql = "select * from `webstore_links` where storeid='$shopid';";
   		if($result = $this->db->query($sql)){
   			$qty = 0;
   			while($row = $result->fetch(PDO::FETCH_ASSOC)){
				$qty = $qty + ($row["qty"]-$row["sold"]);
			}
			return $qty;
		}
   	}
   	
   	public function webstore_get_instock_value($shopid){
   		$sql = "select * from `webstore_links` where storeid='$shopid';";
   		if($result = $this->db->query($sql)){
   			$qty = 0;
   			while($row = $result->fetch(PDO::FETCH_ASSOC)){
				$qty = $qty + (($row["qty"]-$row["sold"])*$row["price"]);
			}
			return $qty;
		}
   	}
   	
   	public function webstore_get_sold_qty($shopid){
   		$sql = "select * from `webstore_links` where storeid='$shopid';";
   		if($result = $this->db->query($sql)){
   			$qty = 0;
   			while($row = $result->fetch(PDO::FETCH_ASSOC)){
				$qty = $qty + $row["sold"];
			}
			return $qty;
		}
   	}
   	
   	public function webstore_get_sold_value($shopid){
   		$sql = "select * from `webstore_links` where storeid='$shopid';";
   		if($result = $this->db->query($sql)){
   			$qty = 0;
   			while($row = $result->fetch(PDO::FETCH_ASSOC)){
				$qty = $qty + ($row["sold"]*$row["price"]);
			}
			return $qty;
		}
   	}


	public function get_exhibition_ware_list($shopid){
		$html = "<table class='table table-striped' width='100%'><thead><tr><th>Ware</th><th>Price Each</th><th>Instock Qty</th><th>Instock $</th><th>Sold Qty</th><th>Sold $</th><th>Action</th></tr></thead><tbody>";
		$sql = "select * from `exhibition_links` where `shopid`='$shopid'";
		$result = $this->db->query($sql);
		
		while($row = $result->fetch(PDO::FETCH_ASSOC)){
			$ware = json_decode($this->load_ware($row['wareid']), true);
			$html .= "<tr><td nowrap width=20%><a href='javascript:iload_ware({$row['wareid']})'>".$ware["name"]."</a></td>";
			$html .= "<td>".$row["price"]."</td>";
			$html .= "<td>".($row["qty"]-$row["sold"])."</td>";
			$html .= "<td>".(($row["qty"]-$row["sold"])*$row["price"])."</td>";
			$html .= "<td>".$row["sold"]."</td>";
			$html .= "<td>".($row["sold"]*$row["price"])."</td>";
							
			$html .= "<td width='20%'><a href='javascript:edit_link_ware_from_exhibition(\"{$row["id"]}\", \"{$row['qty']}\", \"{$row['sold']}\", \"{$row['price']}\", \"{$row['notes']}\")' class='btn'>update</a> <a href='javascript:delete_ware_from_exhibition({$row["id"]})' class='btn'>delete</a></td></tr>";
			//	var_dump($row);
			
		}
		$html .= "</tbody></table>";
		return $html;
	}
	
	
	public function get_shop_ware_list($shopid){
		$html = "<table class='table table-striped' width='100%'><thead><tr><th>Ware</th><th>Price Each</th><th>Instock Qty</th><th>Instock $</th><th>Sold Qty</th><th>Sold $</th><th>Action</th></tr></thead><tbody>";
		$sql = "select * from `shop_links` where `shopid`='$shopid'";
		$result = $this->db->query($sql);
		
		while($row = $result->fetch(PDO::FETCH_ASSOC)){
			$ware = json_decode($this->load_ware($row['wareid']), true);
			$html .= "<tr><td nowrap width=20%><a href='javascript:iload_ware({$row['wareid']})'>".$ware["name"]."</a></td>";
			$html .= "<td>".$row["price"]."</td>";
			$html .= "<td>".($row["qty"]-$row["sold"])."</td>";
			$html .= "<td>".(($row["qty"]-$row["sold"])*$row["price"])."</td>";
			$html .= "<td>".$row["sold"]."</td>";
			$html .= "<td>".($row["sold"]*$row["price"])."</td>";
			
			$html .= "<td width='20%'><a href='javascript:edit_link_ware_from_shop(\"{$row["id"]}\", \"{$row['qty']}\", \"{$row['sold']}\", \"{$row['price']}\", \"{$row['notes']}\")' class='btn'>update</a> <a href='javascript:delete_ware_from_shop({$row["id"]})' class='btn'>delete</a></td></tr>";
		//	var_dump($row);
		}
		$html .= "</tbody></table>";
		return $html;
	}
	
	public function get_webstore_ware_list($shopid){
		$html = "<table class='table table-striped' width='100%'><thead><tr><th>Ware</th><th>Price Each</th><th>Instock Qty</th><th>Instock $</th><th>Sold Qty</th><th>Sold $</th><th>Action</th></tr></thead><tbody>";
		$sql = "select * from `webstore_links` where `storeid`='$shopid'";
		$result = $this->db->query($sql);
		
		while($row = $result->fetch(PDO::FETCH_ASSOC)){
			$ware = json_decode($this->load_ware($row['wareid']), true);
			$html .= "<tr><td nowrap width=20%><a href='javascript:iload_ware({$row['wareid']})'>".$ware["name"]."</a></td>";
			$html .= "<td>".$row["price"]."</td>";
			$html .= "<td>".($row["qty"]-$row["sold"])."</td>";
			$html .= "<td>".(($row["qty"]-$row["sold"])*$row["price"])."</td>";
			$html .= "<td>".$row["sold"]."</td>";
			$html .= "<td>".($row["sold"]*$row["price"])."</td>";
			
			$html .= "<td width='20%'><a href='javascript:edit_link_ware_from_shop(\"{$row["id"]}\", \"{$row['qty']}\", \"{$row['sold']}\", \"{$row['price']}\", \"{$row['notes']}\")' class='btn'>update</a> <a href='javascript:delete_ware_from_shop({$row["id"]})' class='btn'>delete</a></td></tr>";
		}
		$html .= "</tbody></table>";
		return $html;
	}
	
	
	public function get_document_list($parent){
		$html = "<table class='table table-striped' width='100%'><thead><tr><th>File</th><th>Description</th><th>Action</th></tr></thead><tbody>";
		$sql = "select * from `documents` where `parent`='$parent'";
		$result = $this->db->query($sql);
		
		while($row = $result->fetch(PDO::FETCH_ASSOC)){
			if(in_array(strtolower(pathinfo($row["original_file_name"], PATHINFO_EXTENSION)), array('png', 'jpg', 'jpeg'))){
					$file_display = "<img src='".$row["filename"]."' height='80'>";
				}else{
					$file_display = $row["original_file_name"];
				}
			$html .= "<tr><td width=20%><a href='".$row["filename"]."' target='_blank'>".$file_display."</a></td><td>".$row["description"]."</td><td width='10%'><a href='javascript:delete_document(".$row["id"].",".$row["parent"].")'>delete</a></td></tr>";
		}
		$html .= "</tbody></table>";
		return $html;
	}
	
	public function get_link_document_list($parent){
		$html = "<table class='table table-striped' width='100%'><thead><tr><th>File</th><th>Description</th><th>Action</th></tr></thead><tbody>";
		$sql = "select * from `documents` where `parent`='$parent'";
		$result = $this->db->query($sql);
		
		while($row = $result->fetch(PDO::FETCH_ASSOC)){
			if(in_array(strtolower(pathinfo($row["original_file_name"], PATHINFO_EXTENSION)), array('png', 'jpg', 'jpeg'))){
				$file_display = "<img src='".$row["filename"]."' height='80'>";
			}else{
				$file_display = $row["original_file_name"];
			}
			$html .= "<tr><td  width=20%><a href='".$row["filename"]."' target='_blank'>".$file_display."</a></td><td>".$row["description"]."</td><td width='10%'><a href='javascript:link_document(".$row["id"].")' >Link</a></td></tr>";
		}
		$html .= "</tbody></table>";
		return $html;
	}
	
	public function link_item_to_section($parent_table, $parent_id, $item_table, $item_id){
		$sql = "insert into linked_items (`parent_table`, `parent_id`, `item_table`, `item_id`) values ('$parent_table', '$parent_id', '$item_table', '$item_id')";
		$result = $this->db->query($sql);
	}
	
	private function remove_link_item($id){
		$sql = "delete from `linked_items` where `id`='{$id}';";
		$result = $this->db->query($sql);
	}
	
	public function get_link_item_json($parent_table, $parent_id, $item_table){
		$sql = "select * from `linked_items` where `parent_table`='$parent_table' and `parent_id`='$parent_id' and `item_table`='$item_table';";
		$result = $this->db->query($sql);
		$data = array();
		while($row = $result->fetch(PDO::FETCH_ASSOC)){
			$sql2 = "select * from `$item_table` where `id`='".$row["item_id"]."';";
			$_result = $this->db->query($sql2);
			$_row = $_result->fetch(PDO::FETCH_ASSOC);
			array_push($data, $_row);
		}
		return json_encode($data);
	}
	
	
	public function get_link_item_list($parent_table, $parent_id, $item_table){
		if($item_table == "warecats"){
			$html = "<table class='table table-striped' width='100%'><thead><tr><th>Path</th><th> </th></tr></thead><tbody>";
		}else if($item_table == "firings"){
			$html = "<table class='table table-striped' width='100%'><thead><tr><th>date</th><th>kiln</th><th>firing type</th><th>atmosphere</th><th> </th></tr></thead><tbody>";
		}else if($item_table == "formula"){
			$html = "<table class='table table-striped' width='100%'><thead><tr><th>Name</th><th>Comments</th><th> </th></tr></thead><tbody>";
		}else{
			$html = "<table class='table table-striped' width='100%'><thead><tr><th>item</th><th>Description</th><th> </th></tr></thead><tbody>";
		}
		$sql = "select * from `linked_items` where `parent_table`='$parent_table' and `parent_id`='$parent_id' and `item_table`='$item_table';";
		$result = $this->db->query($sql);
		while($row = $result->fetch(PDO::FETCH_ASSOC)){
			$sql2 = "select * from `$item_table` where `id`='".$row["item_id"]."';";
			
			$_result = $this->db->query($sql2);
			$_row = $_result->fetch(PDO::FETCH_ASSOC);
			if($item_table == "documents"){
				//var_dump($_row);
				$isview = "<a href='javascript:remove_link(".$row["id"].")'>remove</a>";
				if(isset($this->url['isview'])){
					$isview = "";
				}
				if(in_array(strtolower(pathinfo($_row["original_file_name"], PATHINFO_EXTENSION)), array('png', 'jpg', 'jpeg'))){
					$file_display = "<img src='".$_row["filename"]."' height='80'>";
				}else{
					$file_display = $_row["original_file_name"];
				}
				
				$html .= "<tr><td width=20%><a href='".$_row["filename"]."' target='_blank'>".$file_display."</a></td><td>".$_row["description"]."</td><td width='10%'>".$isview."</td></tr>";
			}
			if($item_table == "blends"){
				$html .= "<tr><td width=20%><a href='?test=".$_row["id"]."' target='_blank'>".$_row["blend_type"]." : " . $_row["name"] . "</a></td><td>" . $_row["description"] . "</td><td width='10%'><a href='javascript:remove_test_link(".$row["id"].")'>remove</a></td></tr>";
			}
			if($item_table == "warecats"){
				if($_row["name"]==""){
					$_row["name"] = "/";
					$_row["id"] = 0;
				}
				$html .= "<tr><td nowrap width=90%>" . $this->warecatpath($_row["id"]) . "</a></td><td width='1%'><a href='javascript:remove_warecat_link(".$row["id"].")'>remove</a></td></tr>";
			}
			if($item_table == "recipe"){
				$isview = "<a href='javascript:remove_recipe_link(".$row["id"].")'>remove</a>";
				if(isset($this->url['isview'])){
					$isview = "";
				}
				$html .= "<tr><td width=20%><a href='?recipe=".$_row["id"]."' target='_blank'>".$_row["type"]." : " . $_row["name"] . "</a></td><td>" . $_row["description"] . "</td><td width='10%'>".$isview."</td></tr>";
			}
			if($item_table == "firings"){
				$isview = "<a href='javascript:remove_firing_link(".$row["id"].")'>remove</a>";
				if(isset($this->url['isview'])){
					$isview = "";
				}
				$kiln = $this->get_kiln($_row["kiln"]);
				$html .= "<td width='10%' nowrap='nowrap'>".$_row["date"]."</td>";
				$html .= "<td  nowrap='nowrap'>".$kiln["name"]."</td>";
				$html .= "<td>".$_row["firing_type"]."</td>";
				$html .= "<td>".$_row["atmosphere"]."</td>";
				$html .= "<td width='10%' nowrap='nowrap'>".$isview."</td>";
			}
			if($item_table == "ware"){
			
			}
			if($item_table == "formula"){
				$isview = "<a href='javascript:remove_formula_link(".$row["id"].")'>remove</a>";
				if(isset($this->url['isview'])){
					$isview = "";
				}
				$html .= "<td>".$_row['Name']."</td>";
				$html .= "<td>".$_row["Comments"]."</td>";
				$html .= "<td width='10%' nowrap='nowrap'>".$isview."</td>";
			}
			
		//	var_dump($row);
		}
		$html .= "</tbody></table>";
		return $html;
	}
	
	private function warecatpath($item){
		
		if($item!=0){
			$path = "";
			$this->_warecatpath($item, $path);
		}else{
			$path = "/";
		}
		return $path;
	}
	
	private function _warecatpath($id, &$path){
		$sql = "select * from `warecats` where `id`='$id'";
		$result = $this->db->query($sql);
		while($row = $result->fetch(PDO::FETCH_ASSOC)){
			$this->_warecatpath($row["parent"], $path);
			$path .= "/".$row['name'];
		}
	}
	
	
	private function get_document_cat_html($parent, &$html){
		$sql = "select * from `doccats` where `parent`='$parent'";
		
		$result = $this->db->query($sql);
		$rc = $result->fetchColumn(); 
		if($rc >0){
		
			if($parent == 0){
				$html .= "<ul id=\"cat_ul\">";
			}else{
				$html .= "<ul>";
			}
		}
		$result = $this->db->query($sql);
		while($row = $result->fetch(PDO::FETCH_ASSOC)){
				$html .= "<li ><a id=\"cat_{$row["id"]}\" class='catz' href=\"javascript:view_cat_contents({$row["id"]})\">".$row["name"]."</a><a href='javascript:edit_cat({$row['id']},{$row['parent']},\"{$row['name']}\")'>&#x270E;</a><a href='javascript:remove_cat({$row['id']})'>&#215;</a>";
				
				$this->get_document_cat_html($row["id"], $html);
				
				$html .= "</li>";
		}
		if($rc >0){
			$html .= "</ul>";
		}
		
	}
	
		
	private function get_document_cat_html_lite($parent, &$html){
		$sql = "select * from `doccats` where `parent`='$parent'";
		
		$result = $this->db->query($sql);
		$rc = $result->fetchColumn(); 
		if($rc >0){
		
			if($parent == 0){
				$html .= "<ul id=\"cat_ul\">";
			}else{
				$html .= "<ul>";
			}
		}
		$result = $this->db->query($sql);
		while($row = $result->fetch(PDO::FETCH_ASSOC)){
				$html .= "<li ><a id=\"cat_{$row["id"]}\" class='catz' href=\"javascript:view_cat_contents({$row["id"]})\">".$row["name"]."</a>";
				
				$this->get_document_cat_html_lite($row["id"], $html);
				
				$html .= "</li>";
		}
		if($rc >0){
			$html .= "</ul>";
		}
		
	}
	
	private function get_document_cat_options($parent, &$html, $path = '/'){
		$result = $this->db->query("select * from `doccats` where `parent`='$parent' order by 'orderly'");
		//var_dump($rc);
			while($row = $result->fetch(PDO::FETCH_ASSOC)){
			//if($row['web_cat_live']!=0){
				$thispath = $path.$row["name"]."/"	;
				
				$html .= "<option value=\"{$row["id"]}\">".$thispath."</option>";
				$this->get_document_cat_options($row["id"], $html, $thispath);
				
			//}
		}
	}
	
	private function load_ware($id){
		$sql = "select * from ware where id='{$id}';";
		$result = $this->db->query($sql);
		$row = $result->fetch(PDO::FETCH_ASSOC);
		return json_encode($row);
	}
	
	private function add_new_ware(){
		$sql = "INSERT INTO `ware` (`name`,`short_description`,`description`,`price`,`artiststatement`,`qty`,`x_mm`,`y_mm`,`z_mm`,`grams`) VALUES ('".$this->url["name"]."','".$this->url["short_description"]."','".$this->url["description"]."','".$this->url["price"]."','".$this->url["artiststatement"]."','".$this->url["qty"]."',,'".$this->url["x_mm"]."','".$this->url["y_mm"]."','".$this->url["z_mm"]."','".$this->url["grams"]."');";
		$result = $this->db->query($sql);
		$x = $this->db->lastInsertId();
		$sql = "INSERT INTO `linked_items` (`parent_table`,`parent_id`,`item_table`,`item_id`) VALUES ('ware','{$x}','warecats','0')";
		$result = $this->db->query($sql);
		return $x;
	}
	
	private function update_ware(){
		$sql = "update `ware` set `name`='".$this->url["name"]."', `short_description`='".$this->url["short_description"]."', `description`='".$this->url["description"]."', `price`='".$this->url["price"]."', `artiststatement`='".$this->url["artiststatement"]."',`qty`='".$this->url["qty"]."',`x_mm`='".$this->url["x_mm"]."',`y_mm`='".$this->url["y_mm"]."',`z_mm`='".$this->url["z_mm"]."',`grams`='".$this->url["grams"]."' where `id`='".$this->url['id']."'";
		$result = $this->db->query($sql);
		
	}
	
	
	private function delete_kiln($id){
		$sql = "delete from kilns where id='{$id}';";
		$result = $this->db->query($sql);
		$sql = "delete from linked_items where `parent_id`='{$id}' and `parent_table`='kilns';";
		$result = $this->db->query($sql);
	}
	
	private function delete_ware($id){
		$sql = "delete from ware where id='{$id}';";
		$result = $this->db->query($sql);
		$sql = "delete from linked_items where `parent_id`='{$id}' and `parent_table`='ware';";
		$result = $this->db->query($sql);
	}

	
	public function get_ware_list($parent){
		$html = "<table class='table table-striped' width='100%'><thead><tr><th>File</th><th>Description</th><th>Action</th></tr></thead><tbody>";
		$sql = "select * from `linked_items` where `item_table`='warecats' and `parent_table`='ware' and `item_id`='$parent'";
		$result = $this->db->query($sql);
		
		while($row = $result->fetch(PDO::FETCH_ASSOC)){
		
			$sql = "select * from `ware` where `id`='{$row['parent_id']}'";
			$_result = $this->db->query($sql);

			$_row = $_result->fetch(PDO::FETCH_ASSOC);
			$html .= "<tr><td nowrap width=20%><a href='javascript:iload_ware(".$_row["id"].");'>".$_row["name"]."</a></td><td>".$_row["short_description"]."</td><td width='10%'>";
			
			if(isset($this->url["linker"])){
				$html .= "<a href='javascript:link_ware(".$_row["id"].")' class='btn'>link</a></td></tr>";
			}else{
				$html .= "<a href='javascript:delete_ware(".$_row["id"].")'>delete</a></td></tr>";
			}
		//	var_dump($row);
		}
		$html .= "</tbody></table>";
		return $html;
	}
	
	
	private function get_ware_cat_html($parent, &$html){
		$sql = "select * from `warecats` where `parent`='$parent'";
		
		$result = $this->db->query($sql);
		$rc = $result->fetchColumn(); 
		if($rc >0){
		
			if($parent == 0){
				$html .= "<ul id=\"cat_ul\">";
			}else{
				$html .= "<ul>";
			}
		}
		$result = $this->db->query($sql);
		while($row = $result->fetch(PDO::FETCH_ASSOC)){
				$html .= "<li ><a id=\"cat_{$row["id"]}\" class='catz' href=\"javascript:view_ware_cat_contents({$row["id"]})\">".$row["name"]."</a><a href='javascript:edit_cat({$row['id']},{$row['parent']},\"{$row['name']}\")'>&#x270E;</a><a href='javascript:remove_cat({$row['id']})'>&#215;</a>";
				
				$this->get_ware_cat_html($row["id"], $html);
				
				$html .= "</li>";
		}
		if($rc >0){
			$html .= "</ul>";
		}
		
	}


	private function get_ware_cat_html_no_edit($parent, &$html){
		$sql = "select * from `warecats` where `parent`='$parent'";
		
		$result = $this->db->query($sql);
		$rc = $result->fetchColumn(); 
		if($rc >0){
		
			if($parent == 0){
				$html .= "<ul id=\"cat_ul\">";
			}else{
				$html .= "<ul>";
			}
		}
		$result = $this->db->query($sql);
		while($row = $result->fetch(PDO::FETCH_ASSOC)){
				$html .= "<li ><a id=\"cat_{$row["id"]}\" class='catz' href=\"javascript:view_ware_cat_contents({$row["id"]})\">".$row["name"]."</a>";
				
				$this->get_ware_cat_html_no_edit($row["id"], $html);
				
				$html .= "</li>";
		}
		if($rc >0){
			$html .= "</ul>";
		}
		
	}


	public function add_ware_cat(){
   		if($this->url["add_ware_cat"] !='true'){
   			$sql = "update `warecats` set `name`='{$this->url['name']}', `parent`= '{$this->url['parent']}' where `id`='{$this->url["add_ware_cat"]}'";
   			$result = $this->db->query($sql);
   		}else{
   			$sql = "INSERT INTO `warecats` (`name`, `parent`) VALUES ( '{$this->url['name']}', '{$this->url['parent']}');";
   			$result = $this->db->query($sql);
   		}
   } 	
	
	
	
	
	private function get_ware_cat_options($parent, &$html, $path = '/'){
		$result = $this->db->query("select * from `warecats` where `parent`='$parent' order by 'orderly'");
		//var_dump($rc);
			while($row = $result->fetch(PDO::FETCH_ASSOC)){
			//if($row['web_cat_live']!=0){
				$thispath = $path.$row["name"]."/"	;
				
				$html .= "<option value=\"{$row["id"]}\">".$thispath."</option>";
				$this->get_ware_cat_options($row["id"], $html, $thispath);
				
			//}
		}
	}
   
    
    public function get_all_kilns(){
   		$sql = "select * from `kilns`;";
   		if($result = $this->db->query($sql)){
   			$items = array();
   			while($row = $result->fetch(PDO::FETCH_ASSOC)){
   				$item = array();
   				foreach ($row as $key => $value){
					$item[$key] = $value;
				}
				array_push($items, $item);
			}
   			return $items;
   		}else{
   		  //  echo $this->db->errorInfo();
   			return false;
   		}
   		
   }
   
   public function get_all_settings(){
   		$sql = "select * from `ceramassist`;";
   		if($result = $this->db->query($sql)){
   			$items = array();
   			while($row = $result->fetch(PDO::FETCH_ASSOC)){
   				$item = array();
   				foreach ($row as $key => $value){
					$item[$key] = $value;
				}
				array_push($items, $item);
			}
   			return $items;
   		}else{
   		  	//  echo $this->db->errorInfo();
   			return false;
   		}
   		
   }
   
   public function get_all_shops(){
   		$sql = "select * from `shops`;";
   		if($result = $this->db->query($sql)){
   			$items = array();
   			while($row = $result->fetch(PDO::FETCH_ASSOC)){
   				$item = array();
   				foreach ($row as $key => $value){
					$item[$key] = $value;
				}
				array_push($items, $item);
			}
   			return $items;
   		}else{
   		  //  echo $this->db->errorInfo();
   			return false;
   		}
   		
   }
   
   public function get_all_exhibitions(){
   		$sql = "select * from `exhibitions`;";
   		if($result = $this->db->query($sql)){
   			$items = array();
   			while($row = $result->fetch(PDO::FETCH_ASSOC)){
   				$item = array();
   				foreach ($row as $key => $value){
					$item[$key] = $value;
				}
				array_push($items, $item);
			}
   			return $items;
   		}else{
   		  //  echo $this->db->errorInfo();
   			return false;
   		}
   		
   }
   
   public function get_all_webstores(){
   		$sql = "select * from `webstore`;";
   		if($result = $this->db->query($sql)){
   			$items = array();
   			while($row = $result->fetch(PDO::FETCH_ASSOC)){
   				$item = array();
   				foreach ($row as $key => $value){
					$item[$key] = $value;
				}
				array_push($items, $item);
			}
   			return $items;
   		}else{
   		  //  echo $this->db->errorInfo();
   			return false;
   		}
   		
   }
   
   private function delete_webstore($id){
		$sql = "delete from webstore where id='{$id}';";
		$result = $this->db->query($sql);
		$sql = "delete from linked_items where `parent_id`='{$id}' and `parent_table`='webstore';";
		$result = $this->db->query($sql);
	}
	
	private function delete_shop($id){
		$sql = "delete from shops where id='{$id}';";
		$result = $this->db->query($sql);
		$sql = "delete from linked_items where `parent_id`='{$id}' and `parent_table`='shops';";
		$result = $this->db->query($sql);
	}
	
	private function delete_exhibition($id){
		$sql = "delete from exhibitions where id='{$id}';";
		$result = $this->db->query($sql);
		$sql = "delete from linked_items where `parent_id`='{$id}' and `parent_table`='exhibitions';";
		$result = $this->db->query($sql);
	}
    
    
	public function get_all_tests(){
   		$sql = "select * from `blends`;";
   		if($result = $this->db->query($sql)){
   			$items = array();
   			while($row = $result->fetch(PDO::FETCH_ASSOC)){
   				$item = array();
   				foreach ($row as $key => $value){
					$item[$key] = $value;
				}
				array_push($items, $item);
			}
   			return $items;
   		}else{
   		  //  echo $this->db->errorInfo();
   			return false;
   		}
   		
   }
  
  public function get_all_schedules(){
   		$sql = "select * from `firing_schedule`;";
   		if($result = $this->db->query($sql)){
   			$items = array();
   			while($row = $result->fetch(PDO::FETCH_ASSOC)){
   				$item = array();
   				foreach ($row as $key => $value){
					$item[$key] = $value;
				}
				array_push($items, $item);
			}
   			return $items;
   		}else{
   		   // echo $this->db->errorInfo();
   			return false;
   		}
   		
   }	
	
	public function get_all_recipes(){
   		$sql = "select * from `recipe`;";
   		if($result = $this->db->query($sql)){
   			$items = array();
   			while($row = $result->fetch(PDO::FETCH_ASSOC)){
   				$item = array();
   				foreach ($row as $key => $value){
					$item[$key] = $value;
				}
				array_push($items, $item);
			}
   			return $items;
   		}else{
   		   // echo $this->db->errorInfo();
   			return false;
   		}
   		
   }
   
   public function get_all_firings(){
   		$sql = "select * from `firings`;";
   		if($result = $this->db->query($sql)){
   			$items = array();
   			while($row = $result->fetch(PDO::FETCH_ASSOC)){
   				$item = array();
   				foreach ($row as $key => $value){
					$item[$key] = $value;
				}
				array_push($items, $item);
			}
   			return $items;
   		}else{
   		  //  echo $this->db->errorInfo();
   			return false;
   		}
   		
   }
   
   
   public function get_all_formula(){
   		$sql = "select * from `formula`;";
   		if($result = $this->db->query($sql)){
   			$items = array();
   			while($row = $result->fetch(PDO::FETCH_ASSOC)){
   				$item = array();
   				foreach ($row as $key => $value){
					$item[$key] = $value;
				}
				array_push($items, $item);
			}
   			return $items;
   		}else{
   		  //  echo $this->db->errorInfo();
   			return false;
   		}
   		
   }
   
	
	
	
	public function get_all_samples(){
   		$sql = "select * from `samples`;";
   		if($result = $this->db->query($sql)){
   			$items = array();
   			while($row = $result->fetch(PDO::FETCH_ASSOC)){
   				$item = array();
   				foreach ($row as $key => $value){
					$item[$key] = $value;
				}
				array_push($items, $item);
			}
   			return $items;
   		}else{
   		   // echo $this->db->errorInfo();
   			return false;
   		}
   		
   }
   
   public function get_samples_by_type($type){
   		$sql = "select * from `samples` where `type`='$type';";
   		if($result = $this->db->query($sql)){
   			$items = array();
   			while($row = $result->fetch(PDO::FETCH_ASSOC)){
   				$item = array();
   				foreach ($row as $key => $value){
					$item[$key] = $value;
				}
				array_push($items, $item);
			}
   			return $items;
   		}else{
   		  //  echo $this->db->errorInfo();
   			return false;
   		}
   		
   }
   
   public function set_section($section){
   		$this->section = $section;   
   }
   
   public function get_section(){
   		return $this->section ;
   }
   
   
   
   public function get_samples_not_by_type($type){
   		$sql = "select * from `samples` where `type`<>'$type';";
   		if($result = $this->db->query($sql)){
   			$items = array();
   			while($row = $result->fetch(PDO::FETCH_ASSOC)){
   				$item = array();
   				foreach ($row as $key => $value){
					$item[$key] = $value;
				}
				array_push($items, $item);
			}
   			return $items;
   		}else{
   		  //  echo $this->db->errorInfo();
   			return false;
   		}
   		
   }

	public function render(){
   	 	$view = $this;
   	    if(isset($this->url["script"])){
        	echo "var cpd_data = '".json_encode($this->return_data)."';";
        }else if(isset($this->url["json"])){
           echo json_encode($this->return_data);
        }else{
           if($this->render_type != false){
           		ob_start();
           		include($this->render_type);
           		$content = ob_get_clean();
           		
           }else{
     	           		
           		ob_start();
           		include("template/ceramassist.php");
           		$content = ob_get_clean();
           }
           ob_start();
           include("template/chrome.php");
           $page = ob_get_clean();
           $page = str_replace("<!--content-->", $content, $page);
           
           echo $page;
        }
   }
	
	

}

$spice = new ceramassist();



?>