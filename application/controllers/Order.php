<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Order controller:
 * Handles reservation requests, edits, approval/rejection, and reporting.
 * Security: Guards sensitive operations with user/role/context checks.
 * Business Rationale: Provides a clear workflow for internal fleet booking & management.
 */
class Order extends MY_Controller {

    public function __construct() 
    {
        parent::__construct();
        $this->load->model('order_model');
        // Defensive: Log/model loading failures show error for debugging/deployment
        if (!isset($this->order_model)) {
            log_message('error', 'Order_model not loaded.');
            show_error('Order_model failed to load. Please check the model file and loading process.');
        }
    }

    /**
     * Display the reservation form, pre-loaded with user selection data.
     */
    public function create() {
        $users = $this->order_model->getusers_all();
        $this->load->view('order_form', ['users' => $users]);
    }

    /**
     * Utility: Converts a date in d-m-Y format (user input) to Y-m-d for DB.
     * Rationale: Enforces consistent date format for storage/comparison.
     */
    private function convert_date($date) {
        $d = DateTime::createFromFormat('d-m-Y', $date);
        return $d ? $d->format('Y-m-d') : null;
    }

    /**
     * Handles new reservation form submission.
     * - Validates input. 
     * - On error: redisplay form with validation messages.
     * - On success: saves to database, redirects to user summary page.
     */
    public function submit() {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('tanggal_pesanan', 'Tanggal Pesanan', 'required');
        $this->form_validation->set_rules('tanggal_pakai', 'Tanggal Pakai', 'required');
        $this->form_validation->set_rules('waktu_mulai', 'Waktu Mulai', 'required');
        $this->form_validation->set_rules('waktu_selesai', 'Waktu Selesai', 'required');
        $this->form_validation->set_rules('keperluan', 'Keperluan', 'required');
        $this->form_validation->set_rules('jumlah_orang', 'Jumlah Orang', 'required|integer');

        if ($this->form_validation->run() == FALSE) {
            // Validation failed: redisplay form with data/errors
            echo validation_errors();
            $users = $this->order_model->getusers_all();
            $this->load->view('order_form', ['users' => $users]);
            return;
        }

        // Valid: Prepare + save reservation
        $tanggal_pesanan = $this->convert_date($this->input->post('tanggal_pesanan'));
        $tanggal_pakai   = $this->convert_date($this->input->post('tanggal_pakai'));
        $data = [
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
            'pemesan'         => $this->session->userdata('nama'),
            /* 'latitude_tujuan'   => $this->input->post('latitude_tujuan'),
            'longitude_tujuan'  => $this->input->post('longitude_tujuan'),
            'jarak'           => $this->input->post('jarak'), */
        ];

        $order_id = $this->order_model->create($data);

        if ($order_id) {
            // Use flash for toast/SweetAlert2 notification after redirect
            $this->session->set_flashdata('success_message', 'Pesanan kendaraan anda telah berhasil disimpan.');
            redirect('order/order_user');
        } else {
            // On DB error, aid debugging: display last query and error structure
            echo $this->db->last_query();
            print_r($this->db->error());
            log_message('error', 'Gagal menyimpan pesanan.');
            show_error('Gagal menyimpan pesanan. Silakan coba lagi.');
        }
    }

    /**
     * Display all reservations for the currently logged-in user.
     */
    public function order_user() {
        $pemesan = $this->session->userdata('nama');
        $data['pesanan_list'] = $this->order_model->getpesanan_by_pemesan_with_kendaraan($pemesan);
        $this->load->view('details/order_user', $data);
    }

