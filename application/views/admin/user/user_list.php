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
                          <a href="<?= site_url('user/user_detail/'.$user->id); ?>" class="btn btn-sm btn-info">Detail</a>
                          <a href="<?= site_url('user/user_edit/'.$user->id); ?>" class="btn btn-sm btn-warning">Edit</a>
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