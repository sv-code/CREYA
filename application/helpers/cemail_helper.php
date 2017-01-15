<?php
/**
 * CEmail Helper
 * 
 * Purpose
 * 		 
 * Created 
 * 		Oct 24, 2008
 * 
 * Modified
 * 		Oct 24, 2009
 * 
 * @package		Lightbox
 * @subpackage	Helpers
 * @category	Graphics
 * @author		prakash
 * @link		TBD
 */


function cemail_initialize_library()
{
	$CI = &get_instance();
		
	$config['protocol'] = 'smtp';
	//$config['mailpath'] = '/usr/sbin/sendmail';
	$config['charset'] = 'iso-8859-1';
	$config['wordwrap'] = TRUE;
	$config['smtp_host'] = $CI->config->item('cemail_smtp_host');
	$config['smtp_port'] = $CI->config->item('cemail_smtp_port');
	$config['smtp_user'] = $CI->config->item('cemail_smtp_username'); 
	$config['smtp_pass'] = $CI->config->item('cemail_smtp_password');   
	$config['smtp_timeout'] = '5';
	$config['wrapchars'] = 76;
	$config['crlf'] = '\r\n';
	$config['mailtype'] = 'html';
	$config['validate'] = FALSE;
	$config['priority'] = 3;
	$config['newline'] = '\r\n';
	$config['bcc_batch_mode'] = FALSE;
	$config['bcc_batch_size'] = 200;	
	
	$CI = &get_instance();
	$CI->cutil->log->info('[Cemail_helper] Initializing email helper:: '.print_r($config,true));
	
	$CI->email->initialize($config);
}

function cemail_send_email($from,$to,$bcc,$subject,$message,$attachment=null)
{
	$CI = &get_instance();
	$CI->cutil->log->debug('[Cemail_helper] from: '.$from);	
	$CI->cutil->log->debug('[Cemail_helper] to: '.$to);
	$CI->cutil->log->debug('[Cemail_helper] bcc: '.$bcc);
	$CI->cutil->log->debug('[Cemail_helper] subject: '.$subject);
	$CI->cutil->log->debug('[Cemail_helper] message: '.$message);
			
	$CI->email->set_newline("\r\n");
    $CI->email->from($from, 'Creya Studios');
    $CI->email->to($to);
	
	if( $bcc != NULL )
	{
		$CI->email->bcc($bcc);
	}
	$CI->email->subject($subject);
    $CI->email->message($message);
	
    if(!$CI->email->send())
	{
		$CI->cutil->log->debug('[Cemail_helper] Unable to send mail !'.$CI->email->print_debugger());		
		return false;
    }	

	$CI->cutil->log->debug('[Cemail_helper] Sent mail !');	
	$CI->cutil->log->debug('[Cemail_helper] Email header debug log:'.$CI->email->print_debugger());
	
	return true;
	
}

?>