<?
$view->set_section('firings');
if($view->url["firing"]!="new"){
	$firing = $view->get_firing($view->url["firing"]);
	$title = "Update";
}else{
	$firing = array();
	$firing['date'] = date('Y-m-d');
	$firing['kiln'] = "";
	$firing['kiln_type'] = "";
	$firing['atmosphere'] = "";
	$firing['firing_type'] = "";
	$firing['notes'] = "";
	$firing['id'] = "0";
	$title = "Next";
}
?>
<h1>Firing <? if($view->url["firing"]!="new"){ ?><span style="float:right;" id="temp">
<a href="javascript:clear_firing_data();" style="font-weight:300;" class='btn btn-warning btn-sm'>Clear Firing Data</a>
&nbsp;&nbsp;&nbsp;
<div class="btn-group"><a href="javascript:add_test();" class='btn btn-info btn-sm' style="font-weight:300;">Add Tests</a>
<a href="javascript:add_document();" class='btn btn-info btn-sm' style="font-weight:300;">Add Documents</a>
<a href="javascript:add_entry();" style="font-weight:300;" class='btn  btn-info btn-sm'>Add an Entry</a></div>
&nbsp;&nbsp;
<div class="btn-group">
<a href="javascript:add_schedule();" class='btn btn-danger  btn-sm' style="font-weight:300;">Schedule</a>
<a href="javascript:void(0)" onclick="set_up_pyros();" class='btn btn-danger btn-sm' style="font-weight:300;">Pyro</a></div></span><? } ?></h1>
<div style="display:table; width:100%">
<div style="width:30%; display:table-cell; vertical-align: top; ">

<input type="hidden" id="id" value="<?=$firing['id']?>">

<div>
	<label>Date</label>
	<input type="text" id="date" value="<?=$firing['date']?>">
</div>
<div style="border-left:2px solid rgb(255, 208, 208);; margin-left:-12px; padding-left:10px">
	<label>Kiln</label>
	<select id="kiln" onchange="$('.kiln_data').hide();$('#kiln_' + $('#kiln').val() ).show(); ">
		<?php
		$kilns = $view->get_all_kilns();
		foreach($kilns as $kiln){
			$selected = "";
			if($kiln["id"]==$firing['kiln']){
				$selected = "selected";
			}
			echo "<option value='{$kiln["id"]}' $selected>{$kiln["name"]}</option>";
		}
		?>
	</select>
	<?php
		$kilns = $view->get_all_kilns();
		foreach($kilns as $kiln){
			?>
			<div style="margin-top:-5px; margin-bottom:10px; padding:0px; display:none;" class='kiln_data' id='kiln_<?=$kiln['id'];?>'>
			<div style="margin-bottom:5px;">
			<small>Kiln Type</small> 
			<br><?=$kiln["type"]?>
			</div><div>
			<small>Kiln Description</small>
			<br><?=$kiln["description"]?>
			</div></div><?
		}
		?>
</div>
<!-- <div>
	<label>Kiln Type</label>
	<input type="text" id="kiln_type" value="<?=$firing['kiln_type']?>">
</div> -->
<div>
	<label>Atmosphere</label>
	<input type="text" id="atmosphere" value="<?=$firing['atmosphere']?>">
</div>
<div>
	<label>Firing Type</label>
	<input type="text" id="firing_type" value="<?=$firing['firing_type']?>">
</div>
<div>
	<label>Notes</label>
	<textarea type="text" id="notes"><?=$firing['notes']?></textarea>
</div>
<div>
	<a href="javascript:save_firing();" class='btn btn-info btn-sm'><?=$title?></a>
</div>




