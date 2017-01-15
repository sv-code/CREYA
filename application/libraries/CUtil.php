<?php
/**
 * CUtil Class
 * 
 * Purpose
 * 		Utilities for Lightbox
 * Created 
 * 		Dec 16, 2008
 * 
 * @package		Lightbox
 * @subpackage	Libraries
 * @category	Utils
 * @author		venksster
 * @link		TBD
 */

define ('SECOND',1);
define ('MICROSECOND',0);
define('SINGULAR',0);
define('PLURAL',1);

class CUtil
{
	/**
  	* @access public
  	*/  
	public function CUtil()
	{
		$this->log = clogger_return_instance('util');
	}
	
	/**
  	* @access 	public
  	* @param	string	$format	(optional)
  	*/  
	public function GetCurrentDateStamp($format="Y-m-d H:i:s")
	{
		$this->log->fine('Returning current time');
		return date($format);
	}
	
	/**
	* Returns intersection of two arrays
	*
	* @access public
	* @param array $data_array1 		
	* @param array $data_array2	
	* @return array 				An array of groups 
	* @todo 					THIS IS HIGHLY INEFFICIENT. SHOULD CHANGE THIS
	* @todo					Should we pass big arrays by VALUE??
	*/
	public function ReturnArrayIntersection($data_array1,$data_array2,$array_key)
	{
		$data_array = array();
		
		for($i=0; $i < count($data_array1) ;++$i)
		{
			for($j=0; $j < count($data_array2); ++$j)
			{
				if($data_array1[$i][$array_key]===$data_array2[$j][$array_key])
				{
					array_push($data_array,$data_array1[$i]);
				}
			}
		}
		
		return $data_array;
	}
	
	/**
	* Returns merge of two arrays without repetition
	*
	* @access public
	* @param array $data_array1 		
	* @param array $data_array2	
	* @return array 				An array of merged data
	*/
	public function ReturnArrayMerge($data_array1, $data_array2)
	{
		for($i=0; $i < count($data_array1) ;++$i)
		{
			if(!(in_array($data_array1[$i],$data_array2)))
			{
				array_push($data_array2,$data_array1[$i]);
			}
		}
		
		return $data_array2;
	}
	
	/**
	* Return current time in micro seconds
	*
	* @access public
	* @return time in micro comments
	*/	
	public function ReturnMicroTime()
	{
		$microTimeString = microtime();
		$microTimeArray = explode(" ",$microTimeString);
		if( array_key_exists(SECOND,$microTimeArray) )
		{
			$this->log->debug(' Returning microsecond time  '.$microTimeArray[SECOND]);
			return $microTimeArray[SECOND];
		}
		return '';
	}
	
	/**
	* Random number generator
	*
	* @access public
	* @return random number between 1 and 100 million
	*/	
	public function GetRandomNumber()
	{
		$random = rand(1,100000000);
		return $random;		
	}
	
	/**
	* Convert a datestamp to a readable date string
	*
	* @access public
	* @param $comment_timestamp  	 
	* @return A relative date string
	*/	
	public function ReturnRelativeDateStamp($timestamp,&$date_config)
	{
		$timestamp_unixtime = strtotime($timestamp);
		$current_unixtime = time();
		
		$diff_unixtime =  $current_unixtime - $timestamp_unixtime;
		
		/*$months_difference = (float)($diff_unixtime/MONTH_DURATION_SECONDS);
		$weeks_difference = (float)($diff_unixtime/WEEK_DURATION_SECONDS);
		$days_difference = (float) ($diff_unixtime/DAY_DURATION_SECONDS);
		$hours_difference = (float)($diff_unixtime/HOUR_DURATION_SECONDS);
		*/
		
		foreach($date_config as $time_seconds=>$relative_date_string)
		{
			$this->log->debug(' Relative_date_config key:: '.$time_seconds);
			$date_entity = (float)($diff_unixtime/$time_seconds);
			
			$date_entity_floor = floor($date_entity);
			if( $date_entity_floor>1 )
			{
				$this->log->debug('Relative_date_string:'.$relative_date_string[PLURAL]);
				return $date_entity_floor.' '.$relative_date_string[PLURAL];
			}
			else if($date_entity_floor==1 )
			{
				$this->log->debug('Relative_date_string:'.$relative_date_string[SINGULAR]);
				return $date_entity_floor.' '.$relative_date_string[SINGULAR];
			}
		}
		
		$this->log->warning('Time difference is too small to produce a RelativeDateStamp');
		return 'a few moments ago';
	}

