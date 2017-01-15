<div id="control_tabs">

	<a class="tab tab_profile <? if(isset($control_active) && $control_active==VIEW_ARTIST_CONTROL_ACTIVE_PROFILE){?>tab_active tab_profile_active<?}?>" href="/artist/<?=$artist_dname?>"></a>
	
	<a class="tab tab_gallery <? if(isset($control_active) && $control_active==VIEW_ARTIST_CONTROL_ACTIVE_GALLERY){?>tab_active tab_gallery_active<?}?>" href="/artist/<?=$artist_dname?>/gallery"></a>
	
	<a class="tab tab_shoeboxes <? if(isset($control_active) && $control_active==VIEW_ARTIST_CONTROL_ACTIVE_SHOEBOXES){?>tab_active tab_shoeboxes_active<?}?>" href="/artist/<?=$artist_dname?>/shoeboxexplore"></a>

</div>

<div class="clear"></div>