<div class="trusted-clients bg-section cst-p50">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="trusted-client-box">
                    <div class="trusted-client-title">
                        <h3>{!! $section?->content?->heading !!}</h3>
                    </div>

                    <div class="trusted-clients-slider">
                        <div class="swiper">
                            <div class="swiper-wrapper">
                                @foreach ($brands as $brand)
                                    <div class="swiper-slide">
                                        <div class="trusted-client-logo">
                                            <img src="{{ $brand?->getImage() }}" alt="{{ $brand?->content?->title }}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
