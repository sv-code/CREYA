<? $pagination_params = array('function_reload'=>'Reload','url_reload'=>$url_reload,'div_reload'=>$div_reload,'filter_exclude'=>$filter_prompt); ?>
<? $this->load->view('Embed.PAGINATION.Static',$pagination_params); ?>

<div id="groupgallery"  class="fade_preload gridgallery">
	<div class="preloader_container"><div class="preloader"></div></div>
	
	<? if(count($groups)==0)
	{
		$this->load->view('Embed.Prompt.Empty'); 
	}
	else
	{	
		$gid=0 ?>
		<? for($i=0;$i<VIEW_GALLERY_GROUPS_NUM_ROWS;++$i) 
		{?>
			<div class="row">
				
				<? for($j=0;$j<VIEW_GALLERY_GROUPS_NUM_COLUMNS && $gid<count($groups);++$j,++$gid) 
				{?>
					<? $group = $groups[$gid] ?>
					<div class="group_snapshot <? if($j+1==VIEW_GALLERY_GROUPS_NUM_COLUMNS) echo 'last_column';?>">
						<a href="/group/<?=$group['GROUP_ID']?>">
							<span class="group_name chighest"><?=$group['group_name']?></span>
							<img class="hoverhighlight" src="<?=cgraphics_image_group_preview_url(GROUP_PREVIEW_SPANORAMA,$group['group_preview_filename']);?>"/>
						</a>
					</div>
				<?}?>	
				
				<div class="clear"></div>
			
			</div>
			
		<?}?>
	
	<?}?>

</div>		


