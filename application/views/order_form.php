<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('header_footer/header'); ?>

<style>
    .card-modern {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.10);
      padding: 0;
      border: 1px solid #f1f2fa;
    }
    .card-header-modern {
      border-bottom: 1px solid #f3f4f8;
      padding: 1.4rem 2rem 0.7rem 2rem;
      background: #fff;
      border-radius: 10px 10px 0 0;
    }
    .card-body-modern {
      padding: 2rem 2.2rem 2.1rem 2.2rem;
    }
    .form-control-modern {
      border-radius: 5px!important;
      border: 1px solid #e5e7ef;
      box-shadow: none;
      height: 38px;
    }
    .form-control-modern:focus {
      border-color: #57b8ff;
      box-shadow: 0 0 0 2px #e6f5ff;
    }
    .section-title {
      font-weight:600;
      color: #566779;
      margin-top: 22px;
      margin-bottom:12px;
      letter-spacing:0.2px;
    }
    .section-divider {
      border: 0;
      height: 1.5px;
      background: linear-gradient(to right,#e5e7ef 0%, #fff 100%);
      margin:1.5rem 0 1.2rem 0;
    }
    .btn-modern {
      border-radius:5px!important;
      font-size: 15px;
    }
    /* Responsive tweak */
    @media (max-width: 768px){
      .card-body-modern { padding: 1rem !important; }
      .card-header-modern { padding: 1rem 1rem 0.6rem 1rem;}
    }
</style>

<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3 style="font-weight: 600;"><i class="fa fa-car"></i> Pemesanan Kendaraan</h3>
      </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-10 col-md-offset-1 col-sm-12 col-xs-12">
        <div class="card-modern">
          <div class="card-header-modern">
            <div class="row">
                <div class="col-xs-12">
                  <h4 style="margin:0;color:#3fa1df;"><i class="fa fa-file-text-o"></i> Formulir Pemesanan</h4>
                </div>
            </div>
          </div>
          <div class="card-body-modern">
            <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" method="post" action="<?php echo site_url('order/submit'); ?>">

            <!-- SECTION 1 - DATA KARYAWAN -->
            <div class="section-title"><i class="fa fa-user"></i> Data Karyawan</div>
            
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tanggal_pesanan">
                  Tanggal Pesan <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-8 col-xs-12">
                  <div class="input-group">
                    <input type="text" id="tanggal_pesanan" name="tanggal_pesanan" required="required"
                          class="form-control form-control-modern" value="<?php echo date('d-m-Y'); ?>" readonly>
                    <span class="input-group-addon" style="background: #f5f5f5;">
                      <i class="fa fa-calendar"></i>
                    </span>
                  </div>
                </div>
              </div>

              <?php
              $grouped_users = [];
              foreach ($users as $user) {
                  $grouped_users[$user->divisi][] = $user;
              }
              ?>
              
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">
                  Nomor Karyawan
                </label>
                <div class="col-md-6 col-sm-8 col-xs-12">
                  <select class="select2_group form-control form-control-modern" id="nomor_karyawan" name="nomor_karyawan">
                    <?php foreach ($grouped_users as $divisi => $users_in_divisi): ?>
                      <optgroup label="<?= htmlspecialchars($divisi) ?>">
                        <?php foreach ($users_in_divisi as $user): ?>
                          <option 
                            value="<?= htmlspecialchars($user->nomor_karyawan) ?>"
                            data-nama="<?= htmlspecialchars($user->nama) ?>"
                            data-divisi="<?= htmlspecialchars($user->divisi) ?>"
                          >
                          <?= htmlspecialchars($user->nomor_karyawan . ' - ' . $user->nama) ?>
                          </option>
                        <?php endforeach; ?>
                      </optgroup>
                    <?php endforeach; ?>
                  </select>
                  <small class="text-muted" style="margin-top:5px;display:block;">Ketik nama atau nomor karyawan untuk mencari</small>
                </div>
              </div>
              
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">
                  Nama Karyawan <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-8 col-xs-12">
                    <input type="text" id="nama" name="nama" required="required"
                          class="form-control form-control-modern" readonly>
                </div>
              </div>
              
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="divisi">
                  Bagian <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-8 col-xs-12">
                    <input type="text" id="divisi" name="divisi" required="required"
                          class="form-control form-control-modern" readonly>
                </div>
              </div>

              <hr class="section-divider">

              <!-- SECTION 2: PERJALANAN -->
              <div class="section-title"><i class="fa fa-map-marker"></i> Detail Perjalanan</div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tujuan">
                  Tujuan <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-8 col-xs-12">
                    <input type="text" id="tujuan" name="tujuan" required="required"
                          class="form-control form-control-modern"
                          placeholder="Masukkan tujuan perjalanan">
                </div>
              </div>

              <!-- Koordinat kantor (hidden) -->
              <!-- <input type="hidden" id="latitude_kantor" value="-6.2170">
              <input type="hidden" id="longitude_kantor" value="106.9720"> -->

              <!-- Koordinat tujuan -->
              <!-- <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">
                  Koordinat Tujuan
                </label>
                <div class="col-md-6 col-sm-8 col-xs-12">
                  <div class="row">
                    <div class="col-xs-6" style="padding-right:2px;">
                      <input type="text" id="latitude_tujuan" name="latitude_tujuan" class="form-control form-control-modern" placeholder="Latitude" readonly>
                    </div>
                    <div class="col-xs-6" style="padding-left:2px;">
                      <input type="text" id="longitude_tujuan" name="longitude_tujuan" class="form-control form-control-modern" placeholder="Longitude" readonly>
                    </div>
                  </div>
                  <button 
                    type="button"
                    id="cariKoordinat"
                    class="btn btn-info btn-modern btn-xs"
                    style="margin-top: 7px;"
                    data-parsley-exclude
                    tabindex="-1">
                    <i class="fa fa-map-marker"></i> Dapatkan Koordinat
                  </button>
                  <small class="text-muted">Isi tujuan terlebih dahulu, lalu klik untuk mendapatkan koordinat</small>
                </div>
              </div> -->

              <!-- Jarak -->
              <!-- <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">
                  Jarak dari PT Bakrie Autoparts
                </label>
                <div class="col-md-6 col-sm-8 col-xs-12">
                  <div class="input-group">
                    <input type="text" id="jarak" name="jarak" class="form-control form-control-modern" readonly>
                    <span class="input-group-addon">km</span>
                  </div>
                  <button type="button" id="hitungJarak" class="btn btn-primary btn-modern btn-xs" style="margin-top:5px;">
                    <i class="fa fa-calculator"></i> Hitung Jarak
                  </button>
                  <small class="text-muted">Koordinat Kantor: -6.2170, 106.9720</small>
                </div>
              </div> -->

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tanggal_pakai">
                  Tanggal Pakai <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-8 col-xs-12">
                  <div class="input-group date" id="tanggal_pakai_group">
                    <input type="text" id="tanggal_pakai" name="tanggal_pakai" required="required"
                          class="form-control form-control-modern"
                          placeholder="Pilih tanggal">
                    <span class="input-group-addon" style="background: #f5f5f5;">
                      <i class="fa fa-calendar"></i>
                    </span>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="waktu_mulai">
                  Waktu Mulai <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-8 col-xs-12">
                  <div class="input-group date" id="waktu_mulai_group">
                    <input type="text" id="waktu_mulai" name="waktu_mulai" required="required"
                          class="form-control form-control-modern"
                          placeholder="Pilih waktu mulai">
                    <span class="input-group-addon" style="background: #f5f5f5;">
                      <i class="fa fa-clock-o"></i>
                    </span>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="waktu_selesai">
                  Waktu Selesai <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-8 col-xs-12">
                  <div class="input-group date" id="waktu_selesai_group">
                    <input type="text" id="waktu_selesai" name="waktu_selesai" required="required"
                          class="form-control form-control-modern"
                          placeholder="Pilih waktu selesai">
                    <span class="input-group-addon" style="background: #f5f5f5;">
                      <i class="fa fa-clock-o"></i>
                    </span>
                  </div>
                  <small class="text-muted">Waktu selesai harus setelah waktu mulai</small>
                </div>
              </div>
              
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="keperluan">
                  Keperluan <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-8 col-xs-12">
                  <textarea id="keperluan" name="keperluan" required="required"
                        class="form-control form-control-modern" rows="3"
                        style="resize: vertical; min-height:80px;"
                        placeholder="Jelaskan keperluan pemesanan kendaraan"></textarea>
                </div>
              </div>
              
              <div class="form-group" style="margin-bottom: 18px;">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jumlah_orang">
                  Jumlah Orang <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-8 col-xs-12">
                  <input type="number" id="jumlah_orang" name="jumlah_orang" required="required"
                        class="form-control form-control-modern"
                        min="1" step="1" placeholder="Jumlah penumpang">
                </div>
              </div>

              <!-- ACTION BUTTONS -->
              <div class="ln_solid" style="margin-bottom:20px;"></div>
              <div class="form-group">
                <div class="col-md-6 col-sm-8 col-xs-12 col-md-offset-3">
                  <a href="<?php echo site_url('home'); ?>" class="btn btn-default btn-modern" style="margin-right:7px;">
                    <i class="fa fa-times"></i> Batal
                  </a>
                  <button class="btn btn-warning btn-modern" type="reset" style="margin-right:7px;">
                    <i class="fa fa-refresh"></i> Reset
                  </button>
                  <button type="submit" class="btn btn-success btn-modern">
                    <i class="fa fa-paper-plane"></i> Submit
                  </button>
                </div>
              </div>
            </form>
          </div><!-- /card-body -->
        </div><!-- /card -->
      </div>
    </div>
  </div>
</div>

<?php $this->load->view('header_footer/footer'); ?>

<script>
$(function() {
    // Date/time pickers with smart constraints to prevent invalid selections
    var today = moment().startOf('day');
    $('#tanggal_pakai_group').datetimepicker({
        format: 'DD-MM-YYYY',
        minDate: today,  // Prevent selecting past dates
        allowInputToggle: true
    });

    // Time pickers with dependency logic
    $('#waktu_mulai_group').datetimepicker({
        format: 'HH:mm'
    });

    $('#waktu_selesai_group').datetimepicker({
        format: 'HH:mm',
        useCurrent: false  // Prevent auto-selecting current time
    });

    // Enforce valid time ranges - end time must be after start time
    $('#waktu_mulai_group').on("dp.change", function(e) {
        $('#waktu_selesai_group').data("DateTimePicker").minDate(e.date);
    });
    $('#waktu_selesai_group').on("dp.change", function(e) {
        $('#waktu_mulai_group').data("DateTimePicker").maxDate(e.date);
    });

    // Enhanced employee selection with grouped options
    $('.select2_group').select2({
        placeholder: "Pilih Nomor Karyawan...",
        allowClear: true,
    });

    // Auto-fill employee details to prevent data inconsistency
    $('.select2_group').on('change', function() {
        var selected = $(this).find('option:selected');
        $('#nama').val(selected.data('nama'));
        $('#divisi').val(selected.data('divisi'));
    });

    // Initial form population if employee is pre-selected
    $('.select2_group').trigger('change');

    /* Commented out OpenRouteService integration for future use
    This section would handle:
    - Address to coordinate conversion
    - Distance calculation between office and destination
    - Route duration estimation
    
    Removed to simplify the current implementation but kept for reference
    when route planning features are needed */
})
</script>