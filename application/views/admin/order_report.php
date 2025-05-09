<?php $this->load->view('header_footer/header'); ?>

<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Laporan Pesanan Disetujui</h3>
      </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Daftar Pesanan Approved</h2>
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

            <!-- Orders Table -->
            <div class="table-responsive">
              <table class="table table-striped jambo_table">
                <thead>
                  <tr class="headings">
                    <th class="column-title">No.</th>
                    <th class="column-title">Tanggal Pakai</th>
                    <th class="column-title">Nama</th>
                    <th class="column-title">Divisi</th>
                    <th class="column-title">Tujuan</th>
                    <th class="column-title">Kendaraan</th>
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
                        <td><?= htmlspecialchars($row->divisi); ?></td>
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
                        <td><?= htmlspecialchars($row->status); ?></td>
                        <td>
                        <a href="<?php echo site_url('order/single/'.$row->id); ?>" class="btn btn-info btn-xs last">View</a>
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