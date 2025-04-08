<?php

declare(strict_types=1);

namespace App\Enums\Permissions;

use App\Traits\Enums\EnumValue;

enum PermissionEnum: string
{
    use EnumValue;

        // BANNER
    case BANNER_VIEW   = "banner_view";
    case BANNER_CREATE = "banner_create";
    case BANNER_UPDATE = "banner_update";
    case BANNER_DELETE = "banner_delete";

        // BRAND
    case BRAND_VIEW   = "brand_view";
    case BRAND_CREATE = "brand_create";
    case BRAND_UPDATE = "brand_update";
    case BRAND_DELETE = "brand_delete";

        // ABOUT
    case ABOUT_VIEW   = "about_view";
    case ABOUT_UPDATE = "about_update";

        // PAGE
    case PAGE_VIEW   = "page_view";
    case PAGE_CREATE = "page_create";
    case PAGE_UPDATE = "page_update";
    case PAGE_DELETE = "page_delete";

        // PAGE SECTION
    case PAGE_SECTION_VIEW   = "page_section_view";
    case PAGE_SECTION_CREATE = "page_section_create";
    case PAGE_SECTION_UPDATE = "page_section_update";
    case PAGE_SECTION_DELETE = "page_section_delete";

        // BLOG
    case BLOG_VIEW   = "blog_view";
    case BLOG_CREATE = "blog_create";
    case BLOG_UPDATE = "blog_update";
    case BLOG_DELETE = "blog_delete";

        // BLOG CATEGORY
    case BLOG_CATEGORY_VIEW   = "blog_category_view";
    case BLOG_CATEGORY_CREATE = "blog_category_create";
    case BLOG_CATEGORY_UPDATE = "blog_category_update";
    case BLOG_CATEGORY_DELETE = "blog_category_delete";

        // BLOG TAG
    case BLOG_TAG_VIEW   = "blog_tag_view";
    case BLOG_TAG_CREATE = "blog_tag_create";
    case BLOG_TAG_UPDATE = "blog_tag_update";
    case BLOG_TAG_DELETE = "blog_tag_delete";

        // BLOG COMMENT
    case BLOG_COMMENT_VIEW    = "blog_comment_view";
    case BLOG_COMMENT_UPDATE  = "blog_comment_update";
    case BLOG_COMMENT_CONFIRM = "blog_comment_confirm";
    case BLOG_COMMENT_REJECT  = "blog_comment_reject";
    case BLOG_COMMENT_DELETE  = "blog_comment_delete";

        // BLOG SUBSCRIBER
    case BLOG_SUBSCRIBER_VIEW   = "blog_subscriber_view";
    case BLOG_SUBSCRIBER_UPDATE = "blog_subscriber_update";
    case BLOG_SUBSCRIBER_DELETE = "blog_subscriber_delete";

        // SERVICE
    case SERVICE_VIEW   = "service_view";
    case SERVICE_CREATE = "service_create";
    case SERVICE_UPDATE = "service_update";
    case SERVICE_DELETE = "service_delete";

        // BUSINESS PROCESSES
    case BUSINESS_PROCESSES_VIEW   = "business_processes_view";
    case BUSINESS_PROCESSES_CREATE = "business_processes_create";
    case BUSINESS_PROCESSES_UPDATE = "business_processes_update";
    case BUSINESS_PROCESSES_DELETE = "business_processes_delete";

        // REFERENCE
    case REFERENCE_VIEW   = "reference_view";
    case REFERENCE_CREATE = "reference_create";
    case REFERENCE_UPDATE = "reference_update";
    case REFERENCE_DELETE = "reference_delete";

        // FAQ => S. S. S.
    case FAQ_VIEW   = "faq_view";
    case FAQ_CREATE = "faq_create";
    case FAQ_UPDATE = "faq_update";
    case FAQ_DELETE = "faq_delete";


    /******************** ÖZEL İÇERİKLER ********************/

    case TASK_MANAGEMENT_VIEW   = "task_management_view";
    case TASK_MANAGEMENT_CREATE = "task_management_create";
    case TASK_MANAGEMENT_UPDATE = "task_management_update";
    case TASK_MANAGEMENT_DELETE = "task_management_delete";

    case ASSIGNMENT_PERMISSION = "assignment_permission"; // atama yapma izni


    case LANGUAGE_VIEW         = "language_view";
    case LANGUAGE_CHANGE_STATUS = "language_change_status";

    case STATIC_TEXT_VIEW   = "static_text_view";
    case STATIC_TEXT_READ   = "static_text_read";
    case STATIC_TEXT_UPDATE = "static_text_update";


    case SETTINGS_VIEW = "settings_view";

    case SUBSCRIBE_CHANGE_STATUS = "subscribe_change_status";

