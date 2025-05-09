<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Home controller: Handles the dashboard view for both admins and regular users.
 * Shows stats/summary for the current month.
 */
class Home extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        // Always load the Auth model for user/session-related actions
        $this->load->model('auth_model');
    }

    /**
     * Dashboard landing page for logged-in users.
     * Shows general or user-specific order statistics.
     */
    public function index()
    {
        // Redirect to login if not yet authenticated
        if(!$this->session->userdata('user_id')) {
            redirect('auth');
        }

        // Load order model for statistics
        $this->load->model('order_model');
        $role = $this->session->userdata('role');
        $nama = $this->session->userdata('nama');
        $now = date('Y-m'); // Current month in YYYY-MM format

        if ($role === 'admin') {
            // Admins see overall stats for the month
            $data['total_orders']    = $this->order_model->count_orders_by_month($now);
            $data['pending_orders']  = $this->order_model->count_orders_by_month_status($now, 'pending');
            $data['approved_orders'] = $this->order_model->count_orders_by_month_status($now, 'approved');
        } else {
            // Regular users only see their own monthly order count
            $data['user_orders'] = $this->order_model->count_user_orders_by_month($now, $nama);
        }

        // Pass basic session/user info to the view
        $data['username'] = $this->session->userdata('username');
        $data['nama']     = $nama;
        $data['role']     = $role;

        // Load the dashboard (home) view with summary data
        $this->load->view('home', $data);
    }
}