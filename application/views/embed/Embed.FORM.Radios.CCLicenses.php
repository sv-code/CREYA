<div class="cc_licenses tmargin5">

	<? for($i=0;$i<count($cc_licenses);++$i) 
	{?>
		<input class="finput_margin radio_focus" type="radio" value="<?=$cc_licenses[$i]['CC_LICENSE_ID']?>" name="cc_license" <? if(isset($cc_checked) && $cc_checked==$cc_licenses[$i]['CC_LICENSE_ID']){?>checked<?}?>><span class="cc_license lucida" style="position:relative;top:-2px;margin-left:7px;"><?=$cc_licenses[$i]['cc_license_name']?></span>
		<br>	
	<?}?>

</div>