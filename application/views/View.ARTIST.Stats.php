<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head> 
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
	
	<script type="text/javascript" src="/resources/js/jquery-1.3.1.min.js"></script> 
	<script type="text/javascript" src="/resources/js/excanvas.pack.js"></script> 
	<script type="text/javascript" src="/resources/js/jquery.flot.js"></script> 
	<script type="text/javascript" src="/resources/js/fgCharting.js"></script> 
	<script type="text/javascript" src="/resources/js/shadowbox-jquery.js"></script> 
	<script type="text/javascript" src="/resources/js/shadowbox-2.0.js"></script> 
	<script type="text/javascript" src="/resources/js/stats.js"></script> 
	<script type="text/javascript" src="/resources/js/js.js"></script> 
	
	<link rel="stylesheet" type="text/css" href="/resources/css/sifr.css"/>
	<script type="text/javascript" src="/resources/js/sifr.js"></script>
	<script type="text/javascript" src="/resources/js/sifr-config.js"></script>
 
	<link rel="stylesheet" type="text/css" href="/resources/js/skin/classic/skin.css"/> 
	<script type="text/javascript" src="/resources/js/skin/classic/skin.js"></script> 
	
	<link href="/resources/css/reset.css" rel="stylesheet" type="text/css"/> 
	<link href="/resources/css/styles.css" rel="stylesheet" type="text/css"/> 
	
	<link href="/resources/css/artist.css" rel="stylesheet" type="text/css"/>
	
	<link REL="SHORTCUT ICON" HREF="favicon.ico" /> 
 
	<title>Stats</title>
	
	<script type="text/javascript">
		$(function() 
		{
			
			var MetricRatingDataPointArray = new Array();			
			
			<? foreach($artist_metric_rating_data_point_array as $metric_rating_data_points): ?> 
			
				var DataPoints = new Array();
				
				<? foreach($metric_rating_data_points as $metric_rating_data_point): ?> 
				
					DPoint = new Array();
					DPoint.push(<?=$metric_rating_data_point['a_week#']?>);
					DPoint.push(<?=$metric_rating_data_point['AMR']?>);
					DataPoints.push(DPoint);
				
				<? endforeach ?>
				
				MetricRatingDataPointArray.push(DataPoints);
				
			<? endforeach ?>
			
			SetupGraphs(MetricRatingDataPointArray);
		
		});
	</script> 
	
</head> 
 
<body> 
	
	<? $this->load->view('Embed.HEADER'); ?>
	
	<div id="bg-wrapper"></div>
	<div id="wrapper2"> 
			<div id="main"> 
				
				<!-- Control Bar --> 
				<div id="controls"> 
					<h2 class="header left"><?=$artist_dname?>'s Stats</h2>
					<div id="userHeader"> 
						<? $data['control_active'] = VIEW_ARTIST_CONTROL_ACTIVE_STATS ?>
						<? $this->load->view('Embed.CONTROL.Artist',$data); ?>
					</div>
				</div> 
				<!-- END Control Bar --> 
				
				<!-- Content Column --> 
				<div id="content"> 
				
						<!-- ALWAYS KEEP THIS CHECKED TO ENABLE TOOLTIPS --> 
						<input id="enableTooltip" type="checkbox" class="hidden" checked="checked" /> 
						
						<? $this->load->view('Embed.CONTENT.Artist.Stats',$data); ?>
												
				</div> 
				<!-- END Content Column --> 
				
				<!-- Dashboard Column --> 
				<div id="dashboard"> 
				
				  					
					<? $this->load->view('Embed.DASHBOX.Artist.Stats.Ratings_Summary',$data); ?>
										
					<? $this->load->view('Embed.DASHBOX.Artist.Stats.Most_Bookmarked',$data); ?>
					
					<? $this->load->view('Embed.DASHBOX.Artist.Stats.Most_Commented',$data); ?>
					
				</div> 
				<!-- END Dashboard Column --> 
						
			</div> 
			<!-- END Main --> 
			
	</div> 
	<!-- END Wrapper 2 --> 
	
	<? $this->load->view('Embed.FOOTER'); ?>	 
 
</body> 
</html> 
 