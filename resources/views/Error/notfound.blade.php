@extends('Layouts.cms')

@push('css')
@endpush

@section('content')
  <!-- Main content -->
  <section class="content">
    <div class="error-page">
      <h2 class="headline text-danger"> 404</h2>

      <div class="error-content">
        <h3><i class="fas fa-exclamation-triangle text-danger"></i> Oops! Data tidak ditemukan.</h3>

        <p>
          Kami tidak dapat menemukan data yang anda cari, silakan kembali ke <a href="{{ route('home') }}">Beranda</a>.
        </p>
      </div>
      <!-- /.error-content -->
    </div>
    <!-- /.error-page -->
  </section>
  <!-- /.content -->
@endsection

@push('js')
@endpush