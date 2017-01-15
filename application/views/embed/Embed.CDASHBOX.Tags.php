<div id="cdashbox_tags" class="cdashbox fade_preload">
	
	<div class="preloader_container"><div class="preloader"></div></div>
							
	<span class="cdtitle tags"></span><div class="clear"></div>
			
	<? if($session_user!=null && isset($tag_input_allowed) && $tag_input_allowed==true)
	{?>
		<input id="tags_input" class="tags_input text size11 <? if(isset($cdashbox_align_right) && $cdashbox_align_right==true){?>right textright<?}?>" type="text" value="" onkeypress="if (event.keyCode == 13) { VerifySession(AddTag( $('<?=$entity_class?>').attr('id'), $('.tags_input').attr('value') ,'<?=$add_tag_url?>','<?=$delete_tag_url?>'),false); } ;"/>
		<!--input id="tags_input" class="tags_input text size11 <? if(isset($cdashbox_align_right) && $cdashbox_align_right==true){?>right textright rmargin20<?}else{?>lmargin20<?}?>" type="text" value="" onkeypress="if (event.keyCode == 13) {  VerifySession(AddTag('1'),false); } ;"/-->
	<?}?>
	<div class="clear"></div>
							
	<div id="tags_list" class="tmargin10">
	<? if( $tags != null )
   	{  
		for($i=0;$i<count($tags);++$i)
		{?>	
			<span class="tag <? if(isset($cdashbox_align_right) && $cdashbox_align_right==true){?>right<?}else{?>left<?}?>">
				<div class="start"></div>
				<div class="text"><span><?=$tags[$i]?></span></div>
				<? if(isset($tag_delete_allowed) && $tag_delete_allowed==true)
				{?>
					<a href="#" onclick="VerifySession(DeleteTag( $('<?=$entity_class?>').attr('id'), '<?=$tags[$i]?>','<?=$delete_tag_url?>',$(this).parent()),false);return false;"><div class="hoversuperhighlight delete_tag"></div></a>											
					<!--a href="#" onclick="VerifySession(DeleteTag(,<?=$tags[$i]?>,'/community/deletetag',$(this).parent()),false);"><div class="hoversuperhighlight delete_tag"></div></a-->
					<!--a href="#" onclick="VerifySession(DeleteTag($(this).parent()),false);"><div class="hoversuperhighlight delete_tag">23</div></a!-->
				<?}?>
			</span> 
		<?}
	}?>									
	</div>

	<div class="clear"></div>
	
	<div class="divider"></div>	
</div>	