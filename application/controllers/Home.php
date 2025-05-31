<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('auth_model');
        $this->load->model('order_model');
    }

    public function index()
    {   
        date_default_timezone_set('Asia/Jakarta');

        if (!$this->session->userdata('user_id')) {
            redirect('auth');
        }

        $this->order_model->autoUpdateStatus_driver_kendaraan();
        $this->order_model->autoUpdateNoConfirmationStatus();
        
        $role = $this->session->userdata('role');
        $nama = $this->session->userdata('nama');
        $now = date('Y-m');

        if ($role === 'admin') {
            $data['total_orders']    = $this->order_model->count_orders_by_month($now);
            $data['pending_orders']  = $this->order_model->count_orders_by_month_status($now, 'pending');
            $data['approved_orders'] = $this->order_model->count_orders_by_month_status($now, 'approved');
            $data['done_orders']     = $this->order_model->count_orders_by_month_status($now, 'done');
            $data['rejected_orders'] = $this->order_model->count_orders_by_month_status($now, 'rejected');
            $data['no_confirmation_orders'] = $this->order_model->count_orders_by_month_status($now, 'no confirmation');
            
            // Get status from URL parameter if exists
            $status = $this->input->get('status');
            if ($status) {
                $data['status_orders'] = $this->order_model->get_orders_by_status($status);
                $data['selected_status'] = $status;
            }
        } else {
            $data['user_orders'] = $this->order_model->count_user_orders_by_month($now, $nama);
        }

        $data['username'] = $this->session->userdata('username');
        $data['nama']     = $nama;
        $data['role']     = $role;

        $this->load->view('home', $data);
    }

    public function ajax_status_tile_counts(){
        if (!$this->input->is_ajax_request()) {
            show_error('No direct script access allowed', 403);
            return;
        }

        $months = $this->input->post('months');
        if (!is_array($months)) $months = [];
        $results =array(
            'total_orders' => $this->order_model->count_orders_by_months($months),
            'pending_orders' => $this->order_model->count_orders_by_months_status($months, 'pending'),
            'approved_orders' => $this->order_model->count_orders_by_months_status($months, 'approved'),
            'done_orders' => $this->order_model->count_orders_by_months_status($months, 'done'),
            'rejected_orders' => $this->order_model->count_orders_by_months_status($months, 'rejected'),
            'no_confirmation_orders' => $this->order_model->count_orders_by_months_status($months, 'no confirmation')
        );

        echo json_encode($results);
    }

}