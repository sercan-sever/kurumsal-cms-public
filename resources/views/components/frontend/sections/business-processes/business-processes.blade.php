<div class="our-achievements cst-p50">
    <div class="container">
        <div class="row">
            <div class="col-lg-5">

                <div class="our-achievements-content">

                    <div class="section-title">
                        <h3 class="wow fadeInUp">{!! $section?->content?->heading !!}</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque">{!! $section?->content?->sub_heading !!}</h2>
                        <p class="wow fadeInUp" data-wow-delay="0.2s">
                            {!! $section?->content?->description !!}
                        </p>
                    </div>

                    @if (!empty($section?->page?->id))
                        <div class="achievements-content-btn wow fadeInUp" data-wow-delay="0.4s">
                            <a href="{{ route('frontend.detail', [
                                'lang' => session('locale'),
                                'slug' => $section?->page?->content?->slug ?? '',
                            ]) }}" class="btn-default">{{ $section?->content?->button_title }}</a>
                        </div>
                    @endif
                </div>

            </div>

            <div class="col-lg-7">

                <div class="our-achievements-box wow fadeInUp">
                    @foreach ($businessProcesses as $processes)
                        <div class="achievements-item">
                            <h3>{{ $processes?->content?->header }}</h3>
                            <h2>{{ $processes?->content?->title }}</h2>
                            <p>
                                {!! $processes?->content?->description !!}
                            </p>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</div>
