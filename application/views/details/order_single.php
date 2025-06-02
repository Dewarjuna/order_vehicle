<?php $this->load->view('header_footer/header'); ?>

<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Detail Pesanan</h3>
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
      <div class="col-md-8 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>
              <i class="fa fa-file-text-o"></i>
              Order ID: <?= htmlspecialchars($pesanan->id) ?>
            </h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <!-- Back button at the top -->
            <div style="margin-bottom:25px;">
              <?php if ($user_session['role'] === 'admin'): ?>
                  <a href="<?= base_url('order/order_report'); ?>" class="btn btn-primary btn-sm">
                      <i class="fa fa-arrow-left"></i> Back
                  </a>
              <?php else: ?>
                  <a href="<?= base_url('order/detail'); ?>" class="btn btn-primary btn-sm">
                      <i class="fa fa-arrow-left"></i> Back
                  </a>
              <?php endif; ?>
            </div>
            <!-- Order details, vertical pretty format -->
            <div class="order-details well well-sm" style="background:#f9f9f9; border-radius:6px; padding:20px;">
              <?php
              $fields = [
                'Tanggal Pesanan' => date('d-m-Y', strtotime($pesanan->tanggal_pesanan)),
                'Nomor Karyawan'  => htmlspecialchars($pesanan->nomor_karyawan),
                'Nama Karyawan'   => htmlspecialchars($pesanan->nama),
                'Divisi'          => htmlspecialchars($pesanan->divisi),
                'Tujuan'          => htmlspecialchars($pesanan->tujuan),
                'Tanggal Pakai'   => date('d-m-Y', strtotime($pesanan->tanggal_pakai)),
                'Waktu Mulai'     => (new DateTime($pesanan->waktu_mulai))->format('H:i:s'),
                'Waktu Selesai'   => (new DateTime($pesanan->waktu_selesai))->format('H:i:s'),
                'Keperluan'       => nl2br(htmlspecialchars($pesanan->keperluan)),
                'Kendaraan' => 
                    !empty($pesanan->no_pol) && !empty($pesanan->nama_kendaraan)
                        ? htmlspecialchars($pesanan->no_pol) . ' (' . htmlspecialchars($pesanan->nama_kendaraan) . ')'
                        : (!empty($pesanan->kendaraan) ? htmlspecialchars($pesanan->kendaraan) : 'Menunggu Persetujuan'),
                'Driver' => !empty($pesanan->nama_driver) ? htmlspecialchars($pesanan->nama_driver) : 'Menunggu Persetujuan',
                'Jumlah Orang'    => (int)$pesanan->jumlah_orang,
                'Pemesan'         => htmlspecialchars($pesanan->pemesan),
              ];

              foreach ($fields as $label => $value): ?>
                <div class="row" style="margin-bottom:15px;">
                  <div class="col-sm-4">
                    <strong style="color:#2A3F54; font-size:14px;"><?= $label ?></strong>
                  </div>
                  <div class="col-sm-8" style="font-size:15px; color:#555;">
                    <?= $value ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php $this->load->view('header_footer/footer'); ?>