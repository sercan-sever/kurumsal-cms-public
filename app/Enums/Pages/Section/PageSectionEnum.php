<?php

declare(strict_types=1);

namespace App\Enums\Pages\Section;

use App\Models\Page;
use App\Models\Section;
use App\Traits\Enums\EnumValue;

/***** Frontend ******/
use App\Services\Frontend\Sections\AboutService;
use App\Services\Frontend\Sections\BannerService;
use App\Services\Frontend\Sections\BlogService;
use App\Services\Frontend\Sections\BrandService;
use App\Services\Frontend\Sections\BusinessProcessesService;
use App\Services\Frontend\Sections\ContactFormService;
use App\Services\Frontend\Sections\ContactService;
use App\Services\Frontend\Sections\DynamicService;
use App\Services\Frontend\Sections\FaqService;
use App\Services\Frontend\Sections\FooterService;
use App\Services\Frontend\Sections\MissionVisionService;
use App\Services\Frontend\Sections\PrivacyPolicyService;
use App\Services\Frontend\Sections\ReferenceService;
use App\Services\Frontend\Sections\ServiceManager;
use App\Services\Frontend\Sections\TermService;
use App\Services\Frontend\Sections\WeAreService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

enum PageSectionEnum: string
{
    use EnumValue;

    case BANNER             = 'banner';

    case WHO_WE_ARE         = 'who_we_are';
    case WHO_WE_ARE_TWO     = 'who_we_are_two';

    case ABOUT              = 'about';
    case ABOUT_TWO          = 'about_two';

    case MISSION_VISION     = 'mission_vision';
    case MISSION_VISION_TWO = 'mission_vision_two';

    case SERVICE            = 'service';
    case SERVICE_GRID       = 'service_grid';
    case SERVICE_SLIDER     = 'service_slider';

    case BUSINESS_PROCESSES = 'business_processes';

    case REFERENCE          = 'reference';
    case REFERENCE_GRID     = 'reference_grid';
    case REFERENCE_SLIDER   = 'reference_slider';

    case BLOG               = 'blog';
    case BLOG_LATEST        = 'blog_latest';

    case BRAND              = 'brand';
    case BRAND_SLIDER       = 'brand_slider';

    case FAQ                = 'faq';
    case FAQ_TWO            = 'faq_two';

    case CONTACT_FORM       = 'contact_form';

    case CONTACT            = 'contact';
    case CONTACT_TWO        = 'contact_two';

    case FOOTER             = 'footer';
    case FOOTER_TWO         = 'footer_two';

    case PRIVACY_POLICY     = 'privacy_policy';
    case TERMS_CONDITION    = 'terms_condition';

    case DYNAMIC            = 'dynamic';
    case DYNAMIC_IMAGE      = 'dynamic_image';
    case DYNAMIC_RIGHT      = 'dynamic_right';
    case DYNAMIC_LEFT       = 'dynamic_left';


