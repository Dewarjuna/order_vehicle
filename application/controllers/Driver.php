<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Driver extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('driver_model');
    }

    // List all drivers
    public function driver_list()
    {
        // Only admin can access
        if ($this->user_session['role'] !== 'admin') {
            show_error('Access denied.');
            return;
        }else{

            $data['drivers'] = $this->driver_model->get_all();
            $this->load->view('admin/driver/driver_list', $data);
    
        }
    }

    // Show form to add a new driver
    public function updateNama($id)
{
    if ($this->user_session['role'] !== 'admin') {
        show_error('Access denied.');
        return;
    }
    $this->load->library('form_validation');
    $this->form_validation->set_rules('nama', 'Nama', 'required');
    if ($this->form_validation->run() == FALSE) {
        // You could handle errors (e.g. flashdata), but typically just redirect back
        redirect('driver/driver_list');
    } else {
        $data = ['nama' => $this->input->post('nama')];
        $this->driver_model->update($id, $data);
        redirect('driver/driver_list');
    }
}

    // Handle update
    public function storeNama() {
        if ($this->user_session['role'] !== 'admin') {
            show_error('Access denied.');
            return;
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        if ($this->form_validation->run() == FALSE) {
            // You may want to handle errors (e.g., pass errors back to the view)
            redirect('driver/daftarDriver');
        } else {
            $data = ['nama' => $this->input->post('nama')];
            $this->driver_model->insert($data);
            redirect('driver/driver_list');
        }
    }

    // Delete a driver
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