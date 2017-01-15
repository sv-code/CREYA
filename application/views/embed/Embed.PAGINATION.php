<div class="pagination chighest  content_secondary">
	
	<? if($page_count>1)
	{?>
		<ul>
			<? if($current_page>1)
			{?>
				<li class="previous"><a href="#" onclick="<?=$function_reload?>('<?=$url_reload?>','<?=$div_reload?>',<?=$current_page-1?>); return false;"></a></li>
				<li class="first"><a href="#" onclick="<?=$function_reload?>('<?=$url_reload?>','<?=$div_reload?>',1); return false;"></a></li>
			<?}
			else
			{?>
				<li class="previous_none">
				<li class="first_none">
			<?}?>
			
			<li class="current"><?=$current_page?></li>
			
			<? if($current_page < $page_count)
			{?>
				<li class="last"><a href="#" onclick="<?=$function_reload?>('<?=$url_reload?>','<?=$div_reload?>',<?=$page_count?>); return false;"></a></li>
				<li class="next"><a href="#" onclick="<?=$function_reload?>('<?=$url_reload?>','<?=$div_reload?>',<?=$current_page+1?>); return false;"></a></li>			
			<?}
			else
			{?>
				<li class="last_none">
				<li class="next_none">			
			<?}?>
		</ul>
	<?}?>

	<div class="clear"></div>
</div>


