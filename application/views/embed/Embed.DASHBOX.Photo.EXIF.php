<div class="dashBoxPhoto">
	
	<a href="#" class="boxClose" id="link3">EXIF DATA</a>
	
	<div class="box-link3" style="display:none;">
		<? foreach($photo_exif as $exif=>$value): ?>
				<a href="#"><?=$exif?>:<?=$value?></a><br> 
		<? endforeach ?>
	</div>
	
</div>