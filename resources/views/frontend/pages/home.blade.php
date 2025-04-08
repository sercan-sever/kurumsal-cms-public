@extends('frontend.layouts.app')

@section('title', !empty($page?->content?->title) ? env('APP_NAME') . ' | ' . $page?->content?->title : $setting?->general?->content?->title)

@section('meta_descriptions', !empty($page?->content?->meta_descriptions) ? env('APP_NAME') . ' | ' . $page?->content?->meta_descriptions : $setting?->general?->content?->meta_descriptions)

@section('meta_keywords', !empty($page?->content?->meta_keywords) ? env('APP_NAME') . ' | ' . $page?->content?->meta_keywords : $setting?->general?->content?->meta_keywords)

@section('css')
@endsection

@section('content')
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
