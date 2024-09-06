<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('cms/plugins/fontawesome-free/css/all.min.css') }}">

    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('cms/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('cms/dist/css/adminlte.min.css') }}">
  </head>

  <body class="hold-transition login-page">
    <div class="login-box">
      <!-- /.login-logo -->
      <div class="card card-outline card-primary">
        <div class="card-header text-center">
          <a href="#" class="h1">{{ config('app.name') }}</a>
        </div>
        <div class="card-body">
          @if(\Session::get('success'))
            <div class="alert alert-success alert-dismissible">
              <h6>{{ \Session::get('success') }}</h6>
            </div>
          @endif

          @if(\Session::get('error'))
            <div class="alert alert-danger alert-dismissible">
              <h6>{{ \Session::get('error') }}</h6>
            </div>
          @endif


          <form action="{{ route('postLogin') }}" method="post">
            @csrf

            <div class="mb-3">
              <div class="input-group">
                <input type="text" class="form-control" id="email" name="email" placeholder="Masukkan Email">
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                  </div>
                </div>
              </div>
              @error('email')
                <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <div class="input-group">
                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password">
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                  </div>
                </div>
              </div>
              @error('password')
                <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>

            <div class="row">
              <div class="col-8">
                <div class="icheck-primary">
                  <input type="checkbox" id="remember" onclick="showPassword()">
                  <label for="remember">
                    Lihat Password
                  </label>
                </div>
              </div>
              <!-- /.col -->
              <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
              </div>
              <!-- /.col -->
            </div>
          </form>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="{{ asset('cms/plugins/jquery/jquery.min.js') }}"></script>

    <!-- Bootstrap 4 -->
    <script src="{{ asset('cms/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- AdminLTE App -->
    <script src="{{ asset('cms/dist/js/adminlte.min.js') }}"></script>

    <script type="text/javascript">
			function showPassword() {
				var pass	= document.getElementById("password");
				var btn		= document.getElementById("showPass");

				if (pass.type === "password") {
					pass.type = "text";
				} else {
					pass.type = "password";
				}
			}
		</script>
  </body>
</html>
