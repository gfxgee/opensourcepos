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
	
	public function item_search()
	{
		$suggestions = $this->Item->get_search_suggestions($this->input->get('term'), array('search_custom' => FALSE, 'is_deleted' => FALSE), TRUE);
		$suggestions = array_merge($suggestions, $this->Item_kit->get_search_suggestions($this->input->get('term')));

		$suggestions = $this->xss_clean($suggestions);

		echo json_encode($suggestions);
	}

	public function get_item_id(){
		echo json_encode($this->Transfer_model->get_item($this->input->post('item_id'),$this->input->post('location_id')));
	}
}
?>