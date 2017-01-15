<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	
	<? $this->load->view('Meta.HEADER'); ?>
			
	<title>CREYA  | a platform for photography enthusiasts</title>

</head>

<body>
	
	<? $this->load->view('Embed.HEADER'); ?>
	
	<div id="bg-wrapper-black"></div>
	<div id="wrapper2">
		
		<div id="main_black" class="gateway">
			
			<div class="introduce">
				
				<? if($session_user==null)
				{?>
					<a class="button2_request_invite" href="#" onclick="requestInvite();return false;"><img src="/resources/images/button.request_invite.png" alt="request an invite" /></a>
				<?}?>
				
				<a class="link_tell_me_more" href="/meta/about"></a>
				
			</div>
						
		</div>
		
	</div>
	<!-- END Wrapper 2 -->
	
	<? $this->load->view('Embed.FOOTER'); ?>		
	
</body>
</html>