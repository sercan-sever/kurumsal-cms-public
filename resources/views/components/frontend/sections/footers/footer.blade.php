@php
    $subscribe = $setting?->subscribe?->isActiveStatus();

    $col = $subscribe ? ['col-lg-4', 'col-lg-2', 'col-lg-3 cst-mt-sm-4', 'col-lg-3 cst-mt-sm-4'] : ['col-lg-4', 'col-lg-3', 'col-lg-3', 'd-none'];
@endphp

<footer class="footer-section">
    <div class="footer-box bg-section">

        <div class="main-footer cst-p50">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="{{ $col[0] }}">
                        <div class="about-footer">
                            <div class="footer-logo">
                                <img src="{{ $setting?->logo?->getBackendFooterDark() }}" alt="footer logo">
                            </div>

                            <div class="about-footer-content mw-350">
                                <p>{{ $setting?->general?->content?->meta_descriptions }}</p>
                            </div>

                            <div class="footer-social-links">
                                <ul>
                                    @if (!empty($setting?->social?->facebook))
                                        <li>
                                            <a href="{{ $setting?->social?->facebook }}" target="_blank">
                                                <i class="fa-brands fa-facebook-f"></i>
                                            </a>
                                        </li>
                                    @endif

                                    @if (!empty($setting?->social?->twitter))
                                        <li>
                                            <a href="{{ $setting?->social?->twitter }}" target="_blank">
                                                <i class="fa-brands fa-x-twitter"></i>
                                            </a>
                                        </li>
                                    @endif

                                    @if (!empty($setting?->social?->linkedin))
                                        <li>
                                            <a href="{{ $setting?->social?->linkedin }}" target="_blank">
                                                <i class="fa-brands fa-linkedin-in"></i>
                                            </a>
                                        </li>
                                    @endif

                                    @if (!empty($setting?->social?->instagram))
                                        <li>
                                            <a href="{{ $setting?->social?->instagram }}" target="_blank">
                                                <i class="fa-brands fa-instagram"></i>
                                            </a>
                                        </li>
                                    @endif

                                    @if (!empty($setting?->social?->youtube))
                                        <li>
                                            <a href="{{ $setting?->social?->youtube }}" target="_blank">
                                                <i class="fa-brands fa-youtube"></i>
                                            </a>
                                        </li>
                                    @endif

                                    @if (!empty($setting?->social?->pinterest))
                                        <li>
                                            <a href="{{ $setting?->social?->pinterest }}" target="_blank">
                                                <i class="fa-brands fa-pinterest"></i>
                                            </a>
                                        </li>
                                    @endif

                                    @if (!empty($setting?->social?->whatsapp))
                                        <li>
                                            <a href="{{ $setting?->social?->whatsapp }}" target="_blank">
                                                <i class="fa-brands fa-whatsapp"></i>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>

                        </div>
                    </div>

                    <div class="{{ $col[1] }}">
                        <div class="footer-links">
                            <h3>{{ __('custom.footer.quick.access' ?? '') }}</h3>
                            <ul>
                                @foreach ($footerMenus as $menu)
                                    <li>
                                        <a href="{{ route('frontend.detail', ['lang' => session('locale'), 'slug' => $menu?->content?->slug]) }}">
                                            {{ $menu?->content?->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="{{ $col[2] }}">
                        <div class="footer-contact">
                            <h3>{{ __('custom.footer.contact' ?? '') }}</h3>
                            <div class="footer-contact-details">
                                @if (!empty($setting?->address?->content?->phone_number_one))
                                    <div class="footer-info-box">
                                        <div class="icon-box">
                                            <i class="fa-solid fa-phone"></i>
                                        </div>
                                        <div class="footer-info-box-content">
                                            <p>
                                                <a href="tel:{{ $setting?->address?->content?->phone_number_one }}">
                                                    {{ $setting?->address?->content?->phone_number_one }}
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                @if (!empty($setting?->address?->content?->phone_number_two))
                                    <div class="footer-info-box">
                                        <div class="icon-box">
                                            <i class="fa-solid fa-phone"></i>
                                        </div>
                                        <div class="footer-info-box-content">
                                            <p>
                                                <a href="tel:{{ $setting?->address?->content?->phone_number_two }}">
                                                    {{ $setting?->address?->content?->phone_number_two }}
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                <div class="footer-info-box">
                                    <div class="icon-box">
                                        <i class="fa-solid fa-envelope"></i>
                                    </div>
                                    <div class="footer-info-box-content">
                                        <p>
                                            <a href="mailto:{{ $setting?->address?->content?->email_address_one }}">
                                                {{ $setting?->address?->content?->email_address_one }}
                                            </a>
                                        </p>
                                    </div>
                                </div>
                                @if (!empty($setting?->address?->content?->email_address_two))
                                    <div class="footer-info-box">
                                        <div class="icon-box">
                                            <i class="fa-solid fa-envelope"></i>
                                        </div>
                                        <div class="footer-info-box-content">
                                            <p>
                                                <a href="mailto:{{ $setting?->address?->content?->email_address_two }}">
                                                    {{ $setting?->address?->content?->email_address_two }}
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                <div class="footer-info-box">
                                    <div class="icon-box">
                                        <i class="fa-solid fa-location-dot"></i>
                                    </div>
                                    <div class="footer-info-box-content">
                                        <p>{{ $setting?->address?->content?->address_content_one }}</p>
                                    </div>
                                </div>

                                @if (!empty($setting?->address?->content?->address_content_two))
                                    <div class="footer-info-box">
                                        <div class="icon-box">
                                            <i class="fa-solid fa-location-dot"></i>
                                        </div>
                                        <div class="footer-info-box-content">
                                            <p>{{ $setting?->address?->content?->address_content_two }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="{{ $col[3] }}">
                        <div class="footer-contact">
                            <h3>{{ __('custom.footer.newsletter' ?? '') }}</h3>

                            <form id="subscribe-form" data-action="{{ route('frontend.form.subscribe', ['lang' => session('locale')]) }}" method="POST" class="mw-350">
                                @csrf

                                <div class="input-group mb-3">
                                    <input type="email" name="email" class="form-control" placeholder="{{ __('custom.form.email' ?? '') }} *" aria-label="{{ __('custom.form.email' ?? '') }} *" required>
                                    <button class="btn btn-outline-secondary" type="submit" id="button-addon2">{{ __('custom.form.save' ?? '') }}</button>
                                </div>

                                <div class="input-group col-md-12 mb-3">
                                   <div class="d-flex justify-content-center mt-8 flex-wrap gap-3 fs-base fw-semibold mb-8">
                                       {!! NoCaptcha::display() !!}
                                   </div>
                               </div>
                            </form>

                            <div class="about-footer-content mw-350">
                                <p>
                                    {{ __('custom.footer.bulletin.content' ?? '') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-copyright">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-12">
                        <div class="footer-copyright-text">
                            <p>{{ __('custom.footer.copyright' ?? '') }}</p>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="footer-links cst-submenu">
                            <ul>
                                @foreach ($subMenus as $menu)
                                    <li>
                                        <a href="{{ route('frontend.detail', ['lang' => session('locale'), 'slug' => $menu?->content?->slug]) }}">
                                            {{ $menu?->content?->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
