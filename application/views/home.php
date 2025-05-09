<!-- <pre><?php var_dump($user_session); ?></pre> -->
<?php $this->load->view('header_footer/header'); ?>

<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Plain Page</h3>
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
            <h2>Plain Page</h2>
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
              <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="x_panel tile fixed_height_150">
                  <div class="x_title"><h4>Total Pesanan (Bulan ini)</h4></div>
                  <div class="x_content text-center"><strong style="font-size:2em;"><?= $total_orders ?></strong></div>
                </div>
              </div>
              <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="x_panel tile fixed_height_150">
                  <div class="x_title"><h4>Pesanan Belum Diseteujui (Bulan ini)</h4></div>
                  <div class="x_content text-center"><strong style="font-size:2em;"><?= $pending_orders ?></strong></div>
                </div>
              </div>
              <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="x_panel tile fixed_height_150">
                  <div class="x_title"><h4>Pesanan Telah Disetujui (Bulan ini)</h4></div>
                  <div class="x_content text-center"><strong style="font-size:2em;"><?= $approved_orders ?></strong></div>
                </div>
              </div>
            <?php else: ?>
              <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="x_panel tile fixed_height_150">
                  <div class="x_title"><h4>Your Orders (This Month)</h4></div>
                  <div class="x_content text-center"><strong style="font-size:2em;"><?= $user_orders ?></strong></div>
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