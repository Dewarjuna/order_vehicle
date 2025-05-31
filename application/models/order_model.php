<?php
class Order_model extends CI_Model {

    // Table names as properties for easier maintenance.
    protected $table_pesanan = 'PK_pesanan';
    protected $table_user    = 'PK_user';

    public function __construct() {
        parent::__construct();
    }

    // Simple CRUD for orders and users
    public function getpesanan_all()             { return $this->db->get($this->table_pesanan)->result(); }
    public function getpesanan_by_id($id)        { return $this->db->get_where($this->table_pesanan, ['id' => $id])->row(); }
    public function getusers_all()               { return $this->db->get($this->table_user)->result(); }
    public function getusers_by_id($id)          { return $this->db->get_where($this->table_user, ['id' => $id])->row(); }

    /**
     * Create pesanan with created_at/updated_at timestamps.
     */
    public function create($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table_pesanan, $data);
        return $this->db->insert_id(); // Return new order's ID for confirmation/redirect
    }

    public function insert_order($data) { 
        return $this->db->insert($this->table_pesanan, $data); }
    public function getpesanan_by_pemesan($pemesan) {
         return $this->db->get_where($this->table_pesanan, ['pemesan' => $pemesan])->result(); }
    public function delete($id) { 
        return $this->db->delete($this->table_pesanan, array('id' => $id)); }

    /**
     * Update pesanan record and updated_at timestamp.
     */
    public function update($id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        return $this->db->update($this->table_pesanan, $data);
    }

    /**
     * Approve order; assign kendaraan (vehicle) and driver.
     */
    public function approve_order($id, $kendaraan, $driver) {
        $data = [
            'status' => 'approved',
            'kendaraan' => $kendaraan,
            'driver' => $driver,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $this->db->where('id', $id);
        return $this->db->update($this->table_pesanan, $data);
    }

    // Statistical helper methods, used for dashboard/analytics:
    public function count_orders_by_month($month) {
        $this->db->where("LEFT(tanggal_pesanan, 7) = '$month'", NULL, FALSE);
        return $this->db->count_all_results($this->table_pesanan);
    }
    public function count_orders_by_month_status($month = [], $status ="null") {
        if (empty($month)) return 0; // No month provided, return 0
        $this->db->where("LEFT(tanggal_pesanan, 7) = '$month'");
        if ($status) $this->db->where('status', $status);
        return $this->db->count_all_results($this->table_pesanan);
    }
    public function count_user_orders_by_month($month, $pemesan) {
        $this->db->where("LEFT(tanggal_pesanan, 7) = '$month'", NULL, FALSE);
        $this->db->where('pemesan', $pemesan);
        return $this->db->count_all_results($this->table_pesanan);
    }

    /**
     * Count orders for multiple months
     */
    public function count_orders_by_months($months = []) {
        if (empty($months)) return 0;
        $this->db->where_in("LEFT(tanggal_pesanan, 7)", $months);
        return $this->db->count_all_results($this->table_pesanan);
    }

    /**
     * Count orders with specific status for multiple months
     */
    public function count_orders_by_months_status($months = [], $status = null) {
        if (empty($months)) return 0;
        $this->db->where_in("LEFT(tanggal_pesanan, 7)", $months);
        if ($status) $this->db->where('status', $status);
        return $this->db->count_all_results($this->table_pesanan);
    }

    /**
     * Get order (and related kendaraan/driver info) by ID.
     */
    public function getpesanan_with_kendaraan_by_id($id) {
        $this->db->select('p.*, k.no_pol, k.nama_kendaraan, d.nama as nama_driver')
            ->from('PK_pesanan p')
            ->join('PK_kendaraan k', 'p.kendaraan = k.id', 'left')
            ->join('PK_driver d', 'p.driver = d.id', 'left')
            ->where('p.id', $id);
        return $this->db->get()->row();
    }
    /**
     * Get all orders with kendaraan and driver details.
     */
    public function getpesanan_all_with_kendaraan() {
        $this->db->select('p.*, k.no_pol, k.nama_kendaraan, d.nama as nama_driver')
            ->from('PK_pesanan p')
            ->join('PK_kendaraan k', 'p.kendaraan = k.id', 'left')
            ->join('PK_driver d', 'p.driver = d.id', 'left');
        return $this->db->get()->result();
    }
    /**
     * Get all orders for current user including kendaraan/driver.
     */
    public function getpesanan_by_pemesan_with_kendaraan($pemesan) {
        $this->db->select('p.*, k.no_pol, k.nama_kendaraan, d.nama as nama_driver')
            ->from('PK_pesanan p')
            ->join('PK_kendaraan k', 'p.kendaraan = k.id', 'left')
            ->join('PK_driver d', 'p.driver = d.id', 'left')
            ->where('p.pemesan', $pemesan);
        return $this->db->get()->result();
    }

    /**
     * Atomically approve an order and mark vehicle/driver unavailable.
     * Rolls back if either is not available at approval time.
     */
    public function approve_full_order($order_id, $kendaraan_id, $driver_id) {
        $this->db->trans_begin();

        // Re-validate that kendaraan & driver are still available
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

        // Proceed with approval and set resources as unavailable
        $pesananData = [
            'status'    => 'approved',
            'kendaraan' => $kendaraan_id,
            'driver'    => $driver_id,
            'updated_at'=> date('Y-m-d H:i:s')
        ];
        $order_updated = $this->db->where('id', $order_id)->update('PK_pesanan', $pesananData);
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

    /**
     * Find and update orders whose scheduled usage is completed:
     * - Mark kendaraan & driver as available again
     * - Mark pesanan as done makmdimcjomc
     * 
     * This should run periodically as a cron or on user interaction.
     */
    public function autoUpdateStatus_driver_kendaraan()
    {
        $today = date('Y-m-d');
        $now = date('H:i:s');

        /* echo "<pre>";
        echo "PHP Now: $now\n";
        echo "PHP Today: $today\n";
        $this->db->where('status', 'approved');
        $this->db->where('tanggal_pakai <=', $today);
        $this->db->where('waktu_selesai <', $now);
        $query = $this->db->get('PK_pesanan');
        echo $this->db->last_query() . "\n";
        print_r($query->result());
        echo "</pre>"; */

        // Find all orders that are still 'approved' but their usage time is over
        $this->db->where('status', 'approved');
        $this->db->where('tanggal_pakai <=', $today);
        $this->db->where('waktu_selesai <', $now);
        $orders = $this->db->get('PK_pesanan')->result();

        foreach ($orders as $order) {
            // Release kendaraan if assigned and still unavailable
            if ($order->kendaraan) {
                $this->db->where('id', $order->kendaraan)
                    ->where('status', 'unavailable')
                    ->update('PK_kendaraan', [
                        'status' => 'available',
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
            }
            // Release driver if assigned and still unavailable
            if ($order->driver) {
                $this->db->where('id', $order->driver)
                    ->where('status', 'unavailable')
                    ->update('PK_driver', [
                        'status' => 'available',
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
            }
            // Mark pesanan as done
            $this->db->where('id', $order->id)
                ->update('PK_pesanan', [
                    'status' => 'done',
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }
    }

    /**
     * Automatically update orders that are pending and past their usage date
     * to 'no confirmation' status.
     */
    public function autoUpdateNoConfirmationStatus()
    {
        $today = date('Y-m-d');
        $this->db->where('status', 'pending');
        $this->db->where('tanggal_pakai <=', $today);
        $orders = $this->db->get('PK_pesanan')->result();

        foreach ($orders as $order) {
            $this->db->where('id', $order->id)
                ->update('PK_pesanan', [
                    'status' => 'no confirmation',
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
        }
    }

    /**
     * Reject order and mark it as 'rejected'.
     */
    public function reject_order($id)
    {
        $data = [
            'status' => 'rejected',
            'updated_at' => date('Y-m-d H:i:s')
        ];
        $this->db->where('id', $id);
        return $this->db->update($this->table_pesanan, $data);
    }

    // Add this method to your Order_model class
public function get_orders_by_status($status) {
    $this->db->select('p.*, k.no_pol, k.nama_kendaraan, d.nama as nama_driver')
        ->from('PK_pesanan p')
        ->join('PK_kendaraan k', 'p.kendaraan = k.id', 'left')
        ->join('PK_driver d', 'p.driver = d.id', 'left')
        ->where('p.status', $status)
        ->order_by('p.tanggal_pakai', 'DESC');
    return $this->db->get()->result();
}
}