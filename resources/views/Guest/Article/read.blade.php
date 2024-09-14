@extends('Layouts.guest')

@push('css')
@endpush

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-lg-8">
        <!-- Blog Details Section -->
        <section id="blog-details" class="blog-details section">
          <div class="container">
            <article class="article">
              <div class="post-img">
                <img src="{{ asset('uploads/articles/thumbnails/' . $article->thumbnail) }}" alt="{{ $article->title }}" class="img-fluid" width="100%">
              </div>

              <h2 class="title">{{ $article->title }}</h2>
              <div class="meta-top">
                <ul>
                  <li class="d-flex align-items-center"><i class="bi bi-clock"></i><time datetime="article->created_at">{{ \Carbon\Carbon::parse($article->created_at)->translatedFormat('d F Y') }}</time></li>
                  <li class="d-flex align-items-center"><i class="bi bi-eye"></i>{{ $article->totalView > 0 ? $article->totalView : '0' }} kali dibaca</li>
                </ul>
              </div>
              <!-- End meta top -->

              <div class="content">
                {!! $article->content !!}
              </div>
              <!-- End post content -->

              <div class="meta-bottom">
                <i class="bi bi-person"></i>
                <ul class="cats">
                  <li>
                    <a href="#">{{ $article->userName }}</a>
                  </li>
                </ul>
                <i class="bi bi-folder"></i>
                <ul class="cats">
                  <li>
                    <a href="#">{{ $article->category_name }}</a>
                  </li>
                </ul>
              </div>
              <!-- End meta bottom -->
            </article>
          </div>
        </section>
        <!-- /Blog Details Section -->
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
            <h3 class="widget-title">Artikel Terkait</h3>
            @foreach($relateds as $row)
            <div class="post-item">
              <img src="{{ asset('uploads/articles/thumbnails/' . $row->thumbnail) }}" alt="{{ $row->title }}" class="flex-shrink-0">
              <div>
                <h4>
                  <a href="{{ route('guest.article.read', ['year' => $row->created_at->format('Y'), 'month' => $row->created_at->format('m'), 'slug' => $row->slug]) }}">{{ $row->title }}</a>
                </h4>
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