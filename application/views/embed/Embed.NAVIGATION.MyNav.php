<? if($session_user!=null) 
{?>
	<!--div id="user" class="right">
		<div class="uright right"><img src="<?=cgraphics_image_artist_avatar_url(ARTIST_AVATAR_THUMBNAIL_SMALL,$artist_avatar);?>" alt="" onclick="ShowPreloader();" /></div>
		<div class="uleft right">
			<h3><a href="/artist/<?=$session_user?>" class="username"><?=$session_user?></a></h3>
		</div>
		<div class="uleft2 right">
			<div id="mystuff">
				<h4><a href="/artist/<?=$session_user?>/gallery">gallery.</a></h4>
				<h4><a href="/artist/<?=$session_user?>/stats">stats.</a></h4>
				<h4><a href="#" class="last" onclick="bookmarks();">bookmarks</a></h4>
			</div>
		</div>
		
	</div-->
	
	<div id="my_stuff" class="right">
		<a class="hoverhighlight" href="/artist/<?=$session_user?>"><img class="avatar right" src="<?=cgraphics_image_artist_avatar_url(ARTIST_AVATAR_THUMBNAIL_SMALL,$artist_avatar);?>" /></a>
		<div class="right">
			<h3><a class="artist_dname right" href="/artist/<?=$session_user?>"><?=$session_user?></a></h3>
			<div class="clear"></div>
			<a href="#" class="bookmarks right" onclick="bookmarks();return false;"></a>
		</div>
	</div>
<?}
else
{?>
	<!-- NOT LOGGED IN Menu -->
	<div id="non_member" class="right">			
		<!--a href="#" class="login" onclick="alert('[ALPHA] Creya is currently in alpha and is down for maintenance. We have a few upgrades coming up. Please check back later. Thanks for the support!');return false;"></a-->
		<a href="#" class="login" onclick="login();return false;"></a>
		<a href="#" class="invite" onclick="requestInvite();return false;"></a>
	</div>
<?}?>

<div class="clear"></div>

