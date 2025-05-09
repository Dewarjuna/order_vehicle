<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vehicle extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('vehicle_model');
    }

    // List all vehicles
    public function vehicle_list()
    {
        // Only admin can access
        if ($this->user_session['role'] !== 'admin') {
            show_error('Access denied.');
            return;
        }else{

            $data['vehicles'] = $this->vehicle_model->get_all();
            $this->load->view('admin/vehicle/vehicle_list', $data);
    
        }
    }

    
        // Show form to add a new vehicle
        public function updateKendaraan() {
            if ($this->user_session['role'] !== 'admin') {
                show_error('Access denied.');
                return;
            }
            $this->load->library('form_validation');
            $this->form_validation->set_rules('no_pol', 'No Polisi', 'required|is_unique[PK_kendaraan.no_pol]');
            $this->form_validation->set_rules('nama_kendaraan', 'Nama kendaraan', 'required');
            $this->form_validation->set_rules('kapasitas', 'Kapasitas', 'required');
            if ($this->form_validation->run() == FALSE) {
                // You may want to handle errors (e.g., pass errors back to the view)
                redirect('vehicle/vehicle_list');
            } else {
                $data = [
                    'nama_kendaraan' => $this->input->post('nama_kendaraan'), 
                    'no_pol' => $this->input->post('no_pol'),
                    'kapasitas' => $this->input->post('kapasitas')
                ];
                $this->vehicle_model->update($data);
                redirect('vehicle/vehicle_list');
            }
        }

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
                // You may want to handle errors (e.g., pass errors back to the view)
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
}