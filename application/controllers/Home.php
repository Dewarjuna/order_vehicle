<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Home Controller
 * 
 * This controller manages the vehicle booking dashboard which serves two distinct user types:
 * - Admins: Who need a comprehensive overview of all bookings with filtering capabilities
 * - Regular users: Who need a simplified view of just their own bookings
 * 
 * The dashboard uses AJAX for real-time updates to maintain responsiveness when filtering,
 * rather than full page reloads, improving the user experience especially with large datasets.
 */
class Home extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('auth_model');
        $this->load->model('order_model');
    }

    /**
     * Dashboard entry point that handles both admin and user views
     * 
     * We chose to combine both views in one method because:
     * 1. They share similar data loading patterns
     * 2. The logic for determining which view to show is simple (role-based)
     * 3. It makes it easier to maintain consistency between admin/user experiences
     */
    public function index()
    {   
        date_default_timezone_set('Asia/Jakarta');

        if (!$this->session->userdata('user_id')) {
            redirect('auth');
        }

        $this->order_model->autoUpdateStatus_driver_kendaraan();
        $this->order_model->autoUpdateNoConfirmationStatus();
        
        $role = $this->session->userdata('role');
        $nama = $this->session->userdata('nama');
        
        // Get current year and month
        $current_year = date('Y');
        $current_month = date('m');
        
        // Create array of months for current year up to current month
        $months = [];
        for ($i = 1; $i <= $current_month; $i++) {
            $months[] = sprintf('%s-%02d', $current_year, $i);
        }

        if ($role === 'admin') {
            $data['total_orders']    = $this->order_model->count_orders_by_months($months);
            $data['pending_orders']  = $this->order_model->count_orders_by_months_status($months, 'pending');
            $data['approved_orders'] = $this->order_model->count_orders_by_months_status($months, 'approved');
            $data['done_orders']     = $this->order_model->count_orders_by_months_status($months, 'done');
            $data['rejected_orders'] = $this->order_model->count_orders_by_months_status($months, 'rejected');
            $data['no_confirmation_orders'] = $this->order_model->count_orders_by_months_status($months, 'no confirmation');
            
            // Get status from URL parameter if exists
            $status = $this->input->get('status');
            if ($status) {
                $data['status_orders'] = $this->order_model->get_orders_by_status_and_months($status, $months);
                $data['selected_status'] = $status;
            }
        } else {
            $data['user_orders'] = $this->order_model->count_user_orders_by_month($current_month, $nama);
        }

        $data['username'] = $this->session->userdata('username');
        $data['nama']     = $nama;
        $data['role']     = $role;
        $data['current_year'] = $current_year;

        $this->load->view('home', $data);
    }

    /**
     * AJAX endpoint for updating dashboard tile counts
     * 
     * This endpoint exists separately from the main table data because:
     * 1. Tile counts need to update more frequently than the full table
     * 2. It's more efficient to send just the counts rather than full order data
     * 3. The tiles and table can be updated independently for better UX
     */
    public function ajax_status_tile_counts(){
        if (!$this->input->is_ajax_request()) {
            show_error('No direct script access allowed', 403);
            return;
        }

        $selected_months = $this->input->post('months');
        if (!is_array($selected_months) || empty($selected_months)) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Please select at least one month'
            ]);
            return;
        }

        // Sort months chronologically
        sort($selected_months);
        $last_selected = end($selected_months);
        
        // Get all months from start of year up to selected month
        $year = substr($last_selected, 0, 4);
        $month = substr($last_selected, 5, 2);
        $months = [];
        for ($i = 1; $i <= intval($month); $i++) {
            $months[] = sprintf('%s-%02d', $year, $i);
        }

        $results = array(
            'status' => 'success',
            'data' => array(
                'total_orders' => $this->order_model->count_orders_by_months($months),
                'pending_orders' => $this->order_model->count_orders_by_months_status($months, 'pending'),
                'approved_orders' => $this->order_model->count_orders_by_months_status($months, 'approved'),
                'done_orders' => $this->order_model->count_orders_by_months_status($months, 'done'),
                'rejected_orders' => $this->order_model->count_orders_by_months_status($months, 'rejected'),
                'no_confirmation_orders' => $this->order_model->count_orders_by_months_status($months, 'no confirmation')
            )
        );

        header('Content-Type: application/json');
        echo json_encode($results);
    }

    /**
     * AJAX endpoint for loading filtered order tables
     * 
     * We handle this as a separate endpoint rather than loading with initial page because:
     * 1. It allows for dynamic filtering without page reloads
     * 2. Reduces initial page load time by loading table data on-demand
     * 3. Maintains state of filters when users navigate back to dashboard
     * 
     * The table view is loaded as a partial to maintain consistency across all table instances
     * and make it easier to modify the table structure in one place.
     */
    public function ajax_get_orders_table() {
        if (!$this->input->is_ajax_request()) {
            show_error('No direct script access allowed', 403);
            return;
        }

        // Check session first
        if (!$this->session->userdata('user_id')) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'code' => 'session_expired',
                'message' => 'Your session has expired. Please refresh the page to log in again.'
            ]);
            return;
        }

        $status = $this->input->post('status');
        $selected_months = $this->input->post('months');
        
        if (!$status) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Status tidak valid'
            ]);
            return;
        }

        // If months are selected, get all months up to the last selected month
        if (!empty($selected_months) && is_array($selected_months)) {
            sort($selected_months);
            $last_selected = end($selected_months);
            $year = substr($last_selected, 0, 4);
            $month = substr($last_selected, 5, 2);
            $months = [];
            for ($i = 1; $i <= intval($month); $i++) {
                $months[] = sprintf('%s-%02d', $year, $i);
            }
        } else {
            // If no months selected, get current year's months
            $current_year = date('Y');
            $current_month = date('m');
            $months = [];
            for ($i = 1; $i <= $current_month; $i++) {
                $months[] = sprintf('%s-%02d', $current_year, $i);
            }
        }
        
        $data['status_orders'] = $this->order_model->get_orders_by_status_and_months($status, $months);
        $data['selected_status'] = $status;
        
        // Load the table view
        $this->load->view('partials/orders_table', $data);
    }

}