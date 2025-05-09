<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('auth_model');
        $this->load->library('form_validation');
        $this->load->library('session');
    }

    public function index()
    {
        if ($this->auth_model->current_user()) {
            redirect('home'); // Redirect to home if already logged in
        }
        $this->load->view("login_form");
    }

    public function login()
    {
        $this->form_validation->set_rules($this->auth_model->rules());
    
        if ($this->form_validation->run() === FALSE) {
            $this->load->view("login_form");
            return;
        }
    
        $username = $this->input->post("username");
        $password = $this->input->post("password");
    
        if ($this->auth_model->login($username, $password)) {
            redirect('home'); // This line sends the browser to the home page
        } else {
            $data['error'] = "Invalid username or password";
            $this->load->view('login_form', $data);
        }
    }

    public function logout()
    {
        $this->auth_model->logout();
        redirect('auth');
    }
}