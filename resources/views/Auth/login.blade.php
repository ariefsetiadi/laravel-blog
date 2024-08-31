<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ config('app.name') }}</title>

    @vite(['resources/css/app.css','resources/js/app.js'])
  </head>

  <body>
    <section class="bg-gray-200 dark:bg-gray-900">
      <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
        <a href="{{ route('login') }}" class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
          <img class="w-8 h-8 mr-2" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/logo.svg" alt="logo">
          Flowbite
        </a>

        <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
          <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
            <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
              Masuk ke Akun Anda
            </h1>

            @if(\Session::get('success'))
              <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                <span class="font-medium">{{ \Session::get('success') }}
              </div>
            @endif

            @if(\Session::get('error'))
              <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                <span class="font-medium">{{ \Session::get('error') }}
              </div>
            @endif

            <form method="post" action="{{ route('postLogin') }}" class="space-y-4 md:space-y-6">
              @csrf

              <div>
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat Email</label>
                <input
                  type="text"
                  name="email"
                  id="email"
                  placeholder="Masukkan Alamat Email Anda"
                  class="
                    bg-gray-50 border text-gray-900 rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:placeholder-gray-400 dark:text-white
                    @error('email')
                      border-red-300 dark:border-red-600 dark:focus:border-red-500 dark:focus:ring-red-500 focus:border-danger-600 focus:ring-danger-600
                    @else
                      border-gray-300 dark:border-gray-600 dark:focus:border-blue-500 dark:focus:ring-blue-500 focus:border-primary-600 focus:ring-primary-600
                    @enderror
                  "
                />
                @error('email')
                  <span class="mt-2 text-sm font-medium text-red-600 dark:text-red-400">{{ $message }}</span>
                @enderror
              </div>

              <div>
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                <input
                  type="password"
                  name="password"
                  id="password"
                  placeholder="Masukkan Password Anda"
                  class="
                    bg-gray-50 border text-gray-900 rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:placeholder-gray-400 dark:text-white
                    @error('password')
                      border-red-300 dark:border-red-600 dark:focus:border-red-500 dark:focus:ring-red-500 focus:border-danger-600 focus:ring-danger-600
                    @else
                      border-gray-300 dark:border-gray-600 dark:focus:border-blue-500 dark:focus:ring-blue-500 focus:border-primary-600 focus:ring-primary-600
                    @enderror
                  "
                />
                @error('password')
                  <span class="mt-2 text-sm font-medium text-red-600 dark:text-red-400">{{ $message }}</span>
                @enderror
              </div>

              <div class="flex items-center justify-between">
                <div class="flex items-start">
                  <div class="flex items-center h-5">
                    <input id="showPass" aria-describedby="showPass" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-primary-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-600 dark:ring-offset-gray-800" onclick="showPassword()">
                  </div>

                  <div class="ml-3 text-sm">
                    <label for="showPass" class="text-gray-500 dark:text-gray-300">Lihat Password</label>
                  </div>
                </div>
              </div>

              <button type="submit" class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                LOGIN
              </button>
            </form>
          </div>
        </div>
      </div>
    </section>

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