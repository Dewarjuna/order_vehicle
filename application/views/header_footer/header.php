<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Pemesanan Kendaraan | </title>

    <!-- Bootstrap -->
    <link href="<?php echo base_url('assets/vendors/bootstrap/dist/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo base_url('assets/vendors/font-awesome/css/font-awesome.min.css'); ?>" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?php echo base_url('assets/vendors/nprogress/nprogress.css'); ?>" rel="stylesheet">
    <!-- iCheck -->
    <link href="<?php echo base_url('assets/vendors/iCheck/skins/flat/green.css'); ?>" rel="stylesheet">
    <!-- bootstrap-wysiwyg -->
    <link href="<?php echo base_url('assets/vendors/google-code-prettify/bin/prettify.min.css'); ?>" rel="stylesheet">
    <!-- Select2 -->
    <link href="<?php echo base_url('assets/vendors/select2/dist/css/select2.min.css'); ?>" rel="stylesheet">
    <!-- Switchery -->
    <link href="<?php echo base_url('assets/vendors/switchery/dist/switchery.min.css'); ?>" rel="stylesheet">
    <!-- starrr -->
    <link href="<?php echo base_url('assets/vendors/starrr/dist/starrr.css'); ?>" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="<?php echo base_url('assets/vendors/bootstrap-daterangepicker/daterangepicker.css'); ?>" rel="stylesheet">
    <!-- bootstrap-datetimepicker -->
    <link href="<?php echo base_url('assets/vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css'); ?>" rel="stylesheet">
    <!-- Ion.RangeSlider -->
    <link href="<?php echo base_url('assets/vendors/normalize-css/normalize.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/vendors/ion.rangeSlider/css/ion.rangeSlider.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/vendors/ion.rangeSlider/css/ion.rangeSlider.skinFlat.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/vendors/sweetalert2/dist/sweetalert2.min.css'); ?>" rel="stylesheet">
	
    <!-- bootstrap-progressbar -->
    <link href="<?php echo base_url('assets/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css'); ?>" rel="stylesheet">
    <!-- JQVMap -->
    <link href="<?php echo base_url('assets/vendors/jqvmap/dist/jqvmap.min.css'); ?>" rel="stylesheet"/>
    <!-- Datatables -->
    <link href="<?php echo base_url('assets/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css'); ?>" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="<?php echo base_url('assets/build/css/custom.min.css'); ?>" rel="stylesheet">

    <style>
      .sidebar-logout .btn {
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        transition: background 0.2s;
      }
      .sidebar-logout .btn:hover {
        background: #c9302c;
        color: #fff;
      }
    </style>
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="<?php echo base_url('index.php/home'); ?>" class="site_title"><i class="fa fa-car"></i> <span style="font-size: 21px;">Pesan Kendaraan</span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile clearfix">
              <div class="profile_pic">
                <img src="<?php echo base_url("assets/production/images/img.jpg") ?>" alt="..." class="img-circle profile_img">
              </div>
              <div class="profile_info">
                <span>Welcome</span>
                <h2><?php echo !empty($user_session['nama']) ? $user_session['nama'] : 'User'; ?></h2>
                <h2>Role: <?php echo !empty($user_session['role']) ? $user_session['role'] : '-'; ?></h2>
              </div>
              <div class="clearfix"></div>

              <!-- ------------------------------
                   ADDED LOGOUT BUTTON UNDER PROFILE
                ------------------------------- -->
              <div class="sidebar-logout text-center" style="margin-top:10px;">
                <a href="<?php echo site_url('auth/logout'); ?>" 
                  class="btn btn-danger btn-sm"
                  style="border-radius:16px; font-weight:bold; padding: 4px 18px;">
                  <i class="fa fa-sign-out"></i> Logout
                </a>
              </div>
              <!-- END LOGOUT BUTTON -->
            </div>
            <!-- /menu profile quick info -->

            <br />

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                  <li><a><i class="fa fa-home"></i> Home <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?php echo base_url('index.php/home/'); ?>">Dashboard</a></li>
                      <li><a href="<?php echo base_url('index.php/booking'); ?>">Pesan Kendaraan</a></li>
                      <li><a href="<?php echo base_url('index.php/my-bookings'); ?>">List Pesanan</a></li>

                      <li>
                          <a href="<?php echo base_url('index.php/order/pending_orders'); ?>">Menunggu Persetujuan</a>
                      </li>
                      <?php if ($user_session['role'] === 'admin'): ?>
                      <li>
                          <a href="<?php echo base_url('index.php/order/order_report'); ?>">Laporan Pesanan</a>
                      </li>
                      <?php endif; ?>
                    </ul>
                  </li>
                  <?php if ($user_session['role'] === 'admin'): ?>
                  <li><a><i class="fa fa-info-circle"></i> Master Data <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu">
                      <li>
                          <a href="<?php echo base_url('index.php/users'); ?>">Daftar Pengguna</a>
                      </li>
                      <li>
                          <a href="<?php echo base_url('index.php/drivers'); ?>">Daftar Driver</a>
                      </li>
                      <li>
                          <a href="<?php echo base_url('index.php/vehicles'); ?>">Daftar Kendaraan</a>
                      </li>
                  </ul>
                  </li>
                  <?php endif; ?>
                </ul>
              </div>
            </div>
            
            <!-- /sidebar menu -->
          </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav>
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>

              <ul class="nav navbar-nav navbar-right">
                <li class="">
                  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <img src="<?php echo base_url("assets/production/images/img.jpg") ?>" alt=""><?php echo $user_session['nama']; ?>
                    <span class=" fa fa-angle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li><a href="javascript:;"> Profile</a></li>
                    <li>
                      <a href="javascript:;">
                        <span class="badge bg-red pull-right">50%</span>
                        <span>Settings</span>
                      </a>
                    </li>
                    <li><a href="javascript:;">Help</a></li>
                    <li><a href="<?php echo site_url('auth/logout'); ?>"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                  </ul>
                </li>
              </ul>
            </nav>
          </div>
        </div>
        <!-- /top navigation -->