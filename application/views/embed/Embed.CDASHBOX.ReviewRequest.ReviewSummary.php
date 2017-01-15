<div id="review_summary" class="cdashbox review_request rounded_bars">
												
	<span class="cdtitle review_summary" style="margin-bottom:20px;"></span><div class="clear"></div>
									
	<div id="impact" class="metric">
		<span class="title"></span><div class="rating"><span><?=$disc_details['metric_impact_average_rating']?></span></div>
		<div class="ratebar"><div class="ratefill" style="width:<?=$disc_details['metric_impact_average_rating']/5*100?>%"></div></div>		
		<span class="description">emotional appeal,interestingness,originality,style</span>
	</div>	
									
	<div id="balance" class="metric">
		<span class="title left"></span><div class="rating"><span><?=$disc_details['metric_balance_average_rating']?></span></div>
		<div class="clear"></div>
		<div class="ratebar"><div class="ratefill" style="width:<?=$disc_details['metric_balance_average_rating']/5*100?>%"></div></div>
		<span class="description">composition,crop,background</span>
	</div>	
									
	<div id="technique" class="metric">
		<span class="title left"></span><div class="rating"><span><?=$disc_details['metric_technique_average_rating']?></span></div>
		<div class="clear"></div>
		<div class="ratebar"><div class="ratefill" style="width:<?=$disc_details['metric_technique_average_rating']/5*100?>%"></div></div>
		<span class="description">focus,lighting,white balance,contrast</span>
	</div>	
									
	<? if($disc_details['artist_dname']!=$session_user)
	{?>	
		<a id="create_review" class="submit button_add_review left" onclick="CreateReview();"></a>
	<?}?>						
												
	<div class="clear"></div>
	
	<div class="divider"></div>								
</div>
						
