@extends('Layouts.guest')

@push('css')
@endpush

@section('content')
  <!-- Page Title -->
  <div class="page-title position-relative">
    <div class="container d-lg-flex justify-content-between align-items-center">
      <h1 class="mb-2 mb-lg-0">Semua Artikel</h1>
    </div>
  </div>
  <!-- End Page Title -->

  <div class="container">
    <div class="row">
      <div class="col-lg-8">
        <!-- Blog Posts Section -->
        <section id="blog-posts" class="blog-posts section">
          <div class="container">
            <div class="row gy-4" id="articles-list">
              @foreach($articles as $row)
                <div class="col-lg-6">
                  <article class="position-relative h-100">
                    <div class="post-img position-relative overflow-hidden">
                      <img src="{{ asset('uploads/articles/thumbnails/' . $row->thumbnail) }}" alt="{{ $row->title }}" class="img-fluid">
                      <span class="post-date">{{ \Carbon\Carbon::parse($row->created_at)->translatedFormat('d F Y') }}</span>
                    </div>

                    <div class="post-content d-flex flex-column">
                      <a href="{{ route('guest.article.read', ['year' => $row->created_at->format('Y'), 'month' => $row->created_at->format('m'), 'slug' => $row->slug]) }}">
                        <h3 class="post-title">{{ $row->title }}</h3>
                      </a>

                      <div class="meta d-flex align-items-center">
                        <div class="d-flex align-items-center">
                          <i class="bi bi-person"></i> <span class="ps-2">{{ $row->userName }}</span>
                        </div>
                        <span class="px-3 text-black-50">/</span>
                        <div class="d-flex align-items-center">
                          <i class="bi bi-folder2"></i> <span class="ps-2">{{ $row->category_name }}</span>
                        </div>
                      </div>

                      @php
                        $firstPoint = strpos($row->content, '.');
                        $firstSentence = $firstPoint != false ? substr($row->content, 0, $firstPoint + 1) : $row->content;
                      @endphp
                      {!! $firstSentence !!}
                    </div>
                  </article>
                </div>
                <!-- End post list item -->
              @endforeach
            </div>
          </div>
        </section>
        <!-- /Blog Posts Section -->

        <!-- Blog Pagination Section -->
        <section id="blog-pagination" class="blog-pagination section">
          <div class="container">
            <div class="d-flex justify-content-center">
              {!! $articles->links('vendor.pagination.guestCustom') !!}
            </div>
          </div>
        </section>
        <!-- /Blog Pagination Section -->
      </div>

      <div class="col-lg-4 sidebar">
        <div class="widgets-container">
          <!-- Search Widget -->
          <div class="search-widget widget-item">
            <h3 class="widget-title">Search</h3>
            <form action="">
              <input type="text">
              <button type="submit" title="Search"><i class="bi bi-search"></i></button>
            </form>
          </div>
          <!--/Search Widget -->

          <!-- Recent Posts Widget -->
          <div class="recent-posts-widget widget-item">
            <h3 class="widget-title">Terpopuler</h3>
            @foreach($populars as $row)
              <div class="post-item">
                <img src="{{ asset('uploads/articles/thumbnails/' . $row->thumbnail) }}" alt="{{ $row->title }}" class="flex-shrink-0">
                <div>
                  <a href="{{ route('guest.article.read', ['year' => $row->created_at->format('Y'), 'month' => $row->created_at->format('m'), 'slug' => $row->slug]) }}">
                    <h4>{{ $row->title }}</h4>
                  </a>
                  <time datetime="{{ $row->created_at }}">{{ \Carbon\Carbon::parse($row->created_at)->translatedFormat('d M Y') }}</time>
                </div>
              </div>
              <!-- End recent post item-->
            @endforeach
          </div>
          <!--/Recent Posts Widget -->
        </div>
      </div>
    </div>
  </div>
@endsection

@push('js')
@endpush