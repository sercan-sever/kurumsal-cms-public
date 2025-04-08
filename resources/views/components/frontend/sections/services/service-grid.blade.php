<div class="our-services cst-p50">
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
            <div class="col-lg-12">
                <div class="our-services-boxes tab-content wow fadeInUp" data-wow-delay="0.25s" id="servicesbox">
                    <div class="our-services-nav">
                        <ul class="nav nav-tabs" id="servicestab" role="tablist">
                            @foreach ($services as $service)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="{{ $loop->iteration }}-tab" data-bs-toggle="tab" data-bs-target="#{{ $loop->iteration }}" type="button" role="tab" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                        <span>{{ $loop->iteration }}.</span> {{ $service?->content?->title }}
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    @foreach ($services as $service)
                        <div class="our-service-box cst-service-grid-image tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $loop->iteration }}" role="tabpanel">
                            <div class="service-box-image">
                                <div class="overlay"></div>

                                <figure>
                                    <img src="{{ $service?->getOtherImage() }}" alt="{{ $service?->content?->title }}">
                                </figure>
                            </div>
                            <div class="service-box-item">
                                <div class="service-box-item-content">
                                    <h3>
                                        <a href="{{ !empty($section?->page?->id)
                                            ? route('frontend.sub.detail', [
                                                'lang' => session('locale'),
                                                'subSlug' => $section?->page?->content?->slug ?? '',
                                                'slug' => $service?->content?->slug ?? '',
                                            ])
                                            : 'javascript:void(0);' }}">
                                            {{ $service?->content?->title }}
                                        </a>
                                    </h3>

                                    <p>
                                        {!! $service?->content?->short_description !!}
                                    </p>
                                </div>
                                <div class="service-box-item-btn">
                                    <a href="{{ !empty($section?->page?->id)
                                        ? route('frontend.sub.detail', [
                                            'lang' => session('locale'),
                                            'subSlug' => $section?->page?->content?->slug ?? '',
                                            'slug' => $service?->content?->slug ?? '',
                                        ])
                                        : 'javascript:void(0);' }}" class="readmore-btn">{{ $section?->content?->button_title }}</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
