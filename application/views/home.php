<!-- <pre><?php var_dump($user_session); ?></pre> -->
<?php $this->load->view('header_footer/header'); ?>

<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Dashboard Pemesanan Kendaraan</h3>
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
            <h2> Berikut adalah jumlah total pesanan kendaraan bulan ini beserta statusnya</h2>
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
          <div class="row" style="margin-bottom:30px;">
            <?php if ($role === 'admin'): ?>
              <!-- Total Orders -->
              <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="x_panel tile fixed_height_150" style="background:#e3e6f7;">
                  <div class="x_title text-center" style="border-bottom:none;">
                    <i class="fa fa-list-alt fa-2x" style="color:#3a4d9c;"></i>
                    <h4 style="color:#3a4d9c; margin-top:10px;">Total Pesanan</h4>
                  </div>
                  <div class="x_content text-center">
                    <strong style="font-size:2em; color:#3a4d9c;"><?= $total_orders ?></strong>
                    <p class="dashboard-caption">Bulan ini</p>
                  </div>
                </div>
              </div>
              <!-- Pending Orders -->
              <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="x_panel tile fixed_height_150" style="background:#fff5e5;">
                  <div class="x_title text-center" style="border-bottom:none;">
                    <i class="fa fa-hourglass-start fa-2x" style="color:#f39c12;"></i>
                    <h4 style="color:#f39c12; margin-top:10px;">Pending</h4>
                  </div>
                  <div class="x_content text-center">
                    <strong style="font-size:2em; color:#f39c12;"><?= $pending_orders ?></strong>
                    <p class="dashboard-caption" style="color:#f39c12;">Belum Disetujui</p>
                  </div>
                </div>
              </div>
              <!-- Approved Orders -->
              <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="x_panel tile fixed_height_150" style="background:#eafaf1;">
                  <div class="x_title text-center" style="border-bottom:none;">
                    <i class="fa fa-check-circle fa-2x" style="color:#27ae60;"></i>
                    <h4 style="color:#27ae60; margin-top:10px;">Approved</h4>
                  </div>
                  <div class="x_content text-center">
                    <strong style="font-size:2em; color:#27ae60;"><?= $approved_orders ?></strong>
                    <p class="dashboard-caption" style="color:#27ae60;">Disetujui</p>
                  </div>
                </div>
              </div>
              <!-- Done Orders -->
              <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="x_panel tile fixed_height_150" style="background:#f5f5f5;">
                  <div class="x_title text-center" style="border-bottom:none;">
                    <i class="fa fa-flag-checkered fa-2x" style="color:#555;"></i>
                    <h4 style="color:#555; margin-top:10px;">Selesai</h4>
                  </div>
                  <div class="x_content text-center">
                    <strong style="font-size:2em; color:#555;"><?= $done_orders ?></strong>
                    <p class="dashboard-caption" style="color:#555;">Pesanan Selesai</p>
                  </div>
                </div>
              </div>
            <?php else: ?>
              <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="x_panel tile fixed_height_150" style="background:#e3e6f7;">
                  <div class="x_title text-center" style="border-bottom:none;">
                    <i class="fa fa-list fa-2x" style="color:#3a4d9c;"></i>
                    <h4 style="color:#3a4d9c; margin-top:10px;">Pesanan Anda</h4>
                  </div>
                  <div class="x_content text-center">
                    <strong style="font-size:2em; color:#3a4d9c;"><?= $user_orders ?></strong>
                    <p class="dashboard-caption">Bulan ini</p>
                  </div>
                </div>
              </div>
            <?php endif; ?>
          </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php $this->load->view('header_footer/footer'); ?>