<?php
/**
 * Meta Controller Class
 * 
 * Purpose
 * 		Controls meta pages - abous us, terms, privacy, contact
 * Created 
 * 		November 1, 2009
 * 
 * @package	Lightbox
 * @subpackage	Controllers
 * @category	none
 * @author		venksster
 */
require CONTROLLER_BASE_PATH.'Abstract.LightboxController'.EXT;

class Meta extends AbstractLightboxController
{
	/**
   	* Constructor
   	* 
   	* @access	public
   	* @param 	void
   	* @return	void 			
   	*/
	public function Meta()
	{
		parent::AbstractLightboxController('meta');
	}
	
	public function About()
	{
		try
		{
			$this->Display('View.META.About');
		}
		catch(Exception $e)
		{
			echo $this->_OnError($e);
		}		 
	}
	
	public function TermsOfService()
	{
		try
		{
			$this->Display('View.META.TermsOfService');
		}
		catch(Exception $e)
		{
			echo $this->_OnError($e);
		}
	}
	
	public function PrivacyPolicy()
	{
		try
		{
			$this->Display('View.META.PrivacyPolicy');
		}
		catch(Exception $e)
		{
			echo $this->_OnError($e);
		}
	}
	
	public function FAQ()
	{
		try
		{
			$this->Display('View.META.FAQ');
		}
		catch(Exception $e)
		{
			echo $this->_OnError($e);
		}
	}
	
	public function Contact()
	{
		try
		{
			$this->Display('View.META.Contact');
		}
		catch(Exception $e)
		{
			echo $this->_OnError($e);
		}
	}
	
	
}

/* End of file Meta.php */