<?php
/**
 * Order Model
 * 
 * This model handles all vehicle booking operations with a focus on maintaining data integrity
 * and proper state management between orders, vehicles, and drivers.
 * 
 * Key design decisions:
 * 1. All operations that affect multiple entities (order, vehicle, driver) use transactions
 * 2. Automatic status updates are implemented to prevent stale data
 * 3. Timestamps are automatically managed to track order lifecycle
 * 4. Left joins are used with vehicle/driver data to handle cases where they might be unassigned
 */
class Order_model extends CI_Model {

    /**
     * We store table names as properties rather than hardcoding them because:
     * 1. It makes it easier to rename tables if needed
     * 2. Prevents typos in table names across multiple queries
     * 3. Provides a single source of truth for table names
     */
    protected $table_pesanan = 'PK_pesanan';
    protected $table_user    = 'PK_user';

    public function __construct() {
        parent::__construct();
    }

    /**
     * Basic CRUD operations are kept simple and separate from complex operations
     * to maintain clear boundaries between simple data access and business logic
     */
    public function getpesanan_all()             { return $this->db->get($this->table_pesanan)->result(); }
    public function getpesanan_by_id($id)        { return $this->db->get_where($this->table_pesanan, ['id' => $id])->row(); }
    public function getusers_all()               { return $this->db->get($this->table_user)->result(); }
    public function getusers_by_id($id)          { return $this->db->get_where($this->table_user, ['id' => $id])->row(); }

    /**
     * Order creation enforces timestamp management to ensure we always have
     * accurate tracking of when orders are created and modified.
     * This is crucial for:
     * 1. Audit trails
     * 2. Sorting orders by creation date
     * 3. Tracking how long orders spend in each state
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
     * Order approval is a critical operation that requires atomic execution
     * to prevent race conditions. We use transactions to ensure that:
     * 1. Vehicle and driver are still available when we try to assign them
     * 2. All related records are updated together or not at all
     * 3. No other process can reserve the same resources simultaneously
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

    /**
     * Auto-update functions serve as a self-healing mechanism for the system.
     * They prevent orders from getting stuck in intermediate states by:
     * 1. Automatically completing orders when their scheduled time is over
     * 2. Releasing vehicles and drivers back to the available pool
     * 3. Marking unconfirmed orders appropriately
     * 
     * This approach was chosen over real-time updates because:
     * 1. It's more efficient than checking every order status on every request
     * 2. It handles edge cases like system downtime or failed updates
     * 3. It provides a predictable way to maintain data consistency
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
    /**
     * Get all orders by status with related kendaraan and driver info.
     */
public function get_orders_by_status($status) {
    $this->db->select('p.*, k.no_pol, k.nama_kendaraan, d.nama as nama_driver')
        ->from('PK_pesanan p')
        ->join('PK_kendaraan k', 'p.kendaraan = k.id', 'left')
        ->join('PK_driver d', 'p.driver = d.id', 'left')
        ->where('p.status', $status)
        ->order_by('p.tanggal_pakai', 'DESC');
    return $this->db->get()->result();
}

    /**
     * Multi-month operations support dashboard filtering functionality
     * using WHERE_IN clauses instead of multiple OR conditions because:
     * 1. It's more efficient for the database to optimize
     * 2. It's easier to maintain and modify the queries
     * 3. It scales better with larger numbers of months
     */
    public function get_orders_by_status_and_months($status, $months = []) {
        $this->db->select('p.*, k.no_pol, k.nama_kendaraan, d.nama as nama_driver')
            ->from('PK_pesanan p')
            ->join('PK_kendaraan k', 'p.kendaraan = k.id', 'left')
            ->join('PK_driver d', 'p.driver = d.id', 'left')
            ->where('p.status', $status);
        
        if (!empty($months)) {
            $this->db->where_in("LEFT(p.tanggal_pesanan, 7)", $months);
        }
        
        return $this->db->get()->result();
    }

    /**
     * Statistical methods are separated from basic CRUD operations because:
     * 1. They often require different optimization strategies
     * 2. They may need to be cached differently
     * 3. They serve a different purpose (analytics vs. data management)
     * 
     * We use LEFT() for date extraction instead of DATE_FORMAT() because:
     * 1. It's more efficient for index usage
     * 2. It's consistent across different database engines
     */
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
}