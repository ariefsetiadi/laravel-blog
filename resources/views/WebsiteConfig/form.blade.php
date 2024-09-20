@extends('Layouts.cms')

@push('css')
  <!-- Toastr -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endpush

@section('content')
  @php
    $sosmed = $config ? ($config->social_media ? json_decode($config->social_media, true) : []) : [];
    $phones = $config ? ($config->phone ? json_decode($config->phone, true) : []) : [];
  @endphp

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <form id="websiteForm" method="post" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
              <div class="col-md-4">
                <div class="card">
                  <div class="card-body">
                    <div class="form-group mb-3">
                      <label>Nama Website</label>
                      <input type="text" name="name" id="name" class="form-control" value="{{ $config ? $config->name : '' }}" placeholder="Nama Website">
                      <div class="text-danger" id="name_error"></div>
                    </div>

                    <div class="form-group mb-3">
                      <label>Email</label>
                      <input type="text" name="email" id="email" class="form-control" value="{{ $config ? $config->email : '' }}" placeholder="Email">
                      <div class="text-danger" id="email_error"></div>
                    </div>

                    <div class="form-group mb-3">
                      <label>Deskripsi Website</label>
                      <textarea name="description" id="description" class="form-control" rows="5">{{ $config ? $config->description : '' }}</textarea>
                      <div class="text-danger" id="description_error"></div>
                    </div>

                    <div class="form-group mb-3">
                      <label>Icon / Logo</label>
                      <input type="file" name="icon" id="icon" class="form-control" accept=".jpg, .jpeg, .png, .webp, .ico, .svg" onchange="previewImage(event)">
                      <div class="text-danger" id="icon_error"></div>
                    </div>

                    <center>
                      @if($config)
                        <img id="preview" src="{{ asset('uploads/website/' . $config->icon) }}" class="w-50 img-thumbnail" alt="Icon Website">
                      @else
                        <img id="preview" class="w-75 img-thumbnail" style="display: none;" alt="">
                      @endif
                    </center>
                  </div>
                </div>
              </div>

              <div class="col-md-8">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label>Facebook</label>
                          <input type="text" name="facebook" id="facebook" class="form-control" value="{{ $sosmed['facebook'] ?? '' }}" placeholder="https://facebook.com/username">
                          <div class="text-danger" id="facebook_error"></div>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label>Instagram</label>
                          <input type="text" name="instagram" id="instagram" class="form-control" value="{{ $sosmed['instagram'] ?? '' }}" placeholder="https://www.instagram.com/username">
                          <div class="text-danger" id="instagram_error"></div>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label>LinkedIn</label>
                          <input type="text" name="linkedin" id="linkedin" class="form-control" value="{{ $sosmed['linkedin'] ?? '' }}" placeholder="https://linkedin.com/in/username">
                          <div class="text-danger" id="linkedin_error"></div>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label>Twitter / X</label>
                          <input type="text" name="twitter" id="twitter" class="form-control" value="{{ $sosmed['twitter'] ?? '' }}" placeholder="https://x.com/username">
                          <div class="text-danger" id="twitter_error"></div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group mb-3">
                      <label>Telepon</label>
                      <div id="formDynamic">
                        @if ($config && isset($config->phone))
                          @foreach ($phones as $index => $phone)
                            <div class="phone-group mb-3">
                              <div class="input-group">
                                <input type="text" name="phone[]" id="phone[]" class="form-control phone-input" value="{{ $phone }}" placeholder="Telepon">
                                <span class="input-group-append">
                                  @if(count($phones) > 1)
                                    @if ($loop->last)
                                      <button type="button" class="btn btn-danger" onclick="removeField(event)">
                                        <i class="fa fa-trash"></i>
                                      </button>
                                    @else
                                      <button type="button" class="btn btn-success" onclick="addField()">
                                        <i class="fa fa-plus"></i>
                                      </button>
                                    @endif
                                  @else
                                    <button type="button" class="btn btn-success" onclick="addField()">
                                      <i class="fa fa-plus"></i>
                                      </button
                                  @endif
                                </span>
                              </div>
                              <div class="text-danger" id="phone_error"></div>
                            </div>
                          @endforeach
                        @else
                          <div class="phone-group mb-3">
                            <div class="input-group">
                              <input type="text" name="phone[]" id="phone[]" class="form-control phone-input" placeholder="Telepon">
                              <span class="input-group-append">
                                <button type="button" class="btn btn-success" onclick="addField()">
                                  <i class="fa fa-plus"></i>
                                </button>
                              </span>
                            </div>
                            <div class="text-danger" id="phone_error"></div>
                          </div>
                        @endif
                      </div>
                    </div>

                    <div class="form-group mb-3">
                      <label>Alamat</label>
                      <textarea name="address" id="address" class="form-control" rows="5">{{ $config ? $config->address : '' }}</textarea>
                      <div class="text-danger" id="address_error"></div>
                    </div>

                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary" id="btnSave">Update</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

  <script type="text/javascript">
    function previewImage(event) {
			var input = event.target;
			var image = document.getElementById('preview');
			if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function(e) {
					image.src = e.target.result;
					image.style.display = 'block';
				}
				reader.readAsDataURL(input.files[0]);
			}
		}

    function addField() {
      const formFields  = document.getElementById('formDynamic');
      const newField    = document.createElement('div');

      newField.className  = 'phone-group mb-3';
      newField.innerHTML  = `
        <div class="input-group">
          <input type="text" name="phone[]" id="phone" class="form-control phone-input">
          <span class="input-group-append">
            <button type="button" class="btn btn-danger" onclick="removeField(event)">
              <i class="fa fa-trash"></i>
            </button>
          </span>
        </div>
        <div class="text-danger" id="phone_error"></div>
      `;

      formFields.appendChild(newField);
    }

    function removeField(event) {
      const button      = event.target;
      const btn = button.tagName === 'I' ? button.parentElement : button;
      const inputGroup  = btn.parentElement.parentElement;
      inputGroup.remove();
    }

    $('#websiteForm').on('submit', function (e) {
				e.preventDefault();

				if ($('#btnSave').text() == 'Update') {
					$('#name_error').text();
					$('#email_error').text();
					$('#description_error').text();
					$('#icon_error').text();
					$('#facebook_error').text();
					$('#instagram_error').text();
					$('#linkedin_error').text();
					$('#twitter_error').text();
					$('#phone_error').text();

					$.ajax({
						url: "{{ route('webCon.save') }}",
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
									window.location.href = "{{ route('webCon.index') }}";
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
                  if (key.startsWith('phone')) {
                    var index = key.split('.')[1];
                    $('.phone-group').eq(index).find('#phone_error').text(val[0]);
                    $('.phone-group').eq(index).find('.phone-input').addClass('is-invalid');
                  } else {
                    $('#' + key + "_error").text(val[0]);
                    $('#' + key).addClass('is-invalid');
                  }
								});
							});
						}
					});
				}
			});
  </script>
@endpush