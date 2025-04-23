            <!-- breadcrumb -->
            <div class="breadcrumb-header justify-content-between">
                <div class="left-content">
                    <div>
                      <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">Hi, welcome back! {{ Auth::user()->name }}</h2>
                    </div>
                </div>
            </div>
            <!-- /breadcrumb -->

            <!-- carousel -->
            <div id="owl-demo" class="owl-carousel owl-theme">
              @foreach ( $banner_slide as $bs )
              <div class="item d-flex justify-content-center">
                <img src="{{ $bs['image'] }}" alt="{{ $bs['name'].' image' }}" style="object-fit:cover">
              </div>
              @endforeach
            </div>
            <div class="customNavigation d-flex justify-content-center mt-2" style="gap:10px;">
                <a class="btn btn-primary text-white btn-sm prev">Previous</a>
                <a class="btn btn-primary text-white btn-sm next">Next</a>
                <a class="btn btn-primary text-white btn-sm play">Autoplay</a>
                <a class="btn btn-primary text-white btn-sm stop">Stop</a>
              </div>
            <!-- /carousel -->