<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    public $user_session = array();

    public function __construct()
    {
        parent::__construct();

        $this->user_session = array(
            'user_id'  => $this->session->userdata('user_id'),
            'username' => $this->session->userdata('username'),
            'nama'     => $this->session->userdata('nama'),
            'role'     => $this->session->userdata('role')
        );
        $this->load->vars('user_session', $this->user_session);

        // Redirect to login if user is not logged in, except on the Auth controller
        $controller = $this->router->fetch_class();
        if (empty($this->user_session['user_id']) && $controller != 'auth') {
            redirect('auth');
        }
    }
}
