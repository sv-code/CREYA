<? if(count($discs)==0)
{
	$this->load->view('Embed.Prompt.Empty'); 
}	
else
{	
	foreach($discs as $disc)
	{
		$disc_id = $disc['DISC_ID'] ?>
	
		<div class="discussion_preview_box hoverhighlight">
			
			<div class="create_details left textright">
				<span class="artist_dname block tmargin5" style="font-size:12px;color:#999999;"><?=$disc['artist_dname']?></span>
				<span class="block time tmargin5" style="font-size:11px;color:#666666;" ><?=$disc['disc_date']?></span>
			</div>
			
			<div class="discussion_preview left">
				<a class="title yellow ckern" href="/group/<?=$gid?>/post/<?=$disc_id?>"><?=$disc['disc_title']?></a>
				<p class="body_preview"><?=$disc['disc_body']?></p>
			</div> 
			
			<div class="total left bold"><?=$disc['disc_comment_count']?></div>
			
			<div class="clear"></div>
			<div class="cdivider clight"></div>
		</div>
	<?}?>
<?}?>


<!--div class="discussion_preview_box">
	
	<p class="create_details left">
		posted by abigail<br>
		20 minutes ago
	</p>
	
	<div class="discussion left">
		<a class="title yellow" href="#">When does taking pictures for free cross the line?</a>
		<p class="body_preview">Stemming from a topic in a different sub-forum, I was wondering how people feel about taking photographs for free</p>
	</div> 
	
	<h4><span class="num">12</span> comments</h3>
	
	<div class="clear"></div>
	<div class="cdivider clight"></div>
</div-->
