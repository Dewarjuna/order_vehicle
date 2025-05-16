<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CRUD management for drivers.
 * Restricted to admin users (checked per endpoint).
 */
class Driver extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('driver_model');
    }

    /**
     * Show all drivers—admin-only.
     */
    public function driver_list()
    {
        if ($this->user_session['role'] !== 'admin') {
            show_error('Access denied.');
            return;
        }
        $data['drivers'] = $this->driver_model->get_all();
        $this->load->view('admin/driver/driver_list', $data);
    }

    /**
     * Update driver name – form posts to here.
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
            $this->driver_model->update($id, ['nama' => $this->input->post('nama')]);
            redirect('driver/driver_list');
        }
    }

    /**
     * Add new driver (admin-only).
     */
    public function storeNama() {
        if ($this->user_session['role'] !== 'admin') {
            show_error('Access denied.');
            return;
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('nama', 'Nama', 'required');

        if ($this->form_validation->run() == FALSE) {
            redirect('driver/daftarDriver');
        } else {
            $this->driver_model->insert(['nama' => $this->input->post('nama')]);
            redirect('driver/driver_list');
        }
    }

    /**
     * Remove a driver by ID, admin-only.
     */
    public function delete($id)
    {
        if ($this->user_session['role'] !== 'admin') {
            show_error('Access denied.');
            return;
        }
        $this->driver_model->delete($id);
        redirect('/driver');
    }
}