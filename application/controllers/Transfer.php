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

	public function save($id = -1){
		$item_id = $this->input->post('item_id');
		$item_qtf = $this->input->post('item_quantity_to_transfer');
		$item_quantity = $this->input->post('item_quantity');
		$from_location = $this->input->post('from_location');
		$to_location = $this->input->post('to_location');
		$qty_hidden = $this->input->post('qty_hidden');
		$qty_hidden_2 = $this->input->post('qty_hidden_2');
		$data = array();
		$data = [
			'item_quantity'	=>	$item_quantity,
			'item_qtf'	=>	$item_qtf,
			'item_id'	=>	$item_id,
			'from_location'	=>	$from_location,
			'to_location'	=>	$to_location,
			'qty_hidden'	=>	$qty_hidden,
			'qty_hidden_2'	=>	$qty_hidden_2,
		];

		$q = $this->Transfer_model->insert_transer_item($data);
		if($q){
			$inv_data = array(
            'trans_date' => date('Y-m-d H:i:s'),
            'trans_items' => $item_id,
            'trans_user' => $this->Employee->get_logged_in_employee_info()->person_id,
            'trans_location' => $from_location,
            'trans_comment' => "Transfer Item Location",
            'trans_inventory' => '-'.$item_qtf);

        	$this->Inventory->insert($inv_data);

        	$inv_data2 = array(
            'trans_date' => date('Y-m-d H:i:s'),
            'trans_items' => $item_id,
            'trans_user' => $this->Employee->get_logged_in_employee_info()->person_id,
            'trans_location' => $to_location,
            'trans_comment' => "Transfer Item Location",
            'trans_inventory' => $item_qtf);
        	
        	$z = $this->Inventory->insert($inv_data2);
		}
		 
	}
}
?>