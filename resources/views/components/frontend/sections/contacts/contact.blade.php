<div class="page-contact-us cst-p50">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="contact-us-content">

                    <div class="section-title">
                        <h3>{!! $section?->content?->heading !!}</h3>
                        <h2 class="text-anime-style-2" data-cursor="-opaque">
                            {!! $section?->content?->sub_heading !!}
                        </h2>
                    </div>

                    <div class="contact-info-list">
                        @if (!empty($setting?->address?->content?->phone_number_one) || !empty($setting?->address?->content?->phone_number_two))
                            <div class="contact-info-item wow fadeInUp">
                                <div class="icon-box">
                                    <img src="{{ asset('frontend/images/icon-phone.svg') }}" alt="{{ $setting?->address?->content?->phone_title_one }}">
                                </div>
                                <div class="contact-info-content">
                                    <h3>{{ $setting?->address?->content?->phone_title_one }}</h3>
                                    <p>{{ $setting?->address?->content?->phone_number_one }}</p>
                                    @if (!empty($setting?->address?->content?->phone_number_two))
                                        <p>{{ $setting?->address?->content?->phone_number_two }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="contact-info-item wow fadeInUp" data-wow-delay="0.25s">
                            <div class="icon-box">
                                <img src="{{ asset('frontend/images/icon-mail.svg') }}" alt="{{ $setting?->address?->content?->email_title_one }}">
                            </div>
                            <div class="contact-info-content">
                                <h3>{{ $setting?->address?->content?->email_title_one }}</h3>
                                <p>{{ $setting?->address?->content?->email_address_one }}</p>
                                @if (!empty($setting?->address?->content?->email_address_two))
                                    <p>{{ $setting?->address?->content?->email_address_two }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="contact-us-form">
                    <div class="section-title">
                        <h2 class="text-anime-style-2" data-cursor="-opaque">
                            {!! $section?->content?->description !!}
                        </h2>
                    </div>

                    <div class="contact-form">
                        <form id="contact-form" data-action="{{ route('frontend.form.contact', ['lang' => session('locale')]) }}" method="POST" class="wow fadeInUp" data-wow-delay="0.25s">
                            @csrf

                            <div class="row">
                                <div class="form-group col-md-6 mb-4">
                                    <label class="form-label">{{ __('custom.form.name' ?? '') }}</label>
                                    <input type="text" name="name" class="form-control" id="name" placeholder="{{ __('custom.form.name' ?? '') }} *" required>
                                </div>

                                <div class="form-group col-md-6 mb-4">
                                    <label class="form-label">{{ __('custom.form.email' ?? '') }}</label>
                                    <input type="email" name ="email" class="form-control" id="email" placeholder="{{ __('custom.form.email' ?? '') }} *" required>
                                </div>

                                <div class="form-group col-md-6 mb-4">
                                    <label class="form-label">{{ __('custom.form.subject' ?? '') }}</label>
                                    <input type="text" name="subject" class="form-control" id="subject" placeholder="{{ __('custom.form.subject' ?? '') }} *" required>
                                </div>

                                <div class="form-group col-md-6 mb-4">
                                    <label class="form-label">{{ __('custom.form.phone' ?? '') }}</label>
                                    <input type="text" name="phone" class="form-control" id="phone" placeholder="{{ __('custom.form.phone' ?? '') }}">
                                </div>

                                <div class="form-group col-md-12 mb-4">
                                    <label class="form-label">{{ __('custom.form.message' ?? '') }}</label>
                                    <textarea name="message" class="form-control" id="cst-contact-form-message-1" rows="4" placeholder="{{ __('custom.form.message' ?? '') }} *" minlength="1" maxlength="1000" required></textarea>
                                </div>

                                <div class="form-group col-md-12 mb-4 text-center">
                                    <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                                    <label class="form-check-label" for="terms">
                                        <a class="cst-form-terms" href="{{ route('frontend.detail', ['lang' => session('locale'), 'slug' => $section?->page?->content?->slug]) }}">
                                            {!! $section?->content?->short_description !!}
                                        </a>
                                    </label>
                                </div>

                                <div class="form-group col-md-12 mb-4">
                                    <div class="d-flex justify-content-center mt-8 flex-wrap gap-3 fs-base fw-semibold mb-8">
                                        {!! NoCaptcha::display() !!}
                                    </div>
                                </div>

                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn-default">{{ $section?->content?->button_title }}</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<div class="google-map cst-p50">
    <div class="container">
        <div class="row">
            <div class="{{ !empty($setting?->address?->content?->address_iframe_two) ? 'col-lg-6' : 'col-lg-12' }}">
                <div class="contact-info-list mb-7">
                    <div class="contact-info-item wow fadeInUp" data-wow-delay="0.5s">
                        <div class="icon-box">
                            <img src="{{ asset('frontend/images/icon-location.svg') }}" alt="{{ $setting?->address?->content?->address_title_one }}">
                        </div>
                        <div class="contact-info-content">
                            <h3>{{ $setting?->address?->content?->address_title_one }}</h3>
                            <p>{{ $setting?->address?->content?->address_content_one }}</p>
                        </div>
                    </div>
                </div>

                <div class="google-map-iframe">
                    {!! $setting?->address?->content?->address_iframe_one !!}
                </div>
            </div>

            @if (!empty($setting?->address?->content?->address_iframe_two))
                <div class="col-lg-6">
                    @if (!empty($setting?->address?->content?->address_title_two))
                        <div class="contact-info-list mb-7">
                            <div class="contact-info-item wow fadeInUp" data-wow-delay="0.5s">
                                <div class="icon-box">
                                    <img src="{{ asset('frontend/images/icon-location.svg') }}" alt="{{ $setting?->address?->content?->address_title_two }}">
                                </div>
                                <div class="contact-info-content">
                                    <h3>{{ $setting?->address?->content?->address_title_two }}</h3>
                                    <p>{{ $setting?->address?->content?->address_content_two }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="google-map-iframe">
                        {!! $setting?->address?->content?->address_iframe_two !!}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
