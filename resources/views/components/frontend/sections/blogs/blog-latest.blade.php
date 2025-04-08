<div class="our-blog cst-p50">
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

            @foreach ($blogs as $blog)
                <div class="col-lg-4 col-md-6">
                    <div class="post-item wow fadeInUp">
                        <div class="post-featured-image">
                            <a href="{{ !empty($section?->page?->id)
                                ? route('frontend.sub.detail', [
                                    'lang' => session('locale'),
                                    'subSlug' => $section?->page?->content?->slug ?? '',
                                    'slug' => $blog?->content?->slug ?? '',
                                ])
                                : 'javascript:void(0);' }}" data-cursor-text="{{ __('custom.page.view' ?? '') }}">
                                <figure>
                                    <img src="{{ $blog?->getImage() }}" alt="{{ $blog?->content?->title }}">
                                </figure>
                            </a>
                        </div>

                        <div class="post-item-body">
                            <div class="post-item-content">
                                <h2>
                                    <a href="{{ !empty($section?->page?->id)
                                        ? route('frontend.sub.detail', [
                                            'lang' => session('locale'),
                                            'subSlug' => $section?->page?->content?->slug ?? '',
                                            'slug' => $blog?->content?->slug ?? '',
                                        ])
                                        : 'javascript:void(0);' }}">
                                        {{ $blog?->content?->title }}
                                    </a>
                                </h2>
                            </div>

                            <div class="post-item-footer">
                                @if (!empty($blog?->published_at))
                                    <div class="post-item-meta">
                                        <ul>
                                            <li>{!! $blog?->getPublishedAtFrontend() !!}</li>
                                        </ul>
                                    </div>
                                @endif

                                <div class="post-item-btn">
                                    <a class="readmore-btn" href="{{ !empty($section?->page?->id)
                                        ? route('frontend.sub.detail', [
                                            'lang' => session('locale'),
                                            'subSlug' => $section?->page?->content?->slug ?? '',
                                            'slug' => $blog?->content?->slug ?? '',
                                        ])
                                        : 'javascript:void(0);' }}">
                                        {{ $section?->content?->button_title }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
