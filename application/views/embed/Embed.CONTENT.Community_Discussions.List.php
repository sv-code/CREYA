<? foreach($disc_array['discs'] as $disc)
{
	$disc_id = $disc['DISC_ID'] ?>

	<div class="discussion_preview_box">
		
		<div class="create_details left">
			<p class="who textright">
				posted<br>
				by <?=$disc['artist_dname']?>
			</p>
			<span class="time size12 itals right"><?=$disc['disc_date']?></span>
		</div>
		
		<div class="discussion left">
			<a class="title yellow" href="/community/discussion/<?=$disc['DISC_ID']?>"><?=$disc['disc_title']?></a>
			<p class="body_preview"><?=$disc['disc_body']?></p>
			
			<div class="associations">
				<a class="section rmargin" href="#"><div class="start"></div><div class="text"><span>review request</span></div><div class="end"></div></a>
				
				<? foreach($disc['disc_tags'] as $tag)
				{?>
					<a class="tag rmargin" href="#"><div class="start"></div><div class="text"><span><?=$tag?></span></div><div class="end"></div></a>
				<?}?>
			</div>
			
		</div> 
		
		<div class="total left bold"><?=$disc['disc_comment_count']?></div>
		
		<div class="clear"></div>
		<div class="cdivider clight"></div>
	
	</div>
	
<?}?>

<!--div class="discussion_preview_box">
	
	<div class="create_details left">
		<p class="who textright">
			posted<br>
			by abigail
		</p>
		<span class="time size12 itals right">20 minutes ago</span>
	</div>
	
	<div class="discussion left">
		<a class="title yellow" href="#">When does taking pictures for free cross the line?</a>
		<p class="body_preview">In a basic mysql insert you are able to set a password variable 'PASSWORD($password)' but this breaks a PDO statement. </p>
		
		<div class="associations">
			<a class="section" href="#"><div class="start"></div><div class="text"><span>review request</span></div><div class="end"></div></a>
			<a class="tag" href="#"><div class="start"></div><div class="text"><span>black and white</span></div><div class="end"></div></a>
			<a class="tag" href="#"><div class="start"></div><div class="text"><span>portraiture</span></div><div class="end"></div></a>
		</div>
		
	</div> 
	
	<div class="total left bold">207</div>
	
	<div class="clear"></div>
	<div class="cdivider clight"></div>
</div-->