    /**
     * Delete a reservation by ID.
     * - Deletes only if permittable (handled in model).
     * - Responds with JSON for AJAX or redirects for legacy user flows.
     */
    public function delete($id) {
        if ($this->order_model->delete($id)) {
            if ($this->input->is_ajax_request()) {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'success']);
            } else {
                redirect('details/order_user');
            }
        } else {
            if ($this->input->is_ajax_request()) {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus pesanan.']);
            } else {
                show_error('Gagal menghapus pesanan.');
            }
        }
    }

    /**
     * Edit/update reservation (owned by current user).
     * - Validates and applies updates, responds with view or JSON.
     */
    public function update($id) {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('tanggal_pesanan', 'Tanggal Pesanan', 'required');
        $this->form_validation->set_rules('jumlah_orang', 'Jumlah Orang', 'required|integer');
        if ($this->form_validation->run() == FALSE) {
            // On validation error, show form or feedback (AJAX/legacy)
            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'error', 'message' => validation_errors()]);
                exit;
            } else {
                $pesanan = $this->order_model->getpesanan_by_id($id);
                $users = $this->order_model->getusers_all();
                $this->load->view('details/order_user', ['pesanan' => $pesanan, 'users' => $users]);
                return;
            }
        }

        $tanggal_pakai = $this->input->post('tanggal_pakai');
        $data = [
            'tujuan'          => $this->input->post('tujuan'),
            'tanggal_pakai'   => $tanggal_pakai,
            'waktu_mulai'     => $this->input->post('waktu_mulai'),
            'waktu_selesai'   => $this->input->post('waktu_selesai'),
            'keperluan'       => $this->input->post('keperluan'),
            'jumlah_orang'    => $this->input->post('jumlah_orang')
        ];

        $result = $this->order_model->update($id, $data);

        if ($result) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'success']);
                exit;
            } else {
                redirect('order/order_user');
            }
        } else {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'error', 'message' => 'Database error']);
                exit;
            } else {
                // Full DB error/last query for developer troubleshooting (not for end-user)
                echo "Database error:<br>";
                echo $this->db->last_query();
                print_r($this->db->error());
                log_message('error', 'Gagal memperbarui pesanan.');
                show_error('Gagal memperbarui pesanan. Silakan coba lagi.');
            }
        }
    }

    /**
     * Show the detail of a single reservation.
     * Access: Current owner or admin only.
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
     * ========== ADMIN ACTIONS BELOW ==========
     */

    /**
     * Admin: List all pending orders (without assigned vehicle).
     * - For approval workflow UI. 
     * - Pre-load all vehicle/driver options for assignment.
     */
    public function pending_orders() {

        $this->db->where('status', 'pending');
        $this->db->where('kendaraan IS NULL', null, false);
        $data['pending_orders'] = $this->db->get('PK_pesanan')->result();

        // Prepare choice lists for assignment, in modal/dropdowns
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
     * Admin: Load single order + available vehicles/drivers for approval.
     * Access: Admin only, pending status only.
     */
    public function approve($id) {
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
     * Admin: AJAX endpoint to approve & assign vehicle/driver to order.
     * Why: Provides real-time UI feedback, integrates with admin dashboard.
     * Model handles transaction/error. 
     */
    public function do_approve_ajax($id) {
        if ($this->user_session['role'] !== 'admin') {
            echo json_encode(['status' => 'error', 'message' => 'Access denied.']);
            exit;
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
     * Admin: AJAX endpoint to reject a pending order (with feedback).
     * Why: Real-time reject for admin dashboard, improves UX.
     */
    public function reject_ajax($id) {
        if ($this->user_session['role'] !== 'admin') {
            echo json_encode(['status' => 'error', 'message' => 'Access denied.']);
            exit;
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
     * Admin: Report view (paginated) of all historical/active orders.
     * Features: filter by date, see vehicle, driver, key details.
     * Why: Enables ops/management analysis and historic tracking.
     */
    public function order_report() {
    if ($this->user_session['role'] !== 'admin') {
        show_error('Access denied.');
        return;
    }
    $this->load->view('admin/order_report');
}

public function order_report_ajax()
{
    // DataTables standard POST variables
    $draw = intval($this->input->post('draw'));
    $length = intval($this->input->post('length'));
    $start = intval($this->input->post('start'));
    $search = $this->input->post('search')['value'];
    $date_from = $this->input->post('date_from');
    $date_to = $this->input->post('date_to');

    // 1. Get total count (before filter)
    $this->db->from('PK_pesanan');
    $totalRecords = $this->db->count_all_results();

    $this->db->from('PK_pesanan');
    if ($search) {
        $this->db->group_start();
        $this->db->like('nama', $search);
        $this->db->or_like('tujuan', $search);
        // Add more columns as needed
        $this->db->group_end();
    }
    if ($date_from && $date_to) {
        $this->db->where('tanggal_pakai >=', $date_from);
        $this->db->where('tanggal_pakai <=', $date_to);
    }
    $filteredRecords = $this->db->count_all_results();

    // 3. Get paged data
    $this->db->select('p.*, k.no_pol, k.nama_kendaraan, d.nama as nama_driver');
    $this->db->from('PK_pesanan p');
    $this->db->join('PK_kendaraan k', 'p.kendaraan = k.id', 'left');
    $this->db->join('PK_driver d', 'p.driver = d.id', 'left');
    if ($search) {
        $this->db->group_start();
        $this->db->like('p.nama', $search);
        $this->db->or_like('p.tujuan', $search);
        // Add more columns as needed
        $this->db->group_end();
    }
    if ($date_from && $date_to) {
        $this->db->where('p.tanggal_pakai >=', $date_from);
        $this->db->where('p.tanggal_pakai <=', $date_to);
    }
    $this->db->order_by('p.tanggal_pakai', 'DESC');
    $this->db->limit($length, $start);
    $query = $this->db->get();

    $data = [];
    $no = $start + 1;
    foreach ($query->result() as $row) {
        $data[] = [
            $no++, // 0: row number
            date('d-m-Y', strtotime($row->tanggal_pakai)), // 1: tanggal pakai
            htmlspecialchars($row->nama), // 2: pemakai
            htmlspecialchars($row->tujuan), // 3: tujuan
            $row->no_pol, // 4
            $row->nama_kendaraan, // 5
            $row->kendaraan, // 6 (for fallback display)
            $row->nama_driver, // 7
            $row->driver, // 8 (for fallback display)
            $row->status, // 9
            '<button class="btn btn-info btn-xs btn-detail" data-id="'.$row->id.'">Detail</button>', // 10 (Aksi)
        ];
    }

    echo json_encode([
        "draw" => $draw,
        "recordsTotal" => $totalRecords,
        "recordsFiltered" => $filteredRecords,
        "data" => $data
    ]);
}

public function order_detail_ajax($id) {
    $this->load->helper('text');
    $this->db->select('p.*, k.no_pol, k.nama_kendaraan, d.nama as nama_driver');
    $this->db->from('PK_pesanan p');
    $this->db->join('PK_kendaraan k', 'p.kendaraan = k.id', 'left');
    $this->db->join('PK_driver d', 'p.driver = d.id', 'left');
    $this->db->where('p.id', $id);
    $row = $this->db->get()->row();

    if (!$row) {
        echo '<div class="alert alert-danger">Data pesanan tidak ditemukan.</div>';
        return;
    }

    echo '<table class="table table-striped table-bordered">';
    echo '<tr><th>Tanggal Pesanan</th><td>' . date('d-m-Y', strtotime($row->tanggal_pesanan)) . '</td></tr>';
    echo '<tr><th>Nomor Karyawan</th><td>' . htmlspecialchars($row->nomor_karyawan) . '</td></tr>';
    echo '<tr><th>Nama Karyawan</th><td>' . htmlspecialchars($row->nama) . '</td></tr>';
    echo '<tr><th>Divisi</th><td>' . htmlspecialchars($row->divisi) . '</td></tr>';
    echo '<tr><th>Tujuan</th><td>' . htmlspecialchars($row->tujuan) . '</td></tr>';
    echo '<tr><th>Tanggal Pakai</th><td>' . date('d-m-Y', strtotime($row->tanggal_pakai)) . '</td></tr>';
    echo '<tr><th>Waktu Mulai</th><td>' . htmlspecialchars($row->waktu_mulai) . '</td></tr>';
    echo '<tr><th>Waktu Selesai</th><td>' . htmlspecialchars($row->waktu_selesai) . '</td></tr>';
    echo '<tr><th>Keperluan</th><td>' . nl2br(htmlspecialchars($row->keperluan)) . '</td></tr>';
    echo '<tr><th>Kendaraan</th><td>';
    if (!empty($row->no_pol) && !empty($row->nama_kendaraan)) {
        echo htmlspecialchars($row->no_pol) . ' (' . htmlspecialchars($row->nama_kendaraan) . ')';
    } else {
        echo 'Menunggu Persetujuan';
    }
    echo '</td></tr>';
    echo '<tr><th>Driver</th><td>' . (!empty($row->nama_driver) ? htmlspecialchars($row->nama_driver) : 'Menunggu Persetujuan') . '</td></tr>';
    echo '<tr><th>Jumlah Orang</th><td>' . (int)$row->jumlah_orang . '</td></tr>';
    echo '<tr><th>Pemesan</th><td>' . htmlspecialchars($row->pemesan) . '</td></tr>';
    echo '<tr><th>Status</th><td>' . htmlspecialchars($row->status) . '</td></tr>';
    // echo '<tr><th>Jarak</th><td>' . htmlspecialchars($row->jarak) . ' km</td></tr>'; // Distance commented out
    echo '</table>';
}

}