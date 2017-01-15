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
	
	<? if(isset($comment_hidden_fields) && is_array($comment_hidden_fields))
	{?>
		<? foreach($comment_hidden_fields as $comment_hidden_field): ?> 
			<? if(array_key_exists('id',$comment_hidden_field) && array_key_exists('value',$comment_hidden_field))
			{?>
				<input id="<?=$comment_hidden_field['id']?>" type="hidden" value="<?=$comment_hidden_field['value']?>" />
			<?}?>
		<? endforeach ?>		
	<?}?>			
					
	<div class="clear"></div>
	<!--div class="cdivider ctquartmargin"></div-->
	
	<div class="comments fade_preload">
		
		<div class="preloader_container"><div class="preloader"></div></div>	
		
		<? 
			$review_request_image_factor = $this->config->item(DISCUSSION_STANDARD); 
			$review_request_image_factor = $review_request_image_factor['x'];
		?>
		
		<? foreach($comments as $comment): ?>	
			<div id="<?=$comment['REVIEW_ID']?>" class="deletable comment_tile">
				
				<? if($comment['artist_dname']==$session_user) 
				{?>
					<div class="delete">
						<a class="delete_start" href="#"></a>
						<div class="delete_confirmation">
							<a class="accept" href="#" onclick="VerifySession(DeleteComment('<?=$remove_comment_url?>',<?=$comment['REVIEW_ID']?>)); return false;"></a>
							<a class="cancel" href="#"></a>
						</div>
					</div>
				<?}?>
											
				<div class="cdivider barline"></div>
				<div class="bar">
					<span class="time itals"><?=$comment['review_date']?></span>
					<a href="/artist/<?=$comment['artist_dname']?>" class="avatar hoverhighlight thumbnail40"><img src="<?=cgraphics_image_artist_avatar_url(ARTIST_AVATAR_THUMBNAIL_SMALL,$comment['artist_avatar']);?>"/></a>
					<div class="artist_dname"><span>reviewed by </span><a href="/artist/<?=$comment['artist_dname']?>"><span class="hoverhighlightyellow"><?=$comment['artist_dname']?></span></a></div>
				</div>
				<div class="cdivider barline"></div>
				
				<div class=" fade content">
					<p class="metric">
						<span class="impact imagetext block left"></span><span class="rating left"><?=$comment['review_metric_impact_rating']?></span><div class="clear"></div>
						<span class="mcomment itals2 block"><?=$comment['review_metric_impact_comment_text']?></span>
					</p>
					
					<p class="metric">
						<span class="balance imagetext block left"></span><span class="rating left"><?=$comment['review_metric_balance_rating']?></span><div class="clear"></div>
						<span class="mcomment itals2 block"><?=$comment['review_metric_balance_comment_text']?></span>
						<? if(isset($comment['crop_x']))
						{?>						
							<div class="tmargin10">
								<img id="<?=$comment['REVIEW_ID'].'_cropsuggestion'?>" style="width:500px;" class="block" src="<?=cgraphics_image_discussion_preview_url(DISCUSSION_TYPE_COMMUNITY,DISCUSSION_STANDARD,$image_attachment);?>"/>
							</div>
							<!--img id="review_image2" onclick="ShowCropSuggestion('review_image2',10,25,100,200,500/650);return false;" style="margin:10px auto;width:500px;" class="block" src="<?=cgraphics_image_discussion_preview_url(DISCUSSION_TYPE_COMMUNITY,DISCUSSION_STANDARD,$image_attachment);?>"/-->
						<?}?>
					</p>
					
					<p class="metric">
						<span class="technique imagetext block left"></span><span class="rating left"><?=$comment['review_metric_technique_rating']?></span><div class="clear"></div>
						<span class="mcomment itals2 block"><?=$comment['review_metric_technique_comment_text']?></span>
					</p>
					
					<p class="general">
						<?=$comment['review_comment_text']?> 
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