<div class="error-page">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="error-page-content">
                    <div class="section-title">
                        <h2 class="text-anime-style-2" data-cursor="-opaque">{!! __('custom.notfound.title' ?? '') !!}</h2>
                    </div>
                    <div class="error-page-content-body">
                        <p class="wow fadeInUp" data-wow-delay="0.25s">{{ __('custom.notfound.content' ?? '') }}</p>
                        <a class="btn-default wow fadeInUp" data-wow-delay="0.5s" href="{{ route('frontend.home', ['lang' => session('locale')]) }}">
                            <span>{{ __('custom.notfound.button' ?? '') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
