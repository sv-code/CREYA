<?php
/**
 * CDBDriver Class
 * 
 * Purpose
 * 		Extends ActiveRecord and incorporates exceptions 
 * Created 
 * 		March 04, 2009
 * 
 * @package	Lightbox
 * @subpackage	Libraries
 * @category	Core
 * @author		venksster
 * @link		TBD
 */

require EXCEPTION_PATH.'Exception.DBDriver'.EXT;

class CDBDriver  extends CI_DB_mysql_driver
{
	/**
   	* Constructor
   	* 
   	* @access	public
   	* @return	void 			
   	*/
	 public function __construct($params)
	 {
    		parent::__construct($params);
		
		$this->log = clogger_return_instance('CDBDriver');
    		$this->log->info('Extended CDBDriver class instantiated');
  	}
	
	/**
	 * @access	public
	 * @param	string	$table	the table
	 * @param	string	$limit		the limit clause
	 * @param	string	$offset	the offset clause
	 * @return	object		the results
	 * @exception			throws if DB returns an error
	 */
	public function get($table = '', $limit = null, $offset = null)
	{
		if($limit==RESULTS_ALL)
		{
			$limit=null;
			$offset=null;
		}
		
		$result = parent::get($table,$limit,$offset);
		
		$db_error=$this->_error_message();
		if(!($db_error==''))
		{
			throw new DBDriverException($db_error);
		}
		
		return $result;
	}
	
	/**
	 * @access	public
	 * @param	string	$table	the table
	 * @param	array	$where	the where clause
	 * @param	string	$limit		the limit clause
	 * @param	string	$offset	the offset clause
	 * @return	object		the results
	 * @exception			throws if DB returns an error
	 */
	public function get_where($table = '', $where = null, $limit = null, $offset = null)
	{
		$result = parent::get_where($table,$where,$limit,$offset);
		
		$db_error=$this->_error_message();
		if(!($db_error==''))
		{
			throw new DBDriverException($db_error);
		}
		
		return $result;
	}
	
	/**
	 * Returns a single column value. If multiple rows are returned, the value returned is ONLY from the first row
	 * 
	 * @access	public
	 * @param	string	$table		the table
	 * @param	string	$attribute_name	the column name
	 * @return	object			the result
	 * @exception				throws if DB returns an error
	 * @todo					ADD AN OPTIONAL WHERE CLAUSE
	 */
	public function get_attribute($table,$attribute_name)
	{
		$this->select($attribute_name);
				
		$data_db = $this->get($table);
		$data_db = $data_db->row_array();
		
		$db_error=$this->_error_message();
		if(!($db_error==''))
		{
			throw new DBDriverException($db_error);
		}
		
		if(array_key_exists($attribute_name,$data_db))
		{
			return $data_db[$attribute_name];	
		}
		
		return null;
	}
	
	/**
	 * @access	public
	 * @param	string	$table	the table
	 * @param	array	$set		data to insert
	 * @return	??			
	 * @exception			throws if DB returns an error
	 */
	public function insert($table = '', $set = NULL)
	{	
		$result = parent::insert($table,$set);
		
		$db_error=$this->_error_message();
		if(!($db_error==''))
		{
			throw new DBDriverException($db_error);
		}
		
		return $result;
	}
	
	/**
	 * @access	public
	 * @param	string	$table	the table
	 * @param	array	$set		data to insert
	 * @param	array	$where	the where clause
	 * @param	string	$limit		the limit clause
	 * @return	??			
	 * @exception			throws if DB returns an error
	 */
	public function update($table = '', $set = NULL, $where = NULL, $limit = NULL)
	{
		$result = parent::update($table,$set,$where,$limit);
		
		$db_error=$this->_error_message();
		if(!($db_error==''))
		{
			throw new DBDriverException($db_error);
		}
		
		return $result;
	}
	
	/**
	 * @access	public
	 * @param	string	$key	
	 * @param	array	$values
	 */
	public function or_like_in($key,$values)
	{
		if(isset($values) && is_array($values))
		{
			$this->like($key,$values[0]);
			
			for($i=1;$i<count($values);++$i)
			{
				$this->or_like($key,$values[$i]);	
			}
		}
	}
	
	/**
	 * Increments a column, IF that column contains an INTEGER value
	 * 
	 * @access	public
	 * @param	string	$table	the table
	 * @param	string	$column	the column
	 * @param	array	$where	the where clause
	 * @return	??			
	 * @exception			throws if DB returns an error
	 */
	public function increment($table,$column,$where = NULL)
	{
		$result = $this->_updateIntValue(INCREMENT,$table,$column,$where);
		
		$db_error=$this->_error_message();
		if(!($db_error==''))
		{
			throw new DBDriverException($db_error);
		}
		
		return $result;
	}
	
	/**
	 * Decrements a column, IF that column contains an INTEGER value AND is greater than 0
	 * 
	 * @access	public
	 * @param	string	$table	the table
	 * @param	string	$column	the column
	 * @param	array	$where	the where clause
	 * @return	??			
	 * @exception			throws if DB returns an error
	 */
	public function decrement($table,$column,$where = NULL)
	{
		$result = $this->_updateIntValue(DECREMENT,$table,$column,$where);
		
		$db_error=$this->_error_message();
		if(!($db_error==''))
		{
			throw new DBDriverException($db_error);
		}
		
		return $result;
	}
	
	function count_all_results($table = '')
	{
		if ($table != '')
		{
			$this->_track_aliases($table);
			$this->from($table);
		}
		
		$sql = $this->_compile_select($this->_count_string . $this->_protect_identifiers('numrows'));

		$query = $this->query($sql);
		$this->_reset_select();
	
		if(!is_object($query) ||  $query->num_rows() == 0)
		{
			return '0';
		}

		$row = $query->row();
		return $row->numrows;
	}
	
	/**
	 * @access	private
	 * @param	integer	$type		INCREMENT/DECREMENT
	 * @param	string		$table	the table
	 * @param	string		$column	the column
	 * @param	array		$where	the where clause
	 * @return	??			
	 */
	private function _updateIntValue($type,$table,$column,$where = NULL)
	{
		$this->where($where);
		$count = $this->get_attribute($table,$column);
		
		$count = (int)($count);
		
		if(!is_int($count)) 
		{
			throw new DBDriverException($table.'::'.$column.' value is NOT an integer:'.$count);
		}
		
		if($type==INCREMENT)
		{
			++$count;
		}
		else if($type==DECREMENT && $count > 0)
		{
			--$count;
		}
		
		$data = array($column => $count);
		
		if(isset($where))
		{
			$this->where($where);	
		}
			
		return $this->update($table,$data);
	}
	
	/**
  	* @access private
  	*/ 
	private $log;
}

/* End of file CDBDriver.php */


	