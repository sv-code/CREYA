<?php
/**
 * CSession Helper
 * 
 * Purpose
 * 		Session configuration
 * Created 
 * 		Oct 18, 2009
 * 
 * @package		Lightbox
 * @subpackage	Helpers
 * @category	Logging
 * @author		venksster
 * @link		TBD
 */

 
function csession_remember_me($remember_me=true)
{
	$CI = &get_instance();
	$CI->cutil->log->fine('[CSESSION] remember_me:'.$remember_me);	
	
	$session_expiration_timeout=0;
	
	if($remember_me!=true)
	{
		$CI->cutil->log->debug('[CSESSION] remember_me!=true');	
		$session_expiration_timeout = LIGHTBOX_DEFAULT_SESSION_EXPIRATION;
	}
	else
	{
		$CI->cutil->log->debug('[CSESSION] remember_me==true');
	}
	
	$CI->config->set_item('sess_expiration',LIGHTBOX_DEFAULT_SESSION_EXPIRATION);		
}
 
/* End of file csession_helper.php */