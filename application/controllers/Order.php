<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller for managing Orders (PK_pesanan)
 * Handles CRUD, approval, and reporting functionality.
 */
class Order extends MY_Controller {

    public function __construct() 
    {
        parent::__construct();
        // Load order model
        $this->load->model('order_model');

        // Basic error check for model
        if (!isset($this->order_model)) {
            log_message('error', 'Order_model not loaded.');
            show_error('Order_model failed to load. Please check the model file and loading process.');
        } else {
            log_message('debug', 'Order_model successfully loaded.');
        }
    }

    /**
     * Show the "create new order" form
     */
    public function create() {
        $users = $this->order_model->getusers_all();
        $this->load->view('order_form', ['users' => $users]);
    }

    /**
     * Helper: Convert date from d-m-Y to Y-m-d
     */
    private function convert_date($date) {
        $d = DateTime::createFromFormat('d-m-Y', $date);
        return $d ? $d->format('Y-m-d') : null;
    }

    /**
     * Handle order creation form submission.
     * Validates, processes, and inserts a new order.
     */
    public function submit() {
        $this->load->helper('form');
        $this->load->library('form_validation');

        // Set validation rules for order fields
        $this->form_validation->set_rules('tanggal_pesanan', 'Tanggal Pesanan', 'required');
        $this->form_validation->set_rules('nomor_karyawan', 'Nomor Karyawan', 'required');
        $this->form_validation->set_rules('nama', 'Nama Karyawan', 'required');
        $this->form_validation->set_rules('divisi', 'Divisi', 'required');
        $this->form_validation->set_rules('tujuan', 'Tujuan', 'required');
        $this->form_validation->set_rules('tanggal_pakai', 'Tanggal Pakai', 'required');
        $this->form_validation->set_rules('waktu_mulai', 'Waktu Mulai', 'required');
        $this->form_validation->set_rules('waktu_selesai', 'Waktu Selesai', 'required');
        $this->form_validation->set_rules('keperluan', 'Keperluan', 'required');
        $this->form_validation->set_rules('jumlah_orang', 'Jumlah Orang', 'required|integer');

        // On failure, reload form with errors
        if ($this->form_validation->run() == FALSE) {
            echo validation_errors();
            $users = $this->order_model->getusers_all();
            $this->load->view('order_form', ['users' => $users]);
            return;
        }

        // Prepare data for insert
        $tanggal_pesanan = $this->convert_date($this->input->post('tanggal_pesanan'));
        $tanggal_pakai   = $this->convert_date($this->input->post('tanggal_pakai'));

        $data = array(
            'tanggal_pesanan' => $tanggal_pesanan,
            'nomor_karyawan'  => $this->input->post('nomor_karyawan'),
            'nama'   => $this->input->post('nama'),
            'divisi'          => $this->input->post('divisi'),
            'tujuan'          => $this->input->post('tujuan'),
            'tanggal_pakai'   => $tanggal_pakai,
            'waktu_mulai'     => $this->input->post('waktu_mulai'),
            'waktu_selesai'   => $this->input->post('waktu_selesai'),
            'keperluan'       => $this->input->post('keperluan'),
            'kendaraan'       => $this->input->post('kendaraan'),
            'jumlah_orang'    => $this->input->post('jumlah_orang'),
            'pemesan'    => $this->session->userdata('nama')
        );

        // Insert order; on success show confirmation, else error
        if ($this->order_model->insert_order($data)) {
            $this->load->view('order_success', $data);
        } else {
            echo $this->db->last_query();
            echo '<br>';
            print_r($this->db->error());
            log_message('error', 'Gagal menyimpan pesanan.');
            show_error('Gagal menyimpan pesanan. Silakan coba lagi.');
        }
    }

    /**
     * List all orders for the current user (pemesan)
     */
    public function detail() {
        $pemesan = $this->session->userdata('nama');
        $data['pesanan_list'] = $this->order_model->getpesanan_by_pemesan_with_kendaraan($pemesan);
        $this->load->view('details/order_detail', $data);
    }

    /**
     * Delete order by ID, then redirect to list
     */
    public function delete($id) {
        if ($this->order_model->delete($id)) {
            redirect('order/detail');
        } else {
            show_error('Gagal menghapus pesanan.');
        }
    }

    /**
     * Load the edit form for an order. Only the order owner can edit.
     */
    public function edit($id)
    {
        $pesanan = $this->order_model->getpesanan_by_id($id);
        $users = $this->order_model->getusers_all();

        // Prevent editing by other users
        if (!$pesanan || $pesanan->pemesan !== $this->session->userdata('nama')) {
            show_error('Anda tidak memiliki akses untuk mengedit pesanan ini.');
            return;
        }

        $data['pesanan'] = $pesanan;
        $data['users']   = $users;
        $this->load->view('details/order_edit', $data);
    }

