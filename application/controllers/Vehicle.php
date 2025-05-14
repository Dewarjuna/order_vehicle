<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Vehicle controller: Manages vehicle records (CRUD), admin only.
 */
class Vehicle extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Load model handling vehicles database
        $this->load->model('vehicle_model');
    }

    /**
     * Display a list of all vehicles (admin only).
     * Loads the management view with all vehicle info.
     */
    public function vehicle_list()
    {
        // Restrict access to admin users
        if ($this->user_session['role'] !== 'admin') {
            show_error('Access denied.');
            return;
        } else {
            $data['vehicles'] = $this->vehicle_model->get_all();
            $this->load->view('admin/vehicle/vehicle_list', $data);
        }
    }

    /**
     * Handle POST request to update an existing vehicle.
     * (Vehicle selected by some other method, not visible here.)
     * Validates input, then updates the record.
     */
    // Add $id as a parameter:
    public function updateKendaraan($id)
    {
        if ($this->user_session['role'] !== 'admin') {
            show_error('Access denied.');
            return;
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('no_pol', 'No Polisi', 'required');
        $this->form_validation->set_rules('nama_kendaraan', 'Nama kendaraan', 'required');
        $this->form_validation->set_rules('kapasitas', 'Kapasitas', 'required');

        if ($this->form_validation->run() == FALSE) {
            redirect('vehicle/vehicle_list');
        } else {
            $data = [
                'nama_kendaraan' => $this->input->post('nama_kendaraan'),
                'no_pol' => $this->input->post('no_pol'),
                'kapasitas' => $this->input->post('kapasitas'),
            ];
            $this->vehicle_model->update($id, $data);
            redirect('vehicle/vehicle_list');
        }
    }

    /**
     * Handle create/add new vehicle entry (admin only).
     * Validates new vehicle data and inserts to DB.
     */
    public function storeKendaraan() {
        // Only admins may add vehicles
        if ($this->user_session['role'] !== 'admin') {
            show_error('Access denied.');
            return;
        }
        // Validate that license plate (no_pol) is unique, and required fields are filled
        $this->load->library('form_validation');
        $this->form_validation->set_rules('no_pol', 'No Polisi', 'required|is_unique[PK_kendaraan.no_pol]');
        $this->form_validation->set_rules('nama_kendaraan', 'Nama kendaraan', 'required');
        $this->form_validation->set_rules('kapasitas', 'Kapasitas', 'required');

        if ($this->form_validation->run() == FALSE) {
            // On failure, redirect back to list
            redirect('vehicle/vehicle_list');
        } else {
            // On success, insert new vehicle to database
            $data = [
                'nama_kendaraan' => $this->input->post('nama_kendaraan'), 
                'no_pol' => $this->input->post('no_pol'),
                'kapasitas' => $this->input->post('kapasitas')
            ];
            $this->vehicle_model->insert($data);
            redirect('vehicle/vehicle_list');
        }
    }

    public function delete($id)
    {
        if ($this->user_session['role'] !== 'admin') {
            show_error('Access denied.');
            return;
        }
        $this->vehicle_model->delete($id);
        redirect('vehicle/vehicle_list');
    }
}