    /**
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::DYNAMIC            => "Dinamik İçerik",
            self::DYNAMIC_IMAGE      => "Dinamik İçerik",
            self::DYNAMIC_RIGHT      => "Dinamik İçerik",
            self::DYNAMIC_LEFT       => "Dinamik İçerik",

            self::BANNER             => 'Banner',

            self::WHO_WE_ARE         => 'Biz Kimiz',
            self::WHO_WE_ARE_TWO     => 'Biz Kimiz 2',

            self::ABOUT              => 'Hakkımızda',
            self::ABOUT_TWO          => 'Hakkımızda 2',

            self::MISSION_VISION     => 'Misyon & Vizyon',
            self::MISSION_VISION_TWO => 'Misyon & Vizyon 2',

            self::SERVICE            => 'Hizmetler',
            self::SERVICE_GRID       => 'Hizmetler Izgara',
            self::SERVICE_SLIDER     => 'Hizmetler Slayt',

            self::BUSINESS_PROCESSES => 'Hizmet Süreci',

            self::REFERENCE          => 'Referanslar',
            self::REFERENCE_GRID     => 'Referanslar Izgara',
            self::REFERENCE_SLIDER   => 'Referanslar Slayt',

            self::BLOG               => 'Bloglar',
            self::BLOG_LATEST        => 'Son Eklenen Bloglar',

            self::BRAND              => 'Markalar',
            self::BRAND_SLIDER       => 'Markalar Slayt',

            self::FAQ                => 'Sıkça Sorulan Sorular',
            self::FAQ_TWO            => 'Sıkça Sorulan Sorular 2',

            self::CONTACT_FORM       => 'İletişim Formu',

            self::CONTACT            => 'İletişim',
            self::CONTACT_TWO        => 'İletişim 2',

            self::FOOTER             => 'Alt Bilgi',
            self::FOOTER_TWO         => 'Alt Bilgi 2',

            self::PRIVACY_POLICY     => 'Gizlilik Politikası',
            self::TERMS_CONDITION    => 'Şartlar ve Koşullar',

            default => '',
        };
    }

    /**
     * @return string
     */
    public function category(): string
    {
        return match ($this) {
            self::DYNAMIC            => "İçerik",
            self::DYNAMIC_IMAGE      => "İçerik ( Görsel )",
            self::DYNAMIC_RIGHT      => "İçerik + Görsel Sağ",
            self::DYNAMIC_LEFT       => "İçerik + Görsel Sol",

            self::BANNER             => 'Banner',

            self::WHO_WE_ARE         => 'Hakkımızda',
            self::WHO_WE_ARE_TWO     => 'Hakkımızda',

            self::ABOUT              => 'Hakkımızda',
            self::ABOUT_TWO          => 'Hakkımızda',

            self::MISSION_VISION     => 'Hakkımızda',
            self::MISSION_VISION_TWO => 'Hakkımızda',

            self::SERVICE            => 'Hizmet',
            self::SERVICE_GRID       => 'Hizmet',
            self::SERVICE_SLIDER     => 'Hizmet',

            self::BUSINESS_PROCESSES => 'Hizmet Süreç',

            self::REFERENCE          => 'Referans',
            self::REFERENCE_GRID     => 'Referans',
            self::REFERENCE_SLIDER   => 'Referans',

            self::BLOG               => 'Blog',
            self::BLOG_LATEST        => 'Blog',

            self::BRAND              => 'Marka',
            self::BRAND_SLIDER       => 'Marka',

            self::FAQ                => 'Sıkça Sorulan Sorular',
            self::FAQ_TWO            => 'Sıkça Sorulan Sorular',

            self::CONTACT_FORM       => 'İletişim Formu',

            self::CONTACT            => 'İletişim',
            self::CONTACT_TWO        => 'İletişim',

            self::FOOTER             => 'Alt Bilgi',
            self::FOOTER_TWO         => 'Alt Bilgi',

            self::PRIVACY_POLICY     => 'Gizlilik Politikası',
            self::TERMS_CONDITION    => 'Şartlar ve Koşullar',

            default => '',
        };
    }

    /**
     * @param string|null $section
     *
     * @return string
     */
    public static function getSectionName(?string $section): string
    {
        return match ($section) {
            self::DYNAMIC->value            => self::DYNAMIC->label(),
            self::DYNAMIC_IMAGE->value      => self::DYNAMIC_IMAGE->label(),
            self::DYNAMIC_RIGHT->value      => self::DYNAMIC_RIGHT->label(),
            self::DYNAMIC_LEFT->value       => self::DYNAMIC_LEFT->label(),

            self::BANNER->value             => self::BANNER->label(),

            self::WHO_WE_ARE->value         => self::WHO_WE_ARE->label(),
            self::WHO_WE_ARE_TWO->value     => self::WHO_WE_ARE_TWO->label(),

            self::ABOUT->value              => self::ABOUT->label(),
            self::ABOUT_TWO->value          => self::ABOUT_TWO->label(),

            self::MISSION_VISION->value     => self::MISSION_VISION->label(),
            self::MISSION_VISION_TWO->value => self::MISSION_VISION_TWO->label(),

            self::SERVICE->value            => self::SERVICE->label(),
            self::SERVICE_GRID->value       => self::SERVICE_GRID->label(),
            self::SERVICE_SLIDER->value     => self::SERVICE_SLIDER->label(),

            self::BUSINESS_PROCESSES->value => self::BUSINESS_PROCESSES->label(),

            self::REFERENCE->value          => self::REFERENCE->label(),
            self::REFERENCE_GRID->value     => self::REFERENCE_GRID->label(),
            self::REFERENCE_SLIDER->value   => self::REFERENCE_SLIDER->label(),

            self::BLOG->value               => self::BLOG->label(),
            self::BLOG_LATEST->value        => self::BLOG_LATEST->label(),

            self::BRAND->value              => self::BRAND->label(),
            self::BRAND_SLIDER->value       => self::BRAND_SLIDER->label(),

            self::FAQ->value                => self::FAQ->label(),
            self::FAQ_TWO->value            => self::FAQ_TWO->label(),

            self::CONTACT_FORM->value       => self::CONTACT_FORM->label(),

            self::CONTACT->value            => self::CONTACT->label(),
            self::CONTACT_TWO->value        => self::CONTACT_TWO->label(),

            self::FOOTER->value             => self::FOOTER->label(),
            self::FOOTER_TWO->value         => self::FOOTER_TWO->label(),

            self::PRIVACY_POLICY->value     => self::PRIVACY_POLICY->label(),
            self::TERMS_CONDITION->value    => self::TERMS_CONDITION->label(),

            default => '',
        };
    }

