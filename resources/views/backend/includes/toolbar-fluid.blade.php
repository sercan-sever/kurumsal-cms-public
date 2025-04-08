<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{ $title ?? '' }}</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="{{ route('admin.home') }}" class="text-muted text-hover-primary fw-900">Dashboard</a>
                </li>
                @if (!empty($breadcrumbs))
                    @foreach ($breadcrumbs as $breadcrumb)
                        @if (!empty($breadcrumb['name']))
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-400 w-5px h-2px"></span>
                            </li>

                            @if (!empty($breadcrumb['url']))
                                <li class="breadcrumb-item text-muted">
                                    <a href="{{ $breadcrumb['url'] }}" class="text-muted text-hover-primary">{{ $breadcrumb['name'] }}</a>
                                </li>
                            @else
                                <li class="breadcrumb-item text-muted">{{ $breadcrumb['name'] }}</li>
                            @endif
                        @endif
                    @endforeach
                @endif
            </ul>
        </div>

        @if (!empty($viewButton))
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <button type="button" class="read-view-btn btn btn-icon btn-bg btn-primary btn-sm me-1" data-id="{{ $viewButtonId ?? '' }}">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        @endif
    </div>
</div>
