<?php
class Transfer_model extends CI_Model
{
	public function get_locations(){
		$this->db->from('stock_locations');
		$this->db->where('deleted',0);
		return $this->db->get()->result();
	}
}
?>
