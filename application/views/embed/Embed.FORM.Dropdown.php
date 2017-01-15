<div <? if(isset($dropdown_id)){?>id="<?=$dropdown_id?>"<?}?> class="dropdown left">
						
	<a class="selected">select a genre</a>
						
	<div class="choices hidden">
		<? for($j=0;$j<count($photo_type_array);++$j)
		{?>
			<a class="choice"><?=$dropdown_array[$j][$dropdown_array_key]?></a>
		<?}?>
							
		<span class="end"></span>
	</div>
							
</div>