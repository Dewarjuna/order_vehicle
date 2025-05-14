<?php $this->load->view('header_footer/header'); ?>

<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Daftar Kendaraan</h3>
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
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalAddKendaraan">Tambah Kendaraan</button>

                    <!-- Add Nama Modal -->
                    <div class="modal fade" id="modalAddKendaraan" tabindex="-1" role="dialog" aria-labelledby="modalAddKendaraan" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <form method="post" action="<?= site_url('vehicle/storeKendaraan'); ?>">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h4 class="modal-title" id="modalAddKendaraan">Tambah Nama vehicle</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <div class="modal-body">
                            <div class="form-group">
                                <label for="no_pol">Nomor Polisi</label>
                                <input type="text" class="form-control" name="no_pol" id="no_pol" required>
                            </div>
                            <div class="form-group">
                                <label for="nama_kendaraan">Nama Kendaraan</label>
                                <input type="text" class="form-control" name="nama_kendaraan" id="nama_kendaraan" required>
                            </div>
                            <div class="form-group">
                                <label for="kapasitas">Kapasitas</label>
                                <input type="text" class="form-control" name="kapasitas" id="kapasitas" required>
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

       <div class="x_title">
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="table-responsive">
              <table class="table table-striped jambo_table bulk_action">
                <thead>
                  <tr class="headings">
                    <th>No</th>  
                    <th>Nomor Polisi</th>
                    <th>Nama Kendaraan</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($vehicles)): ?>
                    <?php $no = 1; ?>
                    <?php foreach ($vehicles as $vehicle): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($vehicle->no_pol); ?></td>
                        <td><?= htmlspecialchars($vehicle->nama_kendaraan); ?></td>
                        <td>
                        <!-- Edit Button and Modal for this row -->
                        <button
                            type="button"
                            class="btn btn-sm btn-warning"
                            data-toggle="modal"
                            data-target="#modalEditKendaraan<?= $vehicle->id ?>"
                        >Edit</button>
                        <a href="<?= site_url('vehicle/delete/'.$vehicle->id); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus kendaraan ini?');">Hapus</a>
                        </td>
                    </tr>

                    <!-- Edit Nama Modal (for this vehicle) -->
                    <div class="modal fade" id="modalEditKendaraan<?= $vehicle->id ?>" tabindex="-1" role="dialog" aria-labelledby="modalEditKendaraan<?= $vehicle->id ?>" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                        <form method="post" action="<?= site_url('vehicle/updateKendaraan/'.$vehicle->id); ?>">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="modalEditKendaraan<?= $vehicle->id ?>">Edit Nama Kendaraan</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                  <label for="no_pol<?= $vehicle->id ?>">Nomor Polisi</label>
                                  <input type="text" class="form-control" name="no_pol" id="no_pol<?= $vehicle->id ?>" value="<?= htmlspecialchars($vehicle->no_pol); ?>" required>
                                </div>
                                <div class="form-group">
                                  <label for="nama_kendaraan<?= $vehicle->id ?>">Nama Kendaraan</label>
                                  <input type="text" class="form-control" name="nama_kendaraan" id="nama_kendaraan<?= $vehicle->id ?>" value="<?= htmlspecialchars($vehicle->nama_kendaraan); ?>" required>
                                </div>
                                <div class="form-group">
                                  <label for="kapasitas<?= $vehicle->id ?>">Kapasitas</label>
                                  <input type="text" class="form-control" name="kapasitas" id="kapasitas<?= $vehicle->id ?>" value="<?= htmlspecialchars($vehicle->kapasitas); ?>" required>
                                </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Ubah</button>
                            </div>
                            </div>
                        </form>
                        </div>
                    </div>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="7" class="text-center">Belum ada kendaraan</td>
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