</div>
<?php if($view->url["firing"]!="new"){ ?>
<div style="width:70%; display:table-cell;" id="canvas_contain">
	<div id="pyroschedule" class="sch"><div id='pyro' class='pull-right' ></div></div>

	<div id="placeholder"  style="height:400px;"></div>
	
	<div id="tabs">
  <ul>
    <li><a href="#tabs-1">Log</a></li>
    <li><a href="#tabs-2">Documents</a></li>
    <li><a href="#tabs-3">Tests</a></li>
    
  </ul>
  	<div id="tabs-1">
		<div id="comments">
	
		</div>
	</div>
	<div id="tabs-2">
		<?php 
		$parent_id = $firing['id'];
		$parent_table = "firing";
		include('template/select_document.php');
		?>
	</div>
	<div id="tabs-3">
		<?php 
		$parent_id = $firing['id'];
		$parent_table = "firing";
		include('template/select_test.php');
		?>
	</div>
	
	</div>
</div>



</div>

<div id="thermacouple_setup" title="Thermacouple Setup">
	Set Thermocouple 1 type <select id="th1"><option value="1">TYPE_K</option><option value="2">TYPE_E</option><option value="3">TYPE_J</option><option value="4">TYPE_N</option><option value="5">TYPE_R</option><option value="6">TYPE_S</option><option value="7">TYPE_T</option></select><br>
	Set Thermocouple 2 type <select id="th2"><option value="1">TYPE_K</option><option value="2">TYPE_E</option><option value="3">TYPE_J</option><option value="4">TYPE_N</option><option value="5">TYPE_R</option><option value="6">TYPE_S</option><option value="7">TYPE_T</option></select><br>
	<a href="javascript:void(0)" onclick="set_pyros();" class='btn btn-info btn-sm' style="font-weight:300;">Next</a>
</div>

<div id="add_entry" title="Add an Entry">
	Time<br>
	<input type="text" style="width:90%" id="timeselect">
	Probe 1<br>
	<input type="text" style="width:90%" type="number" id="temperature1">
	Probe 2<br>
	<input type="text" style="width:90%" type="number" id="temperature2">
	Comment<br>
	<textarea  id="comment" style="width:90%"></textarea>
	<a href="javascript:save_entry()" class='btn  btn-info btn-sm'>Add Entry</a>
</div>

<div id="select_schedule" title="Select Schedule">
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
		echo "<td width='10%' nowrap='nowrap'><a href='javascript:attach_schedule(".$sample["id"].")'  class='btn btn-xs'>Attach</a></td>";
		echo "</tr>";
	}

?>
</table>
</div>


<div id="data_return"></div>
<? } ?>
<script>
$(function(){
	$('#thermacouple_setup').dialog({
		  autoOpen: false
	});
	$('#add_entry').dialog({
		  autoOpen: false
	});
	$('#select_schedule').dialog({
		  autoOpen: false, width:700
	});
	
 	$( "#date" ).datepicker();
 	$( "#timeselect" ).datetimepicker({timeFormat: "HH:mm:ss", dateFormat: "yy-mm-dd"});
    $( "#date" ).datepicker("option", "dateFormat", "yy-mm-dd");
    $( "#date" ).datepicker('setDate', "<?=$firing['date']?>");
    $( "#tabs" ).tabs();
    
    
});

var schedule = [];

function attach_schedule(id){
	url = "?firing_attach=<?=$firing['id']?>&schedule="+id;
	$.ajax(url).done(function(data){
		$('#select_schedule').dialog('close');
		load_schedule(data);
	});
}

function get_schedule(id){
	url = "?get_schedule="+id;
	$.ajax(url).done(function(data){
		load_schedule(data);
	});
}

function load_schedule(sched){
	schedule = $.parseJSON(sched);
	if(schedule.hasOwnProperty('type')){
	$("#pyroschedule").show();
	$("#pyroschedule").html("<div id='pyro' class='pull-right' ></div><div id='schsch' class='pull-left' >Schedule : "+schedule["type"]+", "+schedule["description"]+", "+schedule["atmosphere"]+" <a href='javascript:remove_schedule("+schedule['id']+")'>[-]</a></div>")
	}else{
	$("#pyroschedule").hide();
	}
	redraw_graph();
}

String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.split(search).join(replacement);
};


function remove_schedule(){
	if(confirm("ARE YOUR SURE?")){
		url = "?firing_attach=<?=$firing['id']?>&schedule=0";
		$.ajax(url).done(function(data){
			schedule = [];
			$("pyroschedule").html("");
			load_schedule(data);
		});
	}
}

