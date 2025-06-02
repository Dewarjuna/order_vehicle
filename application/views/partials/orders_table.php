<div class="row">
  <div class="col-md-12">
    <div class="x_panel">
      <div class="x_title">
        <h2><i class="fa fa-table"></i> Daftar Pesanan - <?= ucfirst($selected_status) ?></h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <div class="table-responsive">
          <table class="table table-striped jambo_table">
            <thead>
              <tr>
                <th>Tanggal Pesan</th>
                <th>Pemesan</th>
                <th>Tujuan</th>
                <th>Tanggal Pakai</th>
                <th>Kendaraan</th>
                <th>Driver</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($status_orders as $order): ?>
              <tr>
                <td><?= date('d-m-Y', strtotime($order->tanggal_pesanan)) ?></td>
                <td><?= htmlspecialchars($order->pemesan) ?></td>
                <td><?= htmlspecialchars($order->tujuan) ?></td>
                <td><?= date('d-m-Y', strtotime($order->tanggal_pakai)) ?></td>
                <td>
                  <?= $order->no_pol ? $order->no_pol . ' (' . $order->nama_kendaraan . ')' : '-' ?>
                </td>
                <td><?= $order->nama_driver ?: '-' ?></td>
                <td>
                  <?php 
                    $status = $order->status;
                    $badge_class = [
                      'pending' => 'warning',
                      'approved' => 'primary',
                      'done' => 'success',
                      'rejected' => 'danger',
                      'no confirmation' => 'default'
                    ];
                    $badge_class = isset($badge_class[$status]) ? $badge_class[$status] : 'default';
                  ?>
                  <span class="label label-<?= $badge_class ?>">
                    <?= ucfirst($status) ?>
                  </span>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div> 