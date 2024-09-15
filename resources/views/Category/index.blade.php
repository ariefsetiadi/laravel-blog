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
							@can('Tambah Kategori')
              <button type="button" class="btn btn-primary" id="btnAdd">
                Tambah
              </button>
							@endcan
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="table-responsive">
                <table id="categoryTable" class="table table-bordered table-hover" width="100%">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Nama Kategori</th>
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

      <!-- Modal Add & Edit Data -->
      <div class="modal fade" id="categoryModal" data-backdrop="static" data-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="modalTitle"></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form id="categoryForm" method="post" enctype="multipart/form-data">
              @csrf

              <div class="modal-body">
                <input type="hidden" name="category_id" id="category_id">

                <div class="form-group mb-3">
                  <label>Nama Kategori</label>
                  <input type="text" name="category_name" id="category_name" class="form-control" placeholder="Nama Kategori">
                  <div class="text-danger" id="category_name_error"></div>
                </div>

                <div class="form-group mb-3">
                  <label>Status</label>
                  <select name="status" id="status" class="form-control">
                    <option disabled selected>-- Pilih Status --</option>
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                  </select>
                  <div class="text-danger" id="status_error"></div>
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

  <script type="text/javascript">
    $(document).ready(function () {
      var table	=	$('#categoryTable').DataTable({
				processing: true,
				serverSide: true,
				ajax:{
					url: "{{ route('category.index') }}",
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
						data: 'category_name',
						name: 'category_name',
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
						width: '15%',
						searchable: false,
					},
				],
			});

      // Form modal Add
			$('#btnAdd').click(function() {
				$('#modalTitle').text("Tambah Kategori");
				$('#btnSave').text("Simpan");
				$('#categoryForm').trigger("reset");
				$('#categoryModal').modal("show");

        // Delete class is-invalid
        $('#categoryForm input').removeClass("is-invalid");
        $('#categoryForm select').removeClass("is-invalid");

        // Remove Error Message
        $('#category_name_error').text("");
        $('#status_error').text("");
			});

      // Form modal Edit
			$(document).on('click', '.btnEdit', function () {
				var url = '{{ route("category.edit", ":id") }}';
				category_id = $(this).attr("id");

				$.ajax({
					url: url.replace(":id", category_id),
					dataType: "json",
					success: function (html) {
						$('#modalTitle').text("Edit Kategori");
						$('#btnSave').text("Update");
						$('#categoryForm').trigger("reset");
						$('#categoryModal').modal("show");

            // Insert value
						$('#category_id').val(html.data.id);
						$('#category_name').val(html.data.category_name);
						$('#status').val(html.data.status);

            // Delete class is-invalid
            $('#categoryForm input').removeClass("is-invalid");
            $('#categoryForm select').removeClass("is-invalid");

            // Remove Error Message
            $('#category_name_error').text("");
            $('#status_error').text("");
					}
				});
			});

      // Submit data
			$('#categoryForm').on('submit', function (e) {
				e.preventDefault();

				if ($('#btnSave').text() == 'Simpan') {
					$('#category_name_error').text();
					$('#status_error').text();

					$.ajax({
						url: "{{ route('category.store') }}",
						method: "POST",
						data: new FormData(this),
						contentType: false,
						cache: false,
						processData: false,
						dataType:"json",

						beforeSend: function() {
							$('#btnSave').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');
						},

						success: function(res) {
							setTimeout(function() {
								$('#categoryForm')[0].reset();
								$('#categoryModal').modal('hide');
								$('#categoryTable').DataTable().ajax.reload();
							});

							toastr.options =
							{
								"closeButton" : true,
								"progressBar" : false,
								"preventDuplicates": true,
								"timeOut": "3000",
								"positionClass": "toast-top-center"
							}
							toastr.success(res.messages);
						},

						error: function(reject) {
							setTimeout(function() {
								$('#btnSave').text('Simpan');
								var response = $.parseJSON(reject.responseText);
								$.each(response.errors, function (key, val) {
									$('#' + key + "_error").text(val[0]);
									$('#' + key).addClass('is-invalid');
								});
							});
						}
					});
				}

				if ($('#btnSave').text() == 'Update') {
					$('#category_name_error').text();
					$('#status_error').text();

					$.ajax({
						url: "{{ route('category.update') }}",
						method: "POST",
						data: new FormData(this),
						contentType: false,
						cache: false,
						processData: false,
						dataType:"json",

						beforeSend: function() {
							$('#btnSave').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');
						},

						success: function(res) {
              setTimeout(function() {
                $('#categoryForm')[0].reset();
                $('#categoryModal').modal('hide');
                $('#categoryTable').DataTable().ajax.reload();
              });

              toastr.options =
              {
                "closeButton" : true,
                "progressBar" : false,
                "preventDuplicates": true,
                "timeOut": "3000",
                "positionClass": "toast-top-center"
              }
                toastr.success(res.messages);
						},

						error: function(reject) {
							setTimeout(function() {
								$('#btnSave').text('Update');
								var response = $.parseJSON(reject.responseText);
								$.each(response.errors, function (key, val) {
									$('#' + key + "_error").text(val[0]);
									$('#' + key).addClass('is-invalid');
								});
							});
						}
					});
				}
			});
    });
  </script>
@endpush