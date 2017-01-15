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
	<span class="title right">comments</span>
						
	<div id="<?=$comment_input_id?>" class="comment_input">
		<a class="add button_add_comment left" href="#" onclick="VerifySession(AddComment,true,'<?=$add_comment_url?>');return false;" style="display:block;"></a>
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
		
		<div class="cdivider clight" style="margin-bottom:10px;margin-top:2px;"></div>	
		
		<? foreach($comments as $comment): ?>
			<div id="<?=$comment['COMMENT_ID']?>" class="deletable comment <? if($comment['artist_dname']==$session_user) {?>background_black<?}?>">
										
				<? if($comment['artist_dname']==$session_user) 
				{?>
					<!-- DELETE COMMENT -->
					<? $delete_data['function_delete'] =  "VerifySession(DeleteComment("."'".$remove_comment_url."'".",".$comment['COMMENT_ID']."));";
					$this->load->view('Embed.PLUGIN.Delete',$delete_data); ?>					
				<?}?>
										
				<div class="fade content">
					<p class="right">
						<?=nl2br($comment['comment_text'])?>
					</p>
					
					<div class="creator">
						<a href="/artist/<?=$comment['artist_dname']?>" class="avatar hoverhighlight right thumbnail40"><img src="<?=cgraphics_image_artist_avatar_url(ARTIST_AVATAR_THUMBNAIL_SMALL,$comment['artist_avatar']);?>"/></a>
						<div class="author right">
							<a href="/artist/<?=$comment['artist_dname']?>"><span class="artist_dname hoverhighlightyellow"><?=$comment['artist_dname']?></span></a>
							<br>
							<span class="time"><?=$comment['comment_date']?></span>									
						</div>
					</div>
					<div class="clear"></div>
				</div>
										
				<div class="cdivider clight"></div>	
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