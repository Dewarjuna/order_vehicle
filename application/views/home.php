<?php $this->load->view('header_footer/header'); ?>

<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Dashboard Pemesanan Kendaraan</h3>
      </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12">
        <div class="x_panel" style="padding: 10px;">
          <div class="x_title">
            <h2><i class="fa fa-dashboard"></i> Statistik Pesanan Bulan Ini</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content" style="padding: 15px 5px;">
            
            <?php if ($role === 'admin'): ?>
            <div class="row" style="margin-bottom: 15px;">
                <div class="col-md-6">
                    <label for="months" style="font-weight:600; margin-bottom:4px;">Filter Bulan <small>(bisa lebih dari 1):</small></label>
                    <select id="months" class="form-control select2" multiple style="width:100%;">
                        <?php
                        $now = new DateTime();
                        $existing = [];
                        for ($i = 0; $i < 12; $i++) {
                            $month = $now->format('Y-m');
                            // Prevent duplicate months
                            if (!in_array($month, $existing)) {
                              $existing[] = $month;
                              echo "<option value=\"$month\">".date('F Y', strtotime($month."-01"))."</option>";
                            }
                            $now->modify('-1 month');
                        }
                        ?>
                    </select>
                    <button id="btn-filter-months" class="btn btn-success btn-sm" style="margin-top:10px;">Terapkan Filter</button>
                </div>
            </div>
            <div class="row top_tiles">
              
              <!-- Total Orders (Admin only tile) -->
              <?php
                $total_order_url = site_url('order/order_report');
                $order_count = $total_orders;
              ?>
              <a href="<?= $total_order_url ?>" class="animated flipInY col-lg-2 col-md-4 col-sm-6 col-xs-12" style="text-decoration: none;">
                <div class="tile-stats" style="background: #2A3F54; border-left: 5px solid #1ABB9C;">
                  <div class="icon"><i class="fa fa-list-alt"></i></div>
                  <div class="count" id="count-total-orders"><?= $order_count ?></div>
                  <h3>Total Pesanan</h3>
                  <p>Semua pesanan bulan ini</p>
                </div>
              </a>
              
              <!-- Pending Orders -->
              <a href="<?= site_url('home?status=pending') ?>" class="animated flipInY col-lg-2 col-md-4 col-sm-6 col-xs-12" style="text-decoration: none;">
                <div class="tile-stats" style="background: #fff; border-left: 5px solid #F39C12;">
                  <div class="icon" style="color: #F39C12;"><i class="fa fa-hourglass-half"></i></div>
                  <div class="count" id="count-pending-orders" style="color: #333;"><?= $pending_orders ?></div>
                  <h3 style="color: #333;">Pending</h3>
                  <p style="color: #73879C;">Menunggu persetujuan</p>
                </div>
              </a>
              
              <!-- Approved Orders -->
              <a href="<?= site_url('home?status=approved') ?>" class="animated flipInY col-lg-2 col-md-4 col-sm-6 col-xs-12" style="text-decoration: none;">
                <div class="tile-stats" style="background: #fff; border-left: 5px solid #3498DB;">
                  <div class="icon" style="color: #3498DB;"><i class="fa fa-check-circle"></i></div>
                  <div class="count" id="count-approved-orders" style="color: #333;"><?= $approved_orders ?></div>
                  <h3 style="color: #333;">Approved</h3>
                  <p style="color: #73879C;">Disetujui</p>
                </div>
              </a>
              
              <!-- Done Orders -->
              <a href="<?= site_url('home?status=done') ?>" class="animated flipInY col-lg-2 col-md-4 col-sm-6 col-xs-12" style="text-decoration: none;">
                <div class="tile-stats" style="background: #fff; border-left: 5px solid #26B99A;">
                  <div class="icon" style="color: #26B99A;"><i class="fa fa-flag-checkered"></i></div>
                  <div class="count" id="count-done-orders" style="color: #333;"><?= $done_orders ?></div>
                  <h3 style="color: #333;">Done</h3>
                  <p style="color: #73879C;">Pesanan selesai</p>
                </div>
              </a>
              
              <!-- Rejected Orders -->
              <a href="<?= site_url('home?status=rejected') ?>" class="animated flipInY col-lg-2 col-md-4 col-sm-6 col-xs-12" style="text-decoration: none;">
                <div class="tile-stats" style="background: #fff; border-left: 5px solid #E74C3C;">
                  <div class="icon" style="color: #E74C3C;"><i class="fa fa-times-circle"></i></div>
                  <div class="count" id="count-rejected-orders" style="color: #333;"><?= $rejected_orders ?></div>
                  <h3 style="color: #333;">Rejected</h3>
                  <p style="color: #73879C;">Pesanan ditolak</p>
                </div>
              </a>
              
              <!-- No Confirmation -->
              <a href="<?= site_url('home?status=no confirmation') ?>" class="animated flipInY col-lg-2 col-md-4 col-sm-6 col-xs-12" style="text-decoration: none;">
                <div class="tile-stats" style="background: #fff; border-left: 5px solid #95A5A6;">
                  <div class="icon" style="color: #95A5A6;"><i class="fa fa-question-circle"></i></div>
                  <div class="count" id="count-no-confirmation-orders" style="color: #333;"><?= $no_confirmation_orders ?></div>
                  <h3 style="color: #333;">No Confirmation</h3>
                  <p style="color: #73879C;">Tidak ada konfirmasi</p>
                </div>
              </a>
              
            </div>

            <!-- Orders Table Section -->
            <?php if (isset($status_orders)): ?>
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
            <?php endif; ?>

            <?php else: ?>
            <!-- User Dashboard -->
            <div class="row top_tiles">
              <!-- User "Total Pesanan" Clickable Tile -->
              <a href="<?= site_url('my-bookings') ?>" class="animated flipInY col-lg-4 col-md-4 col-sm-6 col-xs-12" style="text-decoration: none;">
                <div class="tile-stats" style="background: #2A3F54; border-left: 5px solid #1ABB9C;">
                  <div class="icon"><i class="fa fa-list"></i></div>
                  <div class="count"><?= $user_orders ?></div>
                  <h3>Pesanan Anda</h3>
                  <p>Bulan ini</p>
                </div>
              </a>
            </div>
            <?php endif; ?>
            
          </div>
        </div>
      </div>
    </div>
    
  </div>
