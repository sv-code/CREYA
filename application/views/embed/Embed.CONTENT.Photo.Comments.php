<div class="comment_input">
	<? if($session_user!=null)
	{?>									
		<!--a href="#"><img class="add left" src="/resources/images/button.comment.png"/ onclick="AddPhotoComment();return false;" ></a-->
		<a href="#"><img class="add left" src="/resources/images/button.comment.png"/ onclick="AddPhotoComment();return false;" ></a>
	<?}?>
	<div class="comment_input_expand">
		<a href="#"><span class="cancel left">or cancel</span></a>
		<div class="clear"></div>
		<input id="pid" type="hidden" value="<?=$photo_details['PHOTO_ID']?>" />
		<input id="artist_dname" type="hidden" value="<?=$session_user?>" />
		<textarea id="comment_text"  rows="" cols=""></textarea>
	</div>
	<div class="clear"></div>
</div>


<div id="comments" class="comment_input_collapse">
	<? foreach($photo_comments as $comment): ?>
		
			<div class='comment'>
				<a class="thumb left"><img src="<?=cgraphics_image_artist_avatar_url(ARTIST_AVATAR_THUMBNAIL_MEDIUM,$comment['artist_dname']);?>" alt="" /></a>
				<p class="bubble">
					<label><?=$comment['artist_dname']?> said at time on <?=$comment['comment_date']?></label>
					<span><?=nl2br($comment['comment_text'])?></span>
				</p>
			</div>
		
	<? endforeach ?>
</div>

<!-- @todo: DO WE NEED PAGINATION ON ARTIST/PHOTO COMMENTS?? -->	
<!--div id="pages2btm" style="margin:40px 0 0 0;float:right">
	<ul>
		<li class="previous"><a href="#">« Previous</a></li>
		<li class="currentpage">1</li>
		<li><a href="#">2</a></li>
		<li><a href="#">3</a></li>
		<li><a href="#">4</a></li>
		<li><a href="#">5</a></li>
		<li>... </li>
		<li><a href="#">10</a></li>
		<li class="nextpage"><a href="#">Next »</a></li>
	</ul>
</div-->	



