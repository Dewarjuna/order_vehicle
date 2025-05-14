<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Driver controller: Handles CRUD for driver management (admin only).
 */
class Driver extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Load the driver model for database operations
        $this->load->model('driver_model');
    }

    /**
     * List all drivers (Admin only).
     * Loads a view with all driver data.
     */
    public function driver_list()
    {
        // Only allow access for admin users
        if ($this->user_session['role'] !== 'admin') {
            show_error('Access denied.');
            return;
        } else {
            $data['drivers'] = $this->driver_model->get_all();
            $this->load->view('admin/driver/driver_list', $data);
        }
    }

    /**
     * Handle update request for driver's name.
     * Shows form and processes update if submitted.
     */
    public function updateNama($id)
    {
        if ($this->user_session['role'] !== 'admin') {
            show_error('Access denied.');
            return;
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('nama', 'Nama', 'required');

        if ($this->form_validation->run() == FALSE) {
            redirect('driver/driver_list');
        } else {
            $data = [
                'nama' => $this->input->post('nama'),
            ];
            $this->driver_model->update($id, $data);
            redirect('driver/driver_list');
        }
    }

    /**
     * Handle add/insert new driver (name only).
     */
    public function storeNama() {
        // Admins only
        if ($this->user_session['role'] !== 'admin') {
            show_error('Access denied.');
            return;
        }
        // Validate driver name is provided
        $this->load->library('form_validation');
        $this->form_validation->set_rules('nama', 'Nama', 'required');

        if ($this->form_validation->run() == FALSE) {
            // Validation failed, redirect back to driver input form
            redirect('driver/daftarDriver');
        } else {
            // Add new driver with the provided name
            $data = ['nama' => $this->input->post('nama')];
            $this->driver_model->insert($data);
            redirect('driver/driver_list');
        }
    }

    /**
     * Delete a driver by ID (admin only).
     */
    public function delete($id)
    {
        // Admins only
        if ($this->user_session['role'] !== 'admin') {
            show_error('Access denied.');
            return;
        }
        // Remove the driver and redirect to main page
        $this->driver_model->delete($id);
        redirect('/driver');
    }
}