<header class="main-header">
    <div class="header-sticky">
        <nav class="navbar navbar-expand-lg">
            <div class="container">

                <a class="navbar-brand mw-140" href="{{ route('frontend.home', ['lang' => session('locale')]) }}" title="logo link">
                    <img src="{{ $setting?->logo?->getBackendHeaderWhite() }}" alt="header logo">
                </a>

                <div class="collapse navbar-collapse main-menu">
                    <div class="nav-menu-wrapper">
                        <ul class="navbar-nav mr-auto" id="menu">
                            @foreach ($headerMenus as $menu)
                                @php
                                    $url = $loop->first ? route('frontend.home', ['lang' => session('locale')]) : route('frontend.detail', ['lang' => session('locale'), 'slug' => $menu?->content?->slug]);
                                @endphp
                                @if ($menu?->subPageMenus?->isEmpty())
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ $url }}">
                                            {{ $menu?->content?->title }}
                                        </a>
                                    </li>
                                @else
                                    <li class="nav-item submenu">
                                        <a class="nav-link" href="{{ $url }}">
                                            {{ $menu?->content?->title }}
                                        </a>
                                        <ul>
                                            @foreach ($menu?->subPageMenus as $sub)
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{ route('frontend.sub.detail', [
                                                        'lang' => session('locale'),
                                                        'subSlug' => $menu?->content?->slug,
                                                        'slug' => $sub?->content?->slug,
                                                    ]) }}">
                                                        {{ $sub?->content?->title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @endif
                            @endforeach

                            <li class="nav-item submenu cst-lang-mobil">
                                <a class="nav-link" href="javascript:void(0);">
                                    {{ $activeLanguage?->name }}
                                </a>
                                <ul>
                                    @foreach ($languages as $lang)
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ $activeLanguage?->code != $lang?->code ? url($lang?->code ?? '') : 'javascript:void(0);' }}">
                                                {{ $lang?->name ?? '' }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        </ul>
                    </div>

                    <div class="header-btn">
                        <div class="nav-menu-wrapper">
                            <ul class="navbar-nav mr-auto" id="menu">
                                <li class="nav-item submenu">
                                    <a class="nav-link cst-lang-btn" href="javascript:void(0);">{{ $activeLanguage?->name }}</a>
                                    <ul>
                                        @foreach ($languages as $lang)
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ $activeLanguage?->code != $lang?->code ? url($lang?->code ?? '') : 'javascript:void(0);' }}">
                                                    {{ $lang?->name ?? '' }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="navbar-toggle"></div>
            </div>
        </nav>
        <div class="responsive-menu"></div>
    </div>
</header>
