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
}
?>
