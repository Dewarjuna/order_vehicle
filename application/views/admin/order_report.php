<?php $this->load->view('header_footer/header'); ?>

<?php
// Time formatting helper - place ONCE
function format_time($input) {
    if (!$input) return '-';
    $parts = explode('.', $input);
    $raw = $parts[0];
    $t = date_create_from_format('H:i:s', $raw);
    if ($t) return date_format($t, 'H:i');
    if (preg_match('/^\d{2}:\d{2}$/', $input)) return $input;
    return substr($input,0,5);
}
?>

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
                            <?php
                              $status = strtolower($row->status);
                              if ($status === 'rejected' || $status === 'no confirmation') {
                                  echo '<em class="text-muted">- Tidak Berlaku -</em>';
                              } elseif (!empty($row->no_pol) && !empty($row->nama_kendaraan)) {
                                  echo htmlspecialchars($row->no_pol) . ' (' . htmlspecialchars($row->nama_kendaraan) . ')';
                              } elseif (!empty($row->kendaraan)) {
                                  echo htmlspecialchars($row->kendaraan);
                              } else {
                                  echo 'Menunggu Persetujuan';
                              }
                            ?>
                        </td>
                        <td>
                            <?php
                              if ($status === 'rejected' || $status === 'no confirmation') {
                                  echo '<em class="text-muted">- Tidak Berlaku -</em>';
                              } elseif (!empty($row->nama_driver)) {
                                  echo htmlspecialchars($row->nama_driver);
                              } elseif (!empty($row->driver)) {
                                  echo htmlspecialchars($row->driver);
                              } else {
                                  echo '-';
                              }
                            ?>
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
                            } elseif ($status === 'no confirmation') {
                                echo '<span class="label label-default" style="font-size:14px;">Tidak Ada Konfirmasi</span>';
                            } else {
                                echo '<span style="font-size:14px;">'.htmlspecialchars($row->status).'</span>';
                            }
                          ?>
                        </td>
                        <td>
                        <button class="btn btn-info btn-xs last"
                          data-toggle="modal"
                          data-target="#modalDetail<?= $row->id ?>">Detail</button>
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
            <div><?= $pagination; ?></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- All modals: one detail modal per order -->
<?php if (!empty($orders)): ?>
<?php foreach ($orders as $row): ?>
<div class="modal fade" id="modalDetail<?= $row->id ?>" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel<?= $row->id ?>" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="modalDetailLabel<?= $row->id ?>">
          <i class="fa fa-info-circle"></i> Detail Pesanan #<?= $row->id ?>
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" style="background: #f9fbfc;">
        <table class="table table-striped table-bordered">
          <tr><th>Tanggal Pesanan</th><td><?= date('d-m-Y', strtotime($row->tanggal_pesanan)) ?></td></tr>
          <tr><th>Nomor Karyawan</th><td><?= htmlspecialchars($row->nomor_karyawan) ?></td></tr>
          <tr><th>Nama Karyawan</th><td><?= htmlspecialchars($row->nama) ?></td></tr>
          <tr><th>Divisi</th><td><?= htmlspecialchars($row->divisi) ?></td></tr>
          <tr><th>Tujuan</th><td><?= htmlspecialchars($row->tujuan) ?></td></tr>
          <tr><th>Tanggal Pakai</th><td><?= date('d-m-Y', strtotime($row->tanggal_pakai)) ?></td></tr>
          <tr><th>Waktu Mulai</th><td><?= format_time($row->waktu_mulai) ?></td></tr>
          <tr><th>Waktu Selesai</th><td><?= format_time($row->waktu_selesai) ?></td></tr>
          <tr><th>Keperluan</th><td><?= nl2br(htmlspecialchars($row->keperluan)) ?></td></tr>
          <tr><th>Kendaraan</th>
            <td>
            <?php
                if (!empty($row->no_pol) && !empty($row->nama_kendaraan)) {
                    echo htmlspecialchars($row->no_pol) . ' (' . htmlspecialchars($row->nama_kendaraan) . ')';
                } elseif (!empty($row->kendaraan)) {
                    echo htmlspecialchars($row->kendaraan);
                } else {
                    echo 'Menunggu Persetujuan';
                }
            ?>
            </td>
          </tr>
          <tr><th>Driver</th>
            <td>
                <?php
                  if (!empty($row->nama_driver)) {
                      echo htmlspecialchars($row->nama_driver);
                  } elseif (!empty($row->driver)) {
                      echo htmlspecialchars($row->driver);
                  } else {
                      echo 'Menunggu Persetujuan';
                  }
                ?>
            </td>
          </tr>
          <tr><th>Jumlah Orang</th><td><?= (int)$row->jumlah_orang ?></td></tr>
          <tr><th>Pemesan</th><td><?= htmlspecialchars($row->pemesan) ?></td></tr>
          <tr><th>Status</th>
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
                    } elseif ($status === 'no confirmation') {
                        echo '<span class="label label-default" style="font-size:14px;">Tidak Ada Konfirmasi</span>';
                    } else {
                        echo '<span style="font-size:14px;">'.htmlspecialchars($row->status).'</span>';
                    }
                ?>
            </td>
          </tr>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
<?php endforeach; ?>
<?php endif; ?>

<?php $this->load->view('header_footer/footer'); ?>