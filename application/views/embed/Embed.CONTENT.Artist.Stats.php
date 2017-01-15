<!-- DETAILS CHARTS AND GRAPHS --> 
<div id="overall_rating_details">
	<div id="stats-details-0" class="stats-details">	
		
			
		<div class="statsBox" style="width:650px;"> 
			<div class="statsHead"> 
				<label>Overall Rating <span>0</span></label> 
			 
			</div>		
			
			<div class='statbar left' style="width:550px;"> 
			 
				<div class="statbarOn" style="width:35%;" /></div> 
			</div> 
			 
		</div> 
		
		<? //-- GRAPH -- ?> 
		<div class="contentHead left"> 
			<label class="contentLabel">Overall Timeline</label> 
		</div> 
		
		<div class="chartLinks right"> 
			<a href="#" id="overall_m" class='on'>Last 2 Months</a> | 
			<a href="#" id="overall_y" class='off'>2009</a> 
		</div>
		
		<div id="overallGraph" class="chart" style="width:530px;height:220px;clear:both;"></div> 
		
		<div class="contentHead"> 
			<label class="contentLabel">Top 5 Overall Rated Photographs</label> 
		</div> 
		
		<div class="thumbRow"> 
			<? foreach($artist_top_rated_photos as $photo): ?>
				<a class="thumb left" href="/photo/<?=$photo['PHOTO_ID']?>"><img src="<?=cgraphics_image_photo_url(PHOTO_PREVIEW,$photo['PHOTO_ID']);?>" alt=""/></a>
			<? endforeach ?>
		</div>	 
				
		
			
	</div> 

</div>

<div id="metric_rating_details">
	<? foreach($artist_metric_ratings as $rating): ?>		
	
		<div id="stats-details-<?=$rating['MID']?>" class="stats-details">	
				
			<div class="statsBox" style="width:650px;"> 
				<div class="statsHead"> 
					<label><?=$rating['metric_name']?> Rating <span>0</span></label> 
				</div>		
		
				<div class='statbar left' style="width:550px;"> 
					
					<div class="statbarOn" style="width:<?=doubleval($rating['metric_rating']/5)*100?>%;" /></div> 
				</div> 
				 
			</div> 
		
			<? //-- GRAPH -- ?>
			<div class="contentHead left"> 
				<label class="contentLabel"><?=$rating['metric_name']?> Timeline</label> 
			</div> 
				
			<div class="chartLinks right"> 
				<a href="#" id="metric_<?=$rating['MID']?>_m" class='on'>Last 2 Months</a> | <a href="#" id="metric_<?=$rating['MID']?>_y" class='off'>2009</a> 
			</div> 					
			 
			<div id="Graph-Metric-<?=$rating['MID']?>" class="chart" style="width:530px;height:220px;clear:both;"></div> 
			
					
			<div class="contentHead"> 
				<label class="contentLabel">Top 5 <?=$rating['metric_name']?> Photographs</label> 
			</div> 
			
			<? foreach($artist_metric_top_rated_photos[$rating['MID']] as $photo): ?>
				<a class="thumb left" href="/photo/<?=$photo['PHOTO_ID']?>"><img src="<?=cgraphics_image_photo_url(PHOTO_PREVIEW,$photo['PHOTO_ID']);?>" alt="" /></a>
			<? endforeach ?>
			
			
			<!--
			<? //-- PIE -- ?> 
			<div class="contentHead"> 
				<label class="contentLabel"><?=$rating['metric_name']?> Suggestions</label> 
			</div> 
							
			<table id="dataTable1" class="pieData"> 
				<thead> 
					<tr> 
						<td></td> 
						<th class="hidden"></th> 
					</tr> 
				</thead> 
				<tbody> 
					<tr> 
						<th><span><label></label></span><a href="#" class="sugg">Suggestion 1</a></th> 
						<td class="hidden">150</td>	
					</tr> 
					
					<tr> 
						<th><span><label></label></span><a href="#" class="sugg">Suggestion 2</a></th> 
						<td class="hidden">70</td> 
					</tr> 
					
					<tr> 
						<th><span><label></label></span><a href="#" class="sugg">Suggestion 3</a></th> 
						<td class="hidden">100</td>		
					</tr> 
					
					<tr> 
						<th><span><label></label></span><a href="#" class="sugg">Suggestion 4</a></th> 
						<td class="hidden">40</td>			
					</tr>	
					
					<tr> 
						<th><span><label></label></span><a href="#" class="sugg">Suggestion 5</a></th> 
						<td class="hidden">40</td>			
					</tr>		
				</tbody> 
			</table> 
				
			<canvas id="chart1" class="fgCharting_src-dataTable1_type-pie" scalable="false" width="400" height="400"></canvas> 
			<-->		
		</div> 
		
	<? endforeach ?> 
</div>


	