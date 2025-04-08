@extends('frontend.layouts.app')

@section('title', !empty($reference?->content?->title) ? env('APP_NAME') . ' | ' . $reference?->content?->title : $setting?->general?->content?->title)

@section('meta_descriptions', !empty($reference?->content?->meta_descriptions) ? env('APP_NAME') . ' | ' . $reference?->content?->meta_descriptions : $setting?->general?->content?->meta_descriptions)

@section('meta_keywords', !empty($reference?->content?->meta_keywords) ? env('APP_NAME') . ' | ' . $reference?->content?->meta_keywords : $setting?->general?->content?->meta_keywords)

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
@endsection

@section('content')
    @if ($page->isActiveBreadcrumb())
        <div class="page-header bg-section cst-breadcrumb" style="background-image: url({{ !empty($page->image) ? $page->getImage() : '' }})">
            <div class="overlay"></div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="page-header-box">
                            <h1 class="text-anime-style-2" data-cursor="-opaque">
                                {!! $reference?->content?->title !!}
                            </h1>
                            <nav class="wow fadeInUp" data-wow-delay="0.25s">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('frontend.home', ['lang' => session('locale')]) }}">
                                            {{ __('custom.page.breadcrumb' ?? '') }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('frontend.detail', ['lang' => session('locale'), 'slug' => $page?->content?->slug]) }}">
                                            {{ $page?->content?->title }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {!! $reference?->content?->title !!}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="page-work-single cst-p50">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="work-single-sidebar">
                        <div class="work-category-list wow fadeInUp">
                            <div class="work-category-title">
                                <h3>{{ __('custom.reference.project.detail' ?? '') }}</h3>
                            </div>

                            <div class="category-item-list">
                                <div class="category-list-item cst-project-detail">
                                    <h3>
                                        <img src="{{ asset('frontend/images/icon-work-category-1.svg') }}" alt="{{ __('custom.reference.project.name' ?? '') }}">
                                        {{ __('custom.reference.project.name' ?? '') }}
                                    </h3>
                                    <p>{!! $reference?->content?->title !!}</p>
                                </div>
                                <div class="category-list-item cst-project-detail">
                                    <h3>
                                        <img src="{{ asset('frontend/images/icon-work-category-2.svg') }}" alt="{{ __('custom.reference.client' ?? '') }}">
                                        {{ __('custom.reference.client' ?? '') }}
                                    </h3>
                                    <p>{!! $reference?->brand?->content?->title !!}</p>
                                </div>
                                <div class="category-list-item cst-project-detail">
                                    <h3>
                                        <img src="{{ asset('frontend/images/icon-work-category-3.svg') }}" alt="{{ __('custom.reference.services' ?? '') }}">
                                        {{ __('custom.reference.services' ?? '') }}
                                    </h3>
                                    @foreach ($reference?->services as $service)
                                        <p>- {!! $service?->content?->title !!}</p>
                                    @endforeach
                                </div>
                                <div class="category-list-item cst-project-detail">
                                    <h3>
                                        <img src="{{ asset('frontend/images/icon-work-category-4.svg') }}" alt="{{ __('custom.reference.completion.date' ?? '') }}">
                                        {{ __('custom.reference.completion.date' ?? '') }}
                                    </h3>
                                    <p>{!! $reference?->getCompletionDateFrontend() !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="work-single-content">
                        <div class="work-feature-image">
                            <figure class="image-anime reveal">
                                <img src="{{ $reference?->getImage() }}" alt="{{ $reference?->content?->title }}">
                            </figure>
                        </div>

                        <div class="work-entry">
                            <div class="project-overview">
                                <h2 class="text-anime-style-2">
                                    <span>{{ $reference?->content?->title }}</span>
                                </h2>

                                <div class="wow fadeInUp" data-wow-delay="0.2s">
                                    {!! $reference?->content?->description !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($reference?->allImage->isNotEmpty())
        <div class="our-work bg-section cst-p50">
            <div class="container">
                <div class="row section-row align-items-center">
                    <div class="col-lg-12">
                        <div class="section-title">
                            <h2 class="text-anime-style-2" data-cursor="-opaque">
                                <span>{{ __('custom.reference.images.name' ?? '') }}</span>
                            </h2>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="service-image-slider">
                        <div class="swiper">
                            <div class="swiper-wrapper" data-cursor-text="{{ __('custom.page.drag' ?? '') }}">
                                @foreach ($reference->allImage as $image)
                                    <div class="swiper-slide col-lg-4 col-md-6">
                                        <div class="work-item wow fadeInUp">
                                            <div class="work-image">
                                                <a href="{{ $image?->getImage() }}" data-fancybox="gallery">
                                                    <figure>
                                                        <img src="{{ $image?->getImage() }}" alt="{{ $reference?->content?->slug }}-{{ $loop->iteration }}">
                                                    </figure>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="service-image-pagination"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @include('components.frontend.sections.contact-forms.form', ['section' => $section])

    @include('components.frontend.sections.footers.footer')
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>

    <script>
        $('#message').maxlength({
            alwaysShow: true,
            warningClass: "badge badge-danger",
            limitReachedClass: "badge badge-success"
        });
    </script>

    <script>
        Fancybox.bind('[data-fancybox="gallery"]', {
            //
        });
    </script>
@endsection
