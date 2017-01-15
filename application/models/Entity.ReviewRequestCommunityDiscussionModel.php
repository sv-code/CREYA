<?php
/**
 * CommunityDiscussionReviewRequestEntityModel Class
 * 
 * Interfaces
 * 
 * 		AbstractLightboxEntityModel::Add					($artist_dname,$photo_name,$photo_type,$photo_data=null)
 * 		AbstractLightboxEntityModel::Delete				($pid)
 * 		AbstractLightboxEntityModel::GetDetails				($pid)
 * 
 *  		ILightboxCommentable::GetCommentsCount			($pid)	
 *  		ILightboxCommentable::GetComments				($pid)	
 * 		ILightboxCommentable::AddComment				($pid,$artist_dname,$comment_text,$sid=null)
 * 		ILightboxCommentable::DeleteComment				($comment_id)
 * 
 * 		AbstractDiscussionEntityModel::GetCreator			($discId)
 * 		AbstractDiscussionEntityModel::GetMostActiveArtists	($num_results,$filters=null)
 * 	
 *  		CommunityDiscussionEntityModel::GetSection			($DISC_ID)
 *  		CommunityDiscussionEntityModel::Create			($artist_dname,$disc_title,$disc_comment,$disc_image_attachments=null)
 * 
 * Created 
 * 		Oct 7, 2009
 * 
 * @package	Lightbox
 * @subpackage	Models
 * @category	none
 * @author		venksster
 * @link		TBD
 */
require 'Entity.CommunityDiscussionModel'.EXT;

define('INDEX_CROP_X',0);
define('INDEX_CROP_Y',1);
define('INDEX_CROP_WIDTH',2);
define('INDEX_CROP_HEIGHT',3);
 
final class ReviewRequestCommunityDiscussionEntityModel extends CommunityDiscussionEntityModel
{
	/**
   	* Constructor
   	*   
   	* @access	public
   	* @param 	void
   	* @return	void 			
   	*/
	public function ReviewRequestCommunityDiscussionEntityModel($module='community')
  	{
 		parent::CommunityDiscussionEntityModel($module,$table_discussion_comment='community_discussion_review');
		
		$this->TABLE_REVIEW = 'community_discussion_review';
		$this->TABLE_REVIEW_REQUEST_AVERAGE_RATING = 'community_discussion_review_request_average_rating';
		
		$this->LIGHTBOX_METRIC_ARRAY = array('metric_impact','metric_balance','metric_technique');
	}
	
	public function GetDetails($disc_id,$details='*')
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning details for disc:'.$disc_id);
		
		$this->db->select($details);
		$this->db->where($this->TABLE_ENTITY.'.'.$this->COLUMN_ENTITY_KEY,$disc_id);
		$this->db->join($this->TABLE_REVIEW_REQUEST_AVERAGE_RATING,$this->TABLE_ENTITY.'.DISC_ID='.$this->TABLE_REVIEW_REQUEST_AVERAGE_RATING.'.DISC_ID');
		$this->db->join('photo','photo.PHOTO_ID='.$this->TABLE_REVIEW_REQUEST_AVERAGE_RATING.'.PHOTO_ID');
		
	 	$disc_db = $this->db->get($this->TABLE_ENTITY,SINGLE_RESULT);
		
