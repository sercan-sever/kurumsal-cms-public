@use(App\Enums\Permissions\PermissionEnum)

<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">

    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
        <a href="{{ route('admin.home') }}">
            <img alt="Logo" src="{{ asset('backend/assets/media/logos/localkod-white.png') }}" class="h-50px app-sidebar-logo-default" />
            <img alt="Logo" src="{{ asset('backend/assets/media/logos/localkod-white.png') }}" class="h-20px app-sidebar-logo-minimize" />
        </a>
        <div id="kt_app_sidebar_toggle" class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary body-bg h-30px w-30px position-absolute top-50 start-100 translate-middle rotate" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="app-sidebar-minimize">
            <span class="svg-icon svg-icon-2 rotate-180">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path opacity="0.5" d="M14.2657 11.4343L18.45 7.25C18.8642 6.83579 18.8642 6.16421 18.45 5.75C18.0358 5.33579 17.3642 5.33579 16.95 5.75L11.4071 11.2929C11.0166 11.6834 11.0166 12.3166 11.4071 12.7071L16.95 18.25C17.3642 18.6642 18.0358 18.6642 18.45 18.25C18.8642 17.8358 18.8642 17.1642 18.45 16.75L14.2657 12.5657C13.9533 12.2533 13.9533 11.7467 14.2657 11.4343Z" fill="currentColor" />
                    <path d="M8.2657 11.4343L12.45 7.25C12.8642 6.83579 12.8642 6.16421 12.45 5.75C12.0358 5.33579 11.3642 5.33579 10.95 5.75L5.40712 11.2929C5.01659 11.6834 5.01659 12.3166 5.40712 12.7071L10.95 18.25C11.3642 18.6642 12.0358 18.6642 12.45 18.25C12.8642 17.8358 12.8642 17.1642 12.45 16.75L8.2657 12.5657C7.95328 12.2533 7.95328 11.7467 8.2657 11.4343Z" fill="currentColor" />
                </svg>
            </span>
        </div>
    </div>

    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">

        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer" data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
            <div class="menu menu-column menu-rounded menu-sub-indention px-3" id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">

                <div class="menu-item">
                    <a class="menu-link {{ getSidebarActive('lk-admin/dashboard') }}" href="{{ route('admin.home') }}">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <i class="fa-solid fa-chart-simple fs-3"></i>
                            </span>
                        </span>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </div>


                @can(PermissionEnum::BANNER_VIEW)
                    <div class="menu-item">
                        <a class="menu-link {{ getSidebarActive('lk-admin/banner') }}" href="{{ route('admin.banner') }}">
                            <span class="menu-icon">
                                <span class="svg-icon svg-icon-2">
                                    <i class="fas fa-images fs-3"></i>
                                </span>
                            </span>
                            <span class="menu-title">Banner</span>
                        </a>
                    </div>
                @endcan


                @can(PermissionEnum::ABOUT_VIEW)
                    <div class="menu-item">
                        <a class="menu-link {{ getSidebarActive('lk-admin/about') }}" href="{{ route('admin.about') }}">
                            <span class="menu-icon">
                                <span class="svg-icon svg-icon-2">
                                    <i class="fa-solid fa-address-card fs-3"></i>
                                </span>
                            </span>
                            <span class="menu-title">Hakkımızda</span>
                        </a>
                    </div>
                @endcan


                @can(PermissionEnum::BRAND_VIEW)
                    <div class="menu-item">
                        <a class="menu-link {{ getSidebarActive('lk-admin/brands') }}" href="{{ route('admin.brand') }}">
                            <span class="menu-icon">
                                <span class="svg-icon svg-icon-2">
                                    <i class="fa-brands fa-slack fs-3"></i>
                                </span>
                            </span>
                            <span class="menu-title">Markalar</span>
                        </a>
                    </div>
                @endcan


                @can(PermissionEnum::SERVICE_VIEW)
                    <div class="menu-item">
                        <a class="menu-link {{ getSidebarActive('lk-admin/service') }}" href="{{ route('admin.service') }}">
                            <span class="menu-icon">
                                <span class="svg-icon svg-icon-2">
                                    <i class="fa-solid fa-briefcase fs-3"></i>
                                </span>
                            </span>
                            <span class="menu-title">Hizmetler</span>
                        </a>
                    </div>
                @endcan


                @can(PermissionEnum::REFERENCE_VIEW)
                    <div class="menu-item">
                        <a class="menu-link {{ getSidebarActive('lk-admin/reference') }}" href="{{ route('admin.reference') }}">
                            <span class="menu-icon">
                                <span class="svg-icon svg-icon-2">
                                    <i class="fa-solid fa-icons fs-3"></i>
                                </span>
                            </span>
                            <span class="menu-title">Referanslar</span>
                        </a>
                    </div>
                @endcan



                @can(PermissionEnum::BUSINESS_PROCESSES_VIEW)
                    <div class="menu-item">
                        <a class="menu-link {{ getSidebarActive('lk-admin/business-processes') }}" href="{{ route('admin.business.processes') }}">
                            <span class="menu-icon">
                                <span class="svg-icon svg-icon-2">
                                    <i class="fa-solid fa-business-time fs-3"></i>
                                </span>
                            </span>
                            <span class="menu-title">Hizmet Süreçlerimiz</span>
                        </a>
                    </div>
                @endcan


                @can(PermissionEnum::FAQ_VIEW)
                    <div class="menu-item">
                        <a class="menu-link {{ getSidebarActive('lk-admin/faq') }}" href="{{ route('admin.faq') }}">
                            <span class="menu-icon">
                                <span class="svg-icon svg-icon-2">
                                    <i class="fa-solid fa-circle-question fs-3"></i>
                                </span>
                            </span>
                            <span class="menu-title">S. S. S.</span>
                        </a>
                    </div>
                @endcan


                @can([PermissionEnum::BLOG_CATEGORY_VIEW, PermissionEnum::BLOG_TAG_VIEW, PermissionEnum::BLOG_VIEW, PermissionEnum::BLOG_COMMENT_VIEW, PermissionEnum::BLOG_SUBSCRIBER_VIEW])
                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ !empty(getSidebarActive('lk-admin/blogs*')) ? 'hover show' : '' }}">

                        <span class="menu-link">
                            <span class="menu-icon">
                                <span class="svg-icon svg-icon-2">
                                    <i class="fas fa-blog fs-3"></i>
                                </span>
                            </span>
                            <span class="menu-title">Blog</span>
                            <span class="menu-arrow"></span>
                        </span>

                        <div class="menu-sub menu-sub-accordion {{ !empty(getSidebarActive('lk-admin/blogs*')) ? 'hover' : '' }}" kt-hidden-height="242">

                            @can(PermissionEnum::BLOG_CATEGORY_VIEW)
                                <div class="menu-item">
                                    <a class="menu-link {{ getSidebarActive('lk-admin/blogs/category') }}" href="{{ route('admin.blog.category') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Kategoriler</span>
                                    </a>
                                </div>
                            @endcan

                            @can(PermissionEnum::BLOG_TAG_VIEW)
                                <div class="menu-item">
                                    <a class="menu-link {{ getSidebarActive('lk-admin/blogs/tag') }}" href="{{ route('admin.blog.tag') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Etiketler</span>
                                    </a>
                                </div>
                            @endcan

                            @can(PermissionEnum::BLOG_VIEW)
                                <div class="menu-item">
                                    <a class="menu-link {{ getSidebarActive('lk-admin/blogs') }}" href="{{ route('admin.blog') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Yazılar</span>
                                    </a>
                                </div>
                            @endcan

                            @can(PermissionEnum::BLOG_COMMENT_VIEW)
                                <div class="menu-item">
                                    <a class="menu-link {{ getSidebarActive('lk-admin/blogs/comment') }}" href="{{ route('admin.blog.comment') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Yorumlar</span>
                                    </a>
                                </div>
                            @endcan

                            @can(PermissionEnum::BLOG_SUBSCRIBER_VIEW)
                                <div class="menu-item">
                                    <a class="menu-link {{ getSidebarActive('lk-admin/blogs/subscribe') }}" href="{{ route('admin.blog.subscribe') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Aboneler</span>
                                    </a>
                                </div>
                            @endcan
                        </div>
                    </div>
                @endcan


                @can([PermissionEnum::PAGE_VIEW, PermissionEnum::PAGE_VIEW])
                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ !empty(getSidebarActive('lk-admin/pages*')) ? 'hover show' : '' }}">

                        <span class="menu-link">
                            <span class="menu-icon">
                                <span class="svg-icon svg-icon-2">
                                    <i class="fa-solid fa-pager fs-3"></i>
                                </span>
                            </span>
                            <span class="menu-title">Sayfalar</span>
                            <span class="menu-arrow"></span>
                        </span>

                        <div class="menu-sub menu-sub-accordion {{ !empty(getSidebarActive('lk-admin/pages*')) ? 'hover' : '' }}" kt-hidden-height="242">

                            @can(PermissionEnum::PAGE_VIEW)
                                <div class="menu-item">
                                    <a class="menu-link {{ getSidebarActive('lk-admin/pages') }}" href="{{ route('admin.pages') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Sayfalar</span>
                                    </a>
                                </div>
                            @endcan

                            @can(PermissionEnum::PAGE_VIEW)
                                <div class="menu-item">
                                    <a class="menu-link {{ getSidebarActive('lk-admin/pages/sections') }}" href="{{ route('admin.pages.section') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Bölümler</span>
                                    </a>
                                </div>
                            @endcan
                        </div>
                    </div>
                @endcan

                @can(PermissionEnum::SETTINGS_VIEW)
                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ !empty(getSidebarActive('lk-admin/settings*')) ? 'hover show' : '' }}">

                        <span class="menu-link">
                            <span class="menu-icon">
                                <span class="svg-icon svg-icon-2">
                                    <i class="fa-solid fa-gear fs-3"></i>
                                </span>
                            </span>
                            <span class="menu-title">Ayarlar</span>
                            <span class="menu-arrow"></span>
                        </span>

                        <div class="menu-sub menu-sub-accordion {{ !empty(getSidebarActive('lk-admin/settings*')) ? 'hover' : '' }}" kt-hidden-height="242">

                            <div class="menu-item {{ getSidebarActive('lk-admin/settings*') }}">
                                <a class="menu-link {{ !empty(getSidebarActive('lk-admin/settings')) ? 'active' : '' }}" href="{{ route('admin.setting') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">Tüm Ayarlar</span>
                                </a>
                            </div>

                            @can(PermissionEnum::GENERAL_VIEW)
                                <div class="menu-item">
                                    <a class="menu-link {{ getSidebarActive('lk-admin/settings/general') }}" href="{{ route('admin.setting.general') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Genel</span>
                                    </a>
                                </div>
                            @endcan

                            @can(PermissionEnum::ADDRESS_VIEW)
                                <div class="menu-item">
                                    <a class="menu-link {{ getSidebarActive('lk-admin/settings/address') }}" href="{{ route('admin.setting.address') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Adres</span>
                                    </a>
                                </div>
                            @endcan

                            @can(PermissionEnum::SOCIAL_VIEW)
                                <div class="menu-item">
                                    <a class="menu-link {{ getSidebarActive('lk-admin/settings/social') }}" href="{{ route('admin.setting.social') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Sosyal Medya</span>
                                    </a>
                                </div>
                            @endcan

                            @can(PermissionEnum::LOGO_VIEW)
                                <div class="menu-item">
                                    <a class="menu-link {{ getSidebarActive('lk-admin/settings/logo') }}" href="{{ route('admin.setting.logo') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Logo</span>
                                    </a>
                                </div>
                            @endcan

                            @can(PermissionEnum::PLUGIN_VIEW)
                                <div class="menu-item">
                                    <a class="menu-link {{ getSidebarActive('lk-admin/settings/plugin') }}" href="{{ route('admin.setting.plugin') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Eklenti</span>
                                    </a>
                                </div>
                            @endcan

                            @can(PermissionEnum::EMAIL_VIEW)
                                <div class="menu-item">
                                    <a class="menu-link {{ getSidebarActive('lk-admin/settings/email') }}" href="{{ route('admin.setting.email') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">E-Mail</span>
                                    </a>
                                </div>
                            @endcan
                        </div>
                    </div>
                @endcan


                <div class="menu-item">
                    <a class="menu-link {{ getSidebarActive('lk-admin/user-managements') }}" href="{{ route('admin.user.management') }}">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <i class="fa-solid fa-user-shield fs-3"></i>
                            </span>
                        </span>
                        <span class="menu-title">Yetkili Yönetimi</span>
                    </a>
                </div>


                @can(PermissionEnum::LANGUAGE_VIEW)
                    <div class="menu-item">
                        <a class="menu-link {{ !empty(getSidebarActive('lk-admin/languages*')) ? getSidebarActive('lk-admin/languages*') : getSidebarActive('lk-admin/translation*') }}" href="{{ route('admin.language') }}">
                            <span class="menu-icon">
                                <span class="svg-icon svg-icon-2">
                                    <i class="fa-solid fa-globe fs-3"></i>
                                </span>
                            </span>
                            <span class="menu-title">Dil Yönetimi</span>
                        </a>
                    </div>
                @endcan
            </div>
        </div>
    </div>

    <div class="app-sidebar-footer flex-column-auto pt-2 pb-6 px-6" id="kt_app_sidebar_footer">
        <a href="mailto:info@localkod.com" class="btn btn-flex flex-center btn-custom btn-primary overflow-hidden text-nowrap px-0 h-40px w-100" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss-="click" title="Takıldığınız Durumlarda Bize Ulaşmaktan Çekinmeyiniz 😊">
            <span class="btn-label">İletişime Geç</span>
            <span class="svg-icon btn-icon svg-icon-2 m-0">
                <i class="fa-solid fa-envelope fs-3 p-0"></i>
            </span>
        </a>
    </div>
</div>
