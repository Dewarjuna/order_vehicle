<?php $this->load->view('header_footer/header'); ?>

<div class="right_col" role="main">
  <h3>Daftar Pesanan Menunggu Persetujuan</h3>

  <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
  <?php endif; ?>
  <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
  <?php endif; ?>

  <?php if (count($pending_orders) === 0): ?>
    <!-- <p>No pending orders.</p> -->
  <?php else: ?>

        <style>
        /* Center BOTH table headers and table cells */
        .table th, .table td {
            text-align: center !important;
              vertical-align: middle !important;
          }
        </style>

    <table class="table table-striped jambo_table">
      <thead>
        <tr><th>ID</th><th>Pemesan</th><th>Tanggal Pesanan</th><th>Tujuan</th><th>Action</th></tr>
      </thead>
      <tbody>
        <?php foreach ($pending_orders as $order): ?>
          <tr>
            <td><?= htmlspecialchars($order->id) ?></td>
            <td><?= htmlspecialchars($order->pemesan) ?></td>
            <td><?= date('d-m-Y', strtotime($order->tanggal_pesanan)) ?></td>
            <td><?= htmlspecialchars($order->tujuan) ?></td>
            <td>
              <a href="<?= site_url('order/single/'.$order->id); ?>" class="btn btn-sm btn-info">Detail</a>
              <a href="<?= site_url('order/approve/' . $order->id) ?>" class="btn btn-sm btn-primary">Approve</a>
              <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalRejectOrder<?= $order->id ?>">
                  Reject
              </a>
            </td>
          </tr>
          <div class="modal fade" id="modalRejectOrder<?= $order->id ?>" tabindex="-1" role="dialog" aria-labelledby="modalRejectOrderLabel<?= $order->id ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <form method="post" action="<?= site_url('order/reject/'.$order->id); ?>">
                <div class="modal-content">
                  <div class="modal-header bg-danger text-white">
                    <h4 class="modal-title"><i class="fa fa-times-circle"></i> Konfirmasi Tolak Pesanan</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <p>Apakah anda yakin ingin <b>menolak</b> pesanan oleh <b><?= htmlspecialchars($order->pemesan); ?></b>?<br>
                    Tujuan: <b><?= htmlspecialchars($order->tujuan); ?></b><br>
                    Tanggal Pakai: <b><?= date('d-m-Y', strtotime($order->tanggal_pakai)); ?></b></p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                      <i class="fa fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                      <i class="fa fa-ban"></i> Tolak
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<?php $this->load->view('header_footer/footer'); ?>