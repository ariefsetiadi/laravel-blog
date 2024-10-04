@extends('Layouts.cms')

@push('css')
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endpush

@section('content')
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <form id="changePassword" method="post" enctype="multipart/form-data">
                @csrf

                <div class="form-group mb-3">
                  <label>Password Saat Ini</label>
                  <input type="password" name="oldPassword" id="oldPassword" class="form-control" placeholder="Masukkan Password Saat Ini">
                  <div class="text-danger" id="oldPassword_error"></div>
                </div>

                <div class="form-group mb-3">
                  <label>Password Baru</label>
                  <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan Password Baru">
                  <div class="text-danger" id="password_error"></div>
                </div>

                <div class="form-group mb-3">
                  <label>Ulangi Password Baru</label>
                  <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Masukkan Ulangi Password Baru">
                  <div class="text-danger" id="password_confirmation_error"></div>
                </div>

                <div class="form-check mb-3">
                  <input class="form-check-input" onclick="showPassword()" id="showPass" type="checkbox" />
                  <label class="form-check-label" for="showPass">Lihat Password</label>
                </div>

                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary" id="btnSave">Update Password</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
@endsection

@push('js')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

  <script type="text/javascript">
    function showPassword() {
			var oldPass	= document.getElementById("oldPassword");
			var pass	= document.getElementById("password");
			var passConf	= document.getElementById("password_confirmation");
			var btn		= document.getElementById("showPass");

			if (oldPass.type === "password" || pass.type === "password" || passConf.type === "password") {
				oldPass.type = "text";
				pass.type = "text";
				passConf.type = "text";
			} else {
				oldPass.type = "password";
				pass.type = "password";
				passConf.type = "password";
			}
		}

    // Reset Password
			$('#changePassword').on('submit', function (e) {
				e.preventDefault();

				if ($('#btnSave').text() == 'Update Password') {
					$('#oldPassword_error').text();
					$('#password_error').text();
					$('#password_confirmation_error').text();

					$.ajax({
						url: "{{ route('updatePassword') }}",
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
									window.location.href = "{{ route('home') }}";
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
                  $('#btnSave').text('Update Password');
                });
              }
						},

						error: function(reject) {
							setTimeout(function() {
								$('#btnSave').text('Update Password');
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
  </script>
@endpush