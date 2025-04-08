@if (empty($disabled))
    <form id="update-form" class="form" data-action="{{ route('admin.pages.section.update.service') }}">
        @csrf

        <input type="hidden" name="id" value="{{ $section->id }}">
@endif

<div class="card">
    <div class="card-header card-header-stretch">
        <div class="card-toolbar m-0">
            <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0 fw-bold" role="tablist">
                @foreach ($languages as $language)
                    <li class="nav-item" role="presentation">
                        <a id="update-tab-{{ $language->id }}" class="nav-link justify-content-center text-active-gray-800 {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab" role="tab" href="#update-body-{{ $language->id }}">
                            {{ $language->name }}
                            <span class="symbol symbol-20px ms-4">
                                <img class="rounded-1" src="{{ $language->getImage() }}">
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="tab-content">
            @foreach ($languages as $language)
                <div id="update-body-{{ $language->id }}" class="card-body p-0 tab-pane fade show {{ $loop->first ? 'active' : '' }}" role="tabpanel" aria-labelledby="update-tab-{{ $language->id }}">
                    <div class="card">
                        <div class="card-body">

                            @if ($section->allContent->isEmpty())
                                <x-backend.sections.defaults.service.empty :language="$language" :loop="$loop" :section="$section" :pages="$pages" :disabled="$disabled" />
                            @else
                                <x-backend.sections.defaults.service.full :language="$language" :loop="$loop" :section="$section" :pages="$pages" :disabled="$disabled" />
                            @endif

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>


@if (empty($disabled))
    </form>
@endif

<script>
    $('#page').selectpicker();
</script>
