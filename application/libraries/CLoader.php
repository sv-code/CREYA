<?php
/**
 * CLoader Class
 * 
 * Purpose
 * 		Extends the default loader
 * Created 
 * 		March 04, 2009
 * 
 * @package	Lightbox
 * @subpackage	Libraries
 * @category	Core
 * @author		venksster
 * @link		TBD
 */

class CLoader extends CI_Loader
{
	/**
   	* Constructor
   	* 
   	* @access	public
   	* @return	void 			
   	*/
	public function CLoader()
	{
		parent::CI_Loader();
	}
	
	/**
   	* Overrides CI_Loader::database in order to load the extended CDBDriver library
   	* 
   	* @access	public
   	* @return	void 			
   	*/
	public function database($params = '', $return = FALSE, $active_record = FALSE)
	{
		// Do we even need to load the database class?
		if (class_exists('CI_DB') AND $return == FALSE AND $active_record == FALSE)
		{
			return FALSE;
		}	
	
		require_once(BASEPATH.'database/DB'.EXT);

		/**-----------------------------------------------------------------------*/
		// Load the DB class
            	$db =& DB($params, $active_record);
		
		$my_driver = 'CDBDriver';
            	$my_driver_file = LIBPATH.$my_driver.EXT;

            	if(file_exists($my_driver_file))
            	{
                		require_once($my_driver_file);
                		$db =& new $my_driver(get_object_vars($db));
            	}

            	if ($return === TRUE)
            	{
                		return $db;
            	}
				
		/**-----------------------------------------------------------------------*/
		// Grab the super object
		$CI =& get_instance();
		
		// Initialize the db variable.  Needed to prevent   
		// reference errors with some configurations
		$CI->db = '';
		
		/**-----------------------------------------------------------------------*/
		// Load the DB class
		$CI->db = $db;
		/**-----------------------------------------------------------------------*/
		
		// Assign the DB object to any existing models
		$this->_ci_assign_to_models();
	}
	
	/**
	 * Model Loader
	 *
	 * This function lets users load and instantiate models.
	 *
	 * @access	public
	 * @param	string	the name of the class
	 * @param	string	name for the model
	 * @param	bool	database connection
	 * @return	void
	 */	
	function model($model, $name = '', $db_conn = FALSE)
	{		
		if (is_array($model))
		{
			foreach($model as $babe)
			{
				$this->model($babe);	
			}
			return;
		}

		if ($model == '')
		{
			return;
		}
	
		// Is the model in a sub-folder? If so, parse out the filename and path.
		if (strpos($model, '/') === FALSE)
		{
			$path = '';
		}
		else
		{
			$x = explode('/', $model);
			$model = end($x);			
			unset($x[count($x)-1]);
			$path = implode('/', $x).'/';
		}
	
		if ($name == '')
		{
			$name = $model;
		}
		
		if (in_array($name, $this->_ci_models, TRUE))
		{
			return;
		}
		
		$CI =& get_instance();
		if (isset($CI->$name))
		{
			show_error('The model name you are loading is the name of a resource that is already being used: '.$name);
		}
	
		//$model = strtolower($model);
		
		/*-------------------------------------------------------*/
		 $model_file = $model;
		
		// Replace *CollectionModel with Collection.*Model
		if(substr_count($model_file,'Collection') > 0)
		{
			$model_file = $this->_ReturnModelFile($model,'Collection');
		} 
		
		// Replace *EntityModel with Entity.*Model
		if(substr_count($model_file,'Entity') > 0)
		{
			$model_file = $this->_ReturnModelFile($model,'Entity');
			log_message('debug','[CLOADER] Model_file: '.$model_file);
		} 
		
		// Replace StatsModel,GearModel,CommunityModel,PhotoModel with Static.StatsModel, Static.GearModel,Static.CommunityModel,Static.PhotoModel,Static.ArtistModel
		if($model_file=='StatsModel' || $model_file=='GearModel' || $model_file=='CommunityModel' || $model_file=="PhotoModel"  || $model_file=="ArtistModel")
		{
			$model_file = 'Static.'.$model_file;
		} 
		
		 	  
		 /*--------------------------------------------------------*/
		
		log_message('debug',APPPATH.'models/'.$path.$model_file.EXT);
		if ( ! file_exists(APPPATH.'models/'.$path.$model_file.EXT))
		{
			show_error('Unable to locate the model you have specified: '.$model);
		}
				
		if ($db_conn !== FALSE AND ! class_exists('CI_DB'))
		{
			if ($db_conn === TRUE)
				$db_conn = '';
		
			$CI->load->database($db_conn, FALSE, TRUE);
		}
	
		if ( ! class_exists('Model'))
		{
			load_class('Model', FALSE);
		}

		require_once(APPPATH.'models/'.$path.$model_file.EXT);

		$model = ucfirst($model);
				
		$CI->$name = new $model();
		$CI->$name->_assign_libraries();
		
		$this->_ci_models[] = $name;	
	}
	
