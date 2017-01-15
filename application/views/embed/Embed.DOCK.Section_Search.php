<div id="filters" class="smalldock section_search">
	
	<div id="filtercontrols">
		<a href="#" class="filterUp"></a>
		<a href="#" class="reset" onclick="$('.SS').attr('value','<?=$filter_prompt?>' );Reload('<?=$url_reload?>','<?=$div_reload?>',1,'<?=$filter_prompt?>');return false;" ></a>
	</div>

	<div id="dock">

		<div id="tagBlock">
			<input id="section_search_keywords" class="SS text" type="text" name="tags" value="<?=$filter_prompt?>" onkeyup="if( event.keyCode == 13 ){ Reload('<?=$url_reload?>','<?=$div_reload?>',1,'<?=$filter_prompt?>'); };" onfocus="if($(this).attr('value')=='<?=$filter_prompt?>'){$(this).attr('value','');}return false;" onblur="if($(this).attr('value')==''){$(this).attr('value','<?=$filter_prompt?>');}return false;" />
		</div>
	
	</div>
	
</div>