    /**
     * @param string|null $section
     *
     * @return string
     */
    public static function getSectionCategoryName(?string $section): string
    {
        return match ($section) {
            self::DYNAMIC->value            => self::DYNAMIC->category(),
            self::DYNAMIC_IMAGE->value      => self::DYNAMIC_IMAGE->category(),
            self::DYNAMIC_RIGHT->value      => self::DYNAMIC_RIGHT->category(),
            self::DYNAMIC_LEFT->value       => self::DYNAMIC_LEFT->category(),

            self::BANNER->value             => self::BANNER->category(),

            self::WHO_WE_ARE->value         => self::WHO_WE_ARE->category(),
            self::WHO_WE_ARE_TWO->value     => self::WHO_WE_ARE_TWO->category(),

            self::ABOUT->value              => self::ABOUT->category(),
            self::ABOUT_TWO->value          => self::ABOUT_TWO->category(),

            self::MISSION_VISION->value     => self::MISSION_VISION->category(),
            self::MISSION_VISION_TWO->value => self::MISSION_VISION_TWO->category(),

            self::SERVICE->value            => self::SERVICE->category(),
            self::SERVICE_GRID->value       => self::SERVICE_GRID->category(),
            self::SERVICE_SLIDER->value     => self::SERVICE_SLIDER->category(),

            self::BUSINESS_PROCESSES->value => self::BUSINESS_PROCESSES->category(),

            self::REFERENCE->value          => self::REFERENCE->category(),
            self::REFERENCE_GRID->value     => self::REFERENCE_GRID->category(),
            self::REFERENCE_SLIDER->value   => self::REFERENCE_SLIDER->category(),

            self::BLOG->value               => self::BLOG->category(),
            self::BLOG_LATEST->value        => self::BLOG_LATEST->category(),

            self::BRAND->value              => self::BRAND->category(),
            self::BRAND_SLIDER->value       => self::BRAND_SLIDER->category(),

            self::FAQ->value                => self::FAQ->category(),
            self::FAQ_TWO->value            => self::FAQ_TWO->category(),

            self::CONTACT_FORM->value       => self::CONTACT_FORM->category(),

            self::CONTACT->value            => self::CONTACT->category(),
            self::CONTACT_TWO->value        => self::CONTACT_TWO->category(),

            self::FOOTER->value             => self::FOOTER->category(),
            self::FOOTER_TWO->value         => self::FOOTER_TWO->category(),

            self::PRIVACY_POLICY->value     => self::PRIVACY_POLICY->category(),
            self::TERMS_CONDITION->value    => self::TERMS_CONDITION->category(),

            default => '',
        };
    }

