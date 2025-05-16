<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Handles CRUD, own listings, and approval of orders. 
 * Approval/report pages are restricted to admin.
 */
class Order extends MY_Controller {

    public $order_model;

    public function __construct() 
    {
        parent::__construct();
        $this->load->model('order_model');
        // Defensive: Verify model loaded; logs and errors if not.
        if (!isset($this->order_model)) {
            log_message('error', 'Order_model not loaded.');
            show_error('Order_model failed to load. Please check the model file and loading process.');
        }
    }

    /**
     * Show form to create new order.
     */
    public function create() {
        $users = $this->order_model->getusers_all();
        $this->load->view('order_form', ['users' => $users]);
    }

    /**
     * Utility: Converts user-friendly date to storage date.
     */
    private function convert_date($date) {
        $d = DateTime::createFromFormat('d-m-Y', $date);
        return $d ? $d->format('Y-m-d') : null;
    }

    /**
     * Handle submission for new reservations. Validates and inserts.
     */
    public function submit() {
        $this->load->helper('form');
        $this->load->library('form_validation');
        // Standard validation; any error reloads form.
        $this->form_validation->set_rules('tanggal_pesanan', 'Tanggal Pesanan', 'required');
        // ... similar rules for all fields ...
        $this->form_validation->set_rules('jumlah_orang', 'Jumlah Orang', 'required|integer');

        if ($this->form_validation->run() == FALSE) {
            echo validation_errors();
            $users = $this->order_model->getusers_all();
            $this->load->view('order_form', ['users' => $users]);
            return;
        }

        $tanggal_pesanan = $this->convert_date($this->input->post('tanggal_pesanan'));
        $tanggal_pakai   = $this->convert_date($this->input->post('tanggal_pakai'));
        $data = array(
            // All sanitized inputs
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
            // Show detailed DB error if insert fails for troubleshooting.
            echo $this->db->last_query();
            print_r($this->db->error());
            log_message('error', 'Gagal menyimpan pesanan.');
            show_error('Gagal menyimpan pesanan. Silakan coba lagi.');
        }
    }

    /**
     * List only the current user's orders.
     */
    public function detail() {
        $pemesan = $this->session->userdata('nama');
        $data['pesanan_list'] = $this->order_model->getpesanan_by_pemesan_with_kendaraan($pemesan);
        $this->load->view('details/order_detail', $data);
    }

    /**
     * Remove reservation, after confirming ownership.
     */
    public function delete($id) {
        if ($this->order_model->delete($id)) {
            redirect('order/detail');
        } else {
            show_error('Gagal menghapus pesanan.');
        }
    }

    /**
     * Load edit page, owner-only.
     */
    public function edit($id)
    {
        $pesanan = $this->order_model->getpesanan_by_id($id);
        $users = $this->order_model->getusers_all();
        // Disallow for non-owners
        if (!$pesanan || $pesanan->pemesan !== $this->session->userdata('nama')) {
            show_error('Anda tidak memiliki akses untuk mengedit pesanan ini.');
            return;
        }
        $data['pesanan'] = $pesanan;
        $data['users'] = $users;
        $this->load->view('details/order_edit', $data);
    }

    /**
     * Save edits to a reservation (validates, updates).
     */
    public function update($id)
    {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('tanggal_pesanan', 'Tanggal Pesanan', 'required'); // etc
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
            // All sanitized inputs
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
            echo "Database error:<br>";
            echo $this->db->last_query();
            print_r($this->db->error());
            log_message('error', 'Gagal memperbarui pesanan.');
            show_error('Gagal memperbarui pesanan. Silakan coba lagi.');
        }
    }

    /**
     * Show details (admin or owner only).
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
     * List all pending orders for admin approval.
     */
    public function pending_orders() {
        if ($this->user_session['role'] !== 'admin') {
            show_error('Access denied.');
            return;
        }
        $this->db->where('status', 'pending');
        $this->db->where('kendaraan IS NULL', null, false);
        $data['pending_orders'] = $this->db->get('PK_pesanan')->result();
        $this->load->view('admin/pending_list', $data);
    }

    /**
     * Admin: Show approval form, vehicle and driver options available.
     */
    public function approve($id)
    {
        if ($this->user_session['role'] !== 'admin') {
            show_error('Access denied.');
            return;
        }
        $order = $this->order_model->getpesanan_by_id($id);
        if (!$order || $order->status !== 'pending') {
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
     * Admin: Process approve form submission (actually assigns driver+vehicle).
     * All transaction and rollback logic is in the model for cleanliness.
     */
    public function do_approve($id)
    {
        if ($this->user_session['role'] !== 'admin') {
            show_error('Access denied.');
            return;
        }
        $kendaraan_id = (int)$this->input->post('kendaraan');
        $driver_id = (int)$this->input->post('driver');
        $result = $this->order_model->approve_full_order($id, $kendaraan_id, $driver_id);

        if (isset($result['success'])) {
            $this->session->set_flashdata('success', 'Pesanan berhasil disetujui.');
            redirect('order/pending_orders');
        } else {
            $this->session->set_flashdata('error', isset($result['error']) ? $result['error'] : 'Gagal menyetujui pesanan.');
            redirect('order/approve/' . $id);
        }
    }

    /**
     * Admin: Paginated, filterable list of all bookings for reporting.
     */
    public function order_report() {
        if ($this->user_session['role'] !== 'admin') {
            show_error('Access denied.');
            return;
        }
        $this->load->library('pagination');
        $date_from = $this->input->get('date_from');
        $date_to = $this->input->get('date_to');
        // Get filtered rows count for pagination
        $this->db->select('p.id')
            ->from('PK_pesanan p')
            ->join('PK_kendaraan k', 'p.kendaraan = k.id', 'left')
            ->where_in('p.status', ['approved', 'done']);
        if ($date_from && $date_to) {
            $this->db->where('p.tanggal_pakai >=', $date_from);
            $this->db->where('p.tanggal_pakai <=', $date_to);
        } elseif ($date_from) {
            $this->db->where('p.tanggal_pakai', $date_from);
        }
        $total_rows = $this->db->count_all_results();
        // Set up pagination config
        $config['base_url'] = site_url('order/order_report');
        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['total_rows'] = $total_rows;
        $page = $this->input->get('page') ? (int)$this->input->get('page') : 0;
        $this->pagination->initialize($config);
        // Now get paged (and filtered) orders for view
        $this->db->select('p.*, k.no_pol, k.nama_kendaraan, d.nama as nama_driver')
            ->from('PK_pesanan p')
            ->join('PK_kendaraan k', 'p.kendaraan = k.id', 'left')
            ->join('PK_driver d', 'p.driver = d.id', 'left')
            ->where_in('p.status', ['approved', 'done']);
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