<?php $this->load->view('header_footer/header'); ?>

<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Laporan Pesanan</h3>
      </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <!-- <h2>Daftar Pesanan Approved</h2> -->
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <!-- Filter Form -->
            <form method="get" class="form-inline" style="margin-bottom:20px;">
              <div class="form-group">
                <label for="date_from">Tanggal Pakai:</label>
                <input type="date" id="date_from" name="date_from" class="form-control" value="<?= htmlspecialchars($date_from) ?>">
              </div>
              <span style="margin:0 8px;">s/d</span>
              <div class="form-group">
                <input type="date" id="date_to" name="date_to" class="form-control" value="<?= htmlspecialchars($date_to) ?>">
              </div>
              <button type="submit" class="btn btn-primary">Filter</button>
              <a href="<?= site_url('order/order_report'); ?>" class="btn btn-default">Reset</a>
            </form>

            <style>
            /* Center BOTH table headers and table cells */
            .table th, .table td {
                text-align: center !important;
                vertical-align: middle !important;
            }
            </style>

            <!-- Orders Table -->
            <div class="table-responsive">
              <table class="table table-striped jambo_table">
                <thead>
                  <tr class="headings">
                    <th class="column-title">No.</th>
                    <th class="column-title">Tanggal Pakai</th>
                    <th class="column-title">Pemakai</th>
                    <th class="column-title">Tujuan</th>
                    <th class="column-title">Kendaraan</th>
                    <th class="column-title">Driver</th>
                    <th class="column-title">Status</th>
                    <th class="column-title no-link last"><span class="nobr">Aksi</span></th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($orders)): ?>
                    <?php $no = 1 + ($this->input->get('page') ? (int)$this->input->get('page') : 0); ?>
                    <?php foreach ($orders as $row): ?>
                      <tr>
                        <td><?= $no++; ?></td>
                        <td><?= date('d-m-Y', strtotime($row->tanggal_pakai)); ?></td>
                        <td><?= htmlspecialchars($row->nama); ?></td>
                        <td><?= htmlspecialchars($row->tujuan); ?></td>
                        <td>
                            <?php if (!empty($row->no_pol) && !empty($row->nama_kendaraan)): ?>
                              <?= htmlspecialchars($row->no_pol) ?> (<?= htmlspecialchars($row->nama_kendaraan) ?>)
                            <?php elseif (!empty($row->kendaraan)): ?>
                              <?= htmlspecialchars($row->kendaraan) ?>
                            <?php else: ?>
                              Menunggu Persetujuan
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($row->nama_driver)): ?>
                                <?= htmlspecialchars($row->nama_driver); ?>
                            <?php elseif (!empty($row->driver)): ?>
                                <?= htmlspecialchars($row->driver); ?>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                          <?php
                            $status = strtolower($row->status);
                            if ($status === 'approved') {
                                echo '<span class="label label-primary" style="font-size:14px;">Telah disetujui - Ongoing</span>';
                            } elseif ($status === 'done') {
                                echo '<span class="label label-success" style="font-size:14px;">Selesai</span>';
                            } elseif ($status === 'pending') {
                                echo '<span class="label label-warning" style="font-size:14px;">Menunggu</span>';
                            } elseif ($status === 'rejected') {
                                echo '<span class="label label-danger" style="font-size:14px;">Ditolak</span>';
                            } else {
                                echo '<span style="font-size:14px;">'.htmlspecialchars($row->status).'</span>';
                            }
                          ?>
                        </td>
                        <td>
                        <a href="<?php echo site_url('order/single/'.$row->id); ?>" class="btn btn-info btn-xs last">Detail</a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr><td colspan="8" class="text-center">Tidak ada data pesanan disetujui.</td></tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
            <!-- Pagination -->
            <div>
              <?= $pagination; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php $this->load->view('header_footer/footer'); ?>