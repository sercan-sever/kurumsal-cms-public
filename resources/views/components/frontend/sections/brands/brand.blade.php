@if ($brands->isNotEmpty())
    <div class="creative-tools cst-brand-detail cst-p50">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="creative-tools-box">
                        @foreach ($brands as $brand)
                            <div class="creative-tool-item wow fadeInUp">
                                <div class="icon-box">
                                    <img src="{{ $brand?->getImage() }}" alt="{{ $brand?->content?->title }}">
                                </div>
                                <div class="creative-tool-item-content">
                                    <h3>{{ $brand?->content?->title }}</h3>
                                    <p>{{ $brand?->content?->description }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="page-pagination wow fadeInUp" data-wow-delay="1.2s">
                        {!! $brands->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    @include('components.frontend.sections.not-found.not-found')
@endif
