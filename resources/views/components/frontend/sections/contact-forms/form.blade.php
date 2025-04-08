<div class="page-team-single cst-p50">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="team-single-box">
                    <div class="team-contact-form">
                        <div class="section-title">
                            <h2 class="text-anime-style-2" data-cursor="-opaque">
                                {!! $section?->content?->heading !!}
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
                                        <input type="text" name="subject" class="form-control" value="{{ $subject ?? '' }}" id="subject" placeholder="{{ __('custom.form.subject' ?? '') }} *" required>
                                    </div>

                                    <div class="form-group col-md-6 mb-4">
                                        <label class="form-label">{{ __('custom.form.phone' ?? '') }}</label>
                                        <input type="text" name="phone" class="form-control" id="phone" placeholder="{{ __('custom.form.phone' ?? '') }}">
                                    </div>

                                    <div class="form-group col-md-12 mb-4">
                                        <label class="form-label">{{ __('custom.form.message' ?? '') }}</label>
                                        <textarea name="message" class="form-control" id="cst-contact-form-message" rows="4" placeholder="{{ __('custom.form.message' ?? '') }} *" minlength="1" maxlength="1000" required></textarea>
                                    </div>

                                    <div class="form-group col-md-12 mb-4 text-center">
                                        <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                                        <label class="form-check-label" for="terms">
                                            <a class="cst-form-terms" href="{{ route('frontend.detail', ['lang' => session('locale'), 'slug' => $section?->page?->content?->slug]) }}">
                                                {!! $section?->content?->description !!}
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
</div>

