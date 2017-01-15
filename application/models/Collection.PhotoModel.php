<?php
/**
 * PhotoCollectionModel Class
 *
 * Interfaces
 *
 * 		PhotoCollectionModel::GetEntityCount			($filter_type=FILTER_NONE,$filter_keywords=FILTER_NONE,$filter_photo_property_array=FILTER_NONE,$filter_group_gid=FILTER_NONE)
 * 		PhotoCollectionModel::GetEntities				($order_by=ORDER_RELEVANCE,$filter_type=FILTER_NONE,$filter_keywords=FILTER_NONE,$filter_photo_property_array=FILTER_NONE,$filter_group_gid=FILTER_NONE,$num_photos=RESULTS_ALL,$offset=OFFSET_NONE)
 * 
 * 		ILightboxExplore::GetMostRecent				($num_photos,$offset,$filter_name_tag_keywords=FILTER_NONE,$filter_photo_property_array=FILTER_NONE)
 * 		ILightboxExplore::GetMostRelevant				($num_photos,$offset,$filter_name_tag_keywords,$filter_photo_property_array=FILTER_NONE)
 * 
 * 		PhotoCollectionModel::GetTopRated				($num_photos,$offset,$filter_name_tag_keywords=FILTER_NONE,$filter_photo_property_array=FILTER_NONE,$filter_group=FILTER_NONE)
 * 
 * Created 
 * 		Feb 01, 2009
 *
 * @package 	Lightbox
 * @subpackage 	Models
 * @category 	Collection
 * @author 	venksster 
 */

require MODEL_BASE_PATH.'Abstract.Collection.LightboxModel'.EXT;
require MODEL_INTERFACE_PATH.'ILightboxBookmarkCollection'.EXT;

class PhotoCollectionModel extends AbstractLightboxCollectionModel implements ILightboxBookmarkCollection
{
	/**
	* Constructor
	*
	* @access public
	*/
	public function PhotoCollectionModel($module='photoexplore')
	{
		parent::AbstractLightboxCollectionModel($module,$table_entity='photo',$column_entity_name='photo_name',$column_entity_key='PHOTO_ID',$column_entity_date='photo_date');
	}
	
	/**
	* Returns photos
	* ordered in descending order of date posted
	*
	* @type	GET
	* 
	* @access 	public
	* @param 	int 		$num_photos 					The maximum numbers of photo results to retrieve
	* @param 	int 		$offset 						The offset to start retrieving photo results from (used for pagination)
	* @param 	array 		$filter_name_tag_keywords (optional)	Search keywords delimited by LIGHTBOX_SEARCH_KEYWORD_DELIMITER
	* @return 	array 									array of photos
	*/
	public function GetMostRecent($num_photos,$offset,$filter_name_tag_keywords=FILTER_NONE,$filter_photo_property_array=FILTER_NONE)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning '.$num_photos.' most recent photos from offset:'.$offset);
		
