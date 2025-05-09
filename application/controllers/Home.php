<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('auth_model');
    }

    public function index()
{
    if(!$this->session->userdata('user_id')) {
        redirect('auth');
    }

    $this->load->model('order_model');
    $role = $this->session->userdata('role');
    $nama = $this->session->userdata('nama');
    $now = date('Y-m'); // format YYYY-MM

    if ($role === 'admin') {
        $data['total_orders']    = $this->order_model->count_orders_by_month($now);
        $data['pending_orders']  = $this->order_model->count_orders_by_month_status($now, 'pending');
        $data['approved_orders'] = $this->order_model->count_orders_by_month_status($now, 'approved');
    } else {
        $data['user_orders'] = $this->order_model->count_user_orders_by_month($now, $nama);
    }

    // Existing data
    $data['username'] = $this->session->userdata('username');
    $data['nama']     = $nama;
    $data['role']     = $role;
    $this->load->view('home', $data);
}
}
