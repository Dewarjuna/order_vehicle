<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Order controller: handles reservation requests, edits, admin approval, and reporting.
 * User/role checks and error handling guard sensitive operations.
 */
class Order extends MY_Controller {

    public function __construct() 
    {
        parent::__construct();
        $this->load->model('order_model');
        // Defensive check: possible autoload/model typo or deployment error.
        if (!isset($this->order_model)) {
            log_message('error', 'Order_model not loaded.');
            show_error('Order_model failed to load. Please check the model file and loading process.');
        }
    }

    public function create() {
        $users = $this->order_model->getusers_all();
        $this->load->view('order_form', ['users' => $users]);
    }

    /**
     * Utility: Converts user-entered date to DB format.
     */
    private function convert_date($date) {
        $d = DateTime::createFromFormat('d-m-Y', $date);
        return $d ? $d->format('Y-m-d') : null;
    }

    /**
     * Handle reservation form submission: validates and saves;
     * redisplays form on error, or shows a success message.
     */
    public function submit() {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('tanggal_pesanan', 'Tanggal Pesanan', 'required');
        // ... other rules for completeness ...
        $this->form_validation->set_rules('jumlah_orang', 'Jumlah Orang', 'required|integer');

        if ($this->form_validation->run() == FALSE) {
            // On validation error, show form again
            echo validation_errors();
            $users = $this->order_model->getusers_all();
            $this->load->view('order_form', ['users' => $users]);
            return;
        }

        $tanggal_pesanan = $this->convert_date($this->input->post('tanggal_pesanan'));
        $tanggal_pakai   = $this->convert_date($this->input->post('tanggal_pakai'));
        $data = array(
            // All relevant reservation data
            'tanggal_pesanan' => $tanggal_pesanan,
            'nomor_karyawan'  => $this->input->post('nomor_karyawan'),
            'nama'            => $this->input->post('nama'),
            'divisi'          => $this->input->post('divisi'),
            'tujuan'          => $this->input->post('tujuan'),
            'tanggal_pakai'   => $tanggal_pakai,
            'waktu_mulai'     => $this->input->post('waktu_mulai'),
            'waktu_selesai'   => $this->input->post('waktu_selesai'),
            'keperluan'       => $this->input->post('keperluan'),
            'kendaraan'       => $this->input->post('kendaraan'),
            'jumlah_orang'    => $this->input->post('jumlah_orang'),
            'pemesan'         => $this->session->userdata('nama')
        );

        $order_id = $this->order_model->create($data);

        if ($order_id) {
            $users = $this->order_model->getusers_all();
            $success_message = "Pesanan kendaraan anda telah berhasil disimpan.";
            $this->load->view('order_form', ['users' => $users, 'success_message' => $success_message, 'order_id' => $order_id]);
        } else {
            // If DB error, help debugging: show last query and error
            echo $this->db->last_query();
            print_r($this->db->error());
            log_message('error', 'Gagal menyimpan pesanan.');
            show_error('Gagal menyimpan pesanan. Silakan coba lagi.');
        }
    }

    /**
     * Display current user's reservations.
     */
    public function order_user() {
        $pemesan = $this->session->userdata('nama');
        $data['pesanan_list'] = $this->order_model->getpesanan_by_pemesan_with_kendaraan($pemesan);
        $this->load->view('details/order_user', $data);
    }

    public function delete($id) {
        // Only delete if record exists and is owned by user (implicit in model)
        if ($this->order_model->delete($id)) {
            redirect('order/detail');
        } else {
            show_error('Gagal menghapus pesanan.');
        }
    }

    public function edit($id)
    {
        $pesanan = $this->order_model->getpesanan_by_id($id);
        $users = $this->order_model->getusers_all();
        // Enforce ownership: Only allow edit if user owns the order.
        if (!$pesanan || $pesanan->pemesan !== $this->session->userdata('nama')) {
            show_error('Anda tidak memiliki akses untuk mengedit pesanan ini.');
            return;
        }
        $data['pesanan'] = $pesanan;
        $data['users'] = $users;
        $this->load->view('details/order_edit', $data);
    }

