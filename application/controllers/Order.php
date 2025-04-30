<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends MY_Controller {

    public function __construct() 
    {
        parent::__construct();
        $this->load->model('order_model');

        if (!isset($this->order_model)) {
            log_message('error', 'Order_model not loaded.');
            show_error('Order_model failed to load. Please check the model file and loading process.');
        } else {
            log_message('debug', 'Order_model successfully loaded.');
        }
    }
    

    public function create() {
        $users = $this->order_model->getusers_all();
        $this->load->view('order_form', ['users' => $users]);
    }

    // Helper function to convert d-m-Y to Y-m-d
    private function convert_date($date) {
        $d = DateTime::createFromFormat('d-m-Y', $date);
        return $d ? $d->format('Y-m-d') : null;
    }

    public function submit() {
        $this->load->helper('form');
        $this->load->library('form_validation');

        // Validation rules
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

        if ($this->form_validation->run() == FALSE) {
            // Show validation errors for debugging
            echo validation_errors();
            $users = $this->order_model->getusers_all();
            $this->load->view('order_form', ['users' => $users]);
            return;
        }

        // Convert dates to Y-m-d for SQL Server
        $tanggal_pesanan = $this->convert_date($this->input->post('tanggal_pesanan'));
        $tanggal_pakai   = $this->convert_date($this->input->post('tanggal_pakai'));

        // Prepare data for insert
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

        // Debug: log the data
        log_message('debug', 'Data submitted: ' . print_r($data, true));

        // Insert into database
        if ($this->order_model->insert_order($data)) {
            $this->load->view('order_success', $data);
        } else {
            // Show SQL error for debugging
            echo $this->db->last_query();
            echo '<br>';
            print_r($this->db->error());
            log_message('error', 'Gagal menyimpan data.');
            show_error('Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }

    // controllers/Order.php
        /* public function detail($id) // <-- add $id parameter
        {
            $pemesan = $this->session->userdata('nama');
            $pesanan = $this->order_model->getpesanan_by_id($id);

            // Restrict access for security: only allow original pemesan
            if (!$pesanan || $pesanan->pemesan !== $pemesan) {
                show_error('Anda tidak diizinkan melihat pesanan ini.');
                return;
            }

            $data['pesanan'] = $pesanan;
            $this->load->view('details/order_detail', $data);
        } */
    
        public function detail()
        {
            $pemesan = $this->session->userdata('nama');
            $data['pesanan_list'] = $this->order_model->getpesanan_by_pemesan($pemesan);
            $this->load->view('details/order_detail', $data); // Update this if your view path is different
        }
    
        public function delete($id) {
            if ($this->order_model->delete($id)) {
                redirect('order/detail');
            } else {
                show_error('Gagal menghapus data.');
            }
        }

        public function edit($id)
{
    $pesanan = $this->order_model->getpesanan_by_id($id);
    $users = $this->order_model->getusers_all(); // for the dropdown

    // Security: Only allow editing if this user is the pemesan
    if (!$pesanan || $pesanan->pemesan !== $this->session->userdata('nama')) {
        show_error('Anda tidak diizinkan mengedit pesanan ini.');
        return;
    }

    $data['pesanan'] = $pesanan;
    $data['users']   = $users;
    $this->load->view('details/order_edit', $data);
}

public function update($id)
{
    $this->load->helper('form');
    $this->load->library('form_validation');

    // Validation rules (same as in submit)
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

    // If fails, reload form with current data
    if ($this->form_validation->run() == FALSE) {
        // Repopulate form with current data and validation errors
        $pesanan = $this->order_model->getpesanan_by_id($id);
        $users = $this->order_model->getusers_all();
        $this->load->view('details/order_edit', [
            'pesanan' => $pesanan,
            'users' => $users
        ]);
        return;
    }

    // Convert dates for DB
    $tanggal_pesanan = $this->convert_date($this->input->post('tanggal_pesanan'));
    $tanggal_pakai   = $this->convert_date($this->input->post('tanggal_pakai'));

    // Prepare data to update
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
        'kendaraan'       => $this->input->post('kendaraan'), // add this if you have kendaraan in your form/data
        'jumlah_orang'    => $this->input->post('jumlah_orang'),
        'pemesan'         => $this->session->userdata('nama')
    );

    // Update in DB
    if ($this->order_model->update($id, $data)) {
        // Success: redirect or show message
        redirect('order/detail');
    } else {
        // Error handling
        echo "Database error:<br>";
        echo $this->db->last_query();
        echo '<br>';
        print_r($this->db->error());
        log_message('error', 'Gagal mengupdate data.');
        show_error('Terjadi kesalahan saat mengupdate data. Silakan coba lagi.');
    }
}

public function single($id) {
    $pesanan = $this->order_model->getpesanan_by_id($id);

    // Use the session already set in MY_Controller
    if (
        !$pesanan ||
        ($this->user_session['role'] !== 'admin' && $pesanan->pemesan !== $this->user_session['nama'])
    ) {
        show_error('Anda tidak diizinkan melihat pesanan ini.');
        return;
    }

    $data['pesanan'] = $pesanan;
    $this->load->view('details/order_single', $data);
}

// List all pending orders for admin
public function pending_orders() {
    // Check user role is admin
    if ($this->user_session['role'] !== 'admin') {
        show_error('Access denied.');
        return;
    }

    // Get pending orders with kendaraan IS NULL
    $this->db->where('status', 'pending');
    $this->db->where('kendaraan IS NULL', null, false);
    $data['pending_orders'] = $this->db->get('pesanan')->result();

    $this->load->view('admin/pending_list', $data);
}

// Show form for admin to approve an order
public function approve($id) {
    if ($this->user_session['role'] !== 'admin') {
        show_error('Access denied.');
        return;
    }

    $order = $this->order_model->getpesanan_by_id($id);
    if (!$order || $order->status !== 'pending') {
        show_error('Order not found or already approved.');
        return;
    }

    // Example: predefined kendaraan list, or load from db table if you have
    $data['kendaraan_options'] = ['Kijang Innova', 'Alphard', 'Toyota Avanza', 'Honda'];
    $data['order'] = $order;

    $this->load->view('admin/approve_form', $data);
}

// Handle form submit for approval
public function do_approve($id) {
    if ($this->user_session['role'] !== 'admin') {
        show_error('Access denied.');
        return;
    }

    $kendaraan = $this->input->post('kendaraan');
    if (empty($kendaraan)) {
        $this->session->set_flashdata('error', 'Please select a vehicle.');
        redirect('order/approve/' . $id);
        return;
    }

    if ($this->order_model->approve_order($id, $kendaraan)) {
        $this->session->set_flashdata('success', 'Order approved successfully.');
        redirect('order/pending_orders');
    } else {
        $this->session->set_flashdata('error', 'Failed to approve order.');
        redirect('order/approve/' . $id);
    }
}

public function order_report() {
    // Only admin
    if ($this->user_session['role'] !== 'admin') {
        show_error('Access denied.');
        return;
    }

    $this->load->library('pagination');

    // Get filter values
    $date_from = $this->input->get('date_from');
    $date_to = $this->input->get('date_to');

    // Pagination config
    $config['base_url'] = site_url('order/order_report');
    $config['per_page'] = 10;
    $config['page_query_string'] = TRUE;
    $config['query_string_segment'] = 'page';
    $page = $this->input->get('page') ? (int)$this->input->get('page') : 0;

    // Build query
    $this->db->from('pesanan');
    $this->db->where('status', 'approved');
    if ($date_from && $date_to) {
        $this->db->where('tanggal_pakai >=', $date_from);
        $this->db->where('tanggal_pakai <=', $date_to);
    } elseif ($date_from) {
        $this->db->where('tanggal_pakai', $date_from);
    }
    $total_rows = $this->db->count_all_results('', FALSE);

    // For pagination, limit and get result
    $this->db->limit($config['per_page'], $page);
    $orders = $this->db->get()->result();

    // Init pagination
    $config['total_rows'] = $total_rows;
    $this->pagination->initialize($config);

    $data['orders'] = $orders;
    $data['pagination'] = $this->pagination->create_links();
    $data['date_from'] = $date_from;
    $data['date_to'] = $date_to;

    $this->load->view('admin/order_report', $data);
}
}