<div class="page-about-us cst-p50">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="about-us-content">
                    <div class="section-title">
                        <h3 class="wow fadeInUp">{!! $section?->content?->heading !!}</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque">{!! $section?->content?->sub_heading !!}</h2>
                        <p class="wow fadeInUp" data-wow-delay="0.2s">
                            {{ $weAre?->content?->short_description }}
                        </p>

                        @if (!empty($section?->content?->button_title))
                            <div class="achievements-content-btn mt-7 wow fadeInUp" data-wow-delay="0.4s">
                                <a href="{{ !empty($section?->page?->id)
                                    ? route('frontend.detail', [
                                        'lang' => session('locale'),
                                        'slug' => $section?->page?->content?->slug ?? '',
                                    ])
                                    : 'javascript:void(0);' }}" class="btn-default">
                                    {{ $section?->content?->button_title }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="about-us-img">
                    <figure class="image-anime reveal">
                        <img src="{{ $weAre?->getOtherImage() }}" alt="{{ $section?->content?->title }}">
                    </figure>
                </div>
            </div>
        </div>
    </div>
</div>
