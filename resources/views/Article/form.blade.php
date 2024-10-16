@extends('Layouts.cms')

@push('css')
  <!-- Toastr -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endpush

@section('content')
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          <a href="{{ route('article.index') }}" class="btn btn-secondary">
            Kembali
          </a>
        </div>
      </div>

      <form id="articleForm" method="post" enctype="multipart/form-data">
        @csrf
        
        <div class="row">
          <input type="hidden" name="article_id" id="article_id" value="{{ $article ? $article->id : '' }}">

          <div class="col-md-9">
            <div class="card">
              <div class="card-body">
                @if($article && $article->status == 2)
                  <div class="alert alert-danger alert-dismissible text-center">
                    <h4>{{ $article->notes }}</h4>
                  </div>
                @endif

                <div class="form-group mb-3">
                  <label>Judul Artikel</label>
                  <input type="text" name="title" id="title" class="form-control" value="{{ $article ? $article->title : '' }}" placeholder="Judul Artikel">
                  <div class="text-danger" id="title_error"></div>
                </div>

                <div class="form-group mb-3">
                  <label>Konten</label>
                  <textarea name="content" id="content" class="form-control">{{ $article ? $article->content : '' }}</textarea>
                  <div class="text-danger" id="content_error"></div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card">
              <div class="card-body">
                <div class="form-group mb-3">
                  <label>Kategori</label>
                  <select name="category_id" id="category_id" class="form-control">
                    <option disabled selected>-- Pilih Kategori --</option>
                    @foreach ($category['data'] as $cat)
                      <option value="{{ $cat->id }}" {{ $article ? ($article->category_id == $cat->id ? 'selected' : '') : '' }}>{{ $cat->category_name }}</option>
                    @endforeach
                  </select>
                  <div class="text-danger" id="category_id_error"></div>
                </div>

                <div class="form-group mb-3">
                  <label>Thumbnail</label>
                  <input type="file" name="thumbnail" id="thumbnail" class="form-control" accept=".jpg, .jpeg, .png, .webp" onchange="previewImage(event)">
                  <div class="text-danger" id="thumbnail_error"></div>
                </div>

                <center>
                @if($article)
                  <img id="preview" src="{{ asset('uploads/articles/thumbnails/' . $article->thumbnail) }}" class="w-50 img-thumbnail" alt="Thumbnail Artikel">
                @else
                  <img id="preview" class="w-75 img-thumbnail" style="display: none;" alt="">
                @endif
              </center>

                <div class="modal-footer mb-3">
                  @if (!$article || ($article && $article->status == 0))
                    <button type="button" class="btn btn-secondary" id="btnDraft">Draft</button>
                  @endif
                  <button type="button" class="btn btn-primary" id="btnSubmit">{{ $button }}</button>
                </div>
              </div>
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
  <script src="https://cdn.tiny.cloud/1/nhncdv3hepbszgt5rpk0s3q5ba3mjkxrwra45dswelogpot9/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
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

    $(document).ready(function () {
      tinymce.init({
				selector: '#content', // Replace this CSS selector to match the placeholder element for TinyMCE
				height: 500,
				plugins: 'code table lists image fullscreen',
				toolbar: 'undo redo | formatselect| bold italic underline | alignleft aligncenter alignright | table | image | indent outdent | bullist numlist | fullscreen | code',
				image_title: true,
				automatic_uploads: false,
				convert_urls: false,
				file_picker_types: 'image',
        contextmenu: 'link image imagetools removeImage',

        // Menampilkan button remove untuk hapus gambar
        setup: function (editor) {
          editor.ui.registry.addMenuItem('removeImage', {
            text: 'Remove',
            icon: 'remove',
            onAction: function () {
              let img = editor.selection.getNode();

              if (img && img.nodeName === 'IMG') {
                let src = img.getAttribute('src');
                editor.dom.remove(img);

                deleteImageFromServer(src);
              }
            }
          });

          // Cegah penghapusan gambar dengan tombol Delete di keyboard
          editor.on('keydown', function (event) {
            let img = editor.selection.getNode();
            if (img && img.nodeName === 'IMG' && (event.key === 'Delete' || event.key === 'Backspace')) {
              event.preventDefault(); // Mencegah penghapusan gambar
            }
          });
        },

        // Proses upload gambar dan menampilkan di TinyMce
				file_picker_callback: function (cb, value, meta) {
					var input	=	document.createElement('input');

					input.setAttribute('type', 'file');
					input.setAttribute('accept', 'image/*');

					input.onchange	=	function () {
						var file		  = this.files[0];
            var formData  = new FormData();
            formData.append('file', file);

						$.ajax({
              url: "{{ route('article.uploadImage') }}",
              method: "POST",
              data: formData,
              processData: false,
              contentType: false,
              success: function (response) {
                cb(response.location, { title: response.filename });
              },
              error: function (xhr) {
                console.log('Gambar gagal diupload', xhr.responseText);
              },
            })
					};
					input.click();
        }
			});

      function deleteImageFromServer(imagePath) {
        $.ajax({
          url: "{{ route('article.deleteImage') }}",
          method: "POST",
          data: {
            imagePath: imagePath
          },
          success: function (response) {
            console.log('Gambar berhasil dihapus dari server');
          },
          error: function (xhr) {
            console.log('Gambar gagal dihapus dari server', xhr.responseText);
          },
        });
      }

      let action = '';
      let buttonClicked;

      $('#btnDraft').click(function () {
        action = 'Draft';
        buttonClicked = $(this);
        $('#articleForm').submit();
      });

      $('#btnSubmit').click(function () {
        action = 'Submit';
        buttonClicked = $(this);
        $('#articleForm').submit();
      });

      // Submit data
			$('#articleForm').on('submit', function (e) {
				e.preventDefault();

        var content = tinyMCE.get('content').getContent();
        $('#content').val(content);

				$('#title_error').text();
        $('#content_error').text();
        $('#category_id_error').text();
        $('#thumbnail_error').text();

        var formDatas = new FormData(this);
        formDatas.append('action', action);

        var articleId = $('#article_id').val();
        var redirectUrl = articleId ? "{{ route('article.update') }}" : "{{ route('article.store') }}";

        $.ajax({
          url: redirectUrl,
          method: "POST",
          data: formDatas,
          contentType: false,
          cache: false,
          processData: false,
          dataType:"json",

          beforeSend: function() {
            buttonClicked.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Diproses...');
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
                window.location.href = "{{ route('article.index') }}";
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
              $('#btnDraft').text('Draft');
              $('#btnSubmit').text('Submit');
              var response = $.parseJSON(reject.responseText);
              $.each(response.errors, function (key, val) {
                $('#' + key + "_error").text(val[0]);
                $('#' + key).addClass('is-invalid');
              });
            });
          }
        });
			});
    });
  </script>
@endpush