		if($disc_db->num_rows()==0)
		{
			throw new Exception('[MODEL] No such discussion:'.$disc_id);
		}
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $disc_db->row_array();	
	}
	
	/**
   	* Creates a new review request
   	* 
   	* @type	PUT
   	* 
   	* @access	public
   	* @param 	string		$artist_dname
   	* @param	string		$disc_body				DEFAULT:null
   	* @return	int		$key							
   	*/
	public function Create($pid,$artist_dname,$disc_body=null)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Creating new review request for pid:'.$pid);
		
		$key = parent::Create($artist_dname,$disc_title='Review Request',$disc_body,$disc_image_attachments=$pid,$section_name='review_request');
		
		$this->log->info('[MODEL] Initializing ratings data for pid:'.$pid);
		
		$data_db = array
		(
			$this->COLUMN_ENTITY_KEY				=>	$key,
			'PHOTO_ID'							=>	$pid,			
			'artist_dname'						=>	$artist_dname,
			
			'metric_impact_average_rating'			=>	0,
			'metric_balance_average_rating'			=>	0,
			'metric_technique_average_rating'			=>	0,
			
			'average_rating'						=>	0
		);
		
		$this->db->insert($this->TABLE_REVIEW_REQUEST_AVERAGE_RATING,$data_db);	
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $key;
	
	}
	
	public function AddReview($disc_id,$artist_dname,$metric_data,$review_comment_text,$review_crop_coordinates=0)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Attempting to add new review for disc:'.$disc_id.' from artist_dname:'.$artist_dname);
		
		if($this->IsReviewedByArtist($disc_id,$artist_dname))
		{
			throw new Exception('[MODEL] Artist:'.$artist_dname.' has already reviewed this photo');
		}
		
		$this->_VerifyMetricData($metric_data);
		
		if($review_crop_coordinates!=0)
		{
			$review_crop_coordinates = $this->_ReturnCropCoordinates($review_crop_coordinates);
		}
		$this->log->debug('[MODEL] review_crop_coords:'.print_r($review_crop_coordinates,true));
		
		$this->log->info('[MODEL] Adding new review for disc:'.$disc_id.' from artist_dname:'.$artist_dname);
			
		$data_db = array
		(
			$this->COLUMN_ENTITY_KEY				=>	$disc_id,
			'artist_dname'						=>	$artist_dname,
			
			'review_metric_impact_rating'			=>	$metric_data['metric_impact']['rating'],
			'review_metric_impact_comment_text'		=>	$metric_data['metric_impact']['comment'],
			
			'review_metric_balance_rating'			=>	$metric_data['metric_balance']['rating'],
			'review_metric_balance_comment_text'		=>	$metric_data['metric_balance']['comment'],
			
			'review_metric_technique_rating'			=>	$metric_data['metric_technique']['rating'],
			'review_metric_technique_comment_text'	=>	$metric_data['metric_technique']['comment'],
			
			'review_comment_text'				=>	$review_comment_text,
			'review_crop_coordinates'				=>	$review_crop_coordinates,
			'review_date'						=>	$this->util->GetCurrentDateStamp()
		);
		
		$this->db->insert($this->TABLE_REVIEW,$data_db);	
		$this->log->info('[MODEL] ADDED new review for disc:'.$disc_id.' from artist_dname:'.$artist_dname);
		
		$this->log->info('[MODEL] Recalculating average ratings');
		$this->_RecalculateAverageRatings($disc_id);	
		
		$this->log->info('[MODEL] Updating discussion activity');
		$this->UpdateActivityTimeStamp($disc_id);			
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
	}
		
	public function GetMostRelevantComments($entity_id,$num_comments,$offset,$filter_keywords)
	{
		throw new Exception('[MODEL] Not supported');
	}
	
	public function GetReviews($disc_id,$order_by=ORDER_DATE,$filter_keywords=FILTER_NONE,$num_entities=RESULTS_ALL,$offset=OFFSET_NONE)
	{
		return $this->GetComments($disc_id,$order_by,$filter_keywords,$num_entities,$offset);
	}
	
	public function GetComments($disc_id,$order_by=ORDER_DATE,$filter_keywords=FILTER_NONE,$num_entities=RESULTS_ALL,$offset=OFFSET_NONE)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning review data for disc:'.$disc_id);
		
		$this->db->select('*');
		$this->db->where($this->COLUMN_ENTITY_KEY,$disc_id);
		
		// JOIN CLAUSE FOR artist_avatar
		$join_clause = 'artist.artist_dname='.$this->TABLE_COMMENT.'.artist_dname';
		$this->db->join('artist',$join_clause);
					
		// ORDER 
		$this->db->order_by('review_date','desc');
		$review_array = $this->db->get($this->TABLE_REVIEW,$num_entities,$offset)->result_array();
		
		// CROP CO-ORDINATES
		foreach($review_array as &$review)
		{
			if($review['review_crop_coordinates']!='0')
			{
				$this->log->fine('[MODEL] Crop coordinated found:'.$review['review_crop_coordinates']);
				
				$crop_coordinates = explode(LIGHTBOX_REVIEW_CROP_COORDINATES_DELIMITER,$review['review_crop_coordinates']);
				if(count($crop_coordinates)==4 && is_numeric($crop_coordinates[INDEX_CROP_X]) && is_numeric($crop_coordinates[INDEX_CROP_Y]) && is_numeric($crop_coordinates[INDEX_CROP_WIDTH]) && is_numeric($crop_coordinates[INDEX_CROP_HEIGHT]))
				{
					$review['crop_x'] = $crop_coordinates[INDEX_CROP_X];
					$review['crop_y'] = $crop_coordinates[INDEX_CROP_Y];
					$review['crop_width'] = $crop_coordinates[INDEX_CROP_WIDTH];
					$review['crop_height'] = $crop_coordinates[INDEX_CROP_HEIGHT];
				}
			}
		}
	
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $review_array;
	}
	
	public function AddComment($entity_id,$artist_dname,$comment_text,$additional_data=null)
	{
		throw new Exception('[MODEL] AddComment() cannot be used to add a review. Use AddReview() instead');
	}
	
	public function DeleteReview($disc_id,$review_id)
	{
		$this->DeleteComment($disc_id,$review_id);
	}
	
	public function DeleteComment($disc_id,$review_id)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Deleting review:'.$review_id.' for disc:'.$disc_id);
		
		$where = array('REVIEW_ID' => $review_id);
		$this->log->debug('where:'.print_r($where,true));
		$this->db->where($where);
		$this->db->delete($this->TABLE_REVIEW);
		
		$this->log->info('[MODEL] Recalculating average ratings');
		$this->_RecalculateAverageRatings($disc_id);
				
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
	}
	
	public function IsReviewedByArtist($disc_id,$artist_dname)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Checking if disc:'.$disc_id.' is reviewed by artist:'.$artist_dname);
		
		$where = array('DISC_ID' => $disc_id,'artist_dname' => $artist_dname);
		$this->log->debug('where:'.print_r($where,true));
		
		$this->db->where($where);
		$this->db->from($this->TABLE_REVIEW);
		
		$this->log->debug('TABLE_REVIEW:'.$this->TABLE_REVIEW);
		
		if($this->db->count_all_results() > 0)
		{
			$this->log->debug('result:true');
			$result = true;
		}
		else
		{
			$this->log->debug('result:false');
			$result = false;
		}
			
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $result;
	}
	
	public function IsSubmittedForReview($pid)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Checking if photo:'.$pid.' is submitted for review');
		
		$where = array('PHOTO_ID' => $pid);
		$this->log->debug('where:'.print_r($where,true));
		
		$this->db->where($where);
		$this->db->from($this->TABLE_REVIEW);
		
		$this->log->debug('TABLE_REVIEW:'.$this->TABLE_REVIEW_REQUEST_AVERAGE_RATING);
		
		if($this->db->count_all_results() > 0)
		{
			$this->log->debug('result:true');
			$result = true;
		}
		else
		{
			$this->log->debug('result:false');
			$result = false;
		}
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $result;
	}	
	
	public function GetReviewRequestId($pid)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Returning REVIEW_ID for photo:'.$pid);
		
		$this->db->where('PHOTO_ID',$pid);
		//$join_clause = $this->TABLE_REVIEW_REQUEST_AVERAGE_RATING.'.DISC_ID='.$this->TABLE_REVIEW.'.DISC_ID';
		//$this->db->join($this->TABLE_REVIEW,$join_clause);
		$review_id = $this->db->get_attribute($this->TABLE_REVIEW_REQUEST_AVERAGE_RATING,'DISC_ID');
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
		return $review_id;
	}	
	
	/**
	* Unconditionally deletes the review_request:$review_request_id
	*
	* @param 	int		$review_request_id 		
	* @return	void
	*/
	public function Delete($review_request_id)
	{
		$this->benchmark->Start(__METHOD__);
		$this->log->info('[MODEL] Deleting review_request:'.$review_request_id);
		
		$this->log->fine('[MODEL] Deleting entry in AVERAGE_RATING');
		$this->db->where($this->COLUMN_ENTITY_KEY,$review_request_id);
		$this->db->delete($this->TABLE_REVIEW_REQUEST_AVERAGE_RATING);
		
		$this->log->fine('[MODEL] Deleting entry in DISCUSSION');
		parent::Delete($review_request_id);
		
		$this->log->benchmark('[MODEL] '.$this->benchmark->ElapsedTimeString(__METHOD__));
	}
	
	/**-------------------------------------------------------------------------------------------------------------------*/
	
	private function _VerifyMetricData($metric_data)
	{
		$this->log->fine('[MODEL] Verifying MetricData:'.print_r($metric_data,true));
		
		if(!isset($metric_data) || !is_array($metric_data))
		{
			throw new Exception();
		}
		
		foreach($this->LIGHTBOX_METRIC_ARRAY as $metric)
		{
			if(!array_key_exists($metric,$metric_data) || !is_array($metric_data[$metric]) || !array_key_exists('rating',$metric_data[$metric]) || !array_key_exists('comment',$metric_data[$metric]))
			{
				throw new Exception();
			}
		}
		
		$this->log->fine('[CONTROLLER] MetricData verified');
		return;
	}
	
	private function _ReturnCropCoordinates($review_crop_coordinates)
	{
		$this->log->fine('[MODEL] Verifying CropData:'.print_r($review_crop_coordinates,true));
		
		if(!isset($review_crop_coordinates) || !is_array($review_crop_coordinates) || count($review_crop_coordinates)>4 || !array_key_exists('crop_x',$review_crop_coordinates) || !array_key_exists('crop_y',$review_crop_coordinates) || !array_key_exists('crop_width',$review_crop_coordinates) || !array_key_exists('crop_height',$review_crop_coordinates))
		{
			$this->log->error('[MODEL] CropData invalid');
			return 0;
		}		
		
		$this->log->fine('[CONTROLLER] CropData verified');
		return implode(LIGHTBOX_REVIEW_CROP_COORDINATES_DELIMITER,$review_crop_coordinates);
	}
	
	private function _RecalculateAverageRatings($disc_id)
	{
		$this->log->fine('[MODEL] Recalculating average ratings for disc:'.$disc_id);
		
		$this->db->select('*');
		$this->db->where($this->COLUMN_ENTITY_KEY,$disc_id);
		$review_array = $this->db->get($this->TABLE_REVIEW)->result_array();
		
		$average_metric_ratings = array();
		foreach($this->LIGHTBOX_METRIC_ARRAY as $metric)
		{
			$average_metric_ratings[$metric] = 0.0;
		}
		
		foreach($review_array as $review)
		{
			foreach($this->LIGHTBOX_METRIC_ARRAY as $metric)
			{
				$average_metric_ratings[$metric] += $review['review_'.$metric.'_rating'];
			}	
		}
		
		foreach($this->LIGHTBOX_METRIC_ARRAY as $metric)
		{
			$this->log->debug('[MODEL] Cumulative rating for metric:'.$metric.'::'.$average_metric_ratings[$metric]);
			$average_metric_ratings[$metric] /= count($review_array);
			$this->log->debug('[MODEL] Average rating for metric:'.$metric.'::'.$average_metric_ratings[$metric]);
		}
		
		$average_rating = 0.0;
		foreach($this->LIGHTBOX_METRIC_ARRAY as $metric)
		{
			$average_rating += $average_metric_ratings[$metric];
			$this->log->debug('[MODEL] Cumulative average rating:'.$average_rating);
		}
		$average_rating /= count($this->LIGHTBOX_METRIC_ARRAY);
		$this->log->debug('[MODEL] AVERAGE RATING:'.$average_rating);
		
		$this->log->info('[MODEL] Updating average ratings for disc:'.$disc_id);
		$data_db = array
		(
			$this->COLUMN_ENTITY_KEY			=>	$disc_id,
						
			'metric_impact_average_rating'		=>	$average_metric_ratings['metric_impact'],
			'metric_balance_average_rating'		=>	$average_metric_ratings['metric_balance'],
			'metric_technique_average_rating'		=>	$average_metric_ratings['metric_technique'],
			'average_rating'					=>	$average_rating			
		);
		
		$this->db->where($this->COLUMN_ENTITY_KEY,$disc_id);
		$this->db->update($this->TABLE_REVIEW_REQUEST_AVERAGE_RATING,$data_db);	
		$this->log->info('[MODEL] UPDATED average ratings for disc:'.$disc_id);
							
	}
	
	/**
	* @access private
	*/
	private $LIGHTBOX_METRIC_ARRAY;
		
	/**
	* @access private
	*/
	private $TABLE_REVIEW;
	
	/**
	* @access private
	*/
	private $TABLE_REVIEW_REQUEST_AVERAGE_RATING;
	
	
}  	
 
/* End of file CommunityDiscussionReviewRequestEntityModel.php */


	