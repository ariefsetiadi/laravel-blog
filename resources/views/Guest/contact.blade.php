@extends('Layouts.guest')

@push('css')
@endpush

@section('content')
  @php
    $sosmed = $sharedData ? ($sharedData->social_media ? json_decode($sharedData->social_media, true) : []) : [];
    $phones = $sharedData ? ($sharedData->phone ? json_decode($sharedData->phone, true) : []) : [];
  @endphp

  <!-- Contact Section -->
  <section id="contact" class="contact section">
    <div class="container" data-aos="fade-up" data-aos-delay="100">
      <div class="text-center" data-aos="fade-up" data-aos-delay="200">
        @if ($sharedData && $sharedData->icon)
          <img src="{{ asset('uploads/website/' . $sharedData->icon) }}" class="img-fluid" alt="Responsive image">
        @endif
      </div>
      <!-- End Google Maps -->

      <div class="row gy-4">
        <div class="col-lg-4">
          <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
            <i class="bi bi-geo-alt flex-shrink-0"></i>
            <div>
              <h3>Address</h3>
              <p>{{ $sharedData ? ($sharedData->address ?? '-') : '-' }}</p>
            </div>
          </div>
          <!-- End Info Item -->

          <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
            <i class="bi bi-telephone flex-shrink-0"></i>
            <div>
              <h3>Call Us</h3>
              @if ($sharedData && isset($sharedData->phone))
                @foreach ($phones as $phone)
                  <p>{{ $phone }}</p>
                @endforeach
              @else
                <p>-</p>
              @endif
            </div>
          </div>
          <!-- End Info Item -->

          <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="500">
            <i class="bi bi-envelope flex-shrink-0"></i>
            <div>
              <h3>Email Us</h3>
              @if ($sharedData)
                <p>
                  <a href="mailto:{{ $sharedData->email }}"><span>{{ $sharedData->email }}</span></a>
                </p>
              @else
                <p>-</p>
              @endif
            </div>
          </div>
          <!-- End Info Item -->
        </div>

        <div class="col-lg-8">
          <form action="#" method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="200">
            <div class="row gy-4">

              <div class="col-md-6">
                <input type="text" name="name" class="form-control" placeholder="Your Name" required="">
              </div>

              <div class="col-md-6 ">
                <input type="email" class="form-control" name="email" placeholder="Your Email" required="">
              </div>

              <div class="col-md-12">
                <input type="text" class="form-control" name="subject" placeholder="Subject" required="">
              </div>

              <div class="col-md-12">
                <textarea class="form-control" name="message" rows="6" placeholder="Message" required=""></textarea>
              </div>

              <div class="col-md-12 text-center">
                <div class="loading">Loading</div>
                <div class="error-message"></div>
                <div class="sent-message">Your message has been sent. Thank you!</div>

                <button type="submit">Send Message</button>
              </div>

            </div>
          </form>
        </div>
        <!-- End Contact Form -->
      </div>
    </div>
  </section>
  <!-- /Contact Section -->
@endsection

@push('js')
@endpush