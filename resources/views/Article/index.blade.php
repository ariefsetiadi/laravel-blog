@extends('Layouts.cms')

@push('css')
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('cms/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('cms/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('cms/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endpush

@section('content')
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <a href="{{ route('article.create') }}" class="btn btn-primary">
                Tambah
              </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="table-responsive">
                <table id="articleTable" class="table table-bordered table-hover" width="100%">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Judul Artikel</th>
                      <th>Kategori</th>
                      <th>Penulis</th>
                      <th>Diperbarui</th>
                      <th>Status</th>
                      <th>#</th>
                    </tr>
                  </thead>

                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
@endsection

@push('js')
  <!-- DataTables  & Plugins -->
  <script src="{{ asset('cms/plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('cms/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('cms/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('cms/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('cms/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
  <script src="{{ asset('cms/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

  <script type="text/javascript">
    $(document).ready(function () {
      var table	=	$('#articleTable').DataTable({
				processing: true,
				serverSide: true,
				ajax:{
					url: "{{ route('article.index') }}",
				},
				oLanguage: {
					sEmptyTable: 'Data Masih Kosong',
					sZeroRecords: 'Tidak Ada Data Yang Sesuai'
				},
				columns: [
					{
						data: 'DT_RowIndex',
						name: 'DT_RowIndex',
            className: 'text-center',
						width: '5%',
						searchable: false,
					},
					{
						data: 'title',
						name: 'title',
					},
          {
						data: 'category_name',
						name: 'category_name',
            className: 'text-center',
						width: '15%',
					},
          {
						data: 'userName',
						name: 'userName',
            className: 'text-center',
						width: '15%',
					},
          {
						data: 'updated_at',
						name: 'updated_at',
            className: 'text-center',
						width: '10%',
					},
					{
						data: 'status',
						name: 'status',
            className: 'text-center',
						width: '10%',
					},
					{
						data: 'action',
						name: 'action',
						orderable: false,
            className: 'text-center',
						width: '5%',
						searchable: false,
					},
				],
			});
    });
  </script>
@endpush