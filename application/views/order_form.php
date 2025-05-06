<?php $this->load->view('header_footer/header'); ?>
<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Pemesanan Kendaraan</h3>
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
                    <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" method="post" action="<?php echo site_url('order/submit'); ?>">

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tanggal_pesanan">
                        Tanggal Pesan <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text"
                              id="tanggal_pesanan"
                              name="tanggal_pesanan"
                              required="required"
                              class="form-control col-md-7 col-xs-12"
                              value="<?php echo date('d-m-Y'); ?>"
                              readonly>
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
                          <div class="col-md-6 col-sm-9 col-xs-12">
                              <select class="select2_group form-control" id="nomor_karyawan" name="nomor_karyawan">
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
                          </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama">Nama Karyawan<span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="nama" name="nama" required="required" class="form-control col-md-7 col-xs-12" readonly>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="divisi">Bagian <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="divisi" name="divisi" required="required" class="form-control col-md-7 col-xs-12" readonly>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tujuan">Tujuan <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" id="tujuan" name="tujuan" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tanggal_pakai">
                          Tanggal Pakai <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <div class="input-group date" id="tanggal_pakai_group">
                            <input type="text" id="tanggal_pakai" name="tanggal_pakai" required="required" class="form-control col-md-7 col-xs-12">
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
                          <input type="text" id="waktu_mulai" name="waktu_mulai" required="required" class="form-control col-md-7 col-xs-12">
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
                          <input type="text" id="waktu_selesai" name="waktu_selesai" required="required" class="form-control col-md-7 col-xs-12">
                          <span class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                          </span>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="keperluan">Keperluan<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" id="keperluan" name="keperluan" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jumlah_orang">Jumlah Orang<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="number" id="jumlah_orang" name="jumlah_orang" required="required" class="form-control col-md-7 col-xs-12" min="1" step="1">
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                          <button class="btn btn-primary" type="button">Cancel</button>
						  <button class="btn btn-primary" type="reset">Reset</button>
                          <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                      </div>

                    </form>
                  </div>
                </div>
              </div>
            </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
        <?php $this->load->view('header_footer/footer'); ?>

        <script>
        $(function() {
            var today = moment().startOf('day');
            $('#tanggal_pakai_group').datetimepicker({
                format: 'DD-MM-YYYY',
                minDate: today,
                allowInputToggle: true
            });
            /*
            $('#tanggal_pesanan').datetimepicker({
                format: 'DD-MM-YYYY',
                minDate: today
            });
            */

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
        });
        </script>
        <script>
        $(function() {
            // Initialize Select2 for enhanced dropdown with search
            $('.select2_group').select2({
                placeholder: "Pilih Nomor Karyawan...",
                allowClear: true,
            });

            // When selection changes, update text fields as before
            $('.select2_group').on('change', function() {
                var selected = $(this).find('option:selected');
                $('#nama').val(selected.data('nama'));
                $('#divisi').val(selected.data('divisi'));
            });

            // Auto-fill when opening form (optional)
            $('.select2_group').trigger('change');
        });
        </script>