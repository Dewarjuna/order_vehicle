<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Dashboard for all users.
 * Stats depend on role (admin: global, user: personal).
 * Triggers releasing of kendaraan and drivers that should now be available.
 */
class Home extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('auth_model');
    }

    public function index()
    {   
        date_default_timezone_set('Asia/Jakarta');

        if (!$this->session->userdata('user_id')) {
            redirect('auth');
        }

        $this->load->model('order_model');
        $this->order_model->autoUpdateStatus_driver_kendaraan(); // Release resources before showing the dashboard
        $this->order_model->autoUpdateNoConfirmationStatus();
        $role = $this->session->userdata('role');
        $nama = $this->session->userdata('nama');
        $now = date('Y-m'); // "YYYY-MM" format for current month

        if ($role === 'admin') {
            // Admin users see complete site statistics
            $data['total_orders']    = $this->order_model->count_orders_by_month($now);
            $data['pending_orders']  = $this->order_model->count_orders_by_month_status($now, 'pending');
            $data['approved_orders'] = $this->order_model->count_orders_by_month_status($now, 'approved');
            $data['done_orders']     = $this->order_model->count_orders_by_month_status($now, 'done');
            $data['rejected_orders'] = $this->order_model->count_orders_by_month_status($now, 'rejected');
            $data['no_confirmation_orders'] = $this->order_model->count_orders_by_month_status($now, 'no confirmation');
        } else {
            // Normal users see only their order stats
            $data['user_orders'] = $this->order_model->count_user_orders_by_month($now, $nama);
        }
        // Username/nama/role always available in view for convenience
        $data['username'] = $this->session->userdata('username');
        $data['nama']     = $nama;
        $data['role']     = $role;

        $this->load->view('home', $data);
    }
}