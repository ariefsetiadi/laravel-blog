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

      <!-- Modal Add & Edit Data -->
      <div class="modal fade" id="roleModal" data-backdrop="static" data-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="modalTitle"></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form id="roleForm" method="post" enctype="multipart/form-data">
              @csrf

              <div class="modal-body">
                <input type="hidden" name="role_id" id="role_id">

                <div class="form-group mb-3">
                  <label>Nama Role</label>
                  <input type="text" name="name" id="name" class="form-control" placeholder="Nama Role">
                  <div class="text-danger" id="name_error"></div>
                </div>

                <div class="form-group select2-purple mb-3">
                  <label>Permission</label>
                  <select class="select2" multiple="multiple" id="permissions" name="permissions[]" data-dropdown-css-class="select2-purple" data-placeholder="Pilih Permission" style="width: 100%;">
                    @foreach($permissions as $row)
                      <option value="{{ $row->id }}">{{ $row->name }}</option>
                    @endforeach
                  </select>
                  <div class="text-danger" id="permissions_error"></div>
                </div>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary" id="btnSave"></button>
              </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
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
  <script src="{{ asset('cms/plugins/select2/js/select2.full.min.js') }}"></script>

  <script type="text/javascript">
    $(function () {
      $('.select2').select2();
    });

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