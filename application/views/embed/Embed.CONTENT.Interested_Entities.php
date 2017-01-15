<div class="interested_entities" style="display:none;">

	<? if(isset($interested_entities) && count($interested_entities)>0)
	{?>		
		<div class="cdivider"></div>
		
		<? foreach($interested_entities as $entity)
		{?>		
			<div class="entity">
				<a href="/group/<?=$entity['GROUP_ID']?>"><span class="left"><?=$entity['group_name']?></span></a>
				<span class="right itals"><?=$entity['group_artist_count']?> members</span>
				<div class="clear"></div>	
			</div>
			
			<div class="clear cdivider clight"></div>
		<?}?>
	<?}?>			
</div>