    public function update($id)
    {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('tanggal_pesanan', 'Tanggal Pesanan', 'required');
        $this->form_validation->set_rules('jumlah_orang', 'Jumlah Orang', 'required|integer');

        if ($this->form_validation->run() == FALSE) {
            $pesanan = $this->order_model->getpesanan_by_id($id);
            $users = $this->order_model->getusers_all();
            $this->load->view('details/order_edit', ['pesanan' => $pesanan, 'users' => $users]);
            return;
        }

        $tanggal_pesanan = $this->convert_date($this->input->post('tanggal_pesanan'));
        $tanggal_pakai   = $this->convert_date($this->input->post('tanggal_pakai'));
        $data = array(
            // Edit: user can change all these fields; see submit().
            'tanggal_pesanan' => $tanggal_pesanan,
            'nomor_karyawan'  => $this->input->post('nomor_karyawan'),
            'nama'            => $this->input->post('nama'),
            'divisi'          => $this->input->post('divisi'),
            'tujuan'          => $this->input->post('tujuan'),
            'tanggal_pakai'   => $tanggal_pakai,
            'waktu_mulai'     => $this->input->post('waktu_mulai'),
            'waktu_selesai'   => $this->input->post('waktu_selesai'),
            'keperluan'       => $this->input->post('keperluan'),
            'kendaraan'       => $this->input->post('kendaraan'),
            'jumlah_orang'    => $this->input->post('jumlah_orang'),
            'pemesan'         => $this->session->userdata('nama')
        );

        if ($this->order_model->update($id, $data)) {
            redirect('order/detail');
        } else {
            // For debugging DB problems
            echo "Database error:<br>";
            echo $this->db->last_query();
            print_r($this->db->error());
            log_message('error', 'Gagal memperbarui pesanan.');
            show_error('Gagal memperbarui pesanan. Silakan coba lagi.');
        }
    }

    /**
     * Show a single reservation, enforcing admin or ownership access.
     */
    public function single($id) {
        $pesanan = $this->order_model->getpesanan_with_kendaraan_by_id($id);
        if (!$pesanan ||
            ($this->user_session['role'] !== 'admin' && $pesanan->pemesan !== $this->session->userdata('nama'))
        ) {
            show_error('Anda tidak memiliki akses untuk melihat detail pesanan ini.');
            return;
        }
        $data['pesanan'] = $pesanan;
        $this->load->view('details/order_single', $data);
    }

    /**
     * Admin: View all pending orders, with available vehicles/drivers for approval UI.
     */
    public function pending_orders() {
        if ($this->user_session['role'] !== 'admin') {
            show_error('Access denied.');
            return;
        }

        $this->db->where('status', 'pending');
        $this->db->where('kendaraan IS NULL', null, false);
        $data['pending_orders'] = $this->db->get('PK_pesanan')->result();

        // Get all possible vehicles/drivers for assignment (modal selectors).
        $this->load->model('vehicle_model');
        $kendaraan_options = [];
        foreach ($this->vehicle_model->get_available() as $vehicle) {
            $kendaraan_options[] = [
                'id' => $vehicle->id,
                'label' => $vehicle->nama_kendaraan . " [{$vehicle->no_pol}]"
            ];
        }
        $data['kendaraan_options'] = $kendaraan_options;

        $this->load->model('driver_model');
        $driver_options = [];
        foreach ($this->driver_model->get_available() as $driver) {
            $driver_options[] = [
                'id' => $driver->id,
                'label' => $driver->nama
            ];
        }
        $data['driver_options'] = $driver_options;

        $this->load->view('admin/pending_list', $data);
    }

    /**
     * Admin: Loads approval form for given order.
     */
    public function approve($id)
    {
        if ($this->user_session['role'] !== 'admin') {
            show_error('Access denied.');
            return;
        }
        $order = $this->order_model->getpesanan_by_id($id);
        if (!$order || $order->status !== 'pending') {
            // If already processed or missing, don't allow approve.
            show_error('Pesanan tidak ditemukan atau sudah disetujui.');
            return;
        }
        $this->load->model('vehicle_model');
        $available_vehicles = $this->vehicle_model->get_available();
        $data['kendaraan_options'] = [];
        foreach ($available_vehicles as $vehicle) {
            $data['kendaraan_options'][] = [
                'id' => $vehicle->id,
                'label' => $vehicle->nama_kendaraan . " [{$vehicle->no_pol}]"
            ];
        }
        $this->load->model('driver_model');
        $available_drivers = $this->driver_model->get_available();
        $data['driver_options'] = [];
        foreach ($available_drivers as $driver) {
            $data['driver_options'][] = [
                'id' => $driver->id,
                'label' => $driver->nama
            ];
        }
        $data['order'] = $order;
        $this->load->view('admin/approve_form', $data);
    }

