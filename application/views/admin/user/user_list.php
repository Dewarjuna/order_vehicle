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
                    <div class="modal-header">
                      <h4 class="modal-title" id="modalAddUser">Tambah User</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <div class="form-group">
                        <label for="nomor_karyawan">Nomor Karyawan</label>
                        <input type="text" class="form-control" name="nomor_karyawan" required>
                      </div>
                      <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" class="form-control" name="nama" required>
                      </div>
                      <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" name="username" required>
                      </div>
                      <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" required>
                      </div>
                      <div class="form-group">
                        <label for="divisi">Divisi</label>
                        <input type="text" class="form-control" name="divisi" required>
                      </div>
                      <div class="form-group">
                        <label for="role">Role</label>
                        <select name="role" class="form-control" required>
                          <option value="admin">Admin</option>
                          <option value="user">User</option>
                        </select>
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
            <!-- END ADD USER MODAL -->

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
                          <a href="<?= site_url('user/user_edit/'.$user->id); ?>" class="btn btn-sm btn-warning">Edit</a>
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
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Detail User</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <table class="table">
              <tr><th>Nomor Karyawan</th><td><?= htmlspecialchars($user->nomor_karyawan); ?></td></tr>
              <tr><th>Nama</th><td><?= htmlspecialchars($user->nama); ?></td></tr>
              <tr><th>Username</th><td><?= htmlspecialchars($user->username); ?></td></tr>
              <tr><th>Divisi</th><td><?= htmlspecialchars($user->divisi); ?></td></tr>
              <tr><th>Role</th><td><?= htmlspecialchars($user->role); ?></td></tr>
              <tr><th>Last Login</th><td><?= htmlspecialchars($user->last_login); ?></td></tr>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!-- Delete User Modal -->
    <div class="modal fade" id="modalDeleteUser<?= $user->id; ?>" tabindex="-1" role="dialog" aria-labelledby="modalDeleteUser<?= $user->id; ?>" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form method="post" action="<?= site_url('user/delete_user/'.$user->id); ?>">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Konfirmasi Hapus User</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <p>Apakah yakin ingin menghapus user <b><?= htmlspecialchars($user->nama); ?></b>?</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-danger">Hapus</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>