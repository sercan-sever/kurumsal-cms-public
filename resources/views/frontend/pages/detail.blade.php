@extends('frontend.layouts.app')

@section('title', !empty($page?->content?->title) ? env('APP_NAME') . ' | ' . $page?->content?->title : $setting?->general?->content?->title)

@section('meta_descriptions', !empty($page?->content?->meta_descriptions) ? env('APP_NAME') . ' | ' . $page?->content?->meta_descriptions : $setting?->general?->content?->meta_descriptions)

@section('meta_keywords', !empty($page?->content?->meta_keywords) ? env('APP_NAME') . ' | ' . $page?->content?->meta_keywords : $setting?->general?->content?->meta_keywords)

@section('css')
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
                                {!! $page?->content?->title !!}
                            </h1>
                            <nav class="wow fadeInUp" data-wow-delay="0.25s">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('frontend.home', ['lang' => session('locale')]) }}">
                                            {{ __('custom.page.breadcrumb' ?? '') }}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        {!! $page?->content?->title !!}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {!! $renderedSections ?? '' !!}
@endsection

@section('js')
    <script>
        $('#cst-contact-form-message').maxlength({
            alwaysShow: true,
            warningClass: "badge badge-danger",
            limitReachedClass: "badge badge-success"
        });

        $('#cst-contact-form-message-1').maxlength({
            alwaysShow: true,
            warningClass: "badge badge-danger",
            limitReachedClass: "badge badge-success"
        });

        $('#cst-contact-form-message-2').maxlength({
            alwaysShow: true,
            warningClass: "badge badge-danger",
            limitReachedClass: "badge badge-success"
        });
    </script>
@endsection
