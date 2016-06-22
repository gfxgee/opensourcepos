<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Transfer extends Secure_Controller
{
	public function __construct()
	{
		parent::__construct('transfer');
	}
	
	public function index()
	{
		$data['locations'] = $this->Transfer_model->get_locations();
		//$datas = $this->xss_clean($data);
		
		$this->load->view('transfer/transfer',$data);
	}
	
}
?>