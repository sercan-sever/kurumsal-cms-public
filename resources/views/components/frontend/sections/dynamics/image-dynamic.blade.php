<div class="page-single-post cst-p50">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="post-image">
                    <figure class="image-anime reveal">
                        <img src="{{ $section?->getOtherImage() }}" alt="{{ $section?->title }}">
                    </figure>
                </div>

                <div class="post-content">
                    <div class="post-entry bb-none wow fadeInUp" data-wow-delay="0.2s">
                        {!! $section?->content?->description !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
