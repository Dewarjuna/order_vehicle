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

    public function approve_order($id, $kendaraan, $driver) {
        // Check if the order exists
        $data = array(
            'status' => 'approved',
            'kendaraan' => $kendaraan,
            'driver' => $driver,
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
    $this->db->select('p.*, k.no_pol, k.nama_kendaraan, d.nama as nama_driver')
        ->from('PK_pesanan p')
        ->join('PK_kendaraan k', 'p.kendaraan = k.id', 'left')
        ->join('PK_driver d', 'p.driver = d.id', 'left')
        ->where('p.id', $id);
    return $this->db->get()->row();
}

    // Get all orders with kendaraan details (for list/report)
    public function getpesanan_all_with_kendaraan() {
        $this->db->select('p.*, k.no_pol, k.nama_kendaraan, d.nama as nama_driver')
                ->from('PK_pesanan p')
                ->join('PK_kendaraan k', 'p.kendaraan = k.id', 'left')
                ->join('PK_driver d', 'p.driver = d.id', 'left');
        return $this->db->get()->result();
    }

    // Get orders for current user (pemesan) with kendaraan details
    public function getpesanan_by_pemesan_with_kendaraan($pemesan) {
        $this->db->select('p.*, k.no_pol, k.nama_kendaraan, d.nama as nama_driver')
                ->from('PK_pesanan p')
                ->join('PK_kendaraan k', 'p.kendaraan = k.id', 'left')
                ->join('PK_driver d', 'p.driver = d.id', 'left')
                ->where('p.pemesan', $pemesan);
        return $this->db->get()->result();
    }

        public function approve_full_order($order_id, $kendaraan_id, $driver_id) {
        $this->db->trans_begin();

        // Re-validate
        $vehicle = $this->db->get_where('PK_kendaraan', ['id' => (int)$kendaraan_id, 'status' => 'available'])->row();
        $driver  = $this->db->get_where('PK_driver',   ['id' => (int)$driver_id,    'status' => 'available'])->row();

        if (!$vehicle) {
            $this->db->trans_rollback();
            return ['error' => 'Kendaraan tidak tersedia'];
        }
        if (!$driver) {
            $this->db->trans_rollback();
            return ['error' => 'Driver tidak tersedia'];
        }

        // Update pesanan
        $pesananData = [
            'status'    => 'approved',
            'kendaraan' => $kendaraan_id,
            'driver'    => $driver_id,
            'updated_at'=> date('Y-m-d H:i:s')
        ];
        $order_updated = $this->db->where('id', $order_id)->update('PK_pesanan', $pesananData);

        // Update kendaraan & driver
        $vehicle_updated = $this->db->where('id', $kendaraan_id)
            ->update('PK_kendaraan', ['status' => 'unavailable', 'updated_at' => date('Y-m-d H:i:s')]);
        $driver_updated = $this->db->where('id', $driver_id)
            ->update('PK_driver', ['status' => 'unavailable', 'updated_at' => date('Y-m-d H:i:s')]);

        if ($order_updated && $vehicle_updated && $driver_updated) {
            $this->db->trans_commit();
            return ['success' => true];
        } else {
            $this->db->trans_rollback();
            return ['error' => 'Approval gagal'];
        }
    }

    // application/models/Order_model.php
    public function autoUpdateStatus_driver_kendaraan()
    {
        $today = date('Y-m-d');
        $now = date('H:i:s');

        echo "<pre>";
        echo "PHP Now: $now\n";
        echo "PHP Today: $today\n";
        $this->db->where('status', 'approved');
        $this->db->where('tanggal_pakai <=', $today);
        $this->db->where('waktu_selesai <', $now);
        $query = $this->db->get('PK_pesanan');
        echo $this->db->last_query() . "\n";
        print_r($query->result());
        echo "</pre>";

        // Find all approved pesanan whose usage is done
        $this->db->where('status', 'approved');
        $this->db->where('tanggal_pakai <=', $today);
        $this->db->where('waktu_selesai <', $now);
        $orders = $this->db->get('PK_pesanan')->result();

        foreach ($orders as $order) {
            // Release kendaraan
            if ($order->kendaraan) {
                $this->db->where('id', $order->kendaraan)
                    ->where('status', 'unavailable')
                    ->update('PK_kendaraan', [
                        'status' => 'available',
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
            }
            // Release driver
            if ($order->driver) {
                $this->db->where('id', $order->driver)
                    ->where('status', 'unavailable')
                    ->update('PK_driver', [
                        'status' => 'available',
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
            }
            // Optionally, set pesanan to 'done'
            $this->db->where('id', $order->id)
                ->update('PK_pesanan', [
                    'status' => 'done',
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }
    }
}