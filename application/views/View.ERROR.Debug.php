<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	
	<? $this->load->view('Meta.HEADER'); ?>
	
	<title>CREYA | 404</title>
		
</head>

<body>
	
	<? $this->load->view('Embed.HEADER'); ?>
	
	<div id="bg-wrapper-black"></div>
	<div id="wrapper2">
	
	
		<div id="main_black">
			<!-- Control Bar -->
			<div id="controls_black">
				<h2 class="flashheader left">Oops!</h2>
			</div>
			<div class="content_divider"></div>
			<!-- END Control Bar -->
			
			<div id="content_black" class="messagepage pageminheight">
				
				
				<? if(isset($error))
				{?>
					<span><?=$error?></span>
				<?}
				else
				{?>
					<span>404 not found</span>
				<?}?>
				
				<br><br>
				<span>STACKTRACE:</span>
				<br><br>
				<span><?=$stacktrace?></span>
					
						
			</div>
			
		</div>
		
	</div>
	<!-- END Wrapper 2 -->
	
	<? $this->load->view('Embed.FOOTER'); ?>		
	
</body>
</html>
