<?php
class Order_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getpesanan_all() {
        return $this->db->get('pesanan')->result();
    }

    public function getpesanan_by_id($id) {
        return $this->db->get_where('pesanan', ['id' => $id])->row();
    }

    public function getusers_all() {
        return $this->db->get('users')->result();
    }

    public function getusers_by_id($id) {
        return $this->db->get_where('users', ['id' => $id])->row();
    }

    public function create($data) {
        // Add timestamps
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        $this->db->insert('pesanan', $data);
        return $this->db->insert_id(); // Return newly created ID
    }

    public function insert_order($data) {
        return $this->db->insert('pesanan', $data);
    }
}
