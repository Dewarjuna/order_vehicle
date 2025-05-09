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
}