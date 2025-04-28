<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	  
    <title>Gentelella Alela! | </title>

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
    <!-- Custom Theme Style -->
    <link href="<?php echo base_url('assets/build/css/custom.min.css'); ?>" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="index.html" class="site_title"><i class="fa fa-paw"></i> <span>Gentelella Alela!</span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile clearfix">
              <div class="profile_pic">
                <img src="<?php echo base_url("assets/production/images/img.jpg") ?>" alt="..." class="img-circle profile_img">
              </div>
              <div class="profile_info">
                <span>Welcome,</span>
                <h2><?php echo isset($nama) ? $nama : 'Guest'; ?></h2>
                <small>Role: <?php echo isset($role) ? $role : '-'; ?></small>
              </div>
              <div class="clearfix"></div>
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
                      <li><a href="<?php echo base_url('index.php/welcome/index'); ?>">Dashboard</a></li>
                      <li><a href="<?php echo base_url('index.php/order/submit'); ?>">Pesan Kendaraan</a></li>
                      <li><a href="index3.html">Detail Pesanan</a></li>
                    </ul>
                  </li>
                </ul>
              </div>
            </div>
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            <div class="sidebar-footer hidden-small">
              <a data-toggle="tooltip" data-placement="top" title="Settings">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Lock">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Logout" href="<?php echo site_url('auth/logout'); ?>">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
              </a>
            </div>
            <!-- /menu footer buttons -->
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
                    <img src="<?php echo base_url('assets/production/images/img.jpg') ; ?>" alt="">John Doe
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
                    <li><a href="login.html"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                  </ul>
                </li>

                <li role="presentation" class="dropdown">
                  <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-envelope-o"></i>
                    <span class="badge bg-green">6</span>
                  </a>
                  <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                    <li>
                      <a>
                        <span class="image"><img src="<?php echo base_url('assets/production/images/img.jpg') ; ?>" alt="Profile Image" /></span>
                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                      </a>
                    </li>
                    <li>
                      <a>
                        <span class="image"><img src="<?php echo base_url('assets/production/images/img.jpg') ; ?>" alt="Profile Image" /></span>
                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                      </a>
                    </li>
                    <li>
                      <a>
                        <span class="image"><img src="<?php echo base_url('assets/production/images/img.jpg') ; ?>" alt="Profile Image" /></span>
                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                      </a>
                    </li>
                    <li>
                      <a>
                        <span class="image"><img src="<?php echo base_url('assets/production/images/img.jpg') ; ?>" alt="Profile Image" /></span>
                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                      </a>
                    </li>
                    <li>
                      <div class="text-center">
                        <a>
                          <strong>See All Alerts</strong>
                          <i class="fa fa-angle-right"></i>
                        </a>
                      </div>
                    </li>
                  </ul>
                </li>
              </ul>
            </nav>
          </div>
        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Form Elements</h3>
              </div>

              <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button">Go!</button>
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Form Design <small>different form elements</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                          <li><a href="#">Settings 1</a>
                          </li>
                          <li><a href="#">Settings 2</a>
                          </li>
                        </ul>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" method="post" action="<?php echo site_url('order/submit'); ?>">

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tanggal_pesanan">
                        Tanggal Pesan <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="input-group date" id="tanggal_pesanan">
                          <input type="text" id="tanggal_pesanan" name="tanggal_pesanan" required="required" class="form-control col-md-7 col-xs-12">
                          <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                          </span>
                        </div>
                      </div>
                    </div>
                      
                      <?php
                      // Group users by divisi
                      $grouped_users = [];
                      foreach ($users as $user) {
                          $grouped_users[$user->divisi][] = $user;
                      }
                      ?>
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor Karyawan</label>
                          <div class="col-md-9 col-sm-9 col-xs-12">
                              <select class="select2_group form-control" id="nomor_karyawan" name="nomor_karyawan">
                                  <?php foreach ($grouped_users as $divisi => $users_in_divisi): ?>
                                      <optgroup label="<?= htmlspecialchars($divisi) ?>">
                                          <?php foreach ($users_in_divisi as $user): ?>
                                              <option 
                                                  value="<?= htmlspecialchars($user->nomor_karyawan) ?>"
                                                  data-nama="<?= htmlspecialchars($user->nama) ?>"
                                                  data-divisi="<?= htmlspecialchars($user->divisi) ?>"
                                              >
                                                  <?= htmlspecialchars($user->nomor_karyawan) ?>
                                              </option>
                                          <?php endforeach; ?>
                                      </optgroup>
                                  <?php endforeach; ?>
                              </select>
                          </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Nama Karyawan<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="nama" name="nama" required="required" class="form-control col-md-7 col-xs-12" readonly>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="divisi">Bagian <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="divisi" name="divisi" required="required" class="form-control col-md-7 col-xs-12" readonly>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tujuan">Tujuan <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="tujuan" name="tujuan" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tanggal_pakai">
                        Tanggal Pakai <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="input-group date" id="tanggal_pakai">
                          <input type="text" id="tanggal_pakai" name="tanggal_pakai" required="required" class="form-control col-md-7 col-xs-12">
                          <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                          </span>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="waktu_mulai">Start<span class="required">*</span></label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="input-group date" id="waktu_mulai_group">
                          <input type="text" id="waktu_mulai" name="waktu_mulai" required="required" class="form-control col-md-7 col-xs-12">
                          <span class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                          </span>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="waktu_selesai">Finish<span class="required">*</span></label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="input-group date" id="waktu_selesai_group">
                          <input type="text" id="waktu_selesai" name="waktu_selesai" required="required" class="form-control col-md-7 col-xs-12">
                          <span class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                          </span>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="keperluan">Keperluan<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="keperluan" name="keperluan" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jumlah_orang">Jumlah Orang<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="number" id="jumlah_orang" name="jumlah_orang" required="required" class="form-control col-md-7 col-xs-12" min="1" step="1">
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                          <button class="btn btn-primary" type="button">Cancel</button>
						  <button class="btn btn-primary" type="reset">Reset</button>
                          <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                      </div>

                    </form>
                  </div>
                </div>
              </div>
            </div>
            </div>
          </div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
          <div class="pull-right">
            Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>

    <!-- jQuery -->
    <script src="<?php echo base_url('assets/vendors/jquery/dist/jquery.min.js'); ?>"></script>
    <!-- Bootstrap -->
    <script src="<?php echo base_url('assets/vendors/bootstrap/dist/js/bootstrap.min.js'); ?>"></script>
    <!-- FastClick -->
    <script src="<?php echo base_url('assets/vendors/fastclick/lib/fastclick.js'); ?>"></script>
    <!-- NProgress -->
    <script src="<?php echo base_url('assets/vendors/nprogress/nprogress.js'); ?>"></script>
    <!-- bootstrap-progressbar -->
    <script src="<?php echo base_url('assets/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js'); ?>"></script>
    <!-- iCheck -->
    <script src="<?php echo base_url('assets/vendors/iCheck/icheck.min.js'); ?>"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="<?php echo base_url('assets/vendors/moment/min/moment.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendors/bootstrap-daterangepicker/daterangepicker.js'); ?>"></script>
    <!-- bootstrap-wysiwyg -->
    <script src="<?php echo base_url('assets/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendors/jquery.hotkeys/jquery.hotkeys.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendors/google-code-prettify/src/prettify.js'); ?>"></script>
    <!-- jQuery Tags Input -->
    <script src="<?php echo base_url('assets/vendors/jquery.tagsinput/src/jquery.tagsinput.js'); ?>"></script>
    <!-- Switchery -->
    <script src="<?php echo base_url('assets/vendors/switchery/dist/switchery.min.js'); ?>"></script>
    <!-- Select2 -->
    <script src="<?php echo base_url('assets/vendors/select2/dist/js/select2.full.min.js'); ?>"></script>
    <!-- Parsley -->
    <script src="<?php echo base_url('assets/vendors/parsleyjs/dist/parsley.min.js'); ?>"></script>
    <!-- Autosize -->
    <script src="<?php echo base_url('assets/vendors/autosize/dist/autosize.min.js'); ?>"></script>
    <!-- jQuery autocomplete -->
    <script src="<?php echo base_url('assets/vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js'); ?>"></script>
    <!-- starrr -->
    <script src="<?php echo base_url('assets/vendors/starrr/dist/starrr.js'); ?>"></script>
    <!-- Custom Theme Scripts -->
    <script src="<?php echo base_url('assets/build/js/custom.min.js'); ?>"></script>
    <!-- bootstrap-datetimepicker -->    
    <script src="<?php echo base_url('assets/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js'); ?>"</script>
    <!-- Ion.RangeSlider -->
    <script src="<?php echo base_url('assets/vendors/ion.rangeSlider/js/ion.rangeSlider.min.js'); ?>"></script>
    <!-- Initialize datetimepicker -->
