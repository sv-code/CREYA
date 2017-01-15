<div class="upload_input">
	
	<? if($input_gallery_internal!=false)
	{?>
		<a href="#" class="my_gallery" onclick="$('#inputcarousal').css('display','block');$('#CarouselContainer').slideToggle(500); $(this).toggleClass('my_gallery_active');return false;"></a>
	<?}?>
	
	<a href="#" id="flash_multi_input" class="browse" onclick="return false;"></a>
	
</div>

<div class="clear"></div>

<? if($input_gallery_internal!=false)
{?>
	<div id="inputcarousal" class="hidden">
		<? $this->load->view('Embed.CAROUSEL.Photos.Add_Remove'); ?>
	</div>
<?}?>

