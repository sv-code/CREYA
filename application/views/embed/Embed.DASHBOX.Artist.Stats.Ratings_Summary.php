<div id="rating_summary"> 
	<h3 style="font-weight:bold;font-size:15px;padding:0;color:#666;margin-bottom:10px;text-transform:uppercase;">Ratings Summary</h3> 
	
	<div class="statsBox"> 
		<div class="statsHead"> 
			<label>Overall Rating <span><?=$artist_overall_rating?></span></label> 
			<!--<a href="" class="toggleClose" id="details1">View Details</a>--> 
		</div>		
		<div id="statbar-0" class='statbar left'> 
			<!--  Calculate total rating then give it appropriate width in a percentage --> 
			<a href="#"><div id="BG-details-0" class="statbarOff" style="width:<?=doubleval($artist_overall_rating/5)*100?>%;"></div></a> 
		</div> 
		<!-- <a href="#" class="details" id="details1" onclick="statsToggle('1');return false;"></a> --> 
	</div> 
	
	<? foreach($artist_metric_ratings as $rating): ?>
		<div class="statsBox"> 
			<div class="statsHead"> 
				<label><?=$rating['metric_name']?> <span><?=$rating['metric_rating']?></span></label> 
				<!--<a href="" class="toggleClose" id="details2">View Details</a>--> 
			</div>	
			
			<div id = "statbar-<?=$rating['MID']?>" class='statbar left'> 
				<!--  Calculate total rating then give it appropriate width in a percentage --> 
				<a href="#"><div id="BG-details-<?=$rating['MID']?>" class="statbarOff" style="width:<?=doubleval($rating['metric_rating']/5)*100?>%;"></div></a> 
			</div> 
			<!-- <a href="#" class="details" id="details2" onclick="statsToggle('2');return false;"></a> --> 
		</div>
	<? endforeach ?> 
	
	<!--div class="statsBox"> 
		<div class="statsHead"> 
			<label>Composition <span>0</span></label> 
		</div> 
		
		<div class='statbar left'> 
			<a href="#" onclick="statsToggle('3');return false;"><div id="BG-details3" class="statbarOff" style="width:50%;"></div></a> 
		</div> 
	</div> 
	
	<div class="statsBox"> 
		<div class="statsHead"> 
			<label>Exposure <span>0</span></label> 
		</div> 
		
		<div class='statbar left'> 
			<a href="#" onclick="statsToggle('4');return false;"><div id="BG-details4" class="statbarOff" style="width:25%;"></div></a> 
		</div> 
	</div--> 
</div> 
					
					
					<!-- <div class="dashBox" style="margin-top:0px;">
						<h3>General Stats</h3>
						<ul>
							<li>- Member since January 01, 2009</li>
							<li>- 4,096 photographs uploaded</li>
							<li>- 1,337 comments posted</li>
							<li>- Last photograph uploaded on January 31, 2009</li>
						</ul>
				 	 </div> --> 