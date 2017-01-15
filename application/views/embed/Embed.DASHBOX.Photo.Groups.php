<div class="dashBoxPhoto">
	
	<a href="#" class="boxClose" id="link2">GROUPS</a>
	
	<? if($session_user==$artist_dname)
	{?>
		<div class="box-link2" id="userGroups" style="display:none;">
			<? foreach($photo_groups as $group): ?>
				<p><span><?=$group['group_name']?></span><a href="#"><img src="/resources/images/tagRem.png" alt="" /></a></p>
			<? endforeach ?>
		</div>
	<?}
	else
	{?>
		<div class="box-link2" style="display:none;">
			<? foreach($photo_groups as $group): ?>
				<a href="/group/<?=$group['GROUP_ID']?>"><?=$group['group_name']?></a> . 
			<? endforeach ?>
		</div>
	<?}?>
	
</div>