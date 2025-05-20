<?php $this->load->view('header_footer/header'); ?>
<!-- SweetAlert2 and jQuery are needed for AJAX and alerts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<?php
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
  <div class="page-title">
    <div class="title_left">
      <h3>Daftar Pesanan Saya</h3>
    </div>
  </div>
  <div class="clearfix"></div>
  <div class="x_panel">
    <div class="x_title">
      <h2>Tabel Pesanan</h2>
      <div class="clearfix"></div>
    </div>

    <style>
    .table th, .table td {
        text-align: center !important;
        vertical-align: middle !important;
    }
    </style>

    <div class="x_content">
      <div class="table-responsive">
        <table class="table table-striped jambo_table">
          <thead>
          <tr class="headings">
              <th>No.</th>
              <th>Tanggal Pesanan</th>
              <th>Nama</th>
              <th>Tujuan</th>
              <th>Tanggal Pakai</th>
              <th>Waktu Pemakaian</th>
              <th>Kendaraan</th>
              <th>Status</th>
              <th><span class="nobr">Aksi</span></th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($pesanan_list)): ?>
                <?php $no=1; foreach($pesanan_list as $row): ?>
                  <tr id="orderRow<?= $row->id ?>">
                    <td><?php echo $no++; ?></td>
                    <td><?php echo date('d-m-Y', strtotime($row->tanggal_pesanan)); ?></td>
                    <td><?php echo htmlspecialchars($row->nama); ?></td>
                    <td><?php echo htmlspecialchars($row->tujuan); ?></td>
                    <td><?php echo date('d-m-Y', strtotime($row->tanggal_pakai)); ?></td>
                    <td>
                      <?= format_time($row->waktu_mulai) ?> - <?= format_time($row->waktu_selesai) ?>
                    </td>
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
                      <button type="button" class="btn btn-info btn-xs last"
                          data-toggle="modal"
                          data-target="#modalDetail<?= $row->id ?>">Detail</button>
                      <?php if (!empty($row->kendaraan) && strtolower($row->status) === 'approved'): ?>
                        <button class="btn btn-secondary btn-xs last disabled" tabindex="-1" aria-disabled="true" style="pointer-events:none;opacity:0.6;">Edit</button>
                        <button class="btn btn-secondary btn-xs last disabled" tabindex="-1" aria-disabled="true" style="pointer-events:none;opacity:0.6;">Hapus</button>
                      <?php else: ?>
                        <button type="button" class="btn btn-warning btn-xs last"
                          data-toggle="modal"
                          data-target="#modalEdit<?= $row->id ?>">Edit</button>
                        <button type="button" class="btn btn-danger btn-xs last"
                          data-toggle="modal"
                          data-target="#modalHapus<?= $row->id ?>">Hapus</button>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                  <td colspan="11" class="text-center">Tidak ada pesanan ditemukan.</td>
                </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php if (!empty($pesanan_list)): ?>
<?php foreach($pesanan_list as $row): ?>
<!-- Detail Modal -->
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
          <tr><th>Nama</th><td><?= htmlspecialchars($row->nama) ?></td></tr>
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

<!-- Edit Modal -->
<div class="modal fade" id="modalEdit<?= $row->id ?>" tabindex="-1" role="dialog" aria-labelledby="modalEditLabel<?= $row->id ?>" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form class="formEditPesanan" data-order-id="<?= $row->id ?>">
      <div class="modal-content">
        <div class="modal-header bg-warning text-dark">
          <h5 class="modal-title" id="modalEditLabel<?= $row->id ?>"><i class="fa fa-edit"></i> Edit Pesanan #<?= $row->id ?></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" value="<?= $row->id ?>">
          <div class="form-group">
            <label>Tujuan</label>
            <input type="text" class="form-control" name="tujuan" value="<?= htmlspecialchars($row->tujuan) ?>" required>
          </div>
          <div class="form-group">
            <label>Tanggal Pakai</label>
            <input type="date" class="form-control" name="tanggal_pakai" value="<?= htmlspecialchars($row->tanggal_pakai) ?>" required>
          </div>
          <div class="form-group">
            <label>Waktu Mulai</label>
            <input type="time" class="form-control" name="waktu_mulai" value="<?= substr($row->waktu_mulai, 0, 5) ?>" required>
          </div>
          <div class="form-group">
            <label>Waktu Selesai</label>
            <input type="time" class="form-control" name="waktu_selesai" value="<?= substr($row->waktu_selesai, 0, 5) ?>" required>
          </div>
          <div class="form-group">
            <label>Keperluan</label>
            <textarea class="form-control" name="keperluan" required><?= htmlspecialchars($row->keperluan) ?></textarea>
          </div>
          <div class="form-group">
            <label>Jumlah Orang</label>
            <input type="number" class="form-control" name="jumlah_orang" min="1" value="<?= (int)$row->jumlah_orang ?>" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Hapus Modal -->
<div class="modal fade" id="modalHapus<?= $row->id ?>" tabindex="-1" role="dialog" aria-labelledby="modalHapusLabel<?= $row->id ?>" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form class="formDeletePesanan" data-order-id="<?= $row->id ?>">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="modalHapusLabel<?= $row->id ?>"><i class="fa fa-trash"></i> Konfirmasi Hapus Pesanan</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <p>Yakin ingin menghapus pesanan ke <b><?= htmlspecialchars($row->tujuan) ?></b> pada <b><?= date('d-m-Y', strtotime($row->tanggal_pakai)) ?></b>?</p>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">Hapus</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        </div>
      </div>
    </form>
  </div>
</div>
<?php endforeach; ?>
<?php endif; ?>


<script>
$(function(){

  // AJAX: Edit
  $('.formEditPesanan').submit(function(e){
    e.preventDefault();
    var $form = $(this);
    var orderId = $form.data('order-id');
    var modalId = '#modalEdit' + orderId;
    var formData = $form.serialize();

    $.post('<?= site_url('order/update/') ?>' + orderId, formData, function(response){
      try { response = typeof response === 'object' ? response : JSON.parse(response); } catch(e){}
      $(modalId).modal('hide');
      Swal.fire({
        icon:'success',
        title:'Berhasil',
        text: 'Perubahan pesanan telah disimpan!',
        timer: 2000,
        showConfirmButton: false
      }).then(function(){
        // Optionally reload the table/page, or update the row with new data
        location.reload(); // or update the row in JS if desired
      });
    }).fail(function(xhr){
      Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: 'Gagal menyimpan perubahan. Silakan coba lagi.'
      });
    });
  });

  // AJAX: Delete
  $('.formDeletePesanan').submit(function(e){
    e.preventDefault();
    var $form = $(this);
    var orderId = $form.data('order-id');
    var modalId = '#modalHapus' + orderId;

    $.post('<?= site_url('order/delete/') ?>' + orderId, function(response){
      $(modalId).modal('hide');
      $('#orderRow'+orderId).fadeOut(500, function(){$(this).remove();});
      Swal.fire({
        icon:'success',
        title:'Dihapus',
        text:'Pesanan berhasil dihapus.',
        timer: 1800,
        showConfirmButton: false
      });
    }).fail(function(xhr){
      Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: 'Gagal menghapus pesanan. Silakan coba lagi.'
      });
    });
  });

  // Optional: clear form on modal close
  $('.modal').on('hidden.bs.modal', function () {
    $(this).find('form')[0]?.reset();
  });
});
</script>

<?php $this->load->view('header_footer/footer'); ?>