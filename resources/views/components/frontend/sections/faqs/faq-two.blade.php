<div class="our-faqs cst-p50">
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

        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="our-faq-section">
                    <div class="faq-accordion" id="accordion">
                        @foreach ($faqs as $faq)
                            <div class="accordion-item wow fadeInUp" data-wow-delay="0.2s">
                                <h2 class="accordion-header" id="heading{{ $loop->iteration }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $loop->iteration }}" aria-expanded="false" aria-controls="collapse{{ $loop->iteration }}">
                                        {{ $faq?->content?->title }}
                                    </button>
                                </h2>
                                <div id="collapse{{ $loop->iteration }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $loop->iteration }}" data-bs-parent="#accordion">
                                    <div class="accordion-body">
                                        {!! $faq?->content?->description !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="our-faqs-img">
                    <figure class="image-anime reveal">
                        <img src="{{ $section?->getOtherImage() }}" alt="{{ $section?->content?->heading }}">
                    </figure>
                </div>
            </div>
        </div>
    </div>
</div>