function get_schedule_data(){
	// we need to set it to the first pyro data point
	start = 0;
	if(thermo1.length){
		th = thermo1;
		x = th.pop();
		
		start=x[0];//+6000000
	}
	schedule['points'] = schedule['points'].replaceAll('"{', '{');
	schedule['points'] = schedule['points'].replaceAll(',"{', ',{');
	schedule['points'] = schedule['points'].replaceAll('}"', '}');
	schedule['points'] = schedule['points'].replaceAll('\\"', '"');
	console.log(schedule['points']);
	points = $.parseJSON(schedule['points']);
	plots = [];
	plots.push([start,0]);
	for(i=0;i < points.length;i++){
		console.log(points[i]);
		time = (((points[i]['hr']*60)+(points[i]['min']*1))*60000)+start;
		plots.push([time,points[i]['temp']]);
	}
	return plots;
}

function add_schedule(){
	$('#select_schedule').dialog('open');
}

function set_pyros(){
	pyro = {}
	pyro.temperature1 = $("#th1").val();
	pyro.temperature2 = $("#th2").val();
	json = JSON.stringify(pyro);
	url = "?set_thermo=" + json;
	$.ajax(url).done(function(data){
			$('#thermacouple_setup').dialog('close');
			$("#pyroschedule").show();
			$("#pyro").html("<span id='temp_out'></span> <a href='javascript:void(0);' onclick='record()' id='record' class='btn btn-danger btn-sm'	>Log</a>");
			setTimeout('get_temp()',1000);
	});
}

function add_entry(){
	$("#timeselect").val('');
	$("#temperature1").val('');
	$("#temperature2").val('');
	$("#comment").val('');
	$('#add_entry').dialog('open');
}




var is_recording = false;
function get_temp(){
	url = "?get_thermo=true";
	if(is_recording == true){
		url = "?get_thermo=log&fid="+$('#id').val();
	}
	$.ajax(url).done(function(data){
		pyro = $.parseJSON(data);
		$("#temp_out").html("<span class='pyrotemp'><small>1</small> <b>"+pyro.temperature1+"</b></span> &nbsp; <span><small>2</small>  <b>"+pyro.temperature2+"</b></span>");
		if(is_recording == true){
			// update graph
			thermo1.push([pyro.timestamp, pyro.temperature1]);
			thermo2.push([pyro.timestamp, pyro.temperature2]);
			
			redraw_graph();
			//plot.setData([ { label: "thermo1", data: thermo1 }, { label: "thermo2", data: thermo2 , yaxis: 2 }]);
			//plot.setupGrid();
			//plot.draw();
		}
		/*if(is_recording == true){
			url = "?save_thermo=true";
			$.ajax(url).done(function(data){
				console.log(data);
				pyro = $.parseJSON(data);
				//myLine.datasets
				//myLine.addData([pyro.temperature1, pyro.temperature2, pyro.timestamp]);
			});
		}*/
		setTimeout('get_temp()',1000);
	});
}

function clear_firing_data(){
	if(confirm('Are you are sure. This will permanently delete all the temperature data for this firing.')){
			document.location.href = "?clear_firing_data=<?=$firing['id']?>";
	}
}


function record(){
	if(is_recording == false){
		is_recording = true;
		$("#record").html('Stop');
	}else{
		is_recording = false;
		$("#record").html('Log');
	}
}


function set_up_pyros(){
	// get the pyros and display a 
	$('#thermacouple_setup').dialog('open');
}

