<?php
/**
 * CBenchmark Class
 * 
 * Purpose
 * 		Benchmarking for lightbox
 * Created 
 * 		March 06, 2009
 * 
 * @package	Lightbox
 * @subpackage	Libraries
 * @category	none
 * @author		venksster
 * @link		TBD
 */

class CBenchmark extends CI_Benchmark
{
	/**
   	* Starts the benchmark point, $benchmark_name
   	* 
   	* @access	public
   	* @param	string		$benchmark_name
   	* @return	void					
   	*/
	public function Start($benchmark_name)
	{
		$this->mark($benchmark_name.'Start');
	}
	
	/**
   	* Ends the benchmark point, $benchmark_name
   	* 
   	* @access	public
   	* @param	string		$benchmark_name
   	* @return	void					
   	*/
	public function End($benchmark_name)
	{
		$this->mark($benchmark_name.'End');
	}
	
	/**
   	* Returns execution time in a formatted string format for the benchmark, $benchmark_name' 
   	* Note: functions 'Start' MUST be called with the same $benchmark_name; and it MUST be called BEFORE calling this function
   	* 
   	* @access	public
   	* @param	string		$benchmark_name
   	* @return	string					
   	*/
	public function ElapsedTimeString($benchmark_name)
	{
		$this->End($benchmark_name.'End');
		return $benchmark_name.':'.(1000*$this->elapsed_time($benchmark_name.'Start',$benchmark_name.'End')).'ms';
	}
	
	/**
   	* Returns execution time in millis for the benchmark, $benchmark_name' 
   	* Note: functions 'Start' MUST be called before this function
   	* 
   	* @access	public
   	* @param	string		$benchmark_name
   	* @return	double					time in millis					
   	*/
	public function ElapsedTimeMs($benchmark_name)
	{
		return 1000*$this->elapsed_time($benchmark_name.'Start',$benchmark_name.'End');
	}
}

