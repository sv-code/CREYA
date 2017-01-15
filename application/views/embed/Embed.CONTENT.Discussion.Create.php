<form id="form_discussion_create" action="" method="post">
					
	<div class="form cmargin form_rounded2">
	
		<div id="prompt_discussion_title" class="first"><span class="title left title_post_title">post title</span><div class="indicator right"></div><span class="right prompt"></span></div><div class="clear"></div>
		<div class="input_text finput_margin">
			<div class="start"></div>
			<div class="ctext"><input id="discussion_title" class="mandatory_input text interested_entities_trigger" style="margin-bottom:10px;" type="text" value="" name="discussion_title" /></div>
			<div class="end"></div>
			<div class="clear"></div>				
		</div>	
		
											
		<? $this->load->view('Embed.CONTENT.Interested_Entities'); ?>
						
		<div class="discussion_body">
			<div id="prompt_discussion_body" class="next"><span class="title left title_post_body">post body</span><div class="indicator right"></div><span class="right prompt"></span></div>
			<div class="clear"></div>
			<textarea id="discussion_body" class="mandatory_input finput_margin" name="discussion_body" rows="" cols=""></textarea>
		</div>
						
		<!--div id="prompt_discussion_tags" class="next"><span class="title left title_add_tags">add tags</span><div class="indicator right"></div><span class="right prompt"></span></div><div class="clear"></div>
		<div class="input_text finput_margin">
			<div class="start"></div>
			<div class="ctext"><input id="discussion_tags" class="text" style="margin-bottom:10px;" type="text" value="" name="discussion_tags" /></div>
			<div class="end"></div>
			<div class="clear"></div>				
		</div-->			
						
		<div id="prompt_discussion_images" class="next"><span class="title left title_add_images">add images</span><div class="indicator right"></div><span class="right prompt"></span></div>
		<? $data['input_gallery_internal']=false; $this->load->view('Embed.CONTENT.Plugin.Multi_Upload.Input',$data); ?>
		<div class="clear"></div>
						
		<? $this->load->view('Embed.CONTENT.MultiUpload.Output'); ?>
											
						
	</div>
					
					
	<div class="clear content_divider"></div>
						
	<div class="content_secondary" style="height:60px;padding-top:30px;">
		<a href="#" class="submit button_create" <? if(isset($discussion_entity))
			{?>
				onclick="SubmitDiscussion('<?=$base_url?>',<?=$discussion_entity?>); return false;" 
			<?}
			else
			{?>
				onclick="SubmitDiscussion('<?=$base_url?>'); return false;">
			<?}?>		
		</a>
		<!--input class="submit cmargin" type="image" value="" src="/resources/images/button.create.png"/></div-->
	</div>
					
</form>