<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User controller: Handles user management (view/list/edit) for admin users.
 */
class User extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Load authentication model for user-related data operations
        $this->load->model('auth_model');
    }

    /**
     * List all users (admin only).
     * Loads a view with all user records for admin management.
     */
    public function user_list()
    {
        // Access control: Admin only
        if ($this->user_session['role'] !== 'admin') {
            show_error('Access denied.');
            return;
        }
        $data['users'] = $this->auth_model->getusers_all();
        $this->load->view('admin/user/user_list', $data);
    }

    /**
     * Show details of a specific user by ID (admin only).
     */
    public function user_detail($id)
    {
        // Access control: Admin only
        if ($this->user_session['role'] !== 'admin') {
            show_error('Access denied.');
            return;
        }
        $data['user'] = $this->auth_model->getusers_by_id($id);
        $this->load->view('admin/user/user_detail', $data);
    }

    /**
     * Show the edit form for a specific user by ID (admin only).
     */
    public function user_edit($id)
    {
        // Access control: Admin only
        if ($this->user_session['role'] !== 'admin') {
            show_error('Access denied.');
            return;
        }
        $data['user'] = $this->auth_model->getusers_by_id($id);
        $this->load->view('admin/user/user_edit', $data);
    }
    
    public function add_user()
{
    // Only admin can add users
    if ($this->user_session['role'] !== 'admin') {
        show_error('Access denied.');
        return;
    }

    if ($this->input->method() === 'post') {
        $this->form_validation->set_rules([
            ['field' => 'nomor_karyawan', 'label' => 'Nomor Karyawan', 'rules' => 'required'],
            ['field' => 'nama', 'label' => 'Nama', 'rules' => 'required'],
            ['field' => 'username', 'label' => 'Username', 'rules' => 'required|is_unique[PK_user.username]'],
            ['field' => 'password', 'label' => 'Password', 'rules' => 'required'],
            ['field' => 'divisi', 'label' => 'Divisi', 'rules' => 'required'],
            ['field' => 'role', 'label' => 'Role', 'rules' => 'required'],
        ]);

        // Prepare old values for input sticky (to repopulate the form if error)
        $input_data = [
            'nomor_karyawan' => $this->input->post('nomor_karyawan'),
            'nama'           => $this->input->post('nama'),
            'username'       => $this->input->post('username'),
            'divisi'         => $this->input->post('divisi'),
            'role'           => $this->input->post('role'),
        ];

        if ($this->form_validation->run() === FALSE) {
            $errors = validation_errors();
            $this->session->set_flashdata('error', $errors);
            $this->session->set_flashdata('add_user_input', $input_data);
            redirect('user/user_list');
        } else {
            $data = [
                'nomor_karyawan' => $this->input->post('nomor_karyawan', TRUE),
                'nama'           => $this->input->post('nama', TRUE),
                'username'       => $this->input->post('username', TRUE),
                //'password'       => password_hash($this->input->post('password', TRUE), PASSWORD_BCRYPT), //use hash in case needed
                'password'       => $this->input->post('password', TRUE),
                'divisi'         => $this->input->post('divisi', TRUE),
                'role'           => $this->input->post('role', TRUE),
                'last_login'     => null,
            ];

            if ($this->auth_model->add_user($data)) {
                $this->session->set_flashdata('success', 'User berhasil ditambahkan.');
            } else {
                $db_error = $this->db->error();
                $this->session->set_flashdata('error', 'Gagal menambah user: '.$db_error['message']);
                $this->session->set_flashdata('add_user_input', $input_data);
            }
            redirect('user/user_list');
        }
    } else {
        show_404(); // Should not get here via GET
    }
}
}