    /**
     * Handle edit form submission for an order.
     */
    public function update($id)
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        // Reuse same validation rules as creation
        $this->form_validation->set_rules('tanggal_pesanan', 'Tanggal Pesanan', 'required');
        $this->form_validation->set_rules('nomor_karyawan', 'Nomor Karyawan', 'required');
        $this->form_validation->set_rules('nama', 'Nama Karyawan', 'required');
        $this->form_validation->set_rules('divisi', 'Divisi', 'required');
        $this->form_validation->set_rules('tujuan', 'Tujuan', 'required');
        $this->form_validation->set_rules('tanggal_pakai', 'Tanggal Pakai', 'required');
        $this->form_validation->set_rules('waktu_mulai', 'Waktu Mulai', 'required');
        $this->form_validation->set_rules('waktu_selesai', 'Waktu Selesai', 'required');
        $this->form_validation->set_rules('keperluan', 'Keperluan', 'required');
        $this->form_validation->set_rules('jumlah_orang', 'Jumlah Orang', 'required|integer');

        // On validation failure, reload form
        if ($this->form_validation->run() == FALSE) {
            $pesanan = $this->order_model->getpesanan_by_id($id);
            $users = $this->order_model->getusers_all();
            $this->load->view('details/order_edit', [
                'pesanan' => $pesanan,
                'users' => $users
            ]);
            return;
        }

        // Prepare data for update
        $tanggal_pesanan = $this->convert_date($this->input->post('tanggal_pesanan'));
        $tanggal_pakai   = $this->convert_date($this->input->post('tanggal_pakai'));

        $data = array(
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

        // Save update to database
        if ($this->order_model->update($id, $data)) {
            redirect('order/detail');
        } else {
            echo "Database error:<br>";
            echo $this->db->last_query();
            echo '<br>';
            print_r($this->db->error());
            log_message('error', 'Gagal memperbarui pesanan.');
            show_error('Gagal memperbarui pesanan. Silakan coba lagi.');
        }
    }

    /**
     * Show details of a single order (admin or owner only)
     */
    public function single($id) {
        $pesanan = $this->order_model->getpesanan_with_kendaraan_by_id($id);
        // Admins or owners only
        if (
            !$pesanan ||
            ($this->user_session['role'] !== 'admin' && $pesanan->pemesan !== $this->session->userdata('nama'))
        ) {
            show_error('Anda tidak memiliki akses untuk melihat detail pesanan ini.');
            return;
        }
        $data['pesanan'] = $pesanan;
        $this->load->view('details/order_single', $data);
    }

    /**
     * Admin: List all orders needing approval (status=pending & kendaraan IS NULL)
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
     * Admin: Load approve form for a pending order
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

        // Prepare vehicles for select dropdown
        $data['kendaraan_options'] = [];
        foreach ($available_vehicles as $vehicle) {
            $data['kendaraan_options'][] = [
                'id' => $vehicle->id,
                'label' => $vehicle->nama_kendaraan . " [{$vehicle->no_pol}]"
            ];
        }

        // Load driver model and get available drivers
        $this->load->model('driver_model');
        $available_drivers = $this->driver_model->get_available();

        // Prepare drivers for select dropdown
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
     * Admin: Handle approve form submission.
     */
    public function do_approve($id)
    {
        if ($this->user_session['role'] !== 'admin') {
            show_error('Access denied.');
            return;
        }

        $kendaraan_id = (int)$this->input->post('kendaraan');
        $driver_id = (int)$this->input->post('driver');

        $this->load->model('order_model');
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
     * Admin: Paginated report of all approved orders, with kendaraan info.
     * Allows filtering by tanggal_pakai.
     */
    public function order_report() {
        if ($this->user_session['role'] !== 'admin') {
            show_error('Access denied.');
            return;
        }

        $this->load->library('pagination');

        $date_from = $this->input->get('date_from');
        $date_to = $this->input->get('date_to');

        // Count total filtered rows for pagination
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

        // Pagination config
        $config['base_url'] = site_url('order/order_report');
        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['total_rows'] = $total_rows;
        $page = $this->input->get('page') ? (int)$this->input->get('page') : 0;
        $this->pagination->initialize($config);

        // Fetch paginated & filtered orders, joined with kendaraan info
        $this->db->select('p.*, k.no_pol, k.nama_kendaraan')
            ->from('PK_pesanan p')
            ->join('PK_kendaraan k', 'p.kendaraan = k.id', 'left')
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

        // Pass data to the view for rendering
        $data['orders'] = $orders;
        $data['pagination'] = $this->pagination->create_links();
        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;

        $this->load->view('admin/order_report', $data);
    }
}