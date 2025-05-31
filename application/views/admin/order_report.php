<?php $this->load->view('header_footer/header'); ?>

<style>
.table th, .table td {
    text-align: center !important;
    vertical-align: middle !important;
}
</style>

<div class="right_col" role="main">
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Laporan Pesanan</h3>
      </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
      <div class="col-md-12">
        <div class="x_panel">
          <div class="x_title"><div class="clearfix"></div></div>
          <div class="x_content">
            <!-- Filter Form -->
            <form id="filter-form" class="form-inline" style="margin-bottom:20px;">
              <div class="form-group">
                <label for="date_from">Tanggal Pakai:</label>
                <input type="date" id="date_from" name="date_from" class="form-control">
              </div>
              <span style="margin:0 8px;">s/d</span>
              <div class="form-group">
                <input type="date" id="date_to" name="date_to" class="form-control">
              </div>
              <button type="submit" class="btn btn-primary">Filter</button>
              <button type="button" class="btn btn-default" id="resetFilter">Reset</button>
            </form>

            <div class="table-responsive">
              <table id="datatable" class="table table-striped jambo_table" style="width:100%">
                <thead>
                  <tr class="headings">
                    <th class="column-title">No.</th>
                    <th class="column-title">Tanggal Pakai</th>
                    <th class="column-title">Pemakai</th>
                    <th class="column-title">Tujuan</th>
                    <th class="column-title">Kendaraan</th>
                    <th class="column-title">Driver</th>
                    <th class="column-title">Status</th>
                    <th class="column-title">Aksi</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Single AJAX Modal for Detail -->
<div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title" id="modalDetailLabel">
          <i class="fa fa-info-circle"></i> Detail Pesanan
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" style="background: #f9fbfc;" id="modalDetailBody">
        Memuat...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<?php $this->load->view('header_footer/footer'); ?>

<script>
function formatStatus(status) {
    var s = status.toLowerCase();
    if (s == 'approved')
        return '<span class="label label-primary" style="font-size:14px;">Telah disetujui - Ongoing</span>';
    if (s == 'done')
        return '<span class="label label-success" style="font-size:14px;">Selesai</span>';
    if (s == 'pending')
        return '<span class="label label-warning" style="font-size:14px;">Menunggu</span>';
    if (s == 'rejected')
        return '<span class="label label-danger" style="font-size:14px;">Ditolak</span>';
    if (s == 'no confirmation')
        return '<span class="label label-default" style="font-size:14px;">Tidak Ada Konfirmasi</span>';
    return '<span style="font-size:14px;">'+status+'</span>';
}
function formatKendaraan(no_pol, nama_kendaraan, kendaraan, status) {
    status = (status || '').toLowerCase();
    if (status === 'rejected' || status === 'no confirmation') {
        return '<em class="text-muted">- Tidak Berlaku -</em>';
    } else if (status === 'approved' || status === 'done') {
        if (no_pol && nama_kendaraan) {
            return $('<div>').text(no_pol + " (" + nama_kendaraan + ")").html();
        } else if (kendaraan) {
            return $('<div>').text(kendaraan).html();
        } else {
            return 'Menunggu Persetujuan';
        }
    }
    return 'Menunggu Persetujuan';
}

function formatDriver(nama_driver, driver, status) {
    status = (status || '').toLowerCase();
    if (status === 'rejected' || status === 'no confirmation') {
        return '<em class="text-muted">- Tidak Berlaku -</em>';
    } else if (status === 'approved' || status === 'done') {
        if (nama_driver) {
            return $('<div>').text(nama_driver).html();
        } else if (driver) {
            return $('<div>').text(driver).html();
        } else {
            return 'Menunggu Persetujuan';
        }
    }
    return 'Menunggu Persetujuan';
}
$(document).ready(function() {
  if ($.fn.dataTable.isDataTable('#datatable')) {
      $('#datatable').DataTable().destroy();
  }
  var table = $('#datatable').DataTable({
      "processing": true,
      "serverSide": true,
      "ordering": false,
      "searching": true,
      "paging": true,
      "lengthChange": true,
      "responsive": true,
      "autoWidth": false,
      "columnDefs": [
          { "width": "60px", "targets": 0 }
      ],
      "ajax": {
          "url": "<?= site_url('order/order_report_ajax'); ?>",
          "type": "POST",
          "data": function(d) {
              d.date_from = $('#date_from').val();
              d.date_to = $('#date_to').val();
          }
      },
      columns: [
      {data: 0}, // No.
      {data: 1}, // Tanggal Pakai
      {data: 2}, // Pemakai
      {data: 3}, // Tujuan
      {   // Kendaraan
          data: null,
          render: function(data, type, row) {
              return formatKendaraan(row[4], row[5], row[6], row[9]); // no_pol, nama_kendaraan, kendaraan, status
          }
      },
      {   // Driver
          data: null,
          render: function(data, type, row) {
              return formatDriver(row[7], row[8], row[9]); // nama_driver, driver, status
          }
      },
      {   // Status
          data: 9,
          render: function(data, type, row) {
              return formatStatus(data);
          }
      },
      {data: 10, orderable: false} // Aksi/Detail button
    ]
  });

  $('#filter-form').on('submit', function(e){
      e.preventDefault();
      table.ajax.reload();
  });

  $('#resetFilter').on('click', function() {
      $('#date_from').val('');
      $('#date_to').val('');
      table.ajax.reload();
  });

  $('#datatable').on('click', '.btn-detail', function() {
      var id = $(this).data('id');
      $('#modalDetailBody').html('Memuat...');
      $('#modalDetail').modal('show');
      $.get('<?= site_url('order/order_detail_ajax'); ?>/' + id, function(html) {
          $('#modalDetailBody').html(html);
      });
  });
});
</script>