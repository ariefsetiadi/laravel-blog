@extends('Layouts.cms')

@push('css')
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('cms/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('cms/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('cms/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@section('content')
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              @can('Tambah Role')
              <a href="{{ route('role.create') }}" class="btn btn-primary">
                Tambah
              </a>
              @endcan
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="table-responsive">
                <table id="roleTable" class="table table-bordered table-hover" width="100%">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Nama Role</th>
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
    </div>
    <!-- /.container-fluid -->
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

  <script type="text/javascript">
    $(document).ready(function () {
      var table	=	$('#roleTable').DataTable({
				processing: true,
				serverSide: true,
				ajax:{
					url: "{{ route('role.index') }}",
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
						data: 'name',
						name: 'name',
            width: '40%',
					},
					{
						data: 'action',
						name: 'action',
						orderable: false,
            className: 'text-center',
						width: '15%',
						searchable: false,
					},
				],
			});
    });
  </script>
@endpush