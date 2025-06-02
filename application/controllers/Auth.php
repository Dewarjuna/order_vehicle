<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Authentication Controller
 * 
 * Handles user authentication separately from other controllers to:
 * - Keep security concerns isolated
 * - Allow unauthenticated access to login
 * - Provide clear separation between auth and business logic
 */
class Auth extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('auth_model');
        $this->load->library('form_validation');
        $this->load->library('session');
    }

    /**
     * Login page and processing
     * Combined in one method to reduce attack surface and simplify flow
     */
    public function index()
    {
        if ($this->auth_model->current_user()) {
            redirect('home');
        }
        $this->load->view("login_form");
    }

    /**
     * Process login form.
     * Shows form errors, or redirects upon success.
     */
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
            redirect('home');
        } else {
            $data['error'] = "Invalid username or password";
            $this->load->view('login_form', $data);
        }
    }

    /**
     * Logout handled separately to ensure proper session cleanup
     * and prevent session fixation attacks
     */
    public function logout()
    {
        $this->auth_model->logout();
        redirect('auth');
    }
}