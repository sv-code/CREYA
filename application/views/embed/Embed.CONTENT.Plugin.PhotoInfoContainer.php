<div class="photo_info_container">
	
	<? if($photo_exif_focal!=0 && $photo_exif_aperture!=0 && $photo_exif_shutter!=0 && $photo_exif_iso!=0)
	{?>
			
		<div class="exif_container">
			<div style="width:380px;display:block;margin:0 auto;">
				<span class="subscript focal"></span><span class="exif"><?=$photo_exif_focal?></span><span class="qualifier">mm</span>
				<span class="subscript aperture"></span><span class="qualifier" style="margin:26px 0 0 0;">f/</span><span class="exif"><?=$photo_exif_aperture?></span>
				<span class="subscript shutter"></span><span class="exif"><?=$photo_exif_shutter?></span><span class="qualifier">s</span>
				<span class="subscript iso"></span><span class="exif"><?=$photo_exif_iso?></span>
				<div class="clear"></div>
			</div>
		</div>
		
	<?}?>
			
 </div>	