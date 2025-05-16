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
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<?php $this->load->view('header_footer/footer'); ?>