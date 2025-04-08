@extends('backend.layouts.app')

@use(App\Enums\Permissions\PermissionEnum)

@section('toolbar')
    @include('backend.includes.toolbar', ['title' => 'Dashboard'])
@endsection

@section('content')
    <div class="row g-5 g-xl-10 mt-0">

        <div class="col-xl-4">
            <div class="card card-xl-stretch mb-xl-8" style="height: auto;background-color: transparent;">
                <div class="card-body p-0">
                    <div class="px-9 pt-7 card-rounded h-275px w-100 bg-primary">
                        <div class="d-flex flex-stack justify-content-center">
                            <h3 class="m-0 text-white fw-bold fs-3">SAYFALAR</h3>
                        </div>
                        <div class="d-flex text-center flex-column text-white pt-8">
                            <i class="fa-solid fa-pager text-white mb-3 fs-1"></i>
                            <span class="fw-semibold fs-7">Aktif Sayaflar</span>
                            <span class="fw-bold fs-2x pt-1">{{ $activePageCount ?? 0 }}</span>
                        </div>
                    </div>

                    <div class="bg-body shadow-sm card-rounded mx-9 mb-9 px-6 py-9 position-relative z-index-1" style="margin-top: -100px">
                        <div class="d-flex align-items-center mb-6">
                            <div class="d-flex align-items-center flex-wrap w-100">
                                <div class="mb-1 pe-3 flex-grow-1">
                                    <a href="javascript:void(0);" class="fs-5 text-gray-800 text-hover-primary fw-bold">Pasif Sayfalar</a>
                                </div>

                                <div class="d-flex align-items-center">
                                    <div class="fw-bold fs-5 text-gray-800 pe-1">{{ $passivePageCount ?? 0 }}</div>
                                    <span class="svg-icon svg-icon-5 svg-icon-danger ms-1">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect opacity="0.5" x="11" y="18" width="13" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor"></rect>
                                            <path d="M11.4343 15.4343L7.25 11.25C6.83579 10.8358 6.16421 10.8358 5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75L11.2929 18.2929C11.6834 18.6834 12.3166 18.6834 12.7071 18.2929L18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25C17.8358 10.8358 17.1642 10.8358 16.75 11.25L12.5657 15.4343C12.2533 15.7467 11.7467 15.7467 11.4343 15.4343Z" fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            <div class="d-flex align-items-center flex-wrap w-100">
                                <div class="mb-1 pe-3 flex-grow-1">
                                    <a href="javascript:void(0);" class="fs-5 text-gray-800 text-hover-primary fw-bold">Silinen Sayfalar</a>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="fw-bold fs-5 text-gray-800 pe-1">{{ $deletedPageCount ?? 0 }}</div>
                                    <span class="svg-icon svg-icon-5 svg-icon-danger ms-1">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect opacity="0.5" x="11" y="18" width="13" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor"></rect>
                                            <path d="M11.4343 15.4343L7.25 11.25C6.83579 10.8358 6.16421 10.8358 5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75L11.2929 18.2929C11.6834 18.6834 12.3166 18.6834 12.7071 18.2929L18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25C17.8358 10.8358 17.1642 10.8358 16.75 11.25L12.5657 15.4343C12.2533 15.7467 11.7467 15.7467 11.4343 15.4343Z" fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="col-xl-4">
            <div class="card card-xl-stretch mb-xl-8" style="height: auto;background-color: transparent;">
                <div class="card-body p-0">
                    <div class="px-9 pt-7 card-rounded h-275px w-100 bg-info">
                        <div class="d-flex flex-stack justify-content-center">
                            <h3 class="m-0 text-white fw-bold fs-3">BLOGLAR</h3>
                        </div>
                        <div class="d-flex text-center flex-column text-white pt-8">
                            <i class="fas fa-blog text-white mb-3 fs-1"></i>
                            <span class="fw-semibold fs-7">Aktif Blog Yazıları</span>
                            <span class="fw-bold fs-2x pt-1">{{ $activeBlogCount ?? 0 }}</span>
                        </div>
                    </div>

                    <div class="bg-body shadow-sm card-rounded mx-9 mb-9 px-6 py-9 position-relative z-index-1" style="margin-top: -100px">
                        <div class="d-flex align-items-center mb-6">
                            <div class="d-flex align-items-center flex-wrap w-100">
                                <div class="mb-1 pe-3 flex-grow-1">
                                    <a href="javascript:void(0);" class="fs-5 text-gray-800 text-hover-primary fw-bold">Pasif Bloglar</a>
                                </div>

                                <div class="d-flex align-items-center">
                                    <div class="fw-bold fs-5 text-gray-800 pe-1">{{ $passiveBlogCount ?? 0 }}</div>
                                    <span class="svg-icon svg-icon-5 svg-icon-danger ms-1">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect opacity="0.5" x="11" y="18" width="13" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor"></rect>
                                            <path d="M11.4343 15.4343L7.25 11.25C6.83579 10.8358 6.16421 10.8358 5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75L11.2929 18.2929C11.6834 18.6834 12.3166 18.6834 12.7071 18.2929L18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25C17.8358 10.8358 17.1642 10.8358 16.75 11.25L12.5657 15.4343C12.2533 15.7467 11.7467 15.7467 11.4343 15.4343Z" fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            <div class="d-flex align-items-center flex-wrap w-100">
                                <div class="mb-1 pe-3 flex-grow-1">
                                    <a href="javascript:void(0);" class="fs-5 text-gray-800 text-hover-primary fw-bold">Silinen Bloglar</a>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="fw-bold fs-5 text-gray-800 pe-1">{{ $deletedBlogCount ?? 0 }}</div>
                                    <span class="svg-icon svg-icon-5 svg-icon-danger ms-1">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect opacity="0.5" x="11" y="18" width="13" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor"></rect>
                                            <path d="M11.4343 15.4343L7.25 11.25C6.83579 10.8358 6.16421 10.8358 5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75L11.2929 18.2929C11.6834 18.6834 12.3166 18.6834 12.7071 18.2929L18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25C17.8358 10.8358 17.1642 10.8358 16.75 11.25L12.5657 15.4343C12.2533 15.7467 11.7467 15.7467 11.4343 15.4343Z" fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-4">
            <div class="card card-xl-stretch mb-xl-8" style="height: auto;background-color: transparent;">
                <div class="card-body p-0">
                    <div class="px-9 pt-7 card-rounded h-275px w-100 bg-danger">
                        <div class="d-flex flex-stack justify-content-center">
                            <h3 class="m-0 text-white fw-bold fs-3">ABONELER</h3>
                        </div>
                        <div class="d-flex text-center flex-column text-white pt-8">
                            <i class="fa-solid fa-newspaper text-white mb-3 fs-1"></i>
                            <span class="fw-semibold fs-7">Aktif Aboneler</span>
                            <span class="fw-bold fs-2x pt-1">{{ $activeSubscribeCount ?? 0 }}</span>
                        </div>
                    </div>

                    <div class="bg-body shadow-sm card-rounded mx-9 mb-9 px-6 py-9 position-relative z-index-1" style="margin-top: -100px">
                        <div class="d-flex align-items-center mb-6">
                            <div class="d-flex align-items-center flex-wrap w-100">
                                <div class="mb-1 pe-3 flex-grow-1">
                                    <a href="javascript:void(0);" class="fs-5 text-gray-800 text-hover-primary fw-bold">Pasif Aboneler</a>
                                </div>

                                <div class="d-flex align-items-center">
                                    <div class="fw-bold fs-5 text-gray-800 pe-1">{{ $passiveSubscribeCount ?? 0 }}</div>
                                    <span class="svg-icon svg-icon-5 svg-icon-danger ms-1">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect opacity="0.5" x="11" y="18" width="13" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor"></rect>
                                            <path d="M11.4343 15.4343L7.25 11.25C6.83579 10.8358 6.16421 10.8358 5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75L11.2929 18.2929C11.6834 18.6834 12.3166 18.6834 12.7071 18.2929L18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25C17.8358 10.8358 17.1642 10.8358 16.75 11.25L12.5657 15.4343C12.2533 15.7467 11.7467 15.7467 11.4343 15.4343Z" fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            <div class="d-flex align-items-center flex-wrap w-100">
                                <div class="mb-1 pe-3 flex-grow-1">
                                    <a href="javascript:void(0);" class="fs-5 text-gray-800 text-hover-primary fw-bold">Silinen Aboneler</a>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="fw-bold fs-5 text-gray-800 pe-1">{{ $deletedSubscribeCount ?? 0 }}</div>
                                    <span class="svg-icon svg-icon-5 svg-icon-danger ms-1">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect opacity="0.5" x="11" y="18" width="13" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor"></rect>
                                            <path d="M11.4343 15.4343L7.25 11.25C6.83579 10.8358 6.16421 10.8358 5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75L11.2929 18.2929C11.6834 18.6834 12.3166 18.6834 12.7071 18.2929L18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25C17.8358 10.8358 17.1642 10.8358 16.75 11.25L12.5657 15.4343C12.2533 15.7467 11.7467 15.7467 11.4343 15.4343Z" fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @can([PermissionEnum::BLOG_VIEW])
        {{-- Blog Detay Görüntüleme Modal Başlangıç --}}
        <div class="modal fade" id="view-blog-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">
                            Blog İnceleme
                        </h2>
                        <div id="view-blog-close" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="fa-regular fa-circle-xmark fs-2"></i>
                        </div>
                    </div>

                    <div class="modal-body p-0">
                        <div class="d-flex flex-column" id="view-blog-modal-scroll" data-kt-scroll="false">

                        </div>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="button" id="view-blog-close-btn" data-bs-dismiss="modal" class="btn btn-light-danger me-3">
                            Kapat
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- Blog Detay Görüntüleme Modal Bitiş --}}


        <div class="row gy-5 g-xl-10 mt-10">
            <div class="col-xl-12 mb-5 mt-0 mb-xl-12">
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold fs-3 mb-1">Son Eklenen Blog Yazıları</span>
                            <span class="text-muted mt-1 fw-semibold fs-7">Son Eklenen 10 Blog</span>
                        </h3>
                    </div>

                    <div class="card-body py-3">
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5">
                                <thead>
                                    <tr class="fw-bold text-muted text-center">
                                        <th class="text-center min-w100">İşlemler</th>
                                        <th class="text-center min-w150">Görsel</th>
                                        <th class="text-center min-w150">Ad</th>
                                        <th class="text-center min-w150">Paylaşım Tarihi</th>
                                        <th class="text-center min-w100">Sıralama</th>
                                    </tr>
                                </thead>

                                <tbody class="fw-semibold text-gray-600 text-center">
                                    @if ($blogs->isNotEmpty())
                                        @foreach ($blogs as $blog)
                                            <tr data-id="{{ $blog->id }}">
                                                <td>
                                                    <button type="button" class="read-view-btn btn btn-icon btn-bg-light btn-light-primary btn-sm me-1">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>

                                                <td>
                                                    <div class="symbol symbol-50px">
                                                        <img src="{{ $blog->getImage() }}" />
                                                    </div>
                                                </td>

                                                <td>{{ $blog?->content?->title }}</td>

                                                <td>{!! $blog->getPublishedAtHtml() !!}</td>

                                                <td>{{ $blog?->sorting }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7">
                                                <div class="empty-message text-center text-danger fw-bold py-3">- Eklenmiş Blog Bulunamadı -</div>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endsection

@section('js')
    @can([PermissionEnum::BLOG_VIEW])
        <script src="{{ asset('backend/assets/js/modules/blogs/content/view/read-view.js') }}"></script>
        <script src="{{ asset('backend/assets/js/custom/ckeditor/ckeditor.js') }}"></script>
    @endcan
@endsection
