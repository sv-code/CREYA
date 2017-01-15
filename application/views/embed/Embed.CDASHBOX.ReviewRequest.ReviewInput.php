<div id="review_input" class="cdashbox review_request">
											
	<span class="cdtitle review_this_photograph"></span><div class="clear"></div>
								
	<div id="impact" class="metric">
		<div><span class="title left"></span><div class="relative left"><span class="rating left">0</span></div></div>
			<div class="clear"></div>
			<span class="description prompt left">emotional appeal, interestingness, originality, style, etc</span><div class="indicator right"></div>
		<div class="clear"></div>
									
		<select name="" class="slider_metric" style="display:none">
			<option value="0" selected="selected">0</option>
		 	<option value="1">1</option>
		  	<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
		</select>
									
		<textarea rows="" cols="">click to add comment</textarea>
		<a class="button_comment button_add_remove" href="#"><span class="add"></span></a>
																
	</div>	
								
	<div id="balance" class="metric">
		<div><span class="title left"></span><div class="relative left"><span class="rating left">0</span></div></div>
		<div class="clear"></div>
		<span class="description prompt left">background, composition, crop, etc</span><div class="indicator right"></div>
		<div class="clear"></div>
																		
		<select name="" class="slider_metric" style="display:none">
			<option value="0" selected="selected">0</option>
		  	<option value="1">1</option>
		  	<option value="2">2</option>
		  	<option value="3">3</option>
		  	<option value="4">4</option>
			<option value="5">5</option>
		</select>
									
		<textarea rows="" cols="">click to add comment</textarea>
		<a class="button_comment button_add_remove" href="#"><span class="add list"></span></a>
		<a class="button_crop_suggestion button_add_remove" href="#"><span class="add list"></span></a>
																
								
	</div>	
								
	<div id="technique" class="metric">
		<div><span class="title left"></span><div class="relative left"><span class="rating left">0</span></div></div>
		<div class="clear"></div>
		<span class="description prompt left">focus, lighting, white balance, contrast, etc</span><div class="indicator right"></div>
		<div class="clear"></div>
									
		<select name="" class="slider_metric" style="display:none">
			<option value="0" selected="selected">0</option>
		  	<option value="1">1</option>
		  	<option value="2">2</option>
		  	<option value="3">3</option>
		  	<option value="4">4</option>
			<option value="5">5</option>
		</select>
									
		<textarea rows="" cols="">click to add comment</textarea>
		<a class="button_comment button_add_remove" href="#"><span class="add"></span></a>
																	
	</div>
								
	<div id="general_comment">
		<span class="prompt left"></span><div class="indicator right"></div>
		<div class="clear"></div>
		<textarea rows="" cols="" style="display:block">general comment</textarea>
	</div>
								
	<input id="disc_id" type="hidden" value="<?=$disc_details['DISC_ID']?>" />
	<a id="save_review" class="submit button_save_review left" onclick="VerifySession(SubmitReview);return false;"></a>
	<a class="button" href="#"><span class="cancel left"></span></a>	
	<img class="save_indicator_ajax" src="/resources/images/ajax-loader-black-small.gif" />	
																			
	<div class="clear"></div>	
	
	<div class="divider"></div>							

</div>
												