    /**
     * @param Section|null $section
     * @param Collection $pages
     * @param bool|null $disabled
     *
     * @return string
     */
    public static function getSectionSettingView(?Section $section, Collection $pages, ?bool $disabled = false): string
    {
        return match ($section?->section_type) {
            self::DYNAMIC            => view('components.backend.sections.dynamics.update-dynamic', compact('section', 'pages', 'disabled'))->render(),
            self::DYNAMIC_IMAGE      => view('components.backend.sections.dynamics.update-image-dynamic', compact('section', 'pages', 'disabled'))->render(),
            self::DYNAMIC_RIGHT      => view('components.backend.sections.dynamics.update-right-left-dynamic', compact('section', 'pages', 'disabled'))->render(),
            self::DYNAMIC_LEFT       => view('components.backend.sections.dynamics.update-right-left-dynamic', compact('section', 'pages', 'disabled'))->render(),

            self::BANNER             => view('components.backend.sections.defaults.banner', compact('section', 'pages', 'disabled'))->render(),

            self::WHO_WE_ARE         => view('components.backend.sections.defaults.weare', compact('section', 'pages', 'disabled'))->render(),
            self::WHO_WE_ARE_TWO     => view('components.backend.sections.defaults.weare', compact('section', 'pages', 'disabled'))->render(),

            self::ABOUT              => view('components.backend.sections.defaults.about', compact('section', 'pages', 'disabled'))->render(),
            self::ABOUT_TWO          => view('components.backend.sections.defaults.about', compact('section', 'pages', 'disabled'))->render(),

            self::MISSION_VISION     => view('components.backend.sections.defaults.mission-vision', compact('section', 'pages', 'disabled'))->render(),
            self::MISSION_VISION_TWO => view('components.backend.sections.defaults.mission-vision', compact('section', 'pages', 'disabled'))->render(),

            self::SERVICE            => view('components.backend.sections.defaults.service', compact('section', 'pages', 'disabled'))->render(),
            self::SERVICE_GRID       => view('components.backend.sections.defaults.service', compact('section', 'pages', 'disabled'))->render(),
            self::SERVICE_SLIDER     => view('components.backend.sections.defaults.service', compact('section', 'pages', 'disabled'))->render(),

            self::BUSINESS_PROCESSES => view('components.backend.sections.defaults.business-processes', compact('section', 'pages', 'disabled'))->render(),

            self::REFERENCE          => view('components.backend.sections.defaults.reference', compact('section', 'pages', 'disabled'))->render(),
            self::REFERENCE_GRID     => view('components.backend.sections.defaults.reference', compact('section', 'pages', 'disabled'))->render(),
            self::REFERENCE_SLIDER   => view('components.backend.sections.defaults.reference', compact('section', 'pages', 'disabled'))->render(),

            self::BLOG               => view('components.backend.sections.defaults.blog', compact('section', 'pages', 'disabled'))->render(),
            self::BLOG_LATEST        => view('components.backend.sections.defaults.blog', compact('section', 'pages', 'disabled'))->render(),

            self::BRAND              => view('components.backend.sections.defaults.brand', compact('section', 'pages', 'disabled'))->render(),
            self::BRAND_SLIDER       => view('components.backend.sections.defaults.brand', compact('section', 'pages', 'disabled'))->render(),

            self::FAQ                => view('components.backend.sections.defaults.faq', compact('section', 'pages', 'disabled'))->render(),
            self::FAQ_TWO            => view('components.backend.sections.defaults.faq', compact('section', 'pages', 'disabled'))->render(),

            self::CONTACT_FORM       => view('components.backend.sections.defaults.contact-form', compact('section', 'pages', 'disabled'))->render(),

            self::CONTACT            => view('components.backend.sections.defaults.contact', compact('section', 'pages', 'disabled'))->render(),
            self::CONTACT_TWO        => view('components.backend.sections.defaults.contact', compact('section', 'pages', 'disabled'))->render(),

            self::FOOTER             => view('components.backend.sections.defaults.footer-empty', compact('section', 'pages', 'disabled'))->render(),
            self::FOOTER_TWO         => view('components.backend.sections.defaults.footer', compact('section', 'pages', 'disabled'))->render(),

            self::PRIVACY_POLICY     => view('components.backend.sections.defaults.privacy', compact('section', 'pages', 'disabled'))->render(),
            self::TERMS_CONDITION    => view('components.backend.sections.defaults.terms', compact('section', 'pages', 'disabled'))->render(),

            default => '',
        };
    }


    /**
     * @return array
     */
    public static function getDynamicSection(): array
    {
        return [
            self::DYNAMIC->value,
            self::DYNAMIC_IMAGE->value,
            self::DYNAMIC_RIGHT->value,
            self::DYNAMIC_LEFT->value,
        ];
    }


