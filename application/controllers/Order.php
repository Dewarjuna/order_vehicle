<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('order_model');
    }

    public function create() {
        $users = $this->order_model->getusers_all();
        $this->load->view('order_form', ['users' => $users]);
    }

    // Helper function to convert d-m-Y to Y-m-d
    private function convert_date($date) {
        $d = DateTime::createFromFormat('d-m-Y', $date);
        return $d ? $d->format('Y-m-d') : null;
    }

    public function submit() {
        $this->load->helper('form');
        $this->load->library('form_validation');

        // Validation rules
        $this->form_validation->set_rules('tanggal_pesanan', 'Tanggal Pesanan', 'required');
        $this->form_validation->set_rules('nomor_karyawan', 'Nomor Karyawan', 'required');
        $this->form_validation->set_rules('nama', 'Nama Karyawan', 'required');
        $this->form_validation->set_rules('divisi', 'Divisi', 'required');
        $this->form_validation->set_rules('tujuan', 'Tujuan', 'required');
        $this->form_validation->set_rules('tanggal_pakai', 'Tanggal Pakai', 'required');
        $this->form_validation->set_rules('waktu_mulai', 'Waktu Mulai', 'required');
        $this->form_validation->set_rules('waktu_selesai', 'Waktu Selesai', 'required');
        $this->form_validation->set_rules('keperluan', 'Keperluan', 'required');
        $this->form_validation->set_rules('jumlah_orang', 'Jumlah Orang', 'required|integer');

        if ($this->form_validation->run() == FALSE) {
            // Show validation errors for debugging
            echo validation_errors();
            $users = $this->order_model->getusers_all();
            $this->load->view('order_form', ['users' => $users]);
            return;
        }

        // Convert dates to Y-m-d for SQL Server
        $tanggal_pesanan = $this->convert_date($this->input->post('tanggal_pesanan'));
        $tanggal_pakai   = $this->convert_date($this->input->post('tanggal_pakai'));

        // Prepare data for insert
        $data = array(
            'tanggal_pesanan' => $tanggal_pesanan,
            'nomor_karyawan'  => $this->input->post('nomor_karyawan'),
            'nama'   => $this->input->post('nama'),
            'divisi'          => $this->input->post('divisi'),
            'tujuan'          => $this->input->post('tujuan'),
            'tanggal_pakai'   => $tanggal_pakai,
            'waktu_mulai'     => $this->input->post('waktu_mulai'),
            'waktu_selesai'   => $this->input->post('waktu_selesai'),
            'keperluan'       => $this->input->post('keperluan'),
            'kendaraan'       => $this->input->post('kendaraan'),
            'jumlah_orang'    => $this->input->post('jumlah_orang')
        );

        // Debug: log the data
        log_message('debug', 'Data submitted: ' . print_r($data, true));

        // Insert into database
        if ($this->order_model->insert_order($data)) {
            $this->load->view('order_success', $data);
        } else {
            // Show SQL error for debugging
            echo $this->db->last_query();
            echo '<br>';
            print_r($this->db->error());
            log_message('error', 'Gagal menyimpan data ke database.');
            show_error('Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }
}