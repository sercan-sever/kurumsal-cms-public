<div class="our-team cst-p50">
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

            <div class="col-lg-6">

                <div class="section-btn wow fadeInUp" data-wow-delay="0.2s">
                    <a href="{{ !empty($section?->page?->id)
                        ? route('frontend.detail', [
                            'lang' => session('locale'),
                            'slug' => $section?->page?->content?->slug ?? '',
                        ])
                        : 'javascript:void(0);' }}" title="{{ $section?->page?->content?->title }}" class="btn-default">
                        {{ $section?->content?->button_title }}
                    </a>
                </div>

            </div>
        </div>

        <div class="row">
            @foreach ($references as $reference)
                <div class="col-lg-3 col-md-6">

                    <div class="team-item wow fadeInUp">

                        <div class="team-image">
                            <a href="{{ !empty($section?->page?->id)
                                ? route('frontend.sub.detail', [
                                    'lang' => session('locale'),
                                    'subSlug' => $section?->page?->content?->slug ?? '',
                                    'slug' => $reference?->content?->slug ?? '',
                                ])
                                : 'javascript:void(0);' }}" data-cursor-text="{{ __('custom.page.view' ?? '') }}">
                                <figure>
                                    <img src="{{ $reference?->getOtherImage() }}" alt="{{ $reference?->content?->title }}">
                                </figure>
                            </a>
                        </div>



                        <div class="team-body">

                            <div class="team-content mb-0">
                                <h3 class="mb-0">
                                    <a href="{{ !empty($section?->page?->id)
                                        ? route('frontend.sub.detail', [
                                            'lang' => session('locale'),
                                            'subSlug' => $section?->page?->content?->slug ?? '',
                                            'slug' => $reference?->content?->slug ?? '',
                                        ])
                                        : 'javascript:void(0);' }}">{{ $reference?->content?->title }}</a>
                                </h3>
                            </div>

                        </div>

                    </div>

                </div>
            @endforeach
        </div>
    </div>
</div>