    case GENERAL_VIEW   = "general_view";
    case GENERAL_UPDATE = "general_update";


    case ADDRESS_VIEW   = "address_view";
    case ADDRESS_UPDATE = "address_update";


    case SOCIAL_VIEW   = "social_view";
    case SOCIAL_UPDATE = "social_update";


    case LOGO_VIEW   = "logo_view";
    case LOGO_UPDATE = "logo_update";


    case PLUGIN_VIEW   = "plugin_view";
    case PLUGIN_UPDATE = "plugin_update";


    case EMAIL_VIEW = "email_view";
    case EMAIL_UPDATE = "email_update";

    /**
     * @return array
     */
    public static function getValues(): array
    {
        return [
            'Hakkımızda' => [
                [
                    'value'   => self::ABOUT_VIEW->value,
                    'title' => "Görüntüleme",
                    'view' => '<span class="bullet bg-primary me-3"></span>'
                ],
            ],
            'Hakkımızda Ayarları' => [
                [
                    'value'   => self::ABOUT_UPDATE->value,
                    'title' => "Güncelleme",
                ],
            ],


            'Banner' => [
                [
                    'value'   => self::BANNER_VIEW->value,
                    'title' => "Görüntüleme",
                    'view' => '<span class="bullet bg-primary me-3"></span>'
                ],
            ],
            'Banner Ayarları' => [
                [
                    'value'   => self::BANNER_CREATE->value,
                    'title' => "Oluşturma",
                ],
                [
                    'value'   => self::BANNER_UPDATE->value,
                    'title' => "Güncelleme",
                ],
                [
                    'value'   => self::BANNER_DELETE->value,
                    'title' => "Silme",
                ],
            ],


            'Marka' => [
                [
                    'value'   => self::BRAND_VIEW->value,
                    'title' => "Görüntüleme",
                    'view' => '<span class="bullet bg-primary me-3"></span>'
                ],
            ],
            'Marka Ayarları' => [
                [
                    'value'   => self::BRAND_CREATE->value,
                    'title' => "Oluşturma",
                ],
                [
                    'value'   => self::BRAND_UPDATE->value,
                    'title' => "Güncelleme",
                ],
                [
                    'value'   => self::BRAND_DELETE->value,
                    'title' => "Silme",
                ],
            ],


            'Sayfa' => [
                [
                    'value'   => self::PAGE_VIEW->value,
                    'title' => "Görüntüleme",
                    'view' => '<span class="bullet bg-primary me-3"></span>'
                ],
            ],
            'Sayfa Ayarları' => [
                [
                    'value'   => self::PAGE_CREATE->value,
                    'title' => "Oluşturma",
                ],
                [
                    'value'   => self::PAGE_UPDATE->value,
                    'title' => "Güncelleme",
                ],
                [
                    'value'   => self::PAGE_DELETE->value,
                    'title' => "Silme",
                ],
            ],

            'Sayfa Bölümleri' => [
                [
                    'value'   => self::PAGE_SECTION_VIEW->value,
                    'title' => "Görüntüleme",
                    'view' => '<span class="bullet bg-primary me-3"></span>'
                ],
            ],
            'Sayfa Bölümü Ayarları' => [
                [
                    'value'   => self::PAGE_SECTION_CREATE->value,
                    'title' => "Oluşturma",
                ],
                [
                    'value'   => self::PAGE_SECTION_UPDATE->value,
                    'title' => "Güncelleme",
                ],
                [
                    'value'   => self::PAGE_SECTION_DELETE->value,
                    'title' => "Silme",
                ],
            ],


            'Blog' => [
                [
                    'value'   => self::BLOG_VIEW->value,
                    'title' => "Görüntüleme",
                    'view' => '<span class="bullet bg-primary me-3"></span>'
                ],
            ],
            'Blog Ayarları' => [
                [
                    'value'   => self::BLOG_CREATE->value,
                    'title' => "Oluşturma",
                ],
                [
                    'value'   => self::BLOG_UPDATE->value,
                    'title' => "Güncelleme",
                ],
                [
                    'value'   => self::BLOG_DELETE->value,
                    'title' => "Silme",
                ],
            ],


            'Blog Kategori' => [
                [
                    'value'   => self::BLOG_CATEGORY_VIEW->value,
                    'title' => "Görüntüleme",
                    'view' => '<span class="bullet bg-primary me-3"></span>'
                ],
            ],
            'Blog Kategori Ayarları' => [
                [
                    'value'   => self::BLOG_CATEGORY_CREATE->value,
                    'title' => "Oluşturma",
                ],
                [
                    'value'   => self::BLOG_CATEGORY_UPDATE->value,
                    'title' => "Güncelleme",
                ],
                [
                    'value'   => self::BLOG_CATEGORY_DELETE->value,
                    'title' => "Silme",
                ],
            ],


            'Blog Etiktet' => [
                [
                    'value'   => self::BLOG_TAG_VIEW->value,
                    'title' => "Görüntüleme",
                    'view' => '<span class="bullet bg-primary me-3"></span>'
                ],
            ],
            'Blog Etiktet Ayarları' => [
                [
                    'value'   => self::BLOG_TAG_CREATE->value,
                    'title' => "Oluşturma",
                ],
                [
                    'value'   => self::BLOG_TAG_UPDATE->value,
                    'title' => "Güncelleme",
                ],
                [
                    'value'   => self::BLOG_TAG_DELETE->value,
                    'title' => "Silme",
                ],
            ],


            'Blog Yorumları' => [
                [
                    'value'   => self::BLOG_COMMENT_VIEW->value,
                    'title' => "Görüntüleme",
                    'view' => '<span class="bullet bg-primary me-3"></span>'
                ],
            ],
            'Blog Yorum Ayarları' => [
                [
                    'value'   => self::BLOG_COMMENT_UPDATE->value,
                    'title' => "Güncelleme",
                ],
                [
                    'value'   => self::BLOG_COMMENT_CONFIRM->value,
                    'title' => "Onaylama",
                ],
                [
                    'value'   => self::BLOG_COMMENT_REJECT->value,
                    'title' => "Reddetme",
                ],
                [
                    'value'   => self::BLOG_COMMENT_DELETE->value,
                    'title' => "Silme",
                ],
            ],


            'Blog Abonelik' => [
                [
                    'value'   => self::BLOG_SUBSCRIBER_VIEW->value,
                    'title' => "Görüntüleme",
                    'view' => '<span class="bullet bg-primary me-3"></span>'
                ],
            ],
            'Blog Abonelik Ayarları' => [
                [
                    'value'   => self::BLOG_SUBSCRIBER_UPDATE->value,
                    'title' => "Güncelleme",
                ],
                [
                    'value'   => self::BLOG_SUBSCRIBER_DELETE->value,
                    'title' => "Silme",
                ],
            ],


            'Hizmetler' => [
                [
                    'value'   => self::SERVICE_VIEW->value,
                    'title' => "Görüntüleme",
                    'view' => '<span class="bullet bg-primary me-3"></span>'
                ],
            ],
            'Hizmet Ayarları' => [
                [
                    'value'   => self::SERVICE_CREATE->value,
                    'title' => "Oluşturma",
                ],
                [
                    'value'   => self::SERVICE_UPDATE->value,
                    'title' => "Güncelleme",
                ],
                [
                    'value'   => self::SERVICE_DELETE->value,
                    'title' => "Silme",
                ],
            ],


            'Hizmet Süreçleri' => [
                [
                    'value'   => self::BUSINESS_PROCESSES_VIEW->value,
                    'title' => "Görüntüleme",
                    'view' => '<span class="bullet bg-primary me-3"></span>'
                ],
            ],
            'Hizmet Süreç Ayarları' => [
                [
                    'value'   => self::BUSINESS_PROCESSES_CREATE->value,
                    'title' => "Oluşturma",
                ],
                [
                    'value'   => self::BUSINESS_PROCESSES_UPDATE->value,
                    'title' => "Güncelleme",
                ],
                [
                    'value'   => self::BUSINESS_PROCESSES_DELETE->value,
                    'title' => "Silme",
                ],
            ],


            'Referanslar' => [
                [
                    'value'   => self::REFERENCE_VIEW->value,
                    'title' => "Görüntüleme",
                    'view' => '<span class="bullet bg-primary me-3"></span>'
                ],
            ],
            'Referans Ayarları' => [
                [
                    'value'   => self::REFERENCE_CREATE->value,
                    'title' => "Oluşturma",
                ],
                [
                    'value'   => self::REFERENCE_UPDATE->value,
                    'title' => "Güncelleme",
                ],
                [
                    'value'   => self::REFERENCE_DELETE->value,
                    'title' => "Silme",
                ],
            ],


            'S. S. S.' => [
                [
                    'value'   => self::FAQ_VIEW->value,
                    'title' => "Görüntüleme",
                    'view' => '<span class="bullet bg-primary me-3"></span>'
                ],
            ],
            'S. S. S. Ayarları' => [
                [
                    'value'   => self::FAQ_CREATE->value,
                    'title' => "Oluşturma",
                ],
                [
                    'value'   => self::FAQ_UPDATE->value,
                    'title' => "Güncelleme",
                ],
                [
                    'value'   => self::FAQ_DELETE->value,
                    'title' => "Silme",
                ],
            ],


            /* 'İş Takibi' => [
                [
                    'value'   => self::TASK_MANAGEMENT_VIEW->value,
                    'title' => "Görüntüleme",
                    'view' => '<span class="bullet bg-primary me-3"></span>'
                ],
            ],
            'İş Takip Ayarları' => [
                [
                    'value'   => self::TASK_MANAGEMENT_CREATE->value,
                    'title' => "Oluşturma",
                ],
                [
                    'value'   => self::ASSIGNMENT_PERMISSION->value,
                    'title' => "Atama",
                ],
                [
                    'value'   => self::TASK_MANAGEMENT_UPDATE->value,
                    'title' => "Güncelleme",
                ],
                [
                    'value'   => self::TASK_MANAGEMENT_DELETE->value,
                    'title' => "Silme",
                ],
            ], */

            'Dil Yönetimi' => [
                [
                    'value'   => self::LANGUAGE_VIEW->value,
                    'title' => "Görüntüleme",
                    'view' => '<span class="bullet bg-primary me-3"></span>'
                ],

            ],
            'Dil Yönetim Ayarları' => [
                [
                    'value'   => self::LANGUAGE_CHANGE_STATUS->value,
                    'title' => "Durum Değiştirme",
                ],
            ],

            'Sabit Metin' => [
                [
                    'value'   => self::STATIC_TEXT_VIEW->value,
                    'title' => "Görüntüleme",
                    'view' => '<span class="bullet bg-primary me-3"></span>'
                ],

            ],
            'Sabit Metin Ayarları' => [
                [
                    'value'   => self::STATIC_TEXT_READ->value,
                    'title' => "Okuma",
                ],
                [
                    'value'   => self::STATIC_TEXT_UPDATE->value,
                    'title' => "Güncelleme",
                ],
            ],

            'Ayarlar' => [
                [
                    'value'   => self::SETTINGS_VIEW->value,
                    'title' => "Görüntüleme",
                    'view' => '<span class="bullet bg-danger w-25px me-3"></span>'
                ],
            ],

            'Genel' => [
                [
                    'value'   => self::GENERAL_VIEW->value,
                    'title' => "Görüntüleme",
                    'view' => '<span class="bullet bg-primary me-3"></span>'
                ],
                [
                    'value'   => self::GENERAL_UPDATE->value,
                    'title' => "Güncelleme",
                ],
            ],

            'Abonelik' => [
                [
                    'value'   => self::SUBSCRIBE_CHANGE_STATUS->value,
                    'title' => "Durum Değiştirme",
                    'view' => '<span class="bullet bg-primary me-3"></span>'
                ],
            ],

            'Adres' => [
                [
                    'value'   => self::ADDRESS_VIEW->value,
                    'title' => "Görüntüleme",
                    'view' => '<span class="bullet bg-primary me-3"></span>'
                ],
                [
                    'value'   => self::ADDRESS_UPDATE->value,
                    'title' => "Güncelleme",
                ],
            ],

            'Sosyal Medya' => [
                [
                    'value'   => self::SOCIAL_VIEW->value,
                    'title' => "Görüntüleme",
                    'view' => '<span class="bullet bg-primary me-3"></span>'
                ],
                [
                    'value'   => self::SOCIAL_UPDATE->value,
                    'title' => "Güncelleme",
                ],
            ],

            'Logo' => [
                [
                    'value'   => self::LOGO_VIEW->value,
                    'title' => "Görüntüleme",
                    'view' => '<span class="bullet bg-primary me-3"></span>'
                ],
                [
                    'value'   => self::LOGO_UPDATE->value,
                    'title' => "Güncelleme",
                ],
            ],

            'Eklenti' => [
                [
                    'value'   => self::PLUGIN_VIEW->value,
                    'title' => "Görüntüleme",
                    'view' => '<span class="bullet bg-primary me-3"></span>'
                ],
                [
                    'value'   => self::PLUGIN_UPDATE->value,
                    'title' => "Güncelleme",
                ],
            ],

            'E-Mail' => [
                [
                    'value'   => self::EMAIL_VIEW->value,
                    'title' => "Görüntüleme",
                    'view' => '<span class="bullet bg-primary me-3"></span>'
                ],
                [
                    'value'   => self::EMAIL_UPDATE->value,
                    'title' => "Güncelleme",
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    public static function fileManagerPermissions(): string
    {
        return self::ABOUT_UPDATE->value . '|' .
            self::SERVICE_CREATE->value . '|' .
            self::SERVICE_UPDATE->value . '|' .
            self::REFERENCE_CREATE->value . '|' .
            self::REFERENCE_UPDATE->value . '|' .
            self::BLOG_CREATE->value . '|' .
            self::BLOG_UPDATE->value;
    }
}
