<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin-only: List, create, edit, and delete user accounts.
 */
class User extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('auth_model');
    }

    public function user_list()
    {
        if ($this->user_session['role'] !== 'admin') {
            show_error('Access denied.');
            return;
        }
        $data['users'] = $this->auth_model->getusers_all();
        $this->load->view('admin/user/user_list', $data);
    }

    public function user_detail($id)
    {
        if ($this->user_session['role'] !== 'admin') {
            show_error('Access denied.');
            return;
        }
        $data['user'] = $this->auth_model->getuser_by_id($id);
        $this->load->view('admin/user/user_detail', $data);
    }

    public function user_edit($id)
    {
        if ($this->user_session['role'] !== 'admin') {
            show_error('Access denied.');
            return;
        }
        $data['user'] = $this->auth_model->getuser_by_id($id);
        $this->load->view('admin/user/user_edit', $data);
    }
    
    /**
     * Add user (admin only, POST only)
     * Shows sticky form on error.
     */
    public function add_user()
    {
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
                    //'password'       => password_hash($this->input->post('password', TRUE), PASSWORD_BCRYPT),
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
            show_404(); // Disallow via GET
        }
    }

    public function delete($id)
    {
        if ($this->user_session['role'] !== 'admin') {
            show_error('Access denied.');
            return;
        }
        if ($this->auth_model->delete_user($id)) {
            $this->session->set_flashdata('success', 'User berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus user.');
        }
        redirect('user/user_list');
    }

    public function update_user($id)
    {
        if ($this->user_session['role'] !== 'admin') {
            show_error('Access denied.');
            return;
        }
        if ($this->input->method() === 'post') {
            $data = [
                'nama'     => $this->input->post('nama'),
                'username' => $this->input->post('username'),
                'divisi'   => $this->input->post('divisi'),
                'role'     => $this->input->post('role')
            ];
            if ($this->auth_model->update_user($id, $data)) {
                $this->session->set_flashdata('success', 'User berhasil dirubah.');
            } else {
                $this->session->set_flashdata('error', 'Gagal merubah user.');
            }
        }
        redirect('user/user_list');
    }
}