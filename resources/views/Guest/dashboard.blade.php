@extends('Layouts.guest')

@push('css')
@endpush

@section('content')
  <!-- Slider Section -->
  <section id="slider" class="slider section dark-background">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="swiper init-swiper">
        <script type="application/json" class="swiper-config">
          {
            "loop": true,
            "speed": 600,
            "autoplay": {
              "delay": 5000
            },
            "slidesPerView": "auto",
            "centeredSlides": true,
            "pagination": {
              "el": ".swiper-pagination",
              "type": "bullets",
              "clickable": true
            },
            "navigation": {
              "nextEl": ".swiper-button-next",
              "prevEl": ".swiper-button-prev"
            }
          }
        </script>

        <div class="swiper-wrapper">
          @foreach($banner as $row)
          <div class="swiper-slide" style="background-image: url({{ asset('uploads/articles/thumbnails/' . $row->thumbnail) }});">
            <div class="content">
              <a href="{{ route('guest.article.read', ['year' => $row->created_at->format('Y'), 'month' => $row->created_at->format('m'), 'slug' => $row->slug]) }}">
                <h2>{{ $row->title }}</h2>
              </a>
              <p>
                @php
                  $firstPoint = strpos($row->content, '.');
                  $firstSentence = $firstPoint != false ? substr($row->content, 0, $firstPoint + 1) : $row->content;
                @endphp
                {!! $firstSentence !!}
              </p>
            </div>
          </div>
          @endforeach
        </div>

        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>

        <div class="swiper-pagination"></div>
      </div>
    </div>
  </section>
  <!-- /Slider Section -->

  <!-- Trending Category Section -->
  <section id="trending-category" class="trending-category section">
    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
      <div class="section-title-container d-flex align-items-center justify-content-between">
        <h2>Artikel Terbaru</h2>
        <p><a href="{{ route('guest.article.index') }}">Lihat Semua Artikel</a></p>
      </div>
    </div>
    <!-- End Section Title -->

    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="container" data-aos="fade-up">
        <div class="row g-5">
          <div class="col-lg-4">
            <div class="post-entry lg">
              <img src="{{ asset('uploads/articles/thumbnails/' . $first[3]->thumbnail) }}" alt="" class="img-fluid">
              <div class="post-meta">
                <span class="date">{{ $first[3]->category_name }}</span> <span class="mx-1">•</span> <span>{{ \Carbon\Carbon::parse($first[3]->created_at)->translatedFormat('d M Y') }}</span>
              </div>
              <h2>
                <a href="{{ route('guest.article.read', ['year' => $first[3]->created_at->format('Y'), 'month' => $first[3]->created_at->format('m'), 'slug' => $first[3]->slug]) }}">{{ $first[3]->title }}</a>
              </h2>
              <p class="mb-4 d-block">
                @php
                  $firstPoint = strpos($first[3]->content, '.');
                  $firstSentence = $firstPoint != false ? substr($first[3]->content, 0, $firstPoint + 1) : $first[3]->content;
                @endphp
                {!! $firstSentence !!}
              </p>

              <div class="d-flex align-items-center author">
                <div class="photo">
                  <img src="assets/img/person-1.jpg" alt="" class="img-fluid">
                </div>
                <div class="name">
                  <h3 class="m-0 p-0">{{ $first[3]->userName }}</h3>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-8">
            <div class="row g-5">
              <div class="col-lg-4 border-start custom-border">
                @foreach($second as $row)
                  <div class="post-entry">
                    <img src="{{ asset('uploads/articles/thumbnails/' . $row->thumbnail) }}" alt="" class="img-fluid">
                    <div class="post-meta">
                      <span class="date">{{ $row->category_name }}</span> <span class="mx-1">•</span> <span>{{ \Carbon\Carbon::parse($row->created_at)->translatedFormat('d M Y') }}</span>
                    </div>
                    <h2>
                      <a href="{{ route('guest.article.read', ['year' => $row->created_at->format('Y'), 'month' => $row->created_at->format('m'), 'slug' => $row->slug]) }}">{{ $row->title }}</a>
                    </h2>
                  </div>
                @endforeach
              </div>

              <div class="col-lg-4 border-start custom-border">
                @foreach($third as $row)
                  <div class="post-entry">
                    <img src="{{ asset('uploads/articles/thumbnails/' . $row->thumbnail) }}" alt="" class="img-fluid">
                    <div class="post-meta">
                      <span class="date">{{ $row->category_name }}</span> <span class="mx-1">•</span> <span>{{ \Carbon\Carbon::parse($row->created_at)->translatedFormat('d M Y') }}</span>
                    </div>
                    <h2>
                      <a href="{{ route('guest.article.read', ['year' => $row->created_at->format('Y'), 'month' => $row->created_at->format('m'), 'slug' => $row->slug]) }}">{{ $row->title }}</a>
                    </h2>
                  </div>
                @endforeach
              </div>

              <!-- Trending Section -->
              <div class="col-lg-4">
                <div class="trending">
                  <h3>Terpopuler</h3>
                  <ul class="trending-post">
                    @php
                      $no = 1;
                    @endphp

                    @foreach($trendings as $row)
                    <li>
                      <a href="{{ route('guest.article.read', ['year' => $row->created_at->format('Y'), 'month' => $row->created_at->format('m'), 'slug' => $row->slug]) }}">
                        <span class="number">{{ $no++ }}</span>
                        <h3>{{ $row->title }}</h3>
                        <span class="author">{{ $row->name }}</span>
                      </a>
                    </li>
                    @endforeach
                  </ul>
                </div>
              </div>
              <!-- End Trending Section -->
            </div>
          </div>
        </div>
        <!-- End .row -->
      </div>
    </div>
  </section>
  <!-- /Trending Category Section -->
@endsection

@push('js')
@endpush