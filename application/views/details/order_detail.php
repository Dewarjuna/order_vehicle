<?php $this->load->view('header_footer/header'); ?>
<div class="right_col" role="main">
  <div class="page-title">
    <div class="title_left">
      <h3>Daftar Pesanan Saya</h3>
    </div>
  </div>
  <div class="clearfix"></div>
  <div class="x_panel">
    <div class="x_title">
      <h2>Tabel Pesanan</h2>
      <div class="clearfix"></div>
    </div>

            <style>
            /* Center BOTH table headers and table cells */
            .table th, .table td {
                text-align: center !important;
                vertical-align: middle !important;
            }
            </style>

    <div class="x_content">
      <div class="table-responsive">
        <table class="table table-striped jambo_table">
          <thead>
          <tr class="headings">
              <th class="column-title">No. </th>
              <th class="column-title">Tanggal Pesanan </th>
              <th class="column-title">Nama </th>             
              <th class="column-title">Tujuan </th>
              <th class="column-title">Tanggal Pakai </th>
              <th class="column-title">Waktu Pemakaian </th>
              <th class="column-title">Kendaraan </th>
              <th class="column-title">Status </th>
              <th class="column-title no-link last"><span class="nobr">Aksi</span></th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($pesanan_list)): ?>
                <?php $no=1; foreach($pesanan_list as $row): ?>
                  <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo date('d-m-Y', strtotime($row->tanggal_pesanan)); ?></td>
                    <td><?php echo htmlspecialchars($row->nama); ?></td>
                    <td><?php echo htmlspecialchars($row->tujuan); ?></td>
                    <td><?php echo date('d-m-Y', strtotime($row->tanggal_pakai)); ?></td>
                    <td><?php echo substr($row->waktu_mulai, 0, 5) . ' - ' . substr($row->waktu_selesai, 0, 5); ?></td>
                    <td>
                      <?php if (!empty($row->no_pol) && !empty($row->nama_kendaraan)): ?>
                        <?= htmlspecialchars($row->no_pol) ?> (<?= htmlspecialchars($row->nama_kendaraan) ?>)
                      <?php elseif (!empty($row->kendaraan)): ?>
                        <?= htmlspecialchars($row->kendaraan) ?>
                      <?php else: ?>
                        Menunggu Persetujuan
                      <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($row->status); ?></td>
                    <td>
                      <a href="<?php echo site_url('order/single/'.$row->id); ?>" class="btn btn-info btn-xs last">Detail</a>
                      <?php if (!empty($row->kendaraan) && strtolower($row->status) === 'approved'): ?>
                        <a href="javascript:void(0);" class="btn btn-secondary btn-xs last disabled" tabindex="-1" aria-disabled="true" style="pointer-events:none;opacity:0.6;">Edit</a>
                        <a href="javascript:void(0);" class="btn btn-secondary btn-xs last disabled" tabindex="-1" aria-disabled="true" style="pointer-events:none;opacity:0.6;">Hapus</a>
                      <?php else: ?>
                        <a href="<?php echo site_url('order/edit/'.$row->id); ?>" onclick="return confirm('Yakin ingin merubah pesanan ini?')" class="btn btn-warning btn-xs last">Edit</a>
                        <a href="<?php echo site_url('order/delete/'.$row->id); ?>" onclick="return confirm('Yakin ingin menghapus pesanan ini?')" class="btn btn-danger btn-xs last">Hapus</a>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                  <td colspan="11" class="text-center">Tidak ada pesanan ditemukan.</td>
                </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
        <!-- /page content -->
        <?php $this->load->view('header_footer/footer'); ?>
