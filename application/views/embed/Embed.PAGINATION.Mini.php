<div class="mini_pagination chighest">
	
	<? if($last_page>1)
	{?>
		<ul>
			<? if($current_page>1)
			{?>
				<li class="previous"><a href="#" onclick="<?=$function_reload?>('<?=$url_reload?>',<?=$current_page-1?>); return false;"></a></li>
			<?}
			else
			{?>
				<li class="previous_none">
			<?}?>
			
			<li class="current"><?=$current_page?></li>
			
			<? if($current_page < $last_page)
			{?>
				<li class="next"><a href="#" onclick="<?=$function_reload?>('<?=$url_reload?>',<?=$current_page+1?>); return false;"></a></li>
			<?}
			else
			{?>
				<li class="next_none">
			<?}?>
		</ul>
	<?}?>
</div>

<div class="clear"></div>
