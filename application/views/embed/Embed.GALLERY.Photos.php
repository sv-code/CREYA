<div id="photogallery" class="fade_preload gridgallery">
	<div class="preloader_container"><div class="preloader"></div>
    </div>
    
    <? if(isset($gallery_hidden_field) && is_array($gallery_hidden_field) && array_key_exists('id',$gallery_hidden_field) && array_key_exists('value',$gallery_hidden_field))
	{?>
		<input id="<?=$gallery_hidden_field['id']?>" type="hidden" value="<?=$gallery_hidden_field['value']?>" />
	<?}?>
	
	
    <? $pid = 0?>
    <? 
    for ($i = 0; $i < VIEW_GALLERY_PHOTOS_NUM_ROWS; ++$i) {
        
    ?>
    <div class="row">
        <? 
        for ($j = 0; $j < VIEW_GALLERY_PHOTOS_NUM_COLUMNS && $pid < count($photos); ++$j, ++$pid) {
            
        ?>
        <? $photo = $photos[$pid]?>
        
        <div id="<?=$photo['PHOTO_ID']?>" class="thumbnail140 hoverhighlight deletable photo_snapshot <? if($j+1==VIEW_GALLERY_PHOTOS_NUM_COLUMNS) echo 'last_column';?>">
            <? 
            if ($session_user == $photo['artist_dname']) {
                
            ?>
            <!-- DELETE PHOTO -->
            <div class="delete">
                <a class="delete_start hoverlighthighlight" href="#"></a>
                <div class="delete_confirmation">
                    <a class="accept" href="#" onclick="VerifySession(DeletePhoto('<?=$photo['PHOTO_ID']?>')); return false;"></a>
                    <a class="cancel" href="#"></a>
                </div>
            </div>
            <? 
            } else {
                
            ?>
            <!-- BOOKMARK / UNBOOKMARK PHOTO -->
            <div class="chighest bookmark_container">
                <a class="<? if($session_user!=null && $photo['IsBookmarked']){?>on<?}else{?>off<?}?>"></a>
            </div>
            <? } ?>
            <a href="/photo/<?=$photo['PHOTO_ID']?>"><img class="chigh fade" src="<?=cgraphics_image_photo_url(PHOTO_SNAPSHOT,$photo['PHOTO_ID']);?>"/></a>
        </div>
        <? } ?>
        	
        <div class="clear">
        </div>
    </div>
    <? } ?>
    	
</div>
<div class="content_divider">
</div>
<? $pagination_params = array('function_reload'=>'ReloadGallery', 'div_reload'=>'', 'url_reload'=>0); ?>
<? $this->load->view('Embed.PAGINATION.Static', $pagination_params); ?>