function save_entry(){
	datetime = $("#timeselect").val();
	temp1 = $("#temperature1").val();
	temp2 = $("#temperature2").val();
	comment = $("#comment").val();
	e = "";
	if(datetime == ""){
		e = "set a time!\n";
	}
	if(temp1 == ""){
		if(comment == ""){
			e = e + "A comment is need if you want to add an entry without a temperature!\n";
		}
	}
	if(e != ""){
		alert(e);
	}else{
		url = "?add_firing_item=true&datetime=" + encodeURIComponent(datetime) + "&temp1=" + encodeURIComponent(temp1) + "&temp2=" + encodeURIComponent(temp2) + "&comment=" + encodeURIComponent(comment) + "&fid="+$('#id').val();
		console.log(url)
		$.ajax(url).done(function(data){
			$('#add_entry').dialog('close');
			$("#data_return").html(data);
			load_comments_list();
			
		});
	}
	
}

function del(id){
	if(confirm("Are you sure?")){
		url = "?delete_firing_item=" + id;
		$.ajax(url).done(function(data){
			$("#data_return").html(data);
			load_comments_list();
		});
	}
}



function redraw_graph(){
	if(schedule.hasOwnProperty('points')){
		idata = get_schedule_data();
		console.log("idata"+idata);
		plot.setData([ { label: "probe 1", data: thermo1 }, { label: "probe 2", data: thermo2 , yaxis: 2 },{ data : idata , label: "schedule" } ]);
	
	}else{
		plot.setData([ { label: "probe 1", data: thermo1 }, { label: "probe 2", data: thermo2 , yaxis: 2 } ]);
	}
	plot.setupGrid();
	plot.draw();
}


function load_comments_list(){
	url = "?get_firing_comments_as_html="+$('#id').val();;
	$("#comments").load(url);
}


function save_firing(){
	error = "";
	if($('#kiln').val()==""){
		error = error + "Kiln Required\n";
	}
	if($('#kiln_type').val()==""){
		error = error + "Kiln Type Required\n";
	}
	if($('#atmosphere').val()==""){
		error = error + "Atmosphere Required\n";
	}
	if($('#firing_type').val()==""){
		error = error + "Firing Type Required\n";
	}
	if(error != ""){
		alert(error);
	}else{
		firing = {};
		firing.id = $('#id').val();
		firing.date = $('#date').val();
		firing.kiln = $('#kiln').val();
		firing.kiln_type = $('#kiln_type').val();
		firing.atmosphere = $('#atmosphere').val();
		firing.firing_type = $('#firing_type').val();
		firing.notes = $('#notes').val();
		json = JSON.stringify(firing);
		//alert(json);
		url = "?add_firing=" + json;
		$.ajax(url).done(function(data){
			//$('#id').val(data);
			if($('#id').val()==0){
				document.location.href="?firing="+data;
			}
		});
	}
}

	<? if($view->url["firing"]!="new"){ 
		$data = $view->get_firing_data($firing['id']);
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
		
		if($firing["schedule"]!=""){
			?>
			get_schedule('<?=$firing["schedule"]?>');
			<?
		}
		?>
		load_comments_list();
			
		var thermo1 = [<?=$thermo1?>];
		var thermo2 = [<?=$thermo2?>];
	
		function cFormatter(v, axis) {
			return v.toFixed(axis.tickDecimals) + "C";
		}
		var plot;
		function doPlot(position) {
			plot = $.plot("#placeholder", [
				{ data: thermo1, label: "probe 1" },
				
				{ data: thermo2, label: "probe 2", yaxis: 2 }
				
			], {
				xaxes: [ { mode: "time" } ],
				yaxes: [  {
					// align if we are to the right
					alignTicksWithAxis: position == "right" ? 1 : null,
					position: position,
					tickFormatter: cFormatter
				} ],
				legend: { position: "nw" }
			});
		}

		doPlot("right");
		$('.kiln_data').hide();$('#kiln_<?=$firing["kiln"]?>').show();
	<? }?>

	
	

</script>
<style>
#temp_out{
	font-weight: 300;
}
#temp_out span{
	padding:10px;
	font-size: 20px;
	background: #fff;
	border-bottom-left-radius: 5px;
	border-bottom-right-radius: 5px;
}
.ui-tabs .ui-tabs-panel {
	padding:0px;
}
.sch{
	padding:10px;
	border-radius:5px;
	background: #FFD0D0;
	overflow:hidden;
	display:none;
	margin-bottom: 30px;
}
</style>