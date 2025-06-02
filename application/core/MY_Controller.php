<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * MY_Controller - A Core Extension of CodeIgniter's Base Controller
 * 
 * WHY THIS EXISTS:
 * 1. CodeIgniter allows extending core classes by prefixing them with 'MY_'.
 *    This is a design pattern known as "Core Extension" in CodeIgniter.
 * 
 * 2. Instead of duplicating session checks, user authentication, and timezone setup
 *    in every controller, we centralize these common requirements here.
 *    This follows the DRY (Don't Repeat Yourself) principle.
 * 
 * 3. By having all controllers extend this class instead of CI_Controller,
 *    we ensure consistent behavior across the entire application:
 *    - Unified session management
 *    - Standardized authentication checks
 *    - Consistent timezone handling
 *    - Common data available to all views
 * 
 * 4. This pattern makes the application more maintainable because:
 *    - Changes to core behavior only need to be made in one place
 *    - New common functionality can be easily added for all controllers
 *    - Security policies are enforced consistently
 */
class MY_Controller extends CI_Controller
{
    /**
     * Holds user session data in a standardized format.
     * This prevents inconsistent session access patterns across controllers
     * and provides a single source of truth for user information.
     */
    public $user_session = array();

    public function __construct()
    {
        parent::__construct();
        
        // Set timezone for consistent datetime handling across the application
        // This is crucial for a booking system where time accuracy is important
        date_default_timezone_set('Asia/Jakarta');

        /**
         * Centralize session data access
         * 
         * WHY:
         * 1. Prevents scattered session access throughout controllers
         * 2. Makes it easy to modify session structure in one place
         * 3. Ensures consistent data format for user information
         */
        $this->user_session = array(
            'user_id'  => $this->session->userdata('user_id'),
            'username' => $this->session->userdata('username'),
            'nama'     => $this->session->userdata('nama'),
            'role'     => $this->session->userdata('role')
        );

        /**
         * Make user data available to all views automatically
         * 
         * WHY:
         * 1. Eliminates need to manually pass user data in every controller method
         * 2. Ensures consistent user data access in all views
         * 3. Reduces chance of missing user data in views
         */
        $this->load->vars('user_session', $this->user_session);

        // Get current controller for authentication logic
        $controller = $this->router->fetch_class();

        /**
         * Centralized Authentication Check
         * 
         * WHY:
         * 1. Security should be enforced at the highest possible level
         * 2. Prevents accidentally forgetting auth checks in controllers
         * 3. Makes it easy to modify auth logic for the entire application
         * 4. Provides consistent redirect behavior for unauthenticated users
         * 5. Handles AJAX requests differently to prevent UI glitches
         */
        if (empty($this->user_session['user_id']) && $controller != 'auth') {
            $this->session->set_flashdata('session_expired', 'Your session has expired due to inactivity. Please log in again.');
            
            // Handle AJAX requests differently
            if ($this->input->is_ajax_request()) {
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'error',
                    'code' => 'session_expired',
                    'message' => 'Your session has expired. Please refresh the page to log in again.'
                ]);
                exit;
            }
            
            redirect('auth');
        }
    }
}