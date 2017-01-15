<!-- Main navigation-->

<? if(!isset($lightbox_lockdown) ||  $lightbox_lockdown==false)
{?>

	<ul id="navigation_main">
		
		<li class="hoverhighlight2 logo <? if($session_user!=null){?>actions<?}?>"><a href="<? if($session_user==null){echo '/gateway';}else{echo '/artist/'.$session_user;}?>"></a>
		<? if($session_user!=null)
		{?>
		        	<ul id="global_actions">
		        		<li><a class="view_profile" href="/artist/<?=$session_user?>"></a></li>
				<li><a class="view_gallery" href="/artist/<?=$session_user?>/gallery"></a></li>
		        		<li><a class="upload_photographs" href="/photo/uploader"></a></li>
		                    <!--li><a class="create_shoebox" href="/artist/shoebox/create"></a></li-->
		                    <li><a class="start_community_discussion" href="/community/post/create"></a></li>
				<li><a class="create_group" href="/group/create"></a></li>		                    
		                    <li><a class="change_settings" href="/preferences"></a></li>
		                    <li><a class="logout" href="/artist/logout"></a></li>
		       	</ul>
		<?}?>
		</li>
				
		<!-- class='active' on a tag for active page -->
		<li class="photographs primary"><a <? if(isset($navigation_active) && $navigation_active==VIEW_NAVIGATION_ACTIVE_PHOTOS){?>class="active"<?}?> href="/photoexplore">photographs</a></li>
		<li class="groups primary"><a <? if(isset($navigation_active) && $navigation_active==VIEW_NAVIGATION_ACTIVE_GROUPS){?>class="active"<?}?> href="/groupexplore">groups</a></li>
		<li class="community primary"><a <? if(isset($navigation_active) && $navigation_active==VIEW_NAVIGATION_ACTIVE_COMMUNITY){?>class="active"<?}?> href="/community">community</a></li>
	</ul>
<?}?>
	
<!-- END Main navigation -->	