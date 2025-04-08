<div class="hero hero-slider-layout cst-100vh">
    <div class="swiper">
        <div class="swiper-wrapper">
            @foreach ($banners as $banner)
                <div class="swiper-slide">
                    <div class="hero-slide cst-100vh slide-{{ $loop->iteration }}" style="background: url('{{ $banner?->getImage() }}')">
                        <div class="container">
                            <div class="row align-items-center">
                                <div class="col-lg-7">
                                    <div class="hero-content">
                                        <div class="section-title">
                                            <h1 class="text-anime-style-2" data-cursor="-opaque">{!! $banner?->content?->title !!}</h1>
                                            <p class="wow fadeInUp" data-wow-delay="0.2s">
                                                {{ $banner?->content?->description }}
                                            </p>
                                        </div>

                                        @if (!empty($banner?->content?->button_title))
                                            <div class="hero-btn wow fadeInUp" data-wow-delay="0.4s">
                                                <a href="{{ !empty($banner?->content?->url) ? $banner?->content?->url : 'javascript:void(0);' }}" class="btn-default">
                                                    {{ $banner?->content?->button_title }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
