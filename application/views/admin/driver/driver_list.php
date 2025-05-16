<?php $this->load->view('header_footer/header'); ?>

<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Daftar Driver</h3>
      </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">

                  <!-- Add Nama Modal Trigger -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalTambahNama">Tambah Driver</button>

                    <!-- Add Nama Modal -->
                    <div class="modal fade" id="modalTambahNama" tabindex="-1" role="dialog" aria-labelledby="modalTambahNamaLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <form method="post" action="<?= site_url('driver/storeNama'); ?>">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h4 class="modal-title" id="modalTambahNamaLabel">Tambah Nama Driver</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <div class="modal-body">
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" class="form-control" name="nama" id="nama" required>
                            </div>
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                        </form>
                    </div>
                    </div>

            <style>
            /* Center BOTH table headers and table cells */
            .table th, .table td {
                text-align: center;
                vertical-align: middle;
            }
            </style>

       <div class="x_title">
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="table-responsive">
              <table class="table table-striped jambo_table bulk_action">
                <thead>
                  <tr class="headings">
                    <th>No</th>  
                    <th>Nama</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($drivers)): ?>
                    <?php $no = 1; ?>
                    <?php foreach ($drivers as $driver): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($driver->nama); ?></td>
                        <td>
                        <!-- Edit Button and Modal for this row -->
                        <button
                            type="button"
                            class="btn btn-sm btn-warning"
                            data-toggle="modal"
                            data-target="#modalEditNama<?= $driver->id ?>"
                        >Edit</button>
                        </td>
                    </tr>

                    <!-- Edit Nama Modal (for this driver) -->
                    <div class="modal fade" id="modalEditNama<?= $driver->id ?>" tabindex="-1" role="dialog" aria-labelledby="modalEditNamaLabel<?= $driver->id ?>" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <form method="post" action="<?= site_url('driver/updateNama/'.$driver->id); ?>">
                          <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                              <h4 class="modal-title" id="modalEditNamaLabel<?= $driver->id ?>"><i class="fa fa-user"></i> Edit Nama Driver</h4>
                              <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <div class="form-group mb-2">
                                <label for="nama<?= $driver->id ?>"><strong>Nama</strong></label>
                                <input type="text" class="form-control" name="nama" id="nama<?= $driver->id ?>" value="<?= htmlspecialchars($driver->nama); ?>" required>
                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <i class="fa fa-times"></i> Batal
                              </button>
                              <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Ubah
                              </button>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="7" class="text-center">Belum ada driver</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php $this->load->view('header_footer/footer'); ?>