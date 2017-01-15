<div id="filters" class="smalldock userpad">
	
	<div id="filtercontrols">
		<a href="#" class="filterUp"></a>
	</div>

	<div id="dock">
		
		<? if($session_user!=null && isset($bookmark_entity_id))
		{?>
			<div class="bookmark hoversuperhighlight">
					
				<a class="on <? if($is_bookmarked!=true){?>hidden<?}else{?>block<?}?>" href="#" onclick="RemoveEntityBookmark('<?=$bookmark_entity_id?>','<?=$remove_bookmark_url?>');return false;"></a>
				<a class="off <? if($is_bookmarked==true){?>hidden<?}else{?>block<?}?>" href="#" onclick="AddEntityBookmark('<?=$bookmark_entity_id?>','<?=$add_bookmark_url?>');return false;"></a>
							
			</div>
		<?}
		else if($session_user!=null && isset($delete_entity_id))
		{?>
			<div id="<?=$delete_entity_id?>" class="deleteEntity hoversuperhighlight block deletable_static deletable">
					
				<? $delete_data['function_delete'] =  "VerifySession(".$delete_function."('".$delete_entity_id."','".$delete_entity_url."','".$delete_redirect_url."'));";
				$this->load->view('Embed.PLUGIN.Delete',$delete_data); ?>
				
			</div>
			
		<?}?>
			
		
	
	</div>
</div>