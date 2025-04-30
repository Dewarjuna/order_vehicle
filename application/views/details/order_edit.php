<?php $this->load->view('header_footer/header'); ?>
<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Edit Pesanan</h3>
      </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <br />
            <?php
                if (isset($pesanan) && isset($pesanan->id)) {
                    $form_action = site_url('order/update/' . (int)$pesanan->id);
                } else {
                    $form_action = '#';
                }
                ?>
            <form id="order-form"
                  data-parsley-validate
                  class="form-horizontal form-label-left"
                  method="post"
                  action="<?php echo $form_action; ?>">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tanggal_pesanan">
                  Tanggal Pesan <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class="input-group date" id="tanggal_pesanan">
                    <input type="text"
                           id="tanggal_pesanan_input"
                           name="tanggal_pesanan"
                           required="required"
                           class="form-control col-md-7 col-xs-12"
                           value="<?php echo isset($pesanan) ? date('d-m-Y', strtotime($pesanan->tanggal_pesanan)) : set_value('tanggal_pesanan'); ?>">
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
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

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor Karyawan</label>
                <div class="col-md-9 col-sm-9 col-xs-12">
                  <select class="select2_group form-control" id="nomor_karyawan" name="nomor_karyawan">
                    <?php foreach ($grouped_users as $divisi => $users_in_divisi): ?>
                      <optgroup label="<?= htmlspecialchars($divisi) ?>">
                        <?php foreach ($users_in_divisi as $user): ?>
                          <option 
                            value="<?= htmlspecialchars($user->nomor_karyawan) ?>"
                            data-nama="<?= htmlspecialchars($user->nama) ?>"
                            data-divisi="<?= htmlspecialchars($user->divisi) ?>"
                            <?php
                            // selected if editing and value matches OR if set_value matches
                            $is_selected = (isset($pesanan) && $user->nomor_karyawan == $pesanan->nomor_karyawan)
                              || (set_value('nomor_karyawan') == $user->nomor_karyawan);
                            echo $is_selected ? 'selected' : '';
                            ?>
                          >
                            <?= htmlspecialchars($user->nomor_karyawan) ?>
                          </option>
                        <?php endforeach; ?>
                      </optgroup>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Nama Karyawan<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="nama" name="nama" required="required" class="form-control col-md-7 col-xs-12"
                         value="<?php echo isset($pesanan) ? $pesanan->nama : set_value('nama'); ?>" readonly>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="divisi">Bagian <span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="divisi" name="divisi" required="required" class="form-control col-md-7 col-xs-12"
                         value="<?php echo isset($pesanan) ? $pesanan->divisi : set_value('divisi'); ?>" readonly>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tujuan">Tujuan <span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="tujuan" name="tujuan" required="required" class="form-control col-md-7 col-xs-12"
                         value="<?php echo isset($pesanan) ? $pesanan->tujuan : set_value('tujuan'); ?>">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tanggal_pakai">
                  Tanggal Pakai <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class="input-group date" id="tanggal_pakai">
                    <input type="text"
                           id="tanggal_pakai_input"
                           name="tanggal_pakai"
                           required="required"
                           class="form-control col-md-7 col-xs-12"
                           value="<?php echo isset($pesanan) ? date('d-m-Y', strtotime($pesanan->tanggal_pakai)) : set_value('tanggal_pakai'); ?>">
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="waktu_mulai">Start<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class="input-group date" id="waktu_mulai_group">
                    <input type="text" id="waktu_mulai" name="waktu_mulai" required="required" class="form-control col-md-7 col-xs-12"
                    value="<?php echo isset($pesanan) ? $pesanan->waktu_mulai : set_value('waktu_mulai'); ?>">
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-time"></span>
                    </span>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="waktu_selesai">Finish<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class="input-group date" id="waktu_selesai_group">
                    <input type="text" id="waktu_selesai" name="waktu_selesai" required="required" class="form-control col-md-7 col-xs-12"
                    value="<?php echo isset($pesanan) ? $pesanan->waktu_selesai : set_value('waktu_selesai'); ?>">
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-time"></span>
                    </span>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="keperluan">Keperluan<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="keperluan" name="keperluan" required="required" class="form-control col-md-7 col-xs-12"
                  value="<?php echo isset($pesanan) ? $pesanan->keperluan : set_value('keperluan'); ?>">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jumlah_orang">Jumlah Orang<span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="number" id="jumlah_orang" name="jumlah_orang" required="required" class="form-control col-md-7 col-xs-12"
                  min="1" step="1"
                  value="<?php echo isset($pesanan) ? (int)$pesanan->jumlah_orang : set_value('jumlah_orang'); ?>">
                </div>
              </div>
              <div class="ln_solid"></div>
              <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                  <a class="btn btn-default" href="<?php echo site_url('order/detail'); ?>">Cancel</a>
                  <button class="btn btn-primary" type="reset">Reset</button>
                  <button type="submit" class="btn btn-success"><?php echo isset($pesanan) ? 'Update' : 'Submit'; ?></button>
                </div>
              </div>
            </form>
          </div> <!-- x_content -->
        </div> <!-- x_panel -->
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('header_footer/footer'); ?>

<!-- The JS remains the same as in your code, just update selectors if needed -->

<script>
window.onload = function() {
    var today = new Date();

    $('#tanggal_pesanan').datetimepicker({
        format: 'DD-MM-YYYY',
        minDate: today
    });

    $('#tanggal_pakai').datetimepicker({
        format: 'DD-MM-YYYY',
        minDate: today
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

    $('#myDatepicker4').datetimepicker({
        ignoreReadonly: true,
        allowInputToggle: true
    });

    $('#datetimepicker6').datetimepicker();

    $('#datetimepicker7').datetimepicker({
        useCurrent: false
    });

    $('#datetimepicker6').on("dp.change", function(e) {
        $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
    });

    $('#datetimepicker7').on("dp.change", function(e) {
        $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
    });
};
</script>
<script>
$(document).ready(function() {
    // When the select changes
    $('.select2_group').on('change', function() {
        var selected = $(this).find('option:selected');
        $('#nama').val(selected.data('nama'));
        $('#divisi').val(selected.data('divisi'));
    });

    // Trigger change on page load to auto-fill if default selected
    $('.select2_group').trigger('change');
});
</script>