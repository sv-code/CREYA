<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	
	<? $this->load->view('Meta.HEADER'); ?>
	
	<script type="text/javascript" src="/resources/js/bookmarks.js"></script>
	<script type="text/javascript" src="/resources/js/lightbox_utils.js"></script>
			
	<title>CREYA | bookmarks</title>


</head>

<body class="modal">
	
	

		<div class="header">
			<h1 class="modal_header right">Bookmarks</h1>
		</div>
		
		<div class="cdivider clight clear"></div>
		<div id="subcontrols">
			<a id="photo" href="#" <? if($bookmarks_type==VIEW_BOOKMARKS_TYPE_PHOTO){?>class="active"<?}?> onclick="ReloadBookmarks(this,'/artist/bookmarks/photo',FILTER_NONE); return false;">
				<div class="photographs left"></div>
				<span class="subscript">(<?=$photo_bookmark_count?>)</span>
			</a>
			<a id="artist" href="#" <? if($bookmarks_type==VIEW_BOOKMARKS_TYPE_ARTIST){?>class="active"<?}?> onclick="ReloadBookmarks(this,'/artist/bookmarks/artist',FILTER_NONE); return false;">
				<div class="artists left"></div>
				<span class="subscript">(<?=$artist_bookmark_count?>)</span>
			</a>
			<a id="group" href="#" <? if($bookmarks_type==VIEW_BOOKMARKS_TYPE_GROUP){?>class="active"<?}?> onclick="ReloadBookmarks(this,'/artist/bookmarks/group',FILTER_NONE); return false;">
				<div class="groups left"></div>
				<span class="subscript">(<?=$group_bookmark_count?>)</span>
			</a>
		</div>
		<div class="cdivider clight"></div>

		<div id="CarouselContainer">
		
			<ul id="bookmarks" class="jcarousel-skin-tango fade_preload <?='bookmarks_'.$bookmarks_type_string?>">
				
				<div class="preloader_container"><div class="preloader"></div></div>
				
				<? if($bookmarks_type==VIEW_BOOKMARKS_TYPE_PHOTO)
				{ 
					for($i=0;$i<count($bookmarks_array);$i+=2) 
					{?>
						<li>
							<a id="<?=$bookmarks_array[$i]['PHOTO_ID']?>" href="/photo/<?=$bookmarks_array[$i]['PHOTO_ID']?>" class="left thumbnail90 hoverhighlight" target="_parent">
								<div class="relative"><span class="remove hoverhighlight" onclick="RemovePhotoBookmark('<?=$bookmarks_array[$i]['PHOTO_ID']?>',this); return false;"></span></div>
								<img src="<?=cgraphics_image_photo_url(PHOTO_PREVIEW,$bookmarks_array[$i]['PHOTO_ID']);?>" alt="" width="90" height="90" />
							</a>
							
							<? if(array_key_exists($i+1,$bookmarks_array))
							{?>
								<a id="<?=$bookmarks_array[$i+1]['PHOTO_ID']?>" href="/photo/<?=$bookmarks_array[$i+1]['PHOTO_ID']?>" class="left thumbnail90 hoverhighlight" target="_parent">
									<div class="relative"><span class="remove hoverhighlight" onclick="RemovePhotoBookmark('<?=$bookmarks_array[$i+1]['PHOTO_ID']?>',this); return false;"></span></div>
									<img src="<?=cgraphics_image_photo_url(PHOTO_PREVIEW,$bookmarks_array[$i+1]['PHOTO_ID']);?>" alt="" width="90" height="90" />
								</a>
							<?}?>
								
							
						</li>
					<?}?>
				<?}?>
				
				<? if($bookmarks_type==VIEW_BOOKMARKS_TYPE_ARTIST)
				{
					 for($i=0;$i<count($bookmarks_array);$i+=2) 
					{?>
						<li>
							<a id="<?=$bookmarks_array[$i]['artist_dname']?>" href="/artist/<?=$bookmarks_array[$i]['artist_dname']?>" class="left thumbnail90 hoverhighlight" target="_parent">
								<div class="relative"><span class="remove hoverhighlight" onclick="RemoveArtistBookmark('<?=$bookmarks_array[$i]['artist_dname']?>',this); return false;"></span></div>
								<div class="relative"><span class="title"><?=$bookmarks_array[$i]['artist_dname']?></span></div>
								<img src="<?=cgraphics_image_artist_avatar_url(ARTIST_AVATAR_PREVIEW,$bookmarks_array[$i]['artist_avatar']);?>" alt="" width="90" height="90" />
							</a>
							<? if(array_key_exists($i+1,$bookmarks_array))
							{?>
								<a id="<?=$bookmarks_array[$i+1]['artist_dname']?>" href="/artist/<?=$bookmarks_array[$i+1]['artist_dname']?>" class="left thumbnail90 hoverhighlight" target="_parent">
									<div class="relative"><span class="remove hoverhighlight" onclick="RemoveArtistBookmark('<?=$bookmarks_array[$i+1]['artist_dname']?>',this); return false;"></span></div>
									<div class="relative"><span class="title"><?=$bookmarks_array[$i+1]['artist_dname']?></span></div>
									<img src="<?=cgraphics_image_artist_avatar_url(ARTIST_AVATAR_PREVIEW,$bookmarks_array[$i+1]['artist_avatar']);?>" alt="" width="90" height="90" />
								</a>
							<?}?>	
						</li>
					<?}?>
				<?}?>
				
				<? if($bookmarks_type==VIEW_BOOKMARKS_TYPE_GROUP)
				{
					for($i=0;$i<count($bookmarks_array);++$i) 
					{?>
						<li>
							<a id="<?=$bookmarks_array[$i]['group_preview_filename']?>" href="/group/<?=$bookmarks_array[$i]['GROUP_ID']?>" class="left thumb" target="_parent">
								<div class="relative"><span class="title"><?=$bookmarks_array[$i]['group_name']?></span></div>
								<img class="hoverhighlight" src="<?=cgraphics_image_group_preview_url(GROUP_PREVIEW_THUMBNAIL_MEDIUM,$bookmarks_array[$i]['group_preview_filename']);?>" class="chigh" alt="" />														
							</a>
						</li>
					<?}?>
				<?}?>
			</ul>
			
			<div id="filters">
				<a class="relative reset" style="display:none;" onclick="ReloadBookmarksWithSearch(); $('.reset').hide();$('.filter_keywords').attr('value','filter bookmarks by name or tag' );return false;" ><span class="hoverhighlight"></span></a>
				<input class="filter_keywords text textlight" style="width:99%;" type="text" value="filter bookmarks by name or tag" name="" onkeypress="if (event.keyCode == 13) {  ReloadBookmarksWithSearch($('.filter_keywords').attr('value')); $('.reset').show(); } ;"/>
			</div>
		
		</div>
		
		
	
	

</body>
</html>
	