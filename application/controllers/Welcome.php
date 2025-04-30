<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('auth_model');
    }

    public function index()
    {
        // Check if user is logged in
        if(!$this->session->userdata('user_id')) {
            redirect('auth');
        }

        $data['username'] = $this->session->userdata('username');
        $data['nama'] = $this->session->userdata('nama');
        $data['role'] = $this->session->userdata('role');
        $this->load->view('home', $data);
    }

    public function user_list()
    {
        // Check admin
        if ($this->user_session['role'] !== 'admin') {
            show_error('Access denied.');
            return;
        }

        $data['users'] = $this->auth_model->getusers_all();
        $this->load->view('admin/user_list', $data);
    }
    public function user_detail($id)
    {
        // Check admin
        if ($this->user_session['role'] !== 'admin') {
            show_error('Access denied.');
            return;
        }

        $data['user'] = $this->auth_model->getusers_by_id($id);
        $this->load->view('admin/user_detail', $data);
    }
    public function user_edit($id)
    {
        // Check admin
        if ($this->user_session['role'] !== 'admin') {
            show_error('Access denied.');
            return;
        }

        $data['user'] = $this->auth_model->getusers_by_id($id);
        $this->load->view('admin/user_edit', $data);
    }
}
