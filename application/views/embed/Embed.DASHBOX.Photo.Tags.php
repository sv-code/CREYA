<div class="dashBoxPhoto">
	
	<a href="#" class="boxClose boxOpen" id="link1">TAGS</a>
	
	<? if($session_user==$artist_dname)
	{?>
		<div class="box-link1" id="userTags">
			<form id="addTags" name="theForm" action="" method="get">
				<input id="tagField" type="text" value="add more tags"/>
			</form>
			
			<? foreach($photo_tags as $tag): ?>
				<p><span><?=$tag['tag_name']?></span><a href="#"><img src="/resources/images/tagRem.png" alt="" /></a></p>
			<? endforeach ?>
		</div>
	<?}
	else
	{?>
		<div class="box-link1"> 
			<? foreach($photo_tags as $tag): ?>
				<a href="#"><?=$tag['tag_name']?></a>. 
			<? endforeach ?>
		</div>
	<?}?>

</div>