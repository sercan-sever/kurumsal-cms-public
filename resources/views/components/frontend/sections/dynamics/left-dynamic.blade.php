<div class="our-vision-mission bg-section cst-p50">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="vision-mission-box">
                    <div class="vision-mission-item">
                        <div class="vision-mission-image">
                            <figure class="image-anime reveal">
                                <img src="{{ $section?->getOtherImage() }}" alt="{{ $section?->title }}">
                            </figure>
                        </div>

                        <div class="vision-mission-content">
                            <div class="section-title">
                                <h3 class="wow fadeInUp">{!! $section?->content?->heading !!}</h3>
                                <h2 class="text-anime-style-2" data-cursor="-opaque">{!! $section?->content?->sub_heading !!}</h2>

                                <div class="wow fadeInUp" data-wow-delay="0.2s">
                                    {!! $section?->content?->description !!}
                                </div>
                            </div>

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
            </div>
        </div>
    </div>
</div>
