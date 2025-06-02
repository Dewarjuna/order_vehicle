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
              <div class="animated flipInY col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="tile-stats status-tile" data-status="pending" style="background: #fff; border-left: 5px solid #F39C12; cursor: pointer;">
                  <div class="icon" style="color: #F39C12;"><i class="fa fa-hourglass-half"></i></div>
                  <div class="count" id="count-pending-orders" style="color: #333;"><?= $pending_orders ?></div>
                  <h3 style="color: #333;">Pending</h3>
                  <p style="color: #73879C;">Menunggu persetujuan</p>
                </div>
              </div>
              
              <!-- Approved Orders -->
              <div class="animated flipInY col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="tile-stats status-tile" data-status="approved" style="background: #fff; border-left: 5px solid #3498DB; cursor: pointer;">
                  <div class="icon" style="color: #3498DB;"><i class="fa fa-check-circle"></i></div>
                  <div class="count" id="count-approved-orders" style="color: #333;"><?= $approved_orders ?></div>
                  <h3 style="color: #333;">Approved</h3>
                  <p style="color: #73879C;">Disetujui</p>
                </div>
              </div>
              
              <!-- Done Orders -->
              <div class="animated flipInY col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="tile-stats status-tile" data-status="done" style="background: #fff; border-left: 5px solid #26B99A; cursor: pointer;">
                  <div class="icon" style="color: #26B99A;"><i class="fa fa-flag-checkered"></i></div>
                  <div class="count" id="count-done-orders" style="color: #333;"><?= $done_orders ?></div>
                  <h3 style="color: #333;">Done</h3>
                  <p style="color: #73879C;">Pesanan selesai</p>
                </div>
              </div>
              
              <!-- Rejected Orders -->
              <div class="animated flipInY col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="tile-stats status-tile" data-status="rejected" style="background: #fff; border-left: 5px solid #E74C3C; cursor: pointer;">
                  <div class="icon" style="color: #E74C3C;"><i class="fa fa-times-circle"></i></div>
                  <div class="count" id="count-rejected-orders" style="color: #333;"><?= $rejected_orders ?></div>
                  <h3 style="color: #333;">Rejected</h3>
                  <p style="color: #73879C;">Pesanan ditolak</p>
                </div>
              </div>
              
              <!-- No Confirmation -->
              <div class="animated flipInY col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="tile-stats status-tile" data-status="no confirmation" style="background: #fff; border-left: 5px solid #95A5A6; cursor: pointer;">
                  <div class="icon" style="color: #95A5A6;"><i class="fa fa-question-circle"></i></div>
                  <div class="count" id="count-no-confirmation-orders" style="color: #333;"><?= $no_confirmation_orders ?></div>
                  <h3 style="color: #333;">No Confirmation</h3>
                  <p style="color: #73879C;">Tidak ada konfirmasi</p>
                </div>
              </div>
              
            </div>

            <!-- Orders Table Section -->
            <div id="orders-table-container"></div>

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

  .status-tile.active {
    background: #f8f9fa !important;
  }
</style>
<?php $this->load->view('header_footer/footer'); ?>
<?php if ($role === 'admin'): ?>
<script>
$(document).ready(function() {
    $('#months').select2({
        placeholder: 'Pilih bulan...',
        allowClear: true
    });

    // Handle month filter
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

    // Handle status tile clicks
    $('.status-tile').click(function() {
        var status = $(this).data('status');
        
        // Remove active class from all tiles and add to clicked one
        $('.status-tile').removeClass('active');
        $(this).addClass('active');
        
        // Show loading indicator
        $('#orders-table-container').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x"></i></div>');
        
        // Load orders table via AJAX
        $.ajax({
            url: '<?= site_url('home/ajax_get_orders_table') ?>',
            type: 'POST',
            data: { status: status },
            success: function(response) {
                $('#orders-table-container').html(response);
            },
            error: function() {
                $('#orders-table-container').html('<div class="alert alert-danger">Gagal memuat data</div>');
            }
        });
    });

    // Check URL for status parameter and trigger click if present
    var urlParams = new URLSearchParams(window.location.search);
    var status = urlParams.get('status');
    if (status) {
        $('.status-tile[data-status="' + status + '"]').click();
    }
});
</script>
<?php endif; ?>