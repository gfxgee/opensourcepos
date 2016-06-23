<?php
class Transfer_model extends CI_Model
{
	public function get_locations(){
		$this->db->from('stock_locations');
		$this->db->where('deleted',0);
		return $this->db->get()->result();
	}

	public function get_item($id,$lid)
	{
		$this->db->from('items');
		$this->db->join('item_quantities','items.item_id = item_quantities.item_id');
		$this->db->where('items.item_id',$id);
		$this->db->where('item_quantities.location_id',$lid);
		return $this->db->get()->result();
	}

	public function insert_transer_item($data)
	{
		$this->db->trans_start();
		$datas = array(
	        'quantity' => ($data['item_qtf'] + $data['qty_hidden_2'])
		);

		$this->db->where('item_id', $data['item_id']);
		$this->db->where('location_id', $data['to_location']);
		$this->db->update('item_quantities', $datas);
		$q1 = $this->db->affected_rows();
		$this->db->trans_complete();

		$this->db->trans_start();
		$datas1 = array(
	        'quantity' => ($data['qty_hidden'] - $data['item_qtf'])
		);

		$this->db->where('item_id', $data['item_id']);
		$this->db->where('location_id', $data['from_location']);
		$this->db->update('item_quantities', $datas1);
		$q2 = $this->db->affected_rows();
		$this->db->trans_complete();

		if(!empty($q1) && !empty($q2)){
			echo json_encode(['msg' => 'success']);
			return true;
		}else{
			echo json_encode(['msg' => 'fail']);
			return false;
		}
	}
}
?>