<script>
window.onload = function() {
    var today = new Date();

    $('#tanggal_pesanan').datetimepicker({
        format: 'DD-MM-YYYY',
        minDate: today
    });

    $('#tanggal_pakai').datetimepicker({
        format: 'DD-MM-YYYY',
        minDate: today
    });

    $('#waktu_mulai_group').datetimepicker({
        format: 'HH:mm'
    });

    $('#waktu_selesai_group').datetimepicker({
        format: 'HH:mm',
        useCurrent: false
    });

    $('#waktu_mulai_group').on("dp.change", function(e) {
        $('#waktu_selesai_group').data("DateTimePicker").minDate(e.date);
    });

    $('#waktu_selesai_group').on("dp.change", function(e) {
        $('#waktu_mulai_group').data("DateTimePicker").maxDate(e.date);
    });

    $('#myDatepicker4').datetimepicker({
        ignoreReadonly: true,
        allowInputToggle: true
    });

    $('#datetimepicker6').datetimepicker();

    $('#datetimepicker7').datetimepicker({
        useCurrent: false
    });

    $('#datetimepicker6').on("dp.change", function(e) {
        $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
    });

    $('#datetimepicker7').on("dp.change", function(e) {
        $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
    });
};
</script>
<script>
$(document).ready(function() {
    // When the select changes
    $('.select2_group').on('change', function() {
        var selected = $(this).find('option:selected');
        $('#nama').val(selected.data('nama'));
        $('#divisi').val(selected.data('divisi'));
    });

    // Trigger change on page load to auto-fill if default selected
    $('.select2_group').trigger('change');
});
</script>
  </body>
</html>
