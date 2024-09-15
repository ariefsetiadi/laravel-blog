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
              @can('Tambah User')
              <button type="button" class="btn btn-primary" id="btnAdd">
                Tambah
              </button>
              @endcan
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="table-responsive">
                <table id="userTable" class="table table-bordered table-hover" width="100%">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Nama Lengkap</th>
                      <th>Email</th>
                      <th>Role</th>
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
      <div class="modal fade" id="userModal" data-backdrop="static" data-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="modalTitle"></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form id="userForm" method="post" enctype="multipart/form-data">
              @csrf

              <div class="modal-body">
                <input type="hidden" name="user_id" id="user_id">

                <div class="form-group mb-3">
                  <label>Nama Lengkap</label>
                  <input type="text" name="name" id="name" class="form-control" placeholder="Nama Lengkap">
                  <div class="text-danger" id="name_error"></div>
                </div>

                <div class="form-group mb-3">
                  <label>Email</label>
                  <input type="text" name="email" id="email" class="form-control" placeholder="Email">
                  <div class="text-danger" id="email_error"></div>
                </div>

                <div class="form-group mb-3">
                  <label>Status</label>
                  <select name="role" id="role" class="form-control">
                    <option disabled selected>-- Pilih Role --</option>
                    @foreach($roles as $role)
                      <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                  </select>
                  <div class="text-danger" id="role_error"></div>
                </div>

                <div class="form-group mb-3">
                  <label>Status</label>
                  <select name="is_active" id="is_active" class="form-control">
                    <option disabled selected>-- Pilih Status --</option>
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                  </select>
                  <div class="text-danger" id="is_active_error"></div>
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

      <!-- Modal Reset Password -->
      <div class="modal fade" id="resetModal" data-backdrop="static" data-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="modalTitleReset"></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form id="resetForm" method="post" enctype="multipart/form-data">
              @csrf

              <div class="modal-body">
                <input type="hidden" name="userReset_id" id="userReset_id">

                <div class="form-group mb-3">
                  <label>Nama Lengkap</label>
                  <input type="text" name="nameReset" id="nameReset" class="form-control" disabled>
                </div>

                <div class="form-group mb-3">
                  <label>Password</label>
                  <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                  <div class="text-danger" id="password_error"></div>
                </div>

                <div class="form-group mb-3">
                  <label>Konfirmasi Password</label>
                  <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Konfirmasi Password">
                  <div class="text-danger" id="password_confirmation_error"></div>
                </div>

                <div class="form-check mb-3">
                  <input class="form-check-input" onclick="showPassword()" id="showPass" type="checkbox" />
                  <label class="form-check-label" for="showPass">Lihat Password</label>
                </div>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary" id="btnSavePassword"></button>
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
    function showPassword() {
			var pass	= document.getElementById("password");
			var passConf	= document.getElementById("password_confirmation");
			var btn		= document.getElementById("showPass");

			if (pass.type === "password" || passConf.type === "password") {
				pass.type = "text";
				passConf.type = "text";
			} else {
				pass.type = "password";
				passConf.type = "password";
			}
		}

    $(document).ready(function () {
      var table	=	$('#userTable').DataTable({
				processing: true,
				serverSide: true,
				ajax:{
					url: "{{ route('user.index') }}",
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
						data: 'email',
						name: 'email',
            className: 'text-center',
						width: '15%',
					},
          {
						data: 'roleName',
						name: 'roleName',
            className: 'text-center',
						width: '15%',
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
				$('#modalTitle').text("Tambah User");
				$('#btnSave').text("Simpan");
				$('#userForm').trigger("reset");
				$('#userModal').modal("show");

        // Delete class is-invalid
        $('#userForm input').removeClass("is-invalid");
        $('#userForm select').removeClass("is-invalid");

        // Remove Error Message
        $('#name_error').text("");
        $('#email_error').text("");
        $('#role_error').text("");
        $('#is_active_error').text("");
			});

      // Form modal Edit
			$(document).on('click', '.btnEdit', function () {
				var url = '{{ route("user.edit", ":id") }}';
				user_id = $(this).attr("id");

				$.ajax({
					url: url.replace(":id", user_id),
					dataType: "json",
					success: function (html) {
						$('#modalTitle').text("Edit User");
						$('#btnSave').text("Update");
						$('#userForm').trigger("reset");
						$('#userModal').modal("show");

            // Insert value
						$('#user_id').val(html.data.id);
						$('#name').val(html.data.name);
						$('#email').val(html.data.email);
						$('#role').val(html.data.role_id);
						$('#is_active').val(html.data.is_active);

            // Delete class is-invalid
            $('#userForm input').removeClass("is-invalid");
            $('#userForm select').removeClass("is-invalid");

            // Remove Error Message
            $('#name_error').text("");
            $('#email_error').text("");
            $('#role_error').text("");
            $('#is_active_error').text("");
					}
				});
			});

      // Form Modal Reset Password
			$(document).on('click', '.btnReset', function () {
				var url		=	'{{ route("user.edit", ":id") }}';
				user_id	=	$(this).attr("id");

				$.ajax({
					url: url.replace(":id", user_id),
					dataType: "json",
					success: function (html) {
						$('#modalTitleReset').text("Reset Password User");
						$('#btnSavePassword').text("Reset Password");
						$('#resetForm').trigger("reset");
						$('#resetModal').modal("show");

						$('#userReset_id').val(html.data.id);
						$('#nameReset').val(html.data.name);

            // Delete class is-invalid
            $('#resetForm input').removeClass("is-invalid");

            // Remove Error Message
            $('#password_error').text("");
            $('#password_confirmation_error').text("");
					}
				});
			});

      // Submit data
			$('#userForm').on('submit', function (e) {
				e.preventDefault();

				if ($('#btnSave').text() == 'Simpan') {
					$('#name_error').text();
					$('#email_error').text();
					$('#role_error').text();
					$('#is_active_error').text();

					$.ajax({
						url: "{{ route('user.store') }}",
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
								$('#userForm')[0].reset();
								$('#userModal').modal('hide');
								$('#userTable').DataTable().ajax.reload();
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
					$('#name_error').text();
					$('#email_error').text();
					$('#role_error').text();
					$('#is_active_error').text();

					$.ajax({
						url: "{{ route('user.update') }}",
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
              if (res.success == true) {
                setTimeout(function() {
                  $('#userForm')[0].reset();
                  $('#userModal').modal('hide');
                  $('#userTable').DataTable().ajax.reload();
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
              } else {
                toastr.options =
                {
                  "closeButton" : true,
                  "progressBar" : false,
                  "preventDuplicates": true,
                  "timeOut": "3000",
                  "positionClass": "toast-top-center"
                }
                toastr.error(res.messages);

                setTimeout(function() {
                  $('#btnSave').text('Update');
                });
              }
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

      // Reset Password
			$('#resetForm').on('submit', function (e) {
				e.preventDefault();

				if ($('#btnSavePassword').text() == 'Reset Password') {
					$('#password_error').text();
					$('#password_confirmation_error').text();

					$.ajax({
						url: "{{ route('user.resetPassword') }}",
						method: "POST",
						data: new FormData(this),
						contentType: false,
						cache: false,
						processData: false,
						dataType:"json",

						beforeSend: function() {
							$('#btnSavePassword').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');
						},

						success: function(res) {
              if (res.success == true) {
                setTimeout(function() {
                  $('#resetForm')[0].reset();
                  $('#resetModal').modal('hide');
                  $('#userTable').DataTable().ajax.reload();
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
              } else {
                toastr.options =
                {
                  "closeButton" : true,
                  "progressBar" : false,
                  "preventDuplicates": true,
                  "timeOut": "3000",
                  "positionClass": "toast-top-center"
                }
                toastr.error(res.messages);

                setTimeout(function() {
                  $('#btnSavePassword').text('Reset Password');
                });
              }
						},

						error: function(reject) {
							setTimeout(function() {
								$('#btnSavePassword').text('Reset Password');
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