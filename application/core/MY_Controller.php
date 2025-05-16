<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Base controller to consolidate session/user retrieval and access control logic.
 * Extend all project controllers from this class rather than CI_Controller directly.
 */
class MY_Controller extends CI_Controller
{
    // Holds relevant user information from the session for easy access across all controllers/views
    public $user_session = array();

    public function __construct()
    {
        parent::__construct();

        // Gather session data into $user_session for consistent, centralized access.
        $this->user_session = array(
            'user_id'  => $this->session->userdata('user_id'),
            'username' => $this->session->userdata('username'),
            'nama'     => $this->session->userdata('nama'),
            'role'     => $this->session->userdata('role')
        );

        // Inject $user_session into all views so layout files and partials can reference user details without extra queries.
        $this->load->vars('user_session', $this->user_session);

        // Identify which controller is currently being accessed
        $controller = $this->router->fetch_class();

        // Enforce login/authentication except for the login controller itself.
        // This pattern helps avoid duplicating "is logged in" checks in each controller.
        if (empty($this->user_session['user_id']) && $controller != 'auth') {
            // Show a user-friendly reason for redirecting.
            $this->session->set_flashdata('session_expired', 'Your session has expired due to inactivity. Please log in again.');
            redirect('auth');
        }
    }
}