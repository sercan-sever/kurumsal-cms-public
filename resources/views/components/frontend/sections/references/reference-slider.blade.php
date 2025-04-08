<div class="our-work bg-section cst-p50">
    <div class="container">
        <div class="row section-row align-items-center">
            <div class="col-lg-6">
                <div class="section-title">
                    <h3>{!! $section?->content?->heading !!}</h3>
                    <h2 class="text-anime-style-2" data-cursor="-opaque">
                        {!! $section?->content?->sub_heading !!}
                    </h2>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="our-work-slider">
                <div class="swiper">
                    <div class="swiper-wrapper" data-cursor-text="{{ __('custom.page.drag' ?? '') }}">
                        @foreach ($references as $reference)
                            <div class="swiper-slide col-lg-4 col-md-6">
                                <div class="work-item wow fadeInUp">
                                    <div class="work-image">
                                        <a href="{{ !empty($section?->page?->id)
                                            ? route('frontend.sub.detail', [
                                                'lang' => session('locale'),
                                                'subSlug' => $section?->page?->content?->slug ?? '',
                                                'slug' => $reference?->content?->slug ?? '',
                                            ])
                                            : 'javascript:void(0);' }}">
                                            <figure>
                                                <img src="{{ $reference?->getOtherImage() }}" alt="{{ $reference?->content?->title }}">
                                            </figure>
                                        </a>
                                    </div>
                                    <div class="work-body">
                                        <div class="work-content">
                                            <h3>
                                                <a href="{{ !empty($section?->page?->id)
                                                    ? route('frontend.sub.detail', [
                                                        'lang' => session('locale'),
                                                        'subSlug' => $section?->page?->content?->slug ?? '',
                                                        'slug' => $reference?->content?->slug ?? '',
                                                    ])
                                                    : 'javascript:void(0);' }}">{{ $reference?->content?->title }}</a>
                                            </h3>

                                            <p>
                                                {!! getStringLimit($reference?->content?->short_description, 55) !!}
                                            </p>
                                        </div>
                                        <div class="work-btn">
                                            <a href="{{ !empty($section?->page?->id)
                                                ? route('frontend.sub.detail', [
                                                    'lang' => session('locale'),
                                                    'subSlug' => $section?->page?->content?->slug ?? '',
                                                    'slug' => $reference?->content?->slug ?? '',
                                                ])
                                                : 'javascript:void(0);' }}">
                                                <img src="{{ asset('frontend/images/arrrow-light.svg') }}" alt="readmore btn">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="our-work-pagination"></div>
                </div>
            </div>
        </div>
    </div>
</div>
