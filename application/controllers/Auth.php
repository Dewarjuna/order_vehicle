<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Auth controller: Handles user authentication (login/logout) functionality.
 */
class Auth extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Load authentication model and supporting libraries
        $this->load->model('auth_model');
        $this->load->library('form_validation');
        $this->load->library('session');
    }

    /**
     * Show the login form.
     * Redirects to home if the user is already logged in.
     */
    public function index()
    {
        if ($this->auth_model->current_user()) {
            // User is logged in, proceed to home/dashboard
            redirect('home');
        }
        // Else, show the login form view
        $this->load->view("login_form");
    }

    /**
     * Handle login form submission.
     * Validates credentials and logs user in if correct.
     */
    public function login()
    {
        // Set form validation rules (from the auth model)
        $this->form_validation->set_rules($this->auth_model->rules());
    
        // On validation failure, reload form
        if ($this->form_validation->run() === FALSE) {
            $this->load->view("login_form");
            return;
        }
    
        $username = $this->input->post("username");
        $password = $this->input->post("password");
    
        // Perform authentication via the model
        if ($this->auth_model->login($username, $password)) {
            // Credentials correct: redirect to dashboard
            redirect('home');
        } else {
            // Credentials incorrect: show form with error message
            $data['error'] = "Invalid username or password";
            $this->load->view('login_form', $data);
        }
    }

    /**
     * Log out a user and destroy session.
     * Redirects to login page.
     */
    public function logout()
    {
        $this->auth_model->logout();
        redirect('auth');
    }
}