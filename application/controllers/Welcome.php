<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('auth_model');
        $this->load->library('session');
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

    public function test_session()
{
    $this->session->set_userdata('test', 'hello');
    echo 'Session value: ' . $this->session->userdata('test');
}
}