	public function DeletePhotoImageStack($pid)
	{
		 $PhotoTypes = array(
			PHOTO_STANDARD 		=>		 	PHOTO_STANDARD_PATH,
			PHOTO_SNAPSHOT	=>	PHOTO_SNAPSHOT_PATH,
			PHOTO_SNAPSHOT_TILE	=>	PHOTO_SNAPSHOT_TILE_PATH,
			PHOTO_CAPTURE	=>	PHOTO_CAPTURE_PATH,
			PHOTO_PREVIEW	=>	PHOTO_PREVIEW_PATH,
			PHOTO_THUMBNAIL	=>	PHOTO_THUMBNAIL_PATH,
			PHOTO_PANORAMA	=>	PHOTO_PANORAMA_PATH,
			'PHOTO_ORIGINAL'	=>	PHOTO_ORIGINAL_PATH
			);
			
			$this->log->info('Invoking DeletePhotoImageStack for: '.$pid);			
			return $this->DeleteImageStack($pid,$PhotoTypes);		
	}

	public function DeleteCommunityImageStack($pid)
	{
		 $CommunityTypes = array(
			PHOTO_STANDARD 		=>		 	PHOTO_STANDARD_PATH,
			);
			$this->log->info('Invoking DeleteCommunityImageStack for: '.$pid);				
			return $this->DeleteImageStack($pid,$CommunityTypes);		
	}	
	
	
	/**
	* Given a pid ,delete the image stack for it
	*
	* @access public
	* @param $pid
	* @param $ImageTypes	 
	* @return boolean
	*/	
	public function DeleteImageStack($pid,$ImageTypes)
	{
		$result = true;
		foreach( $ImageTypes as $ImageType=>$ImagePath )
		{
			//$this->log->debug($ImageType.','.$ImagePath);
			/* This is a special case : for the ORIGINAL photo */
			if( $ImageType == 'PHOTO_ORIGINAL' )
			{
				$ImageName = $ImagePath.$pid.'.jpg';
			}
			else
			{
				$ImageName = $ImagePath.$ImageType.'.'.$pid.'.jpg';				
			}
			if( is_file($ImageName) == true )
			{
				if( unlink($ImageName) == false )
				{
					$this->log->debug('Unable to delete image: '.$ImageName);					
					$result = false;
				}
				else
				{
					$this->log->debug('Deleting image: '.$ImageName);	
				}
			}
		}
		
		return $result;
	}
	
	public function ObjectArrayUnique(&$entity_array,$comparator_key)
	{
		$this->log->debug('[ArrayUnique] Original count:'.count($entity_array));	
		
		$foundmap = array();
		
		foreach($entity_array as $entity_key=>$entity_value)
		{
			$comparator_value =  $entity_array[$entity_key][$comparator_key];
			
			if(isset($foundmap[$comparator_value]) && $foundmap[$comparator_value]==true)
			{
				unset($entity_array[$entity_key]);
			}
			else
			{
				$foundmap[$comparator_value] = true;	
			}
			
		}
		
		$this->log->debug('[ArrayUnique] Final count:'.count($entity_array));		
	}
	
	public function ArraySearch($value,$array)
	{
		foreach($array as $array_value)
		{
			if($array_value==$value)
			{
				return true;
			}
		}
		
		return false;
	}
	
	public function ArrayShuffle($array)
	{
  		// shuffle using Fisher-Yates
  		$i = count($array);
		$k = $i-1;
 
  		while(--$i)
		{
  			$j = mt_rand(0,$k);
    			if($i != $j)
			{
      				// swap items
      				$tmp = $array[$j];
      				$array[$j] = $array[$i];
      				$array[$i] = $tmp;
    			}
  		}
  		
		return $array;
	}
	
	/**
  	* @access public
  	*/  
  	public $log;
	
}

/* End of file CUtil.php */
