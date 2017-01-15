<div class="dashBox newMembers" style="margin-top:10px;">
	
	<h3>Groups You May Be Interested In</h3>
	<div id="InterestedGroups">
		
		<? if(isset($groups_interested))
			{?>
			
				<? foreach($groups_interested as $group): ?>
					<div class="group_thumb_info">
						<a class="thumb left"><img src="/resources/images/thumb40.png" alt="" /></a>
						<div class="thumbInfo">
							<a class="name" href="/group/<?=$group['GROUP_ID']?>"><?=$group['group_name']?></a>
							<p>Created on <?=$group['group_date']?></p>
						</div>
					</div>
				<? endforeach ?>
			<?}?>
	</div>
	
</div>