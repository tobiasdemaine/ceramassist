<?
$view->set_section('firings');
?>
<h4>Firing Schedule</h4>
<div style="width:15%; display:table-cell; vertical-align:top;">
<div>
<div>Type</div>
<input type="hidden" id="id" value="0">
<input type="text" id="type">
</div>
<div>
<div>Description</div>
<textarea id="description"></textarea>
</div>
<div>
<div>Atmosphere</div>
<input type="text" id="atmosphere">
</div>
<a href="javascript:save_schedule()" class="btn">Save</a>
</div>

<div style="width:15%; display:table-cell;  vertical-align:top;">
	<a href="javascript:$('#addPointPopup').dialog('open');" class="pull-right">+ Add a point</a>

	<div id="addPointPopup" title="Add">
		<div>
			Time : Hrs <br>
			<select id="addhrs" style="width:100%;">
				<?php 
				for($i=0;$i<120;$i++){
					echo "<option value='{$i}'>{$i}</option>";
				
				}
				?>
			</select><br>
			 Minutes<br>
			<select id="addmin" style="width:100%;">
				<?php 
				for($i=0;$i<60;$i++){
					echo "<option value='{$i}'>{$i}</option>";
				
				}
				?>
			</select>
		</div>
		<div>
			Temperature<br><input type="text" id="temp" style="width:96%;">
		</div>
		<div>
			Notes<br><input type="text" id="notes" style="width:96%;">
		</div>
		<div>
			<a href="javascript:addPoint()" class="btn">Add Point</a>
		</div>
	</div>
	<div id="points">
	
	</div>
</div>
<div style="width:60%; display:table-cell;" id="canvas_contain">
	<div id="placeholder"  style="height:400px;"></div>
	<div>
</div>
<script>
	var points = [];

	function addPoint(){
		if($("#temp").val()==""){
			alert("temperature required!");
		}else{
			point = [];
			point["hr"] = $("#addhrs").val();
			point["min"] = $("#addmin").val();
			point["temp"] = $("#temp").val();
			point["notes"] = $("#notes").val();
			points.push(point);
		}
		draw_points();
		$('#addPointPopup').dialog('close');

	}
	
	function draw_points(){
		$("#points").html('');
		plots = [];
		html = "";
		plots.push([0,0]);
		for(i=0;i<points.length;i++){
			html = html + "<div class='point' data-hrs='"+points[i]['hr']+"' data-mins='"+points[i]['min']+"' data-temp='"+points[i]['temp']+"' data-notes='"+points[i]['notes']+"'><a href='javascript:remove_point(" + i + ");' class='pull-right'>[ - ]</a>";
			html = html + "<div>Time " + points[i]['hr']+" : "+points[i]['min']+", Temp ";
			html = html + points[i]['temp'] + " C</div>";
			html = html + "<div>"+points[i]['notes']+"</div>";
			html = html + "</div></div>";
			console.log(((points[i]['hr']*60)+(points[i]['min']*1))*60000);
			time = ((points[i]['hr']*60)+(points[i]['min'])*1)*60000
			plots.push([time,points[i]['temp']]);
			
		}
		console.log(plots);
		$.plot("#placeholder", [ plots ], { xaxes: [ { mode: "time" } ]});
		$("#points").html(html);
	}
	
	function remove_point(id){
		_points = [];
		for(i=0;i<points.length;i++){
			if(i != id){
				_points.push(points[i]);
			}
		}
		points = _points;
		draw_points();	
	}
	
	(function(){
    // Convert array to object
    var convArrToObj = function(array){
        var thisEleObj = new Object();
        if(typeof array == "object"){
            for(var i in array){
                var thisEle = convArrToObj(array[i]);
                thisEleObj[i] = thisEle;
            }
        }else {
            thisEleObj = array;
        }
        return thisEleObj;
    };
    var oldJSONStringify = JSON.stringify;
    JSON.stringify = function(input){
        if(oldJSONStringify(input) == '[]')
            return oldJSONStringify(convArrToObj(input));
        else
            return oldJSONStringify(input);
    };
	})();
	
	function save_schedule(){
		schedule = {}
		schedule.id = $('#id').val();
		schedule.type = $('#type').val();
		schedule.description = $('#description').val();
		schedule.atmosphere = $('#atmosphere').val();
		schedule.points = [];
		for(i=0;i<points.length;i++){
			schedule.points.push(JSON.stringify(points[i]));
		}
		if(schedule.type == ""){
			alert("Type of firing required");
		}else{
			if(schedule.description == ""){
				alert("Description required");
			}else{
				url = "?update_schedule=" + encodeURIComponent(JSON.stringify(schedule));
				$.ajax(url).done(function(data){
					if($('#id').val()==0){
						document.location.href="?add_schedule="+data;
					}
				});
			}
		}
	}
	
	
	
	$(function(){
		$('#addPointPopup').dialog({
		  autoOpen: false
		});
		
	});
	var temp = {};
	function cFormatter(v, axis) {
			return v.toFixed(axis.tickDecimals) + "C";
		}
		var plot;
		function doPlot(position) {
			plot = $.plot("#placeholder", [
				{ data: temp, label: "probe 1" }
			], {
				xaxes: [ { mode: "time" } ],
				yaxes: [  {
					// align if we are to the right
					alignTicksWithAxis: position == "right" ? 1 : null,
					position: position,
					tickFormatter: cFormatter
				} ],
				legend: { position: "sw" }
			});
		}

		doPlot("right");
		<?php 
		if($view->url["add_schedule"] != "new"){
			$schedule = $view->get_schedule($view->url["add_schedule"]);
			
			
			$pointz = str_replace('"{', '{', $schedule["points"]); 
			$pointz = str_replace(',"{', ',{', $pointz); 
			$pointz = str_replace('}"', '}', $pointz);
			echo "$('#id').val(".$schedule["id"].");";
		 	echo "$('#type').val('".$schedule["type"]."');";
			echo "$('#description').val('".$schedule["description"]."');";
			echo "$('#atmosphere').val('".$schedule["atmosphere"]."');";
			echo "pointz = '".$pointz."';";
			?>
			console.log(pointz);
			points = $.parseJSON(pointz); draw_points();
			<?
			
		} ?>
</script>
<style>
	.point{
		clear:both;
		width:90%;
		margin-bottom: 10px;
		border-radius: 5px;
		background-color: #efefef;
		padding:10px;
		margin-left: 5px;
		margin-right: 5px; 
	}
</style>

