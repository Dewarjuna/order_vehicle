<?php
class Order_model extends CI_Model {

    // Set table names as properties
    protected $table_pesanan = 'PK_pesanan';
    protected $table_user    = 'PK_user';

    public function __construct() {
        parent::__construct();
    }

    public function getpesanan_all() {
        return $this->db->get($this->table_pesanan)->result();
    }

    public function getpesanan_by_id($id) {
        return $this->db->get_where($this->table_pesanan, ['id' => $id])->row();
    }

    public function getusers_all() {
        return $this->db->get($this->table_user)->result();
    }

    public function getusers_by_id($id) {
        return $this->db->get_where($this->table_user, ['id' => $id])->row();
    }

    public function create($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table_pesanan, $data);
        return $this->db->insert_id();
    }

    public function insert_order($data) {
        return $this->db->insert($this->table_pesanan, $data);
    }

    public function getpesanan_by_pemesan($pemesan) {
        return $this->db->get_where($this->table_pesanan, ['pemesan' => $pemesan])->result();
    }

    public function delete($id) {
        return $this->db->delete($this->table_pesanan, array('id' => $id));
    }

    public function update($id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        return $this->db->update($this->table_pesanan, $data);
    }

    public function approve_order($id, $kendaraan) {
        $data = array(
            'status' => 'approved',
            'kendaraan' => $kendaraan,
            'updated_at' => date('Y-m-d H:i:s')
        );
        $this->db->where('id', $id);
        return $this->db->update($this->table_pesanan, $data);
    }

    public function count_orders_by_month($month) {
        $this->db->where("LEFT(tanggal_pesanan, 7) = '$month'", NULL, FALSE);
        return $this->db->count_all_results($this->table_pesanan);
    }
    
    public function count_orders_by_month_status($month, $status) {
        $this->db->where("LEFT(tanggal_pesanan, 7) = '$month'", NULL, FALSE);
        $this->db->where('status', $status);
        return $this->db->count_all_results($this->table_pesanan);
    }
    
    public function count_user_orders_by_month($month, $pemesan) {
        $this->db->where("LEFT(tanggal_pesanan, 7) = '$month'", NULL, FALSE);
        $this->db->where('pemesan', $pemesan);
        return $this->db->count_all_results($this->table_pesanan);
    }

    // Get one order by id with kendaraan details
    public function getpesanan_with_kendaraan_by_id($id) {
        $this->db->select('p.*, k.no_pol, k.nama_kendaraan')
                ->from('PK_pesanan p')
                ->join('PK_kendaraan k', 'p.kendaraan = k.id', 'left')
                ->where('p.id', $id);
        return $this->db->get()->row();
    }

    // Get all orders with kendaraan details (for list/report)
    public function getpesanan_all_with_kendaraan() {
        $this->db->select('p.*, k.no_pol, k.nama_kendaraan')
                ->from('PK_pesanan p')
                ->join('PK_kendaraan k', 'p.kendaraan = k.id', 'left');
        return $this->db->get()->result();
    }

    // Get orders for current user (pemesan) with kendaraan details
    public function getpesanan_by_pemesan_with_kendaraan($pemesan) {
        $this->db->select('p.*, k.no_pol, k.nama_kendaraan')
                ->from('PK_pesanan p')
                ->join('PK_kendaraan k', 'p.kendaraan = k.id', 'left')
                ->where('p.pemesan', $pemesan);
        return $this->db->get()->result();
    }
}