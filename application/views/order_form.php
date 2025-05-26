<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('header_footer/header'); ?>

<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3><i class="fa fa-car"></i> Pemesanan Kendaraan</h3>
      </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel" style="border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
          <div class="x_title" style="border-bottom: 2px solid #e0e0e0;">
            <h2 style="font-weight: 600;"><i class="fa fa-file-text-o"></i> Formulir Pemesanan</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content" style="padding: 20px 30px;">
            <br />
            <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" method="post" action="<?php echo site_url('order/submit'); ?>">

              <!-- Section 1: Employee Information -->
              <div class="form-group" style="margin-bottom: 25px;">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tanggal_pesanan" style="font-weight: 500;">
                  Tanggal Pesan <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class="input-group" style="border-radius: 4px; border: 1px solid #d2d6de;">
                    <input type="text" id="tanggal_pesanan" name="tanggal_pesanan" required="required"
                          class="form-control col-md-7 col-xs-12" style="height: 38px;"
                          value="<?php echo date('d-m-Y'); ?>" readonly>
                    <span class="input-group-addon" style="background: #f5f5f5;">
                      <i class="fa fa-calendar"></i>
                    </span>
                  </div>
                </div>
              </div>

              <?php
              // Group users by divisi
              $grouped_users = [];
              foreach ($users as $user) {
                  $grouped_users[$user->divisi][] = $user;
              }
              ?>
              
              <div class="form-group" style="margin-bottom: 25px;">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" style="font-weight: 500;">Nomor Karyawan</label>
                  <div class="col-md-6 col-sm-9 col-xs-12">
                      <select class="select2_group form-control" id="nomor_karyawan" name="nomor_karyawan" style="height: 38px; border-radius: 4px;">
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
                      <small class="text-muted" style="display: block; margin-top: 5px;">Cari dengan mengetik nama atau nomor karyawan</small>
                  </div>
              </div>
              
              <div class="form-group" style="margin-bottom: 25px;">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama" style="font-weight: 500;">Nama Karyawan<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" id="nama" name="nama" required="required" 
                          class="form-control col-md-7 col-xs-12" style="height: 38px;"
                          readonly>
                </div>
              </div>
              
              <div class="form-group" style="margin-bottom: 25px;">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="divisi" style="font-weight: 500;">Bagian <span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" id="divisi" name="divisi" required="required" 
                          class="form-control col-md-7 col-xs-12" style="height: 38px;"
                          readonly>
                </div>
              </div>

              <!-- Section 2: Trip Details -->
              <div class="form-group" style="margin-bottom: 25px;">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tujuan" style="font-weight: 500;">Tujuan <span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="text" id="tujuan" name="tujuan" required="required" 
                          class="form-control col-md-7 col-xs-12" style="height: 38px;"
                          placeholder="Masukkan tujuan perjalanan">
                </div>
              </div>
              
              <div class="form-group" style="margin-bottom: 25px;">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tanggal_pakai" style="font-weight: 500;">
                  Tanggal Pakai <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class="input-group date" id="tanggal_pakai_group" style="border-radius: 4px; border: 1px solid #d2d6de;">
                    <input type="text" id="tanggal_pakai" name="tanggal_pakai" required="required" 
                          class="form-control col-md-7 col-xs-12" style="height: 38px;"
                          placeholder="Pilih tanggal">
                    <span class="input-group-addon" style="background: #f5f5f5;">
                      <i class="fa fa-calendar"></i>
                    </span>
                  </div>
                </div>
              </div>
              
              <div class="form-group" style="margin-bottom: 25px;">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="waktu_mulai" style="font-weight: 500;">Waktu Mulai<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class="input-group date" id="waktu_mulai_group" style="border-radius: 4px; border: 1px solid #d2d6de;">
                    <input type="text" id="waktu_mulai" name="waktu_mulai" required="required" 
                          class="form-control col-md-7 col-xs-12" style="height: 38px;"
                          placeholder="Pilih waktu mulai">
                    <span class="input-group-addon" style="background: #f5f5f5;">
                      <i class="fa fa-clock-o"></i>
                    </span>
                  </div>
                </div>
              </div>

              <div class="form-group" style="margin-bottom: 25px;">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="waktu_selesai" style="font-weight: 500;">Waktu Selesai<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class="input-group date" id="waktu_selesai_group" style="border-radius: 4px; border: 1px solid #d2d6de;">
                    <input type="text" id="waktu_selesai" name="waktu_selesai" required="required" 
                          class="form-control col-md-7 col-xs-12" style="height: 38px;"
                          placeholder="Pilih waktu selesai">
                    <span class="input-group-addon" style="background: #f5f5f5;">
                      <i class="fa fa-clock-o"></i>
                    </span>
                  </div>
                  <small class="text-muted" style="display: block; margin-top: 5px;">Pastikan waktu selesai setelah waktu mulai</small>
                </div>
              </div>
              
              <div class="form-group" style="margin-bottom: 25px;">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="keperluan" style="font-weight: 500;">Keperluan<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <textarea id="keperluan" name="keperluan" required="required" 
                        class="form-control col-md-7 col-xs-12" rows="3"
                        placeholder="Jelaskan keperluan pemesanan kendaraan"></textarea>
                </div>
              </div>
              
              <div class="form-group" style="margin-bottom: 25px;">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jumlah_orang" style="font-weight: 500;">Jumlah Orang<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="number" id="jumlah_orang" name="jumlah_orang" required="required" 
                        class="form-control col-md-7 col-xs-12" style="height: 38px;"
                        min="1" step="1" placeholder="Jumlah penumpang">
                </div>
              </div>
              
              <div class="ln_solid"></div>
                <div class="form-group">
                  <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                    <a href="<?php echo site_url('home'); ?>" class="btn btn-default" style="padding: 8px 20px;">
                      <i class="fa fa-times"></i> Batal
                    </a>
                    <button class="btn btn-warning" type="reset" style="padding: 8px 20px;">
                      <i class="fa fa-refresh"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-success" style="padding: 8px 20px;">
                      <i class="fa fa-paper-plane"></i> Submit
                    </button>
                  </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php $this->load->view('header_footer/footer'); ?>

<!-- Keep all original JavaScript exactly the same -->
<script>
$(function() {
    var today = moment().startOf('day');
    $('#tanggal_pakai_group').datetimepicker({
        format: 'DD-MM-YYYY',
        minDate: today,
        allowInputToggle: true
    });

    $('#waktu_mulai_group').datetimepicker({
        format: 'HH:mm'
    });

    $('#waktu_selesai_group').datetimepicker({
        format: 'HH:mm',
        useCurrent: false
    });

    $('#waktu_mulai_group').on("dp.change", function(e) {
        $('#waktu_selesai_group').data("DateTimePicker").minDate(e.date);
    });

    $('#waktu_selesai_group').on("dp.change", function(e) {
        $('#waktu_mulai_group').data("DateTimePicker").maxDate(e.date);
    });

    $('.select2_group').select2({
        placeholder: "Pilih Nomor Karyawan...",
        allowClear: true,
    });

    $('.select2_group').on('change', function() {
        var selected = $(this).find('option:selected');
        $('#nama').val(selected.data('nama'));
        $('#divisi').val(selected.data('divisi'));
    });

    $('.select2_group').trigger('change');
});
</script>