    /**
     * @param Section|null $section
     * @param Collection $pages
     * @param int|null $sorting
     *
     * @return string
     */
    public static function getAddDynamicSectionSettingView(string $section, Collection $pages, ?int $sorting): string
    {
        return match ($section) {
            self::DYNAMIC->value       => view('components.backend.sections.dynamics.create-dynamic', compact('section', 'pages', 'sorting'))->render(),
            self::DYNAMIC_IMAGE->value => view('components.backend.sections.dynamics.create-image-dynamic', compact('section', 'pages', 'sorting'))->render(),
            self::DYNAMIC_RIGHT->value => view('components.backend.sections.dynamics.create-right-dynamic', compact('section', 'pages', 'sorting'))->render(),
            self::DYNAMIC_LEFT->value  => view('components.backend.sections.dynamics.create-left-dynamic', compact('section', 'pages', 'sorting'))->render(),

            default => '',
        };
    }




    /**
     * @param Request $request
     * @param Page $page
     * @param Section $section
     *
     * @return string
     */
    public static function getSectionFrontendView(Request $request, Page $page, Section $section): string
    {
        return match ($section?->section_type) {
            self::DYNAMIC            => (new DynamicService)->dynamic(request: $request, page: $page, section: $section),
            self::DYNAMIC_IMAGE      => (new DynamicService)->dynamicImage(request: $request, page: $page, section: $section),
            self::DYNAMIC_RIGHT      => (new DynamicService)->dynamicRight(request: $request, page: $page, section: $section),
            self::DYNAMIC_LEFT       => (new DynamicService)->dynamicLeft(request: $request, page: $page, section: $section),

            self::BANNER             => (new BannerService)->banner(request: $request, page: $page, section: $section),

            self::WHO_WE_ARE         => (new WeAreService)->weAre(request: $request, page: $page, section: $section),
            self::WHO_WE_ARE_TWO     => (new WeAreService)->weAreTwo(request: $request, page: $page, section: $section),

            self::ABOUT              => (new AboutService)->about(request: $request, page: $page, section: $section),
            self::ABOUT_TWO          => (new AboutService)->aboutTwo(request: $request, page: $page, section: $section),

            self::MISSION_VISION     => (new MissionVisionService)->missionVision(request: $request, page: $page, section: $section),
            self::MISSION_VISION_TWO => (new MissionVisionService)->missionVisionTwo(request: $request, page: $page, section: $section),

            self::SERVICE            => (new ServiceManager)->service(request: $request, page: $page, section: $section),
            self::SERVICE_GRID       => (new ServiceManager)->serviceGrid(request: $request, page: $page, section: $section),
            self::SERVICE_SLIDER     => (new ServiceManager)->serviceSlider(request: $request, page: $page, section: $section),

            self::BUSINESS_PROCESSES => (new BusinessProcessesService)->businessProcesses(request: $request, page: $page, section: $section),

            self::REFERENCE          => (new ReferenceService)->reference(request: $request, page: $page, section: $section),
            self::REFERENCE_GRID     => (new ReferenceService)->referenceGrid(request: $request, page: $page, section: $section),
            self::REFERENCE_SLIDER   => (new ReferenceService)->referenceSlider(request: $request, page: $page, section: $section),

            self::BLOG               => (new BlogService)->blog(request: $request, page: $page, section: $section),
            self::BLOG_LATEST        => (new BlogService)->latestBlogs(request: $request, page: $page, section: $section),

            self::BRAND              => (new BrandService)->brand(request: $request, page: $page, section: $section),
            self::BRAND_SLIDER       => (new BrandService)->brandSlider(request: $request, page: $page, section: $section),

            self::FAQ                => (new FaqService)->faq(request: $request, page: $page, section: $section),
            self::FAQ_TWO            => (new FaqService)->faqTwo(request: $request, page: $page, section: $section),

            self::CONTACT_FORM       => (new ContactFormService)->form(request: $request, page: $page, section: $section),

            self::CONTACT            => (new ContactService)->contact(request: $request, page: $page, section: $section),
            self::CONTACT_TWO        => (new ContactService)->contactTwo(request: $request, page: $page, section: $section),

            self::FOOTER             => (new FooterService)->footer(request: $request, page: $page, section: $section),
            self::FOOTER_TWO         => (new FooterService)->footerTwo(request: $request, page: $page, section: $section),

            self::PRIVACY_POLICY     => (new PrivacyPolicyService)->privacyPolicy(request: $request, page: $page, section: $section),
            self::TERMS_CONDITION    => (new TermService)->term(request: $request, page: $page, section: $section),

            default => '',
        };
    }
}
