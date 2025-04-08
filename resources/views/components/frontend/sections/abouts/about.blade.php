<div class="page-team-single cst-p50">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="team-single-box">
                    <div class="team-about-box">
                        <div class="team-single-image">
                            <figure class="image-anime reveal">
                                <img src="{{ $about->getImage() }}" alt="{{ $about?->content?->title }}">
                            </figure>
                        </div>

                        <div class="team-about-content">
                            <div class="section-title">
                                <h3 class="wow fadeInUp">{!! $section?->content?->heading !!}</h3>
                                <h2 class="text-anime-style-2" data-cursor="-opaque">{!! $about?->content?->title !!}</h2>

                                {!! $about?->content?->description !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