</div>

<style>
  .tile-stats {
    border-radius: 5px;
    position: relative;
    display: block;
    margin-bottom: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
    transition: all 0.3s cubic-bezier(.25,.8,.25,1);
    min-height: 180px;
    padding: 15px;
  }
  
  .tile-stats:hover {
    box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
    transform: translateY(-2px);
  }
  
  .tile-stats .icon {
    width: 50px;
    height: 50px;
    color: white;
    position: absolute;
    right: 15px;
    top: 15px;
    z-index: 1;
    font-size: 30px;
    line-height: 0;
  }
  
  .tile-stats .count {
    font-size: 38px;
    font-weight: 700;
    color: white;
    margin-top: 10px;
  }
  
  .tile-stats h3 {
    font-size: 16px;
    margin-top: 5px;
    color: white;
    font-weight: 600;
  }
  
  .tile-stats p {
    margin: 0;
    padding: 0;
    font-size: 12px;
    color: rgba(255,255,255,0.8);
  }
  
  .animated.flipInY:nth-child(1) { animation-delay: 0.1s; }
  .animated.flipInY:nth-child(2) { animation-delay: 0.2s; }
  .animated.flipInY:nth-child(3) { animation-delay: 0.3s; }
  .animated.flipInY:nth-child(4) { animation-delay: 0.4s; }
  .animated.flipInY:nth-child(5) { animation-delay: 0.5s; }
  .animated.flipInY:nth-child(6) { animation-delay: 0.6s; }
</style>
<?php $this->load->view('header_footer/footer'); ?>
<?php if ($role === 'admin'): ?>
<script>
$(document).ready(function() {
    $('#months').select2({
        placeholder: 'Pilih bulan...',
        allowClear: true
    });
    $('#btn-filter-months').click(function(){
        var months = $('#months').val();
        console.log('Selected months:', months);
        if (!months || months.length === 0){
            alert('Pilih minimal satu bulan!');
            return;
        }
        $.ajax({
          url: '<?= site_url('home/ajax_status_tile_counts') ?>',
          type: 'POST',
          data: { months: months },
          dataType: 'json',
          success: function(resp) {
            if(resp && typeof resp === 'object'){
              $('#count-total-orders').text(resp.total_orders);
              $('#count-pending-orders').text(resp.pending_orders);
              $('#count-approved-orders').text(resp.approved_orders);
              $('#count-done-orders').text(resp.done_orders);
              $('#count-rejected-orders').text(resp.rejected_orders);
              $('#count-no-confirmation-orders').text(resp.no_confirmation_orders);
            }
          },
          error: function() {
            alert('Gagal memuat data');
          }
        });
    });
});
</script>
<?php endif; ?>