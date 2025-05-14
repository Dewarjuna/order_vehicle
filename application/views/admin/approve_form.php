<?php $this->load->view('header_footer/header'); ?>

<div class="right_col" role="main">
  <h3>Approve Order #<?= htmlspecialchars($order->id) ?></h3>

  <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
  <?php endif; ?>

  <form action="<?= site_url('order/do_approve/' . $order->id) ?>" method="post" class="form-horizontal">
    <div class="form-group">
      <label class="control-label col-md-3">Pemesan</label>
      <div class="col-md-6">
        <input type="text" class="form-control" value="<?= htmlspecialchars($order->pemesan) ?>" disabled>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-3">Tujuan</label>
      <div class="col-md-6">
        <input type="text" class="form-control" value="<?= htmlspecialchars($order->tujuan) ?>" disabled>
      </div>
    </div>

    <div class="form-group">
      <label for="kendaraan" class="control-label col-md-3">Assign Vehicle</label>
      <div class="col-md-6">
      <select name="kendaraan" id="kendaraan" class="form-control" required>
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
      <label for="driver" class="control-label col-md-3">Assign Driver</label>
      <div class="col-md-6">
      <select name="driver" id="driver" class="form-control" required>
        <option value="">-- Pilih Driver --</option>
        <?php foreach ($driver_options as $driver): ?>
          <option value="<?= htmlspecialchars($driver['id']) ?>">
            <?= htmlspecialchars($driver['label']) ?>
          </option>
        <?php endforeach; ?>
      </select>
      </div>
    </div>

    <div class="form-group">
      <div class="col-md-offset-3 col-md-6">
        <button type="submit" class="btn btn-success">Approve Order</button>
        <a href="<?= site_url('order/pending_orders') ?>" class="btn btn-default">Cancel</a>
      </div>
    </div>
  </form>
</div>

<?php $this->load->view('header_footer/footer'); ?>