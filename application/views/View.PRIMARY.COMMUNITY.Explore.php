<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	
	<? $this->load->view('Meta.HEADER'); ?>
	<? $this->load->view('Meta.FILTERS'); ?>
		
	<link href="/resources/css/community.css" rel="stylesheet" type="text/css"/>
		
	<title>CREYA | community</title>

</head>

<body>
	
	<? $data['navigation_active'] = VIEW_NAVIGATION_ACTIVE_COMMUNITY ?>
	<? $this->load->view('Embed.HEADER',$data); ?>
	
	<div id="bg-wrapper-black"></div>
	<div id="wrapper2">
		<div id="main" class="pageminheight content_secondary">
						
			<? if($current_page==1)
			{?>			
				<div class="intro">
					<div class="info">
						<h2>a forum to post questions, comments, review requests or just about anything related to photography</h2>
					</div>
					<a class="create button_create_a_post" href="/community/discussion/create"></a>
				</div>
			<?}?>		
			
			<div class="reload_gallery fade_preload">
				<div class="preloader_container"><div class="preloader"></div></div>	
			
				<? $filter_data['filter_prompt'] = 'filter posts by title or type'; 
				$pagination_params = array('function_reload'=>'Reload','url_reload'=>'/community .reload_gallery','div_reload'=>'.reload_gallery','filter_exclude'=>$filter_data['filter_prompt']); ?>
				<? $this->load->view('Embed.PAGINATION.Static',$pagination_params); ?>			
				
				<!-- Content Column -->
				<div id="community">
								
					<? if(count($discs)==0)
					{
						$this->load->view('Embed.Prompt.Empty'); 
					}	
					else
					{				
						for($total_columns=0;$total_columns<COMMUNITY_DISCUSSIONS_PREVIEW_NUM_COLUMNS;++$total_columns)
						{
							$current_column = $total_columns; ?>
							
							<div class="discussion_preview_column">
							<? for($i=$current_column;$i<count($discs);$i+=COMMUNITY_DISCUSSIONS_PREVIEW_NUM_COLUMNS) 
							{
								if(isset($discs[$i]))
								{
									$disc = $discs[$i];
									$disc_id = $disc['DISC_ID']; 
									$disc_section = $disc['SECTION_ID']; ?>
								
									<div id="<?=$disc['DISC_ID']?>" class="relative deletable discussion_preview <? if($disc_section==DISCUSSION_SECTION_REVIEW_REQUEST){?>discussion_review_request<?}else if($disc['disc_image_attachment_preview']!=null){?>discussion_image_attachment<?}?>">
										
										<!-- the LINK -->
										<!--div class="link">
											<a href="/community/<? if($disc_section==DISCUSSION_SECTION_REVIEW_REQUEST){?>reviewrequest<?}else{?>discussion<?}?>/<?=$disc_id?>" class="chigher"></a>
										</div-->	
										
										<? if($session_user==$disc['artist_dname'])
										{
											 if($disc_section==DISCUSSION_SECTION_DEFAULT) 
											{ 
												$delete_data['function_delete'] =  "VerifySession(Ajax_DeleteEntity('".$disc['DISC_ID']."','/community/deletepost'));";
											}
											if($disc_section==DISCUSSION_SECTION_REVIEW_REQUEST) 
											{ 
												$delete_data['function_delete'] =  "VerifySession(Ajax_DeleteEntity('".$disc['DISC_ID']."','/community/deletereviewrequest'));"; 
											}																		
											$this->load->view('Embed.PLUGIN.Delete',$delete_data);
										}?>								
										
										<!-- Comment Count -->
										<div class="count">
											<div class="num"><span><?=$disc['disc_comment_count']?></span></div>
										</div>	
										
										<!-- Creator dname -->
										<div class="creator">
											<div class="cdivider clight"></div>	
											<div class="posted_by"><span class="artist_dname">posted by <?=$disc['artist_dname']?></span></div>													
										</div>
										
										<!-- hoverhighlight the images, title and preview -->
										<a class="hoverhighlight" href="/community/<? if($disc_section==DISCUSSION_SECTION_REVIEW_REQUEST){?>reviewrequest<?}else{?>post<?}?>/<?=$disc_id?>">
										
											<? if($disc['disc_image_attachment_preview']!=null)
											{?>
												<img class="image_preview fade" src="<?=cgraphics_image_discussion_preview_url(DISCUSSION_TYPE_COMMUNITY,DISCUSSION_PREVIEW,$disc['disc_image_attachment_preview']);?>"/>
											<?}?>
											
											<? if($disc_section==DISCUSSION_SECTION_REVIEW_REQUEST)
											{?>
												<span class="title"></span>
											<?}
											else
											{?>
												<div class="text hoverhighlight fade">
													<span class="title yellow block"><?=$disc['disc_title']?></span>
													<p><?=$disc['disc_body']?></p>
													<p class="dots">...</p>
												</div>
											<?}?>
										</a>
																		
									</div>
								<?}
							}?>
							</div>
						<?}?>
									
						<div class="clear"></div>
						
					<?}?>
											
				</div>
				<!-- END Content Column -->
				
			</div>
										
		</div>
		<!-- END Main -->
			
	</div>
	<!-- END Wrapper 2 -->
	
	<? $this->load->view('Embed.DOCK.Section_Search',$filter_data); ?>
	
	<!--? $this->load->view('Embed.USERPAD'); ?-->
	
	<? $this->load->view('Embed.FOOTER'); ?>		
	
</body>
</html>