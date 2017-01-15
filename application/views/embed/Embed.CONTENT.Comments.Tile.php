<? if(!isset($comment_input_id))
{
	$comment_input_id="NID";
}
$comments = $comment_array['comments'] ?>

<div id="section_comments">
	
	<? if($comment_array['total_comment_count']>0)
	{?>					
		<!--span class="total right"><?=$comment_array['total_comment_count']?> comment<? if($comment_array['total_comment_count']>1){?>s<?}?></span-->
	<?}?>
	<!--div class="total right"><span><?=count($comments)?></span><img src="/resources/images/imagetext.comments.png" /></div-->
						
	<div id="<?=$comment_input_id?>" class="comment_input">
		<a class="add button_add_comment left" href="#" onclick="VerifySession(AddComment('<?=$add_comment_url?>'),true);return false;"></a>
		<!--a href="#"><img class="add left" src="/resources/images/button.comment.png" onclick="return false;"></a-->
		
		<div class="expand">
			<a class="button" href="#"><span class="cancel left"></span></a>	
			<img class="save_indicator_ajax" src="/resources/images/ajax-loader-black-small.gif" />
			<div class="clear"></div>
			<div class="hide_comment_input comment_textbox">
				<div class="start"></div>
				<div class="ctext"><textarea class="comment_textarea" id="comment_text" name="comment_text" rows="" cols=""></textarea></div>
				<div class="end"></div>
				<div class="clear"></div>				
			</div>
			<? if(isset($comment_hidden_fields) && is_array($comment_hidden_fields))
			{?>
				<? foreach($comment_hidden_fields as $comment_hidden_field): ?> 
					<? if(array_key_exists('id',$comment_hidden_field) && array_key_exists('value',$comment_hidden_field))
					{?>
						<input id="<?=$comment_hidden_field['id']?>" type="hidden" value="<?=$comment_hidden_field['value']?>" />
					<?}?>
				<? endforeach ?>		
			<?}?>							
		</div>
													
	</div>
				
	<div class="clear"></div>
	<!--div class="cdivider ctquartmargin"></div-->
	
	<div class="comments fade_preload">
		
		<div class="preloader_container"><div class="preloader"></div></div>	
		
		<? foreach($comments as $comment): ?>
			<div id="<?=$comment['COMMENT_ID']?>" class="deletable comment_tile <? if($comment['artist_dname']==$session_user) {?>background_black<?}?>">
										
				<? if($comment['artist_dname']==$session_user) 
				{?>
					<div class="delete">
						<a class="delete_start" href="#"></a>
						<div class="delete_confirmation">
							<a class="accept" href="#" onclick="VerifySession(DeleteComment('<?=$remove_comment_url?>',<?=$comment['COMMENT_ID']?>)); return false;"></a>
							<a class="cancel" href="#"></a>
						</div>
					</div>
				<?}?>
										
				<div class="cdivider barline"></div>
				<div class="bar">
					<span class="time itals"><?=$comment['comment_date']?></span>
					<a href="/artist/<?=$comment['artist_dname']?>" class="avatar hoverhighlight thumbnail40"><img src="<?=cgraphics_image_artist_avatar_url(ARTIST_AVATAR_THUMBNAIL_SMALL,$comment['artist_avatar']);?>"/></a>
					<div class="artist_dname"><span>commented by </span><a href="/artist/<?=$comment['artist_dname']?>"><span class="hoverhighlightyellow"><?=$comment['artist_dname']?></span></a></div>
					
				</div>
				<div class="cdivider barline"></div>
				
				<div class="fade content">				
					<p>
						<?=nl2br($comment['comment_text'])?>
					</p>
					
				</div>
									
				<div class="clear"></div>
			
										
			</div>	
			
		<? endforeach ?>		
		
		<!-- PAGINATION -->
		<? if($comment_array['comment_page_count']>1)
		{ 
			$pagination_params = array('function_reload'=>'ReloadComments','url_reload'=>$url_reload,'current_page'=>$comment_array['current_comment_page'],'last_page'=>$comment_array['comment_page_count']);
			$this->load->view('Embed.PAGINATION.Mini',$pagination_params); 
		}?>	
		<!-- END PAGINATION -->
	
	</div>	
							
</div>