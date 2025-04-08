@extends('frontend.layouts.app')

@section('title', !empty($service?->content?->title) ? env('APP_NAME') . ' | ' . $service?->content?->title : $setting?->general?->content?->title)

@section('meta_descriptions', !empty($service?->content?->meta_descriptions) ? env('APP_NAME') . ' | ' . $service?->content?->meta_descriptions : $setting?->general?->content?->meta_descriptions)

@section('meta_keywords', !empty($service?->content?->meta_keywords) ? env('APP_NAME') . ' | ' . $service?->content?->meta_keywords : $setting?->general?->content?->meta_keywords)

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
                                {!! $service?->content?->title !!}
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
                                        {!! $service?->content?->title !!}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="page-service-single cst-p50">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">

                    <div class="service-sidebar">

                        <div class="service-catagery-list wow fadeInUp">
                            <h3>{{ $page?->content?->title }}</h3>
                            <ul>
                                @foreach ($services as $otherService)
                                    @php
                                        $active = $otherService?->content?->slug == $service?->content?->slug ? 'active' : '';
                                    @endphp
                                    <li>
                                        <a class="{{ $active }}" href="{{ route('frontend.sub.detail', [
                                            'lang' => session('locale'),
                                            'subSlug' => $page?->content?->slug,
                                            'slug' => $otherService?->content?->slug,
                                        ]) }}">
                                            {{ $otherService?->content?->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="service-single-content">
                        <div class="service-feature-image">
                            <figure class="image-anime reveal">
                                <img src="{{ $service?->getImage() }}" alt="{{ $service?->content?->title }}">
                            </figure>
                        </div>

                        <div class="service-entry">
                            <div class="service-benefits">
                                <h2 class="text-anime-style-2">
                                    <span>{{ $service?->content?->title }}</span>
                                </h2>

                                <div class="wow fadeInUp">
                                    {!! $service?->content?->description !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($service?->allImage->isNotEmpty())
        <div class="our-work bg-section cst-p50">
            <div class="container">
                <div class="row section-row align-items-center">
                    <div class="col-lg-12">
                        <div class="section-title">
                            <h2 class="text-anime-style-2" data-cursor="-opaque">
                                <span>{{ __('custom.service.images.name' ?? '') }}</span>
                            </h2>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="service-image-slider">
                        <div class="swiper">
                            <div class="swiper-wrapper" data-cursor-text="{{ __('custom.page.drag' ?? '') }}">
                                @foreach ($service->allImage as $image)
                                    <div class="swiper-slide col-lg-4 col-md-6">
                                        <div class="work-item wow fadeInUp">
                                            <div class="work-image">
                                                <a href="{{ $image?->getImage() }}" data-fancybox="gallery">
                                                    <figure>
                                                        <img src="{{ $image?->getImage() }}" alt="{{ $service?->content?->slug }}-{{ $loop->iteration }}">
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

    @include('components.frontend.sections.contact-forms.form', ['section' => $section, 'subject' => $service?->content?->title])

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
