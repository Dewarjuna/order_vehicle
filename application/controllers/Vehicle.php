<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Vehicle Controller
 * 
 * Separated from Order controller to:
 * - Allow independent vehicle management
 * - Simplify fleet maintenance operations
 * - Keep vehicle-specific logic isolated
 */
class Vehicle extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('vehicle_model');
    }

    /**
     * List all vehicles—admin-only.
     */
    public function vehicle_list()
    {
        if ($this->user_session['role'] !== 'admin') {
            show_error('Access denied.');
            return;
        }
        $data['vehicles'] = $this->vehicle_model->get_all();
        $this->load->view('admin/vehicle/vehicle_list', $data);
    }

    /**
     * Update vehicle record via POST, admin-only.
     */
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
     * Add new vehicle (admin-only).
     */
    public function storeKendaraan() {
        if ($this->user_session['role'] !== 'admin') {
            show_error('Access denied.');
            return;
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('no_pol', 'No Polisi', 'required|is_unique[PK_kendaraan.no_pol]');
        $this->form_validation->set_rules('nama_kendaraan', 'Nama kendaraan', 'required');
        $this->form_validation->set_rules('kapasitas', 'Kapasitas', 'required');

        if ($this->form_validation->run() == FALSE) {
            redirect('vehicle/vehicle_list');
        } else {
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