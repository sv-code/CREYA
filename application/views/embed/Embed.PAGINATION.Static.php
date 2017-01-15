<div class="pagination_static">
		
	<ul class="left">
		
		<? if($current_page>1)
		{?>
			<li class="first"><a href="#" onclick="<?=$function_reload?>('<?=$url_reload?>','<?=$div_reload?>',1,'<?=$filter_exclude?>'); return false;"></a></li>
			<li class="previous"><a href="#" onclick="<?=$function_reload?>('<?=$url_reload?>','<?=$div_reload?>',<?=$current_page-1?>,'<?=$filter_exclude?>'); return false;"></a></li>			
		<?}
		else
		{?>
			<li class="first_none">
			<li class="previous_none">			
		<?}?>	
		
	</ul>
		
</div>

<div class="pagination_static">
		
	<ul class="right">
		
		<? if($current_page < $page_count)
		{?>
			<li class="last"><a href="#" onclick="<?=$function_reload?>('<?=$url_reload?>','<?=$div_reload?>',<?=$page_count?>,'<?=$filter_exclude?>'); return false;"></a></li>
			<li class="next"><a href="#" onclick="<?=$function_reload?>('<?=$url_reload?>','<?=$div_reload?>',<?=$current_page+1?>,'<?=$filter_exclude?>'); return false;"></a></li>			
		<?}
		else
		{?>
			<li class="last_none">
			<li class="next_none">			
		<?}?>	
		
	</ul>
		
</div>