    /**
     * Admin: AJAX endpoint for approving an order (driver+vehicle assignment).
     * Why: Used by frontend to update order approval state inline, without reload. 
     * Any transactional/rollback logic is handled in the model itself for clarity and single-responsibility.
     */
    public function do_approve_ajax($id)
    {
        if ($this->user_session['role'] !== 'admin') {
            echo json_encode(['status' => 'error', 'message' => 'Access denied.']); exit;
        }
        $kendaraan_id = (int)$this->input->post('kendaraan');
        $driver_id = (int)$this->input->post('driver');
        $result = $this->order_model->approve_full_order($id, $kendaraan_id, $driver_id);

        if (isset($result['success'])) {
            echo json_encode(['status' => 'success', 'message' => 'Pesanan berhasil disetujui.']);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => isset($result['error']) ? $result['error'] : 'Gagal menyetujui pesanan.'
            ]);
        }
    }

    /**
     * Admin: AJAX endpoint to reject a pending order.
     * Why: Allows real-time feedback for users; used by admin JS interface.
     */
    public function reject_ajax($id)
    {
        if ($this->user_session['role'] !== 'admin') {
            echo json_encode(['status' => 'error', 'message' => 'Access denied.']); exit;
        }
        $order = $this->order_model->getpesanan_by_id($id);
        if (!$order || $order->status !== 'pending') {
            echo json_encode(['status' => 'error', 'message' => 'Pesanan tidak ditemukan atau sudah diproses.']);
            exit;
        }
        if ($this->order_model->reject_order($id)) {
            echo json_encode(['status' => 'success', 'message' => 'Pesanan berhasil ditolak.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menolak pesanan.']);
        }
    }

    /**
     * Admin: Paginated, filterable list of all bookings for monitoring/analysis.
     * Why: Enables management to review fleet use historically or operationally.
     */
    public function order_report() {
        if ($this->user_session['role'] !== 'admin') {
            show_error('Access denied.');
            return;
        }
        $this->load->library('pagination');
        $date_from = $this->input->get('date_from');
        $date_to = $this->input->get('date_to');
        // 2-step approach: first, get total count for pagination, then page data
        $this->db->select('p.id')
            ->from('PK_pesanan p')
            ->join('PK_kendaraan k', 'p.kendaraan = k.id', 'left')
            ->where_in('p.status', ['approved', 'done', 'no confirmation', 'rejected']);
        // Apply date filter if set (filtering may be slow if not indexed)
        if ($date_from && $date_to) {
            $this->db->where('p.tanggal_pakai >=', $date_from);
            $this->db->where('p.tanggal_pakai <=', $date_to);
        } elseif ($date_from) {
            $this->db->where('p.tanggal_pakai', $date_from);
        }
        $total_rows = $this->db->count_all_results();

        $config['base_url'] = site_url('order/order_report');
        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['total_rows'] = $total_rows;
        $page = $this->input->get('page') ? (int)$this->input->get('page') : 0;
        $this->pagination->initialize($config);

        // Get actual record data for current page; join all related info for full report
        $this->db->select('p.*, k.no_pol, k.nama_kendaraan, d.nama as nama_driver')
            ->from('PK_pesanan p')
            ->join('PK_kendaraan k', 'p.kendaraan = k.id', 'left')
            ->join('PK_driver d', 'p.driver = d.id', 'left')
            ->where_in('p.status', ['approved', 'done', 'no confirmation', 'rejected']);
        if ($date_from && $date_to) {
            $this->db->where('p.tanggal_pakai >=', $date_from);
            $this->db->where('p.tanggal_pakai <=', $date_to);
        } elseif ($date_from) {
            $this->db->where('p.tanggal_pakai', $date_from);
        }
        $this->db->order_by('p.tanggal_pakai', 'DESC');
        $this->db->limit($config['per_page'], $page);

        $orders = $this->db->get()->result();
        $data['orders'] = $orders;
        $data['pagination'] = $this->pagination->create_links();
        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;

        $this->load->view('admin/order_report', $data);
    }
}