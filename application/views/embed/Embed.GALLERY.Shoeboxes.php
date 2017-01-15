<? $pagination_params = array('function_reload'=>'Reload','url_reload'=>$url_reload,'div_reload'=>$div_reload,'filter_exclude'=>$filter_prompt); ?>
<? $this->load->view('Embed.PAGINATION.Static',$pagination_params); ?>

<div id="shoeboxgallery" class="gridgallery">
		
	<? if(count($shoeboxes)==0)
	{
		$this->load->view('Embed.Prompt.Empty'); 
	}
	else
	{	
		$shoebox_id=0 ?>
		<? for($i=0;$i<VIEW_GALLERY_SHOEBOXES_NUM_ROWS;++$i) 
		{?>
			<div class="row">
			<? for($j=0;$j<VIEW_GALLERY_SHOEBOXES_NUM_COLUMNS && $shoebox_id<count($shoeboxes);++$j,++$shoebox_id) 
			{?>
				<? $shoebox = $shoeboxes[$shoebox_id] ?>
				
					<div id="<?=$shoebox['SHOEBOX_ID']?>" class="fade deletable shoebox_snapshot <? if($j+1==VIEW_GALLERY_SHOEBOXES_NUM_COLUMNS) echo 'last_column';?>">
						
										
							<? if($session_user==$shoebox['artist_dname'])
							{?>
								<!-- DELETE SHOEBOX -->
								<? $delete_data['function_delete'] =  "VerifySession(Ajax_DeleteEntity('".$shoebox['SHOEBOX_ID']."','/artist/deleteshoebox'));";
								$this->load->view('Embed.PLUGIN.Delete',$delete_data); ?>
							<?}?>		
						
							<a class="block thumbnail140 hoverhighlight" href="/shoebox/<?=$shoebox['SHOEBOX_ID']?>">
								<? if( $shoebox['photo_preview'] != null )
								{?>
									<img class="fade rheight33" src="<?=cgraphics_image_photo_url(PHOTO_SNAPSHOT,$shoebox['photo_preview']);?>" alt=""/>
								<?}	
								else 
								{?>		
									<img class="fade rheight33" src="/resources/images/placeholder.shoebox.png" alt=""/>					
								<?}?>
							</a>				
						
						
						<span class="name <? if($artist_dname==$session_user){?>editable<?}?>"><?=$shoebox['shoebox_name']?></span>	
										
					</div>
									
							
			<?}?>		
			</div>
	
		<?}?>	
	
	<?}?>
	
</div>		
<!-- END mainGallery -->


	