		if($filter_name_tag_keywords!=FILTER_NONE)
		{
			$result_array = $this->GetEntitiesByKeywords($num_photos,$offset,$filter_name_tag_keywords,$order_by=ORDER_RELEVANCE,$order_callback_function='PhotoCollectionModel::RankPhotosByRelevance',$filter_artist=FILTER_NONE,$filter_group=FILTER_NONE,$filter_photo_property_array);
		}
		else
		{
			$result_array = $this->GetEntities($order_by=ORDER_DATE,$filter_artist=FILTER_NONE,$filter_group=FILTER_NONE,$filter_photo_property_array,$filter_keyword_type=FILTER_NONE,$filter_name_tag_keywords=FILTER_NONE,$num_photos,$offset);
		}
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $result_array;
	}
	
	/**
	* Returns photos
	* from artist:$artist_dname
	* ordered in descending order of date posted
	*
	* @type	GET
	* 
	* @access 	public
	* @param	string		$artist_dname
	* @param 	int 		$num_photos 					The maximum numbers of photo results to retrieve
	* @param 	int 		$offset 						The offset to start retrieving photo results from (used for pagination)
	* @param 	array 		$filter_name_tag_keywords (optional)	Search keywords delimited by LIGHTBOX_SEARCH_KEYWORD_DELIMITER
	* @return 	array 									array of photos
	*/
	public function GetMostRecentByArtist($artist_dname,$num_photos,$offset,$filter_name_tag_keywords=FILTER_NONE,$filter_photo_property_array=FILTER_NONE)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning '.$num_photos.' most recent photos from offset:'.$offset.' for artist:'.$artist_dname);
		
		if($filter_name_tag_keywords!=FILTER_NONE)
		{
			$result_array = $this->GetEntitiesByKeywords($num_photos,$offset,$filter_name_tag_keywords,$order_by=ORDER_RELEVANCE,$order_callback_function='PhotoCollectionModel::RankPhotosByRelevance',$filter_artist=$artist_dname,$filter_group=FILTER_NONE,$filter_photo_property_array);
		}
		else
		{
			$result_array = $this->GetEntities($order_by=ORDER_DATE,$filter_artist=$artist_dname,$filter_group=FILTER_NONE,$filter_photo_property_array,$filter_keyword_type=FILTER_NONE,$filter_name_tag_keywords=FILTER_NONE,$num_photos,$offset);
		}
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $result_array;
	}
	
	/**
	* Returns photos
	* from group:$gid
	* ordered in descending order of date posted
	*
	* @type	GET
	* 
	* @access 	public
	* @param	int		$gid							GROUP_ID
	* @param 	int 		$num_photos 					The maximum numbers of photo results to retrieve
	* @param 	int 		$offset 						The offset to start retrieving photo results from (used for pagination)
	* @param 	array 		$filter_name_tag_keywords (optional)	Search keywords delimited by LIGHTBOX_SEARCH_KEYWORD_DELIMITER
	* @return 	array 									array of photos
	*/
	public function GetMostRecentByGroup($gid,$num_photos,$offset,$filter_name_tag_keywords=FILTER_NONE,$filter_photo_property_array=FILTER_NONE)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning '.$num_photos.' most recent photos from offset:'.$offset.' for group:'.$gid);
		
		if($filter_name_tag_keywords!=FILTER_NONE)
		{
			$result_array = $this->GetEntitiesByKeywords($num_photos,$offset,$filter_name_tag_keywords,$order_by=ORDER_RELEVANCE,$order_callback_function='PhotoCollectionModel::RankPhotosByRelevance',$filter_artist=FILTER_NONE,$filter_group=$gid,$filter_photo_property_array);
		}
		else
		{
			$result_array = $this->GetEntities($order_by=ORDER_DATE,$filter_artist=FILTER_NONE,$filter_group=$gid,$filter_photo_property_array,$filter_keyword_type=FILTER_NONE,$filter_name_tag_keywords=FILTER_NONE,$num_photos,$offset);
		}
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $result_array;
	}
	
	/**
	* Returns photos
	* from shoebox:$shoebox_id
	* ordered in descending order of date posted
	*
	* @type	GET
	* 
	* @access 	public
	* @param	int		$shoebox_id						
	* @param 	int 		$num_photos 					The maximum numbers of photo results to retrieve
	* @param 	int 		$offset 						The offset to start retrieving photo results from (used for pagination)
	* @return 	array 									array of photos
	*/
	public function GetMostRecentByShoebox($shoebox_id,$num_photos,$offset)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning '.$num_photos.' most recent photos from offset:'.$offset.' for shoebox:'.$shoebox_id);
		
		// Retrieving the shoebox creator
		$this->db->where('SHOEBOX_ID',$shoebox_id);
		$artist_dname = $this->db->get_attribute('artist_shoebox','artist_dname');
		
		// Retrieving the shoebox filters
		$this->db->select('photo_tag_list,photo_type,photo_exif_focal,photo_exif_aperture,photo_exif_shutter,photo_exif_iso');
		$this->db->where('SHOEBOX_ID',$shoebox_id);
		$shoebox_filters = $this->db->get('artist_shoebox')->row_array();
		
		// FILTERS - keywords
		$filter_name_tag_keywords = FILTER_NONE;
		$filter_keyword_type = FILTER_NONE;
		if($shoebox_filters['photo_tag_list']!='TAG_NONE')
		{
			$filter_name_tag_keywords = $shoebox_filters['photo_tag_list'];
			$filter_keyword_type = FILTER_PHOTO_TAGS;
			unset($shoebox_filters['photo_tag_list']);
		}
		$this->log->debug("[MODEL] shoebox_keyword_filters:".$filter_name_tag_keywords);
		
		// FILTER - photo properties
		$filter_photo_property_array = FILTER_NONE;
		foreach($shoebox_filters as $filter=>$value)
		{
			if($value==0 || $value=='0')
			{
				unset($shoebox_filters[$filter]);
			}
		}
		
		if(is_array($shoebox_filters) && sizeof($shoebox_filters)>0)
		{
			$filter_photo_property_array = array();
			
			foreach($shoebox_filters as $filter=>$value)
			{
				$filter_photo_property_array[$filter] = $value;	
			}	
		}		
		$this->log->debug("[MODEL] shoebox_photo_property_filters:".print_r($filter_photo_property_array,true));
				
		$result_array = $this->GetEntities($order_by=ORDER_DATE,$filter_artist=$artist_dname,$filter_group=FILTER_NONE,$filter_photo_property_array,$filter_keyword_type,$filter_name_tag_keywords,$num_photos,$offset);
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $result_array;
	}
	
	/**
	* Returns photos
	* ordered by most relevant
	*
	* @type	GET
	* 
	* @access 	public
	* @param 	int 		$num_photos 		The maximum numbers of photo results to retrieve
	* @param 	int 		$offset 			The offset to start retrieving photo results from (used for pagination)
	* @param 	array 		$filters (optional)		Array of seach keywords
	* @return 	array 						array of photos 
	*/
	public function GetMostRelevant($num_photos,$offset,$filter_name_tag_keywords,$filter_photo_property_array=FILTER_NONE)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning '.$num_photos.' most relevant photos from offset:'.$offset);
		
		$result_array = $this->GetEntitiesByKeywords($num_photos,$offset,$filter_name_tag_keywords,$order_by=ORDER_RELEVANCE,$order_callback_function='PhotoCollectionModel::RankPhotosByRelevance');
				
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $result_array;
	}
	
	/**
	* Returns photos
	* ordered by most relevant
	*
	* @type	GET
	* 
	* @access 	public
	* @param 	int 		$num_photos 		The maximum numbers of photo results to retrieve
	* @param 	int 		$offset 			The offset to start retrieving photo results from (used for pagination)
	* @param 	array 		$filters (optional)		Array of seach keywords
	* @return 	array 						array of photos 
	*/
	public function GetMostRelevantByArtist($artist_dname,$num_photos,$offset,$filter_name_tag_keywords,$filter_photo_property_array=FILTER_NONE)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning '.$num_photos.' most relevant photos from offset:'.$offset.' for artist:'.$artist_dname);
		
		$result_array = $this->GetEntitiesByKeywords($num_photos,$offset,$filter_name_tag_keywords,$order_by=ORDER_RELEVANCE,$order_callback_function='PhotoCollectionModel::RankPhotosByRelevance',$filter_artist=$artist_dname);
				
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $result_array;
	}
	
	/**
	* Returns photos
	* ordered by most relevant
	*
	* @type	GET
	* 
	* @access 	public
	* @param 	int 		$num_photos 		The maximum numbers of photo results to retrieve
	* @param 	int 		$offset 			The offset to start retrieving photo results from (used for pagination)
	* @param 	array 		$filters (optional)		Array of seach keywords
	* @return 	array 						array of photos 
	*/
	public function GetMostRelevantByGroup($gid,$artist_dname,$num_photos,$offset,$filter_name_tag_keywords,$filter_photo_property_array=FILTER_NONE)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning '.$num_photos.' most relevant photos from offset:'.$offset.' for group:'.$gid);
		
		$result_array = $this->GetEntitiesByKeywords($num_photos,$offset,$filter_name_tag_keywords,$order_by=ORDER_RELEVANCE,$order_callback_function='PhotoCollectionModel::RankPhotosByRelevance',$filter_artist=FILTER_NONE,$filter_group=$gid);
			
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $result_array;
	}

	protected function GetEntitiesByKeywords($num_photos,$offset,$filter_name_tag_keywords,$order_by,$order_callback_function,$filter_artist=FILTER_NONE,$filter_group=FILTER_NONE,$filter_photo_property_array=FILTER_NONE)
	{
		$result_photo_tag 	= $this->GetEntities($order_by,$filter_artist,$filter_group,$filter_photo_property_array,$filter_keyword_type=FILTER_PHOTO_TAGS,$filter_name_tag_keywords,$num_photos,$offset);
		$result_photo_name 	= $this->GetEntities($order_by,$filter_artist,$filter_group,$filter_photo_property_array,$filter_keyword_type=FILTER_PHOTO_NAME,$filter_name_tag_keywords,$num_photos,$offset);
		
		$result_array = array_merge($result_photo_tag,$result_photo_name);
		
		// SORT AND SLICE
		usort($result_array,'PhotoCollectionModel::RankPhotosByRelevance');
		array_slice($result_array,$offset,$num_photos);
		
		// REMOVE DUPLICATES
		$this->cutil->ObjectArrayUnique($result_array,'PHOTO_ID');
		
		$this->log->debug('[MODEL] Returning '.count($result_array).' photos:'.print_r($result_array,true));
		return $result_array;
	}
	
	/**
	* @type	GET
	* 
	* @access public

	* @param string 		$orderBy 				The field name to order the search results by (eg: 'p_rating' to get top rated)
	* @param int 		$filter_type 				FILTER_PHOTO_NAME/FILTER_PHOTO_TAGS/FILTER_NONE
	* @param string 		$filter_keywords 			Search keywords delimited by LIGHTBOX_SEARCH_KEYWORD_DELIMITER
	* @param array 		$filter_photo_property_array 	Photo property filters (eg: 'p_exif_iso')
	* @param int 		$filter_group_gid 			GROUP_ID that photos belong to
	* @param int 		$num_photos 			The maximum numbers of photo results to retrieve
	* @param int 		$offset 				The offset to start retrieving photo results from (used for pagination)
	* @return array 							array of photos  
	* @todo								NAME/TAG FILTERS SHOULD HAVE AND/OR OPTION! (shoebox needs AND)
	* @todo								MAKE $filter_keyword an array
	*/
	public function GetEntities($order_by=ORDER_DATE,$filter_artist=FILTER_NONE,$filter_group_gid=FILTER_NONE,$filter_photo_property_array=FILTER_NONE,$filter_keyword_type=FILTER_NONE,$filter_keywords=FILTER_NONE,$num_photos=RESULTS_ALL,$offset=OFFSET_NONE)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning photos');
		
		$this->log->info('[MODEL] GET ENTITIES');
		$this->log->fine('[MODEL] ORDER_BY:'.				$order_by);
		$this->log->fine('[MODEL] FILTER_ARTIST:'.			$filter_artist);
		$this->log->fine('[MODEL] FILTER_GROUP_GID:'.		$filter_group_gid);
		$this->log->fine('[MODEL] FILTER_KEYWORD_TYPE:'.		$filter_keyword_type);
		$this->log->fine('[MODEL] FILTER_KEYWORDS:'.			$filter_keywords);
		$this->log->fine('[MODEL] FILTER_PHOTO_PROPERTY:'.	print_r($filter_photo_property_array,true));
		$this->log->fine('[MODEL] NUM_PHOTOS:'.			$num_photos);
		$this->log->fine('[MODEL] OFFSET:'.				$offset);	
		
		$this->PrepareFilters($filter_artist,$filter_group_gid,$filter_photo_property_array,$filter_keyword_type,$filter_keywords);
		
		// ORDER 
		switch($order_by)
		{
			case ORDER_DATE:
				$this->db->order_by('photo_date','desc');
				$photo_array = $this->db->get('photo',$num_photos,$offset)->result_array();
				break;

			case ORDER_RELEVANCE:
				switch($filter_keyword_type)
				{
					case FILTER_PHOTO_NAME:
						$photo_array = $this->db->get('photo',RESULTS_ALL,OFFSET_NONE)->result_array(); 
						$photo_array = $this->cranker->RankEntitiesByKeywordRelevance($photo_array,'PHOTO_ID','photo_name',$filter_keywords,$num_slice_entities=$num_photos,$offset);
						break;
						
					case FILTER_PHOTO_TAGS:
						$this->db->order_by('keyword_match_count', 'desc');
						$photo_array = $this->db->get('photo',$num_photos,$offset)->result_array(); 
						break;
				}
				
				break;
		
			DEFAULT:
				$this->db->order_by($order_by,'desc');
				$photo_array = $this->db->get('photo',$num_photos,$offset)->result_array();
		}
		
		//$query = "SELECT `photo`.`PHOTO_ID`, `photo`.`artist_dname`, COUNT(tag_name) as 'keyword_match_count' FROM (`photo`) JOIN `photo_tag` ON `photo_tag`.`PHOTO_ID` = `photo`.`PHOTO_ID` WHERE `tag_name` IN ('a','b') OR `photo_name` LIKE '%a%' OR `photo_name` LIKE '%b%' GROUP BY `PHOTO_ID` ORDER BY `photo_date` desc LIMIT 5";
		//$photo_array = $this->db->query($query)->result_array();
		
		if($filter_keyword_type==FILTER_PHOTO_TAGS_ALL)
		{
			$photo_array = $this->cranker->ReturnByRank($rank=count($photo_keywords),$photo_array,'keyword_match_count');
		}
		
		$this->log->debug('[MODEL] Returning '.count($photo_array).' photos:'.print_r($photo_array,true));
		$this->log->info('[MODEL] Returning '.count($photo_array).' photos');
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $photo_array;
	}
	
	public function GetEntityCount($filter_keywords=FILTER_NONE,$filter_photo_property_array=FILTER_NONE,$filter_artist=FILTER_NONE,$filter_group_gid=FILTER_NONE)
	{
		$this->benchmark->Start(__METHOD__);
		
		$this->log->info('[MODEL] GET ENTITY COUNT');
		$this->log->fine('[MODEL] FILTER_ARTIST:'.			$filter_artist);
		$this->log->fine('[MODEL] FILTER_GROUP_GID:'.		$filter_group_gid);
		$this->log->fine('[MODEL] FILTER_KEYWORDS:'.			$filter_keywords);
		$this->log->fine('[MODEL] FILTER_PHOTO_PROPERTY:'.	print_r($filter_photo_property_array,true));
		
		$photo_count=0;
		if($filter_keywords!=FILTER_NONE)
		{
			$this->PrepareFilters($filter_artist,$filter_group_gid,$filter_photo_property_array,$filter_keyword_type=FILTER_PHOTO_TAGS,$filter_keywords,$group_by=false);
			$photo_count_by_tags = $this->db->count_all_results('photo');
			$this->log->debug('[MODEL] photo_count_by_tags:'.$photo_count_by_tags);
			$photo_count += $photo_count_by_tags;
			
			$this->PrepareFilters($filter_artist,$filter_group_gid,$filter_photo_property_array,$filter_keyword_type=FILTER_PHOTO_NAME,$filter_keywords,$group_by=false);
			$photo_count_by_name = $this->db->count_all_results('photo');
			$this->log->debug('[MODEL] photo_count_by_name:'.$photo_count_by_name);
			$photo_count += $photo_count_by_name;
		}
		else
		{
			$this->PrepareFilters($filter_artist,$filter_group_gid,$filter_photo_property_array);
			$photo_count = $this->db->count_all_results('photo');	
		}
								
		$this->log->info('[MODEL] PHOTO COUNT:'.$photo_count);
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $photo_count;		
	}
	
	protected function PrepareFilters($filter_artist=FILTER_NONE,$filter_group_gid=FILTER_NONE,$filter_photo_property_array=FILTER_NONE,$filter_keyword_type=FILTER_NONE,$filter_keywords=FILTER_NONE,$group_by=true)
	{
		$this->log->info('[MODEL] Preparing filters');
		
		// Validate filters	
		if(($filter_keyword_type!=FILTER_NONE && $filter_keywords==FILTER_NONE) ||($filter_keywords!=FILTER_NONE && $filter_keyword_type==FILTER_NONE))
		{
			throw new Exception('[MODEL] Both OR neither of $filter_keyword_type and $filter_keywords should be set');
		}
		
		// FILTER BY ARTIST
		if($filter_artist!=FILTER_NONE)
		{
			$this->log->fine('[MODEL] Filtering by artist:'.$filter_artist);
			$this->db->where('photo.artist_dname',$filter_artist);
		}
		
		// FILTER BY GROUP
		if($filter_group_gid!=FILTER_NONE)
		{
			$this->log->fine('[MODEL] Filtering by group:'.$filter_group_gid);
			$this->db->join('group_photo', 'group_photo.PHOTO_ID = photo.PHOTO_ID');
			$this->db->where('group_photo.GROUP_ID',$filter_group_gid);
		}
		
		// FILTER BY NAME OR TAG
		$photo_keywords = array();
		
		if($filter_keywords!=FILTER_NONE)
		{
			$filter_keywords = rtrim($filter_keywords,LIGHTBOX_SEARCH_KEYWORD_DELIMITER);
			$photo_keywords = explode(LIGHTBOX_SEARCH_KEYWORD_DELIMITER,$filter_keywords);
			if(count($photo_keywords) > 0)
			{
				$this->log->fine('[MODEL] Filtering by photo_keywords:'.print_r($photo_keywords,true));
				
				switch($filter_keyword_type)
				{
					case FILTER_PHOTO_NAME:
						$this->log->fine('[MODEL] Filtering by NAME');
						$this->db->select('*'); 					
						$this->db->or_like_in('photo_name',$photo_keywords);	
						break;
						
					case FILTER_PHOTO_TAGS:
					case FILTER_PHOTO_TAGS_ALL:					
						$this->log->fine('[MODEL] Filtering by TAGS');
						
						$this->db->select('photo.PHOTO_ID, photo.artist_dname,COUNT(tag_name) as "keyword_match_count"');
						$this->db->join('photo_tag', 'photo_tag.PHOTO_ID = photo.PHOTO_ID');
						
						$this->db->where_in('tag_name',$photo_keywords);	
						if($group_by==true)
						{
							$this->db->group_by('photo.PHOTO_ID');
						}
						
						break; 
																			
				}
			}
		}
		else
		{
			$this->db->select('*'); 
		}
		
		// PHOTO PROPERTIES
		if($filter_photo_property_array!=FILTER_NONE && is_array($filter_photo_property_array))
		{
			$this->log->fine('[MODEL] Filtering by photo properties:'.print_r($filter_photo_property_array,true));
			
			foreach($filter_photo_property_array as $filter_name=>$filter_value)
			{
				$this->log->fine('[MODEL] filtername:'.$filter_name.' filter_value:'.$filter_value);
				
				if(is_array($filter_value))
				{
					$this->log->fine('[MODEL] Range filter:'.$filter_name.'Values:'.print_r($filter_value,true));
					
					$filter_min_value = $filter_value['min'];
					$filter_max_value = $filter_value['max'];
					
					$this->db->where($filter_name.' >',$filter_min_value);
					$this->db->where($filter_name.' <',$filter_max_value);
					
					unset($filter_photo_property_array[$filter_name]);					
				}
			}
			
			$this->db->where($filter_photo_property_array);
		}
		
		$this->log->fine('[MODEL] FILTERS PREPARED');
	}
	
	public function GetEntityCountByArtist($artist_dname,$filter_keywords=FILTER_NONE,$filter_photo_property_array=FILTER_NONE)
	{
		return $this->GetEntityCount($filter_keywords,$filter_photo_property_array,$filter_artist=$artist_dname);
	}

	public function GetEntityCountByGroup($gid,$filter_keywords=FILTER_NONE,$filter_photo_property_array=FILTER_NONE)
	{
		return $this->GetEntityCount($filter_keywords,$filter_photo_property_array,$filter_artist=FILTER_NONE,$filter_group_gid=$gid);
	}
	
	function GetBookmarkCount($artist_dname,$filter_keywords=FILTER_NONE)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Retrieving photo bookmark count for artist:'.$artist_dname);
		
		$this->db->where('artist_dname',$artist_dname);
		$this->db->from('artist_bookmark_photo');
		$bookmark_count = $this->db->count_all_results();
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $bookmark_count;
	}
	
	function GetBookmarks($artist_dname,$filter_keywords=FILTER_NONE,$num_results=RESULTS_ALL,$offset=OFFSET_NONE)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning '.$num_results.' photo bookmarks by artist:'.$artist_dname.' from offset:'.$offset.'::$filter_keywords:'.$filter_keywords);
		
		/*
		$this->db->select('artist_bookmark_photo.PHOTO_ID,bookmark_date');
		$this->db->order_by('bookmark_date','desc');
		$this->db->where('artist_bookmark_photo.artist_dname',$artist_dname);
		
		if($filter_keywords!=FILTER_NONE)
		{
			$this->log->info('[MODEL] Filtering bookmarks by name');
			$this->db->join('photo', 'artist_bookmark_photo.PHOTO_ID = photo.PHOTO_ID');
			$filter_keyword_array = explode(WHITESPACE,$filter_keywords);
			$this->db->or_like_in('photo.photo_name',$filter_keyword_array);
			
			$this->log->fine('[MODEL] Filtering bookmarks by tag');		
			$this->db->join('photo_tag', 'photo_tag.PHOTO_ID = photo.PHOTO_ID','left');
			$this->db->where_in('photo_tag.tag_name',$filter_keyword_array);			
		}
		
		$this->db->distinct();
		$bookmark_db = $this->db->get('artist_bookmark_photo',$num_results,OFFSET_NONE);
		*/
		
		
		//$query = "SELECT DISTINCT `artist_bookmark_photo`.`PHOTO_ID`, `bookmark_date` FROM (`artist_bookmark_photo`) JOIN `photo` ON `artist_bookmark_photo`.`PHOTO_ID` = `photo`.`PHOTO_ID` LEFT JOIN `photo_tag` ON `photo_tag`.`PHOTO_ID` = `photo`.`PHOTO_ID` WHERE `artist_bookmark_photo`.`artist_dname` = 'testuser1' AND (`photo_tag`.`tag_name` IN ('tags') OR `photo`.`photo_name` LIKE '%tags%') ORDER BY `bookmark_date` desc";
		$query = "SELECT DISTINCT `artist_bookmark_photo`.`PHOTO_ID`, `bookmark_date` FROM (`artist_bookmark_photo`) JOIN `photo` ON `artist_bookmark_photo`.`PHOTO_ID` = `photo`.`PHOTO_ID` "; 
		if($filter_keywords!=FILTER_NONE)
		{
			$query = $query."LEFT JOIN `photo_tag` ON `photo_tag`.`PHOTO_ID` = `photo`.`PHOTO_ID` WHERE `artist_bookmark_photo`.`artist_dname` = '".$artist_dname."'";
			$query = $query.' AND (';
			
			$filter_keyword_array = explode(WHITESPACE,$filter_keywords);
			for($i=0;$i<count($filter_keyword_array);++$i)
			{
				if($i>0)
				{
					$query = $query." OR ";
				}
				
				$query = $query."`photo_tag`.`tag_name` IN ('".$filter_keyword_array[$i]."')";
				$query = $query." OR `photo`.`photo_name` LIKE '".$filter_keyword_array[$i]."'";
			}			
			
			$query = $query.')';
		}
		else
		{
			$query = $query."WHERE `artist_bookmark_photo`.`artist_dname` = '".$artist_dname."'";
		}
		
		$photo_bookmarks = $this->db->query($query)->result_array();
		$this->log->fine('[MODEL] Returning photo bookmarks:'.print_r($photo_bookmarks,true));
		$this->log->fine('[MODEL] Returning '.count($photo_bookmarks).' photo bookmarks');
		
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $photo_bookmarks;
	}
	
	
	/**-------------------------------------------------------------------------------------------------------------------*/
	
	public static function RankPhotosByRelevance($photo1,$photo2)
	{
		return ($photo1['keyword_match_count'] - $photo2['keyword_match_count']);
	}
	
	public static function RankPhotosByDate($photo1,$photo2)
	{
		// @todo
		return 0;
	}
	
	public static function RankPhotosByRating($photo1,$photo2)
	{
		return ($photo1['photo_rating'] - $photo2['photo_rating']);
	}
	
	/**-------------------------------------------------------------------------------------------------------------------*/
	
}

/* End of file PhotoCollectionModel.php */