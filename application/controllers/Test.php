<?php
/**
 * Photo Class
 * 
 * Purpose
 * 		'The Photograph' Controller
 * Created 
 * 		Nov 23, 2008
 * 
 * @package		Lightbox
 * @subpackage	Controllers
 * @category	none
 * @author		venksster
 * @link		TBD
 */
require CONTROLLER_BASE_PATH.'Abstract.LightboxController'.EXT;

class Test extends AbstractLightboxController
{
	public function Test()
	{
		parent::AbstractLightboxController('util');
		
		$this->load->library('cgraphics');
		$this->load->helper('cgraphics');
		$this->load->model('ShoeboxEntityModel');
		set_time_limit(3000000);
	}
	
	public function AddArtistRatings()
	{
		//$query = "insert into `lightbox`.`artist_stat_metric` values('testuser5',2,2,3.4)";
		
		$num_weeks=52;
		$mid_array = array('1','2','3','4');
		
		for($i=1;$i<=$num_weeks;++$i)
		{
			$prev_amr = 1;
			
			foreach($mid_array as $key=>$mid)
			{
				$direction = rand(0,1);
				if($direction==0) $direction=-1;
				$amr_deviance = lcg_value() * $direction;
				
				$AMR = $prev_amr + $amr_deviance; 
				if($AMR<0) $AMR=0;
				if($AMR>5) $AMR=5;				
				
				$data = array
				(
					'artist_dname'	=> 'testuser5',
					'MID'			=> $mid,
					'a_week#'		=> $i,
					'AMR'			=> $AMR
				);
				
				$this->db->insert('artist_stat_metric',$data);
			}
		}
		
		
	}
	
		
	public function CropPhoto()
	{
		
				
		//cgraphics_create_image($target_image_type=PHOTO_SNAPSHOT,$source_image_path=PHOTO_ORIGINAL_PATH,$source_image_name='prof.jpg',$target_image_path=PHOTO_SNAPSHOT_PATH);
		
		
		set_time_limit(300000);
		for($i=75;$i<=75;++$i)
		//for($i=62;$i<=62;++$i)
		{
			$this->log->info('--------------------------------------------------------------PROCESSING IMAGE:'.$i.'-------------------------------------------------------');
			cgraphics_create_photo_image_stack($i.'.jpg');	
		}
		
	}
	
	public function CropArtist()
	{
		
			
		set_time_limit(30000);
		for($i=0;$i<=5;++$i)
		//for($i=62;$i<=62;++$i)
		{
			$avatar = 'testuser'.$i;
			$this->log->info('--------------------------------------------------------------PROCESSING AVATAR:'.$avatar.'-------------------------------------------------------');
			cgraphics_create_artist_avatar_image_stack($avatar.'.jpg');	
		}
		
	}
	
	public function CropGroup()
	{
		
			
		set_time_limit(30000);
		for($i=0;$i<=294;++$i)
		//for($i=62;$i<=62;++$i)
		{
			$this->log->info('--------------------------------------------------------------PROCESSING IMAGE:'.$i.'-------------------------------------------------------');
			cgraphics_create_group_preview_image_stack($i.'.jpg');	
		}
		
	}
	
	public function CDImages()
	{
		cgraphics_create_discussion_image_stack();
	}
	
	public function SetEXIF()
	{
		$this->load->model('PhotoCollectionModel');
		$this->load->model('PhotoEntityModel');
		
		$photos = $this->PhotoCollectionModel->GetEntities();
		
		foreach($photos as $photo)
		{
			$pid = $photo['PHOTO_ID'];
			$image_id = $pid-920+1;
			
			$file = PHOTO_STANDARD_PATH.PHOTO_STANDARD.'.'.$image_id.'.jpg';
			
			try
			{
				$exif = cgraphics_get_exif($file);
			}
			
			catch(Exception $e)
			{
				
			}
			
			$this->log->info(print_r($exif,true));
		//	$this->log->info('photo_exif_aperture'.$exif['photo_exif_aperture']);
			
			if(array_key_exists('photo_exif_shutter',$exif) && strlen($exif['photo_exif_shutter'])>5)
			{
				$exif['photo_exif_shutter']=-1;
			}
			$exif['photo_exif_sw']=0;
			
			$this->PhotoEntityModel->EditExifData($pid,$exif);
		}
	}
	
	public function ResetEXIF()
	{
		$this->load->model('PhotoCollectionModel');
		$this->load->model('PhotoEntityModel');
		
		$photos = $this->PhotoCollectionModel->GetEntities();
		
		foreach($photos as $photo)
		{
			$pid = $photo['PHOTO_ID'];
			
			$exif = array
			(
				'photo_exif_focal' =>0,
				'photo_exif_aperture' =>0,
				'photo_exif_shutter' =>0,
				'photo_exif_iso' =>0		
			);
			
			print_r($exif);
			
			$this->PhotoEntityModel->EditExifData($pid,$exif);
		}
	}
	
	public function doNothing()
	{
		echo $_POST['value'];
	}
	
	public function filterphotos()
	{
		$this->load->model('PhotoCollectionModel');
		$entities = $this->PhotoCollectionModel->GetEntities(
		
				$order_by=ORDER_DATE,
				$filter_artist=FILTER_NONE,
				$filter_group_gid=FILTER_NONE,
				$filter_photo_property_array=array
									(
									//	'photo_type'	=>'Fashion',
										'photo_exif_focal' => array
													(
														'min'	=> 51,
														'max'	=> 70
													)
									),
				$filter_keyword_type=FILTER_NONE,
				$filter_keywords=FILTER_NONE,
				$num_photos=RESULTS_ALL,
				$offset=OFFSET_NONE);
		
		
		
		
		echo count($entities);
	}
	
	public function sp1()
	{
		$result = $this->db->query('call sp1');
		echo $result->result_array();
	}
	
	public function upload()
	{
		$this->display('View.Upload3');
	}
	
	public function newPhoto()
	{
		$this->load->model('PhotoEntityModel');
		$this->PhotoEntityModel->Add($artist_dname="abc",$photo_data=array('PHOTO_ID'=>'1244945518932709','photo_type'=>2));
	}
	
	public function newShoebox()
	{
		
		$shoebox_data = array
		(
			'shoebox_name'	=>	'landscape',
			//'shoebox_photo'	=>	$_POST['shoebox_photo'],
			'shoebox_tags'	=>	array('landscape')
		);
				
		$this->ShoeboxEntityModel->Add('prakash',$shoebox_data);
	}
	
	public function AddShoebox()
	{
		$this->load->model('ShoeboxEntityModel');
		$shoebox_filters = array
		(
			'photo_exif_aperture' => 5.6
		);
		
		$shoebox_data = array
		(
			'shoebox_name' => 'SomeNameeee',
			'shoebox_filters' => $shoebox_filters
		);
		
		$this->ShoeboxEntityModel->Add('testuser0',$shoebox_data);
	}
	
	public function GetShoebox()
	{
		$this->load->model('PhotoCollectionModel');
		$photos = $this->PhotoCollectionModel->GetMostRecentByShoebox(2,10,0);
	}
	
}

/* End of file Photo.php */


	