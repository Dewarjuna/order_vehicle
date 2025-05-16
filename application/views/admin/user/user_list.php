<?php $this->load->view('header_footer/header'); ?>

<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Daftar Pengguna</h3>
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

          <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade in">
              <?=$this->session->flashdata('error');?>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
          <?php endif; ?>
          <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade in">
              <?=$this->session->flashdata('success');?>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
          <?php endif; ?>

            <!-- Tambah User Modal Trigger -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalAddUser">Tambah User</button>

            <!-- Tambah User Modal -->
              <div class="modal fade" id="modalAddUser" tabindex="-1" role="dialog" aria-labelledby="modalAddUser" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <form method="post" action="<?= site_url('user/add_user'); ?>">
                    <div class="modal-content">
                      <div class="modal-header bg-success text-white">
                        <h4 class="modal-title"><i class="fa fa-user-plus"></i> Tambah User</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div class="form-group mb-2">
                          <label for="nomor_karyawan"><strong>Nomor Karyawan</strong></label>
                          <input type="text" id="nomor_karyawan" name="nomor_karyawan" class="form-control" required>
                        </div>
                        <div class="form-group mb-2">
                          <label for="nama"><strong>Nama</strong></label>
                          <input type="text" id="nama" name="nama" class="form-control" required>
                        </div>
                        <div class="form-group mb-2">
                          <label for="username"><strong>Username</strong></label>
                          <input type="text" id="username" name="username" class="form-control" required autocomplete="off">
                        </div>
                        <div class="form-group mb-2">
                          <label for="password"><strong>Password</strong></label>
                          <input type="password" id="password" name="password" class="form-control" required autocomplete="off">
                        </div>
                        <div class="form-group mb-2">
                          <label for="divisi"><strong>Divisi</strong></label>
                          <input type="text" id="divisi" name="divisi" class="form-control" required>
                        </div>
                        <div class="form-group mb-2">
                          <label for="role"><strong>Role</strong></label>
                          <select id="role" name="role" class="form-control" required>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                          </select>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                          <i class="fa fa-times"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-success">
                          <i class="fa fa-save"></i> Simpan User
                        </button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            <!-- END ADD USER MODAL -->

            <style>
            /* Center BOTH table headers and table cells */
            .table th, .table td {
                text-align: center !important;
                vertical-align: middle !important;
            }
            </style>

            <div class="table-responsive">
              <table class="table table-striped jambo_table bulk_action">
                <thead>
                  <tr class="headings">
                    <th>#</th>
                    <th>Nomor Karyawan</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>Divisi</th>
                    <th>Role</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($users)): ?>
                    <?php $no=1; foreach($users as $user): ?>
                      <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($user->nomor_karyawan); ?></td>
                        <td><?= htmlspecialchars($user->nama); ?></td>
                        <td><?= htmlspecialchars($user->username); ?></td>
                        <td><?= htmlspecialchars($user->divisi); ?></td>
                        <td><?= htmlspecialchars($user->role); ?></td>
                        <td>
                          <!-- Buttons trigger modals -->
                          <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalDetailUser<?= $user->id; ?>">Detail</button>
                          <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalEditUser<?= $user->id; ?>">Edit</button>
                          <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalDeleteUser<?= $user->id; ?>">Hapus</button>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="7" class="text-center">Belum ada user.</td>
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

<!-- Modals for Each User - OUTSIDE TABLE for Bootstrap reliability -->
<?php if (!empty($users)): ?>
  <?php foreach($users as $user): ?>

<!-- Detail User Modal -->
<div class="modal fade" id="modalDetailUser<?= $user->id; ?>" tabindex="-1" role="dialog" aria-labelledby="modalDetailUser<?= $user->id; ?>" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h4 class="modal-title"><i class="fa fa-user"></i> Detail User</h4>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-striped">
          <tr><th>Nomor Karyawan</th><td><?= htmlspecialchars($user->nomor_karyawan); ?></td></tr>
          <tr><th>Nama</th><td><?= htmlspecialchars($user->nama); ?></td></tr>
          <tr><th>Username</th><td><?= htmlspecialchars($user->username); ?></td></tr>
          <tr><th>Divisi</th><td><?= htmlspecialchars($user->divisi); ?></td></tr>
          <tr><th>Role</th><td><?= htmlspecialchars($user->role); ?></td></tr>
          <tr><th>Last Login</th><td><?= htmlspecialchars($user->last_login); ?></td></tr>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="modalEditUser<?= $user->id; ?>" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="post" action="<?= site_url('user/update_user/' . $user->id); ?>">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h4 class="modal-title"><i class="fa fa-edit"></i> Edit User</h4>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group mb-2">
            <label for="nama_<?= $user->id; ?>"><strong>Nama</strong></label>
            <input type="text" id="nama_<?= $user->id; ?>" name="nama" class="form-control" value="<?= htmlspecialchars($user->nama); ?>" required>
          </div>
          <div class="form-group mb-2">
            <label for="username_<?= $user->id; ?>"><strong>Username</strong></label>
            <input type="text" id="username_<?= $user->id; ?>" name="username" class="form-control" value="<?= htmlspecialchars($user->username); ?>" required autocomplete="off">
          </div>
          <div class="form-group mb-2">
            <label for="divisi_<?= $user->id; ?>"><strong>Divisi</strong></label>
            <input type="text" id="divisi_<?= $user->id; ?>" name="divisi" class="form-control" value="<?= htmlspecialchars($user->divisi); ?>" required>
          </div>
          <div class="form-group mb-2">
            <label for="role_<?= $user->id; ?>"><strong>Role</strong></label>
            <select id="role_<?= $user->id; ?>" name="role" class="form-control" required>
              <option value="admin" <?= $user->role == 'admin' ? 'selected' : ''; ?>>Admin</option>
              <option value="user"  <?= $user->role == 'user'  ? 'selected' : ''; ?>>User</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="fa fa-times"></i> Batal
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="fa fa-save"></i> Simpan Perubahan
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Delete User Modal -->
<div class="modal fade" id="modalDeleteUser<?= $user->id; ?>" tabindex="-1" role="dialog" aria-labelledby="modalDeleteUser<?= $user->id; ?>" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="post" action="<?= site_url('user/delete/'.$user->id); ?>">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h4 class="modal-title"><i class="fa fa-trash"></i> Konfirmasi Hapus User</h4>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Apakah yakin ingin menghapus user <b><?= htmlspecialchars($user->nama); ?></b>?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="fa fa-times"></i> Batal
          </button>
          <button type="submit" class="btn btn-danger">
            <i class="fa fa-trash"></i> Hapus
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<?php endforeach; ?>
<?php endif; ?>