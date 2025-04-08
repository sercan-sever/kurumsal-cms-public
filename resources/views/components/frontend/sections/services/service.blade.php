@if ($services->isNotEmpty())
    <div class="page-services cst-p50">
        <div class="container">
            <div class="row">
                @foreach ($services as $service)
                    <div class="col-lg-4 col-md-6">
                        <div class="service-item wow fadeInUp">
                            <div class="service-image">
                                <a href="{{ !empty($section?->page?->id)
                                    ? route('frontend.sub.detail', [
                                        'lang' => session('locale'),
                                        'subSlug' => $section?->page?->content?->slug ?? '',
                                        'slug' => $service?->content?->slug ?? '',
                                    ])
                                    : 'javascript:void(0);' }}" data-cursor-text="{{ __('custom.page.view' ?? '') }}">
                                    <figure>
                                        <img src="{{ $service?->getOtherImage() }}" alt="{{ $service?->content?->title }}">
                                    </figure>
                                </a>
                            </div>

                            <div class="service-body">
                                <div class="service-content">
                                    <h3>
                                        <a href="{{ !empty($section?->page?->id)
                                            ? route('frontend.sub.detail', [
                                                'lang' => session('locale'),
                                                'subSlug' => $section?->page?->content?->slug ?? '',
                                                'slug' => $service?->content?->slug ?? '',
                                            ])
                                            : 'javascript:void(0);' }}">{{ $service?->content?->title }}</a>
                                    </h3>

                                    <p>
                                        {!! getStringLimit($service?->content?->short_description, 55) !!}
                                    </p>
                                </div>

                                <div class="service-btn">
                                    <a href="{{ !empty($section?->page?->id)
                                        ? route('frontend.sub.detail', [
                                            'lang' => session('locale'),
                                            'subSlug' => $section?->page?->content?->slug ?? '',
                                            'slug' => $service?->content?->slug ?? '',
                                        ])
                                        : 'javascript:void(0);' }}">
                                        <img src="{{ asset('frontend/images/arrrow-light.svg') }}" alt="readmore btn">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="col-lg-12">
                    <div class="page-pagination wow fadeInUp" data-wow-delay="1.2s">
                        {!! $services->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    @include('components.frontend.sections.not-found.not-found')
@endif
