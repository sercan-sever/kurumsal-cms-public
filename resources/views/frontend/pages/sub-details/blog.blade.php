@extends('frontend.layouts.app')

@section('title', !empty($blog?->content?->title) ? env('APP_NAME') . ' | ' . $blog?->content?->title : $setting?->general?->content?->title)

@section('meta_descriptions', !empty($blog?->content?->meta_descriptions) ? env('APP_NAME') . ' | ' . $blog?->content?->meta_descriptions : $setting?->general?->content?->meta_descriptions)

@section('meta_keywords', !empty($blog?->content?->meta_keywords) ? env('APP_NAME') . ' | ' . $blog?->content?->meta_keywords : $setting?->general?->content?->meta_keywords)

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
                                {!! $blog?->content?->title !!}
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
                                        {!! $blog?->content?->title !!}
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="page-single-post cst-p50 cst-detail">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="post-image">
                        <figure class="image-anime reveal">
                            <img src="{{ $blog?->getImage() }}" alt="{{ $blog?->content?->title }}">
                        </figure>
                    </div>

                    <div class="post-content">
                        <div class="post-entry">
                            <h2 class="text-anime-style-2">
                                <span>{{ $blog?->content?->title }}</span>
                            </h2>
                            <div class="wow fadeInUp" data-wow-delay="0.2s">
                                {!! $blog?->content?->description !!}
                            </div>
                        </div>

                        <div class="post-tag-links cst-pt50">
                            <div class="row align-items-center">
                                @if ($blog?->categories?->isNotEmpty())
                                    <div class="col-lg-12 mb-7">
                                        <div class="post-tags wow fadeInUp" data-wow-delay="0.5s">
                                            <span class="tag-links">
                                                {{ __('custom.blog.categories' ?? '') }}
                                                @foreach ($blog?->categories as $category)
                                                    <a href="{{ route('frontend.detail', [
                                                        'lang' => session('locale'),
                                                        'slug' => $page?->content?->slug,
                                                        'category' => $category?->content?->slug,
                                                    ]) }}">
                                                        {{ $category?->content?->title }}
                                                    </a>
                                                @endforeach
                                            </span>
                                        </div>
                                    </div>
                                @endif

                                @if ($blog?->tags?->isNotEmpty())
                                    <div class="col-lg-12 mb-0">
                                        <div class="post-tags wow fadeInUp" data-wow-delay="0.5s">
                                            <span class="tag-links">
                                                {{ __('custom.blog.tags' ?? '') }}
                                                @foreach ($blog?->tags as $tag)
                                                    <a href="{{ route('frontend.detail', [
                                                        'lang' => session('locale'),
                                                        'slug' => $page?->content?->slug,
                                                        'tag' => $tag?->content?->slug,
                                                    ]) }}">
                                                        {{ $tag?->content?->title }}
                                                    </a>
                                                @endforeach
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($blog?->isActiveCommentStatus())
        <div class="page-team-single cst-comment-form cst-p50">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="team-single-box">
                            <div class="team-contact-form">
                                <div class="section-title">
                                    <h3 class="text-anime-style-2" data-cursor="-opaque">
                                        {{ __('custom.blog.form.name' ?? '') }}
                                    </h3>
                                </div>

                                <div class="contact-form">
                                    <form id="comment-form" data-action="{{ route('frontend.form.comment', ['lang' => session('locale')]) }}" method="POST" class="wow fadeInUp" data-wow-delay="0.25s">
                                        @csrf

                                        <input type="hidden" name="slug" value="{{ $blog?->content?->slug }}">

                                        <div class="row">
                                            <div class="form-group col-md-6 mb-4">
                                                <label class="form-label">{{ __('custom.form.name' ?? '') }}</label>
                                                <input type="text" name="name" class="form-control" id="name" placeholder="{{ __('custom.form.name' ?? '') }} *" required>
                                                <div class="help-block with-errors"></div>
                                            </div>

                                            <div class="form-group col-md-6 mb-4">
                                                <label class="form-label">{{ __('custom.form.email' ?? '') }}</label>
                                                <input type="email" name ="email" class="form-control" id="email" placeholder="{{ __('custom.form.email' ?? '') }} *" required>
                                                <div class="help-block with-errors"></div>
                                            </div>

                                            <div class="form-group col-md-12 mb-4">
                                                <label class="form-label">{{ __('custom.form.comment' ?? '') }}</label>
                                                <textarea name="comment" class="form-control" id="comment" rows="4" placeholder="{{ __('custom.form.comment' ?? '') }} *" minlength="1" maxlength="1000" required></textarea>
                                                <div class="help-block with-errors"></div>
                                            </div>

                                            <div class="form-group col-md-12 mb-4">
                                                <div class="d-flex justify-content-center flex-wrap gap-3 fs-base fw-semibold">
                                                    {!! NoCaptcha::display() !!}
                                                </div>
                                            </div>

                                            <div class="col-md-12 text-center">
                                                <button type="submit" class="btn-default">
                                                    {{ __('custom.form.button' ?? '') }}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="creative-tools cst-comments">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-9">
                    <div class="section-title">
                        <h3 class="text-anime-style-2" data-cursor="-opaque">
                            {{ __('custom.blog.comment.name' ?? '') }} ({{ $blog?->comments?->count() ?? 0 }})
                        </h3>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="creative-tools-box">
                        @foreach ($blog?->comments as $comment)
                            <div class="creative-tool-item wow fadeInUp {{ !empty($comment?->reply_comment) ? 'mb-0' : 'mb-7' }}" data-wow-delay="0.2s">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row align-items-center">
                                            <div class="col-md-12 d-flex align-items-center">
                                                <div class="icon-box">
                                                    <img src="{{ asset('frontend/images/user.png') }}" alt="{{ __('custom.blog.user' ?? '') }}-{{ $loop->iteration }}">
                                                </div>

                                                <div class="creative-tool-item-content">
                                                    <h3>{{ __('custom.blog.user' ?? '') }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="creative-tool-item-content content mt-4">
                                            <p>
                                                {{ $comment?->comment }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if (!empty($comment?->reply_comment))
                                <div class="creative-tool-item cst-admin wow fadeInUp mb-7" data-wow-delay="0.2s">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row align-items-center">
                                                <div class="col-md-12 d-flex align-items-center">
                                                    <div class="icon-box">
                                                        <img src="{{ asset('frontend/images/admin.png') }}" alt="{{ __('custom.blog.admin' ?? '') }}-{{ $loop->iteration }}">
                                                    </div>
                                                    <div class="creative-tool-item-content">
                                                        <h3>{{ __('custom.blog.admin' ?? '') }}</h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="creative-tool-item-content content mt-4">
                                                <p>
                                                    {{ $comment?->reply_comment }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('components.frontend.sections.footers.footer')
@endsection

@section('js')
    <script>
        $('#comment').maxlength({
            alwaysShow: true,
            warningClass: "badge badge-danger",
            limitReachedClass: "badge badge-success"
        });
    </script>
@endsection