	private function _ReturnModelFile($model,$type)
	{
		$module_suffix 	= strstr($model,$type.'Model');
		$module       	= substr($model,0,strlen($model)-strlen($module_suffix));
		return $type.'.'.$module.'Model'; 
	}	
	
	function _ci_load($_ci_data)
	{
		// Set the default data variables
		foreach (array('_ci_view', '_ci_vars', '_ci_path', '_ci_return') as $_ci_val)
		{
			$$_ci_val = ( ! isset($_ci_data[$_ci_val])) ? FALSE : $_ci_data[$_ci_val];
		}

		// Set the path to the requested file
		if ($_ci_path == '')
		{
			$_ci_ext = pathinfo($_ci_view, PATHINFO_EXTENSION);
			$_ci_file = ($_ci_ext == '') ? $_ci_view.EXT : $_ci_view;
			
			/*---------------------------------------*/
			if(substr_count($_ci_file,'View.') > 0)
			{
				$_ci_file = $_ci_view.EXT; 
			}
			
			if(substr_count($_ci_file,'Meta.') > 0)
			{
				$_ci_file = 'meta/'.$_ci_view.EXT; 
			}
			
			if(substr_count($_ci_file,'Embed.') > 0)
			{
				$_ci_file = 'embed/'.$_ci_view.EXT; 
			}	
			
			if(substr_count($_ci_file,'Modal.') > 0)
			{
				$_ci_file = 'modal/'.$_ci_view.EXT; 
			}				
			/*---------------------------------------*/
			
			$_ci_path = $this->_ci_view_path.$_ci_file;
		}
		else
		{
			$_ci_x = explode('/', $_ci_path);
			$_ci_file = end($_ci_x);
		}
		
		if ( ! file_exists($_ci_path))
		{
			show_error('Unable to load the requested file: '.$_ci_file);
		}
	
		// This allows anything loaded using $this->load (views, files, etc.)
		// to become accessible from within the Controller and Model functions.
		// Only needed when running PHP 5
		
		if ($this->_ci_is_instance())
		{
			$_ci_CI =& get_instance();
			foreach (get_object_vars($_ci_CI) as $_ci_key => $_ci_var)
			{
				if ( ! isset($this->$_ci_key))
				{
					$this->$_ci_key =& $_ci_CI->$_ci_key;
				}
			}
		}

		/*
		 * Extract and cache variables
		 *
		 * You can either set variables using the dedicated $this->load_vars()
		 * function or via the second parameter of this function. We'll merge
		 * the two types and cache them so that views that are embedded within
		 * other views can have access to these variables.
		 */	
		if (is_array($_ci_vars))
		{
			$this->_ci_cached_vars = array_merge($this->_ci_cached_vars, $_ci_vars);
		}
		extract($this->_ci_cached_vars);
				
		/*
		 * Buffer the output
		 *
		 * We buffer the output for two reasons:
		 * 1. Speed. You get a significant speed boost.
		 * 2. So that the final rendered template can be
		 * post-processed by the output class.  Why do we
		 * need post processing?  For one thing, in order to
		 * show the elapsed page load time.  Unless we
		 * can intercept the content right before it's sent to
		 * the browser and then stop the timer it won't be accurate.
		 */
		ob_start();
				
		// If the PHP installation does not support short tags we'll
		// do a little string replacement, changing the short tags
		// to standard PHP echo statements.
		
		if ((bool) @ini_get('short_open_tag') === FALSE AND config_item('rewrite_short_tags') == TRUE)
		{
			echo eval('?>'.preg_replace("/;*\s*\?>/", "; ?>", str_replace('<?=', '<?php echo ', file_get_contents($_ci_path))));
		}
		else
		{
			include($_ci_path); // include() vs include_once() allows for multiple views with the same name
		}
		
		log_message('debug', 'File loaded: '.$_ci_path);
		
		// Return the file data if requested
		if ($_ci_return === TRUE)
		{		
			$buffer = ob_get_contents();
			@ob_end_clean();
			return $buffer;
		}

		/*
		 * Flush the buffer... or buff the flusher?
		 *
		 * In order to permit views to be nested within
		 * other views, we need to flush the content back out whenever
		 * we are beyond the first level of output buffering so that
		 * it can be seen and included properly by the first included
		 * template and any subsequent ones. Oy!
		 *
		 */	
		if (ob_get_level() > $this->_ci_ob_level + 1)
		{
			ob_end_flush();
		}
		else
		{
			// PHP 4 requires that we use a global
			global $OUT;
			$OUT->append_output(ob_get_contents());
			@ob_end_clean();
		}
	}
}
 
/* End of file CLoader.php */


	