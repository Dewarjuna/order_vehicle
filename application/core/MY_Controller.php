<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Define a base controller that extends CodeIgniter's built-in controller
class MY_Controller extends CI_Controller
{
    // Store user session data
    public $user_session = array();

    public function __construct()
    {
        parent::__construct();

        // Retrieve user data from session and store in $user_session
        $this->user_session = array(
            'user_id'  => $this->session->userdata('user_id'),
            'username' => $this->session->userdata('username'),
            'nama'     => $this->session->userdata('nama'),
            'role'     => $this->session->userdata('role')
        );

        // Make $user_session available to all views
        $this->load->vars('user_session', $this->user_session);

        // Get the current controller name
        $controller = $this->router->fetch_class();

        // If user is not logged in and not on the auth controller, redirect to login
        if (empty($this->user_session['user_id']) && $controller != 'auth') {
            // Set a flash message to inform the user that their session expired
            $this->session->set_flashdata('session_expired', 'Your session has expired due to inactivity. Please log in again.');
            redirect('auth');
        }
    }
}