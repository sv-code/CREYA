<div id="filters">
	
	<div id="filtercontrols">
		<a href="#" class="filterUp"></a>
		<a href="#" class="reset" title="Reset Filters"></a>		
	</div>

	<div id="dock" <? if(isset($shoebox_creator) && $shoebox_creator==true){?>class="shoebox_creator" style="height:105px;"<?}?>>

		<div id="tagBlock">
			<div id="tags">
				<input id="tag_values" class="tagsL" type="text" name="tags" value="filter by tags and names" onfocus="$(this).attr('value','');return false;"/>
				<input class="tagsR" type="image" value="Search" src="/resources/images/filterTagsR.png"/>
			</div>
		</div>
			
			<div id="typeBlock">
				<!-- TYPE Drop Down -->
				<div id="typeDD" style="visibility:hidden;">
				
					<a href="#" class="first"><span style="margin-left:2px;">ALL</span></a>
					
					<? foreach($photo_type_array as $photo_type)
					{?>
						<a href="#"><?=$photo_type['photo_type_name']?></a>
					<?}?>	
					
				</div>
				<a href="#" class="type">
					<span style="margin-left:2px;">genre</span>
				</a>
			</div>
		
		<div class="filter-container">
			<label>Aperture: <span id="fstopVal">All</span></label>
			<div id='fstop-bar' class='filter-bar'> 
				
			</div>
		</div>
		
		<div class="filter-container">
			<label>Shutter Speed: <span id="shutterVal">All</span></label>
			<div id='shutter-bar' class='filter-bar'> 
				
			</div>
		</div>
		
		<div class="filter-container">
			<label>ISO: <span id="isoVal">All</span></label>
			<div id='iso-bar' class='filter-bar'> 
				
			</div>
		</div>
		
		<div class="filter-container">
			<label>Focal Length: <span id="focalVal">All</span></label>
			<div id='focal-bar' class='filter-bar'> 
				
			</div>
		</div>
		
		
		<? if(isset($shoebox_creator) && $shoebox_creator==true)
		{?>
			<div class="clear"></div>
			<div class="cdivider"></div>
			<div class="shoebox_creator_input">
				
				<img class="prompt left" src="/resources/images/imagetext.save_filters_as_shoebox.png" /> 
				<input id="input_shoebox_name" class="shoebox_name text left" name="shoebox_name" type="text" value="enter shoebox name" onKeyUp="CheckAndMakeShoeboxSaveable();return false;"/>
				<a id="shoebox_save" href="#" class="button_create right button_create_disabled" onclick="Ajax_AddShoebox();return false;" style="margin-right:15px;"></a><div class="clear"></div>
								
			</div>		
		<?}?>
		
	</div>
	
</div>

