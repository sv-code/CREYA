<h4 id="average_rating_box"><span class="average_rating">Average rating: <?=$photo_overall_rating?></span><span class="num_reviews right itals">based on <?=$photo_rate_count?> review<?if($photo_rate_count!=1){?>s<?}?></span></h4>
<div class="clear"></div>

<div id="photo_ratings" class="dashBoxPhoto2">
	
	<!--span class="title">REVIEW</span--> 
	
	<? if($has_artist_rated || $photo_details['artist_dname']==$session_user) 
	{?>
		<? if(count($photo_metric_ratings) > 0)
		{?>
			<div class="rateBox_black">
				
				<div id="rateTotals">
				
					<? $top=true; ?>
					<? foreach($photo_metric_ratings as $rating): ?>
								
						<div class="statsHead" <? if($top) {?> style="margin-top:0;" <?}?>>
							<!--label><?=$rating['metric_name']?><span><?=$rating['metric_rating']?></span></label-->
						</div>	
											
						<div class='statbar_black'>
							<!--  Calculate total rating then give it appropriate width in a percentage -->
							<div id="BG-details<?=$rating['MID']?>" class="statbarOff" style="width:<?=doubleval($rating['metric_rating']/5)*100?>%;"></div>
						</div>
						
						<? $top=false; ?>
						
					<? endforeach ?>
				
				</div>
				
			</div>
		<?}
		else
		{?>
			<BR>
		<?}?>
	<?}
	else
	{?>
		<!--h3 class="gray rateAvg" id="rateHeader">Rate this Photograph</h3-->
		<div class="rateBox_black" id="rateControls">
			
			<div id="rate_metrics">
			
				<? $metric_num=1 ?> 
				<? foreach($lightbox_metrics as $metric): ?>
				
					<div class="metric">
					
						<!--div class="suggestHead"></div-->
						
						<div id="Container<?=$metric_num?>">
							<select name="<?=$metric['metric_name']?>" id="metric<?=$metric_num?>" style="display:none">
								<option value="0" selected="selected">0</option>
							  	<option value="1">1</option>
							  	<option value="2">2</option>
							  	<option value="3">3</option>
							  	<option value="4">4</option>
								<option value="5">5</option>
							</select>
						</div>
						
						<div id="suggestBox-suggest<?=$metric_num?>" class="suggestBox">
							<? foreach($lightbox_metric_suggestions as $metric_suggestion): ?>
								<? if($metric_suggestion['MID']==$metric['MID'])
								{?>
									<a href="#" class="addSugg">Add <?=$metric_suggestion['suggestion']?></a>
								<?}?>
							<? endforeach ?>
							<a href="#" class="other">Add Custom Suggestion</a>
							<form id="otherForm<?=$metric_num?>" name="theForm" action="" method="get">
								<input id="other<?=$metric_num?>" type="text" value="write suggestion and hit enter"/>
							</form>
						</div>
					
					</div>
					
					<? ++$metric_num; ?>
				
				<? endforeach ?>	
			
			</div>			
			
			<a class="button_comment"></a>
			<input id="rate_photo" type="image" value="" src="/resources/images/button.rate.png"/ <? if($session_user!=null){?> onclick="AddPhotoRating(<?=$photo_details['PHOTO_ID']?>); <?} else {?> onclick="login(); <?}?>">
	
		</div>
		
	<?}?>
	
</div>