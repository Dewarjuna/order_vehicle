<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| URI ROUTING
|--------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
| See the user guide for details.
|--------------------------------------------------------------------------
*/

// RESERVED ROUTES
$route['default_controller']    = 'home';
$route['404_override']          = '';
$route['translate_uri_dashes']  = FALSE;

/* ==========================================================
| PRETTY/PUBLIC ORDER ROUTES
========================================================== */
// Users can now access booking with easy URLs

$route['booking']                  = 'order/create';         // Form to book/reserve
$route['my-bookings']              = 'order/order_user';     // See my reservations
$route['booking/view/(:num)']      = 'order/single/$1';      // View details of one booking
$route['booking/update/(:num)']    = 'order/update/$1';      // Update booking

// If you want to allow direct delete via URL, be careful (security)
$route['booking/delete/(:num)']    = 'order/delete/$1';

// Submission (usually POST, but aliased for completeness)
$route['booking/submit']                  = 'order/submit';

/* ==========================================================
| KEEP ORIGINAL BACKEND/AJAX ORDER ROUTES (for admin/tools)
========================================================== */
$route['order/create']                      = 'order/create';
$route['order/submit']                      = 'order/submit';
$route['order/order_user']                  = 'order/order_user';
$route['order/delete/(:num)']               = 'order/delete/$1';
$route['order/update/(:num)']               = 'order/update/$1';
$route['order/single/(:num)']               = 'order/single/$1';

$route['order/pending_orders']              = 'order/pending_orders';
$route['order/approve/(:num)']              = 'order/approve/$1';
$route['order/do_approve_ajax/(:num)']      = 'order/do_approve_ajax/$1';
$route['order/reject_ajax/(:num)']          = 'order/reject_ajax/$1';

$route['order/order_report']                = 'order/order_report';
$route['order/order_report_ajax']           = 'order/order_report_ajax';
$route['order/order_detail_ajax/(:num)']    = 'order/order_detail_ajax/$1';

/* ==========================================================
| USER MANAGEMENT (Admin only) - Prettified
========================================================== */
$route['users']                             = 'user/user_list';            // List all users
$route['users/view/(:num)']                 = 'user/user_detail/$1';       // User profile
$route['users/edit/(:num)']                 = 'user/user_edit/$1';         // Edit user
$route['users/delete/(:num)']               = 'user/delete/$1';            // Delete user
$route['users/update/(:num)']               = 'user/update_user/$1';       // Update user (POST)
$route['users/add']                         = 'user/add_user';             // Add user (POST)

/* Keep original for internal tooling compatibility */
$route['user/user_list']                    = 'user/user_list';
$route['user/user_detail/(:num)']           = 'user/user_detail/$1';
$route['user/user_edit/(:num)']             = 'user/user_edit/$1';
$route['user/add_user']                     = 'user/add_user';
$route['user/delete/(:num)']                = 'user/delete/$1';
$route['user/update_user/(:num)']           = 'user/update_user/$1';

/* ==========================================================
| VEHICLE MANAGEMENT (Admin only) - Prettified
========================================================== */
$route['vehicles']                          = 'vehicle/vehicle_list';           // List all vehicles
$route['vehicles/edit/(:num)']              = 'vehicle/updateKendaraan/$1';     // Edit vehicle
$route['vehicles/delete/(:num)']            = 'vehicle/delete/$1';              // Delete vehicle
$route['vehicles/add']                      = 'vehicle/storeKendaraan';         // Add vehicle

$route['vehicle/vehicle_list']              = 'vehicle/vehicle_list';
$route['vehicle/updateKendaraan/(:num)']    = 'vehicle/updateKendaraan/$1';
$route['vehicle/storeKendaraan']            = 'vehicle/storeKendaraan';
$route['vehicle/delete/(:num)']             = 'vehicle/delete/$1';

/* ==========================================================
| DRIVER MANAGEMENT (Admin only) - Prettified
========================================================== */
$route['drivers']                           = 'driver/driver_list';            // List all drivers
$route['drivers/edit/(:num)']               = 'driver/updateNama/$1';          // Edit driver
$route['drivers/delete/(:num)']             = 'driver/delete/$1';              // Delete driver
$route['drivers/add']                       = 'driver/storeNama';              // Add driver

$route['driver/driver_list']                = 'driver/driver_list';
$route['driver/updateNama/(:num)']          = 'driver/updateNama/$1';
$route['driver/storeNama']                  = 'driver/storeNama';
$route['driver/delete/(:num)']              = 'driver/delete/$1';

/* ==========================================================
| AUTH (Login/Logout) - Prettified (optional, shorter)
========================================================== */
$route['login']                             = 'auth/index';
$route['logout']                            = 'auth/logout';
$route['login/submit']                      = 'auth/login';

$route['auth']                              = 'auth/index';
$route['auth/login']                        = 'auth/login';
$route['auth/logout']                       = 'auth/logout';