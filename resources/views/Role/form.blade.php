@extends('Layouts.cms')

@push('css')
  <!-- Toastr -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('cms/plugins/select2/css/select2.min.css') }}">
@endpush

@section('content')
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          <a href="{{ route('role.index') }}" class="btn btn-secondary">
            Kembali
          </a>
        </div>
      </div>

      <form id="roleForm" method="post" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="role_id" id="role_id" value="{{ $role ? $role->id : '' }}">
        <div class="card">
          <div class="card-body">
            <div class="form-group mb-3">
              <label>Nama Role</label>
              <input type="text" name="name" id="name" class="form-control" value="{{ $role ? $role->name : '' }}" placeholder="Nama Role">
              <div class="text-danger" id="name_error"></div>
            </div>

            <div class="form-group select2-purple mb-3">
              <label>Permission</label>
              <select class="select2" multiple="multiple" id="permission" name="permission[]" data-dropdown-css-class="select2-purple" data-placeholder="Pilih Permission" style="width: 100%;">
                @foreach($permissions['data'] as $row)
                  <option value="{{ $row->id }}" {{ $role ? (in_array($row->id, $rolePermissions) ? 'selected' : '') : '' }}>{{ $row->name }}</option>
                @endforeach
              </select>
              <div class="text-danger" id="permission_error"></div>
            </div>

            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" id="btnSave">{{ $button  }}</button>
            </div>
          </div>
        </div>
      </form>
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
@endsection

@push('js')
  <!-- Plugins -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <script src="{{ asset('cms/plugins/select2/js/select2.full.min.js') }}"></script>

  <script type="text/javascript">
    $(function () {
      $('.select2').select2();
    });

    $(document).ready(function () {
      // Submit data
			$('#roleForm').on('submit', function (e) {
				e.preventDefault();

				if ($('#btnSave').text() == 'Simpan') {
					$('#name_error').text();
					$('#permission_error').text();

					$.ajax({
						url: "{{ route('role.store') }}",
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
								toastr.options =
								{
									"closeButton" : true,
									"progressBar" : false,
									"preventDuplicates": true,
									"timeOut": "1500",
									"positionClass": "toast-top-center"
								}
								toastr.options.onHidden = function () {
									window.location.href = "{{ route('role.index') }}";
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
							}
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
					$('#permission_error').text();

					$.ajax({
						url: "{{ route('role.update') }}",
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
								toastr.options =
								{
									"closeButton" : true,
									"progressBar" : false,
									"preventDuplicates": true,
									"timeOut": "1500",
									"positionClass": "toast-top-center"
								}
								toastr.options.onHidden = function () {
									window.location.href = "{{ route('role.index') }}";
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
    });
  </script>
@endpush