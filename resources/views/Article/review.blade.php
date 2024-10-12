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

      <form id="reviewForm" method="post" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="article_id" id="article_id" value="{{ $article->id }}">

        <div class="row">
          <div class="col-md-8">
            <div class="card">
              <div class="card-body">
                <h3 class="text-center mb-3">{{ $article->title }}</h3>
                <center>
                  <img src="{{ asset('uploads/articles/thumbnails/' . $article->thumbnail) }}" alt="" class="img-thumbnail">
                </center>

                {!! $article->content !!}
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card">
              <div class="card-body">
                <div class="form-group mb-3">
                  <label>Penulis</label>
                  <input type="text" class="form-control" value="{{ $article->name }}" readonly>
                </div>

                <div class="form-group mb-3">
                  <label>Kategori</label>
                  <input type="text" class="form-control" value="{{ $article->category_name }}" readonly>
                </div>

                <div class="form-group mb-3">
                  <label>Catatan (Jika Revisi)</label>
                  <textarea class="form-control" id="notes" name="notes" rows="3">{{ $article->notes }}</textarea>
                  <div class="text-danger" id="notes_error"></div>
                </div>

                <div class="modal-footer mb-3">
                  <button type="button" class="btn btn-warning" id="btnRevise">{{ $btnRevise }}</button>
                  <button type="button" class="btn btn-success" id="btnPublish">{{ $btnPublish }}</button>
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

  <script type="text/javascript">
    $(document).ready(function () {
      let action = '';
      let buttonClicked;

      $('#btnRevise').click(function () {
        action = 'Revise';
        buttonClicked = $(this);
        $('#reviewForm').submit();
      });

      $('#btnPublish').click(function () {
        action = 'Publish';
        buttonClicked = $(this);
        $('#reviewForm').submit();
      });

      // Submit Data
      $('#reviewForm').on('submit', function (e) {
        e.preventDefault();

        $('#notes_error').text();

        var formData = new FormData(this);
        formData.append('action', action);

        $.ajax({
          url: "{{ route('article.updateReview') }}",
          method: "POST",
          data: formData,
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
              $('#btnRevise').text('Revise');
              $('#btnPublish').text('Publish');
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