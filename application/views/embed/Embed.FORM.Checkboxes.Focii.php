<div class="checkboxes_focii checkboxes tmargin10">
	
	<? for($c=0;$c<$column_count;++$c)
	{?>
	
		<div class="column" <? if(($c+1)==$column_count){?>style="width:120px;"<?}?>>
			<? for($i=$c;$i<count($artist_focii);$i+=$column_count) 
			{
				if(array_key_exists($i,$artist_focii))
				{?>
					<div class="checkbox_unit">
						<input class="block checkbox_focus left" type="checkbox" value="<?=$artist_focii[$i]['artist_focus_name']?>" name="artist_focus[]"/><span class="block focus left"><?=$artist_focii[$i]['artist_focus_name']?></span>
						<div class="clear"></div>
					</div>
				<?}
			}?>
		</div>
	
	<?}?>
	
	<div class="clear"></div>
	
	<input class="checkbox_focus" type="checkbox" value="other" name="artist_focus[]"/><span class="focus"></span>
	<input id="focus_other" style="position:relative;top:-2px;" class="text" type="text" value="other" name="artist_focus_other"/>
							
</div>
<div class="clear"></div>