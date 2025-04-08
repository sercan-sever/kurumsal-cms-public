<div class="how-it-work bg-section cst-p50">
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
                <div class="how-it-work-images">
                    <div class="how-it-work-image cst-faq-image">
                        <figure>
                            <img src="{{ $section?->getOtherImage() }}" alt="{{ $section?->content?->heading }}">
                        </figure>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="work-faq-accordion" id="workaccordion">
                    @foreach ($faqs as $faq)
                        <div class="work-accordion-item wow fadeInUp">
                            <h2 class="accordion-header" id="heading{{ $loop->iteration }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $loop->iteration }}" aria-expanded="false" aria-controls="collapse{{ $loop->iteration }}">
                                    {{ $faq?->content?->title }}
                                </button>
                            </h2>
                            <div id="collapse{{ $loop->iteration }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $loop->iteration }}" data-bs-parent="#workaccordion">
                                <div class="accordion-body">
                                    {!! $faq?->content?->description !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
