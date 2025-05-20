<?php $this->load->view('header_footer/header'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="right_col" role="main">
  <div class="page-title">
    <div class="title_left"><h3>Daftar Pesanan Menunggu Persetujuan</h3></div>
  </div>
  <div class="clearfix"></div>
  <div class="x_panel">
    <div class="x_title">
      <h2>Tabel Pesanan</h2>
      <div class="clearfix"></div>
    </div>
    <?php if (empty($pending_orders)): ?>
      <div class="alert alert-info text-center">Tidak ada pesanan menunggu persetujuan.</div>
    <?php else: ?>
      <style>
      .table th, .table td { text-align: center !important; vertical-align: middle !important; }
      </style>
      <div class="x_content">
        <div class="table-responsive">
          <table class="table table-striped jambo_table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Pemesan</th>
                <th>Tanggal Pesanan</th>
                <th>Tujuan</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($pending_orders as $order): ?>
              <tr id="orderRow<?= $order->id ?>">
                <td><?= htmlspecialchars($order->id) ?></td>
                <td><?= htmlspecialchars($order->pemesan) ?></td>
                <td><?= date('d-m-Y', strtotime($order->tanggal_pesanan)) ?></td>
                <td><?= htmlspecialchars($order->tujuan) ?></td>
                <td>
                  <button type="button"
                    class="btn btn-info btn-sm"
                    data-toggle="modal"
                    data-target="#modalDetail<?= $order->id ?>">Detail</button>
                  <button type="button"
                    class="btn btn-primary btn-sm"
                    data-toggle="modal"
                    data-target="#modalApprove<?= $order->id ?>">Approve</button>
                  <button type="button"
                    class="btn btn-danger btn-sm"
                    data-toggle="modal"
                    data-target="#modalReject<?= $order->id ?>">Reject</button>
                </td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php
// Helper for formatting time to H:i
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

<!-- All MODALS are output after the table -->
<?php foreach ($pending_orders as $order): ?>
<!-- Detail Modal (stylish, compact, pretty hour) -->
<div class="modal fade" id="modalDetail<?= $order->id ?>" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel<?= $order->id ?>" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="modalDetailLabel<?= $order->id ?>">
          <i class="fa fa-info-circle"></i> Detail Pesanan #<?= $order->id ?>
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <table class="table table-striped table-bordered">
          <tr><th>Nomor Karyawan</th><td><?= htmlspecialchars($order->nomor_karyawan) ?></td></tr>
          <tr><th>Nama</th><td><?= htmlspecialchars($order->nama) ?></td></tr>
          <tr><th>Divisi</th><td><?= htmlspecialchars($order->divisi) ?></td></tr>
          <tr><th>Tujuan</th><td><?= htmlspecialchars($order->tujuan) ?></td></tr>
          <tr><th>Tanggal Pakai</th><td><?= date('d-m-Y', strtotime($order->tanggal_pakai)) ?></td></tr>
          <tr><th>Waktu Mulai</th><td><?= format_time($order->waktu_mulai) ?></td></tr>
          <tr><th>Waktu Selesai</th><td><?= format_time($order->waktu_selesai) ?></td></tr>
          <tr><th>Keperluan</th><td><?= nl2br(htmlspecialchars($order->keperluan)) ?></td></tr>
          <tr><th>Jumlah Orang</th><td><?= (int)$order->jumlah_orang ?></td></tr>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Approve Modal (use approve_form style) -->
<div class="modal fade" id="modalApprove<?= $order->id ?>" tabindex="-1" role="dialog" aria-labelledby="modalApproveLabel<?= $order->id ?>" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form class="formApprove form-horizontal" data-order-id="<?= $order->id ?>">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Approve Pesanan #<?= $order->id ?></h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label class="control-label col-md-4">Pemesan</label>
            <div class="col-md-8">
              <input type="text" class="form-control" value="<?= htmlspecialchars($order->pemesan) ?>" disabled>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-4">Tujuan</label>
            <div class="col-md-8">
              <input type="text" class="form-control" value="<?= htmlspecialchars($order->tujuan) ?>" disabled>
            </div>
          </div>
          <div class="form-group">
            <label for="kendaraan<?= $order->id ?>" class="control-label col-md-4">Assign Vehicle</label>
            <div class="col-md-8">
              <select name="kendaraan" id="kendaraan<?= $order->id ?>" class="form-control" required>
                <option value="">-- Pilih Kendaraan --</option>
                <?php foreach ($kendaraan_options as $vehicle): ?>
                  <option value="<?= htmlspecialchars($vehicle['id']) ?>">
                    <?= htmlspecialchars($vehicle['label']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="driver<?= $order->id ?>" class="control-label col-md-4">Assign Driver</label>
            <div class="col-md-8">
              <select name="driver" id="driver<?= $order->id ?>" class="form-control" required>
                <option value="">-- Pilih Driver --</option>
                <?php foreach ($driver_options as $driver): ?>
                  <option value="<?= htmlspecialchars($driver['id']) ?>">
                    <?= htmlspecialchars($driver['label']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Approve</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Reject Modal (AJAX) unchanged -->
<div class="modal fade" id="modalReject<?= $order->id ?>" tabindex="-1" role="dialog" aria-labelledby="modalRejectLabel<?= $order->id ?>" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form class="formReject" data-order-id="<?= $order->id ?>">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title">Tolak Pesanan #<?= $order->id ?></h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <p>
          Yakin menolak pesanan oleh <b><?= htmlspecialchars($order->pemesan) ?></b>
          untuk tujuan <b><?= htmlspecialchars($order->tujuan) ?></b>?
          </p>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">Tolak</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        </div>
      </div>
    </form>
  </div>
</div>
<?php endforeach; ?>

<script>
$(function(){
  $('.formApprove').submit(function(e){
    e.preventDefault();
    var $form = $(this);
    var orderId = $form.data('order-id');
    var modalId = '#modalApprove' + orderId;
    var formData = $form.serialize();

    $.post('<?= site_url('order/do_approve_ajax/') ?>' + orderId, formData, function(response){
      try { response = typeof response === 'object' ? response : JSON.parse(response); } catch(e){}
      $(modalId).modal('hide');
      if (response.status === 'success') {
        $('#orderRow'+orderId).fadeOut(400, function(){$(this).remove();});
        Swal.fire({icon:'success', title:'Berhasil', text: response.message, timer:2000, showConfirmButton: false});
      } else {
        Swal.fire({icon:'error', title:'Gagal', text: response.message});
      }
    });
  });

  $('.formReject').submit(function(e){
    e.preventDefault();
    var $form = $(this);
    var orderId = $form.data('order-id');
    var modalId = '#modalReject' + orderId;

    $.post('<?= site_url('order/reject_ajax/') ?>' + orderId, {}, function(response){
      try { response = typeof response === 'object' ? response : JSON.parse(response); } catch(e){}
      $(modalId).modal('hide');
      if (response.status === 'success') {
        $('#orderRow'+orderId).fadeOut(400, function(){$(this).remove();});
        Swal.fire({icon:'success', title:'Ditolak', text: response.message, timer:2000, showConfirmButton: false});
      } else {
        Swal.fire({icon:'error', title:'Gagal', text: response.message});
      }
    });
  });

  $('.modal').on('hidden.bs.modal', function () {
    var form = $(this).find('form')[0];
    if(form) form.reset();
  });
});
</script>

<?php $this->load->view('header_footer/footer'); ?>