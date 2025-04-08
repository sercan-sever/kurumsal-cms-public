<?php

use App\Enums\Permissions\PermissionEnum;
use App\Enums\Prefixes\RoutePrefixEnum;
use App\Enums\Roles\RoleEnum;
use App\Http\Controllers\Backend\About\AboutController;
use App\Http\Controllers\Backend\Auth\LoginController as BackendLoginController;
use App\Http\Controllers\Backend\Auth\LogoutController as BackendLogoutController;

use App\Http\Controllers\Backend\Dashboard\DashboardController;
use App\Http\Controllers\Backend\Banner\BannerController;
use App\Http\Controllers\Backend\Blog\BlogCategoryController;
use App\Http\Controllers\Backend\Blog\BlogCommentController;
use App\Http\Controllers\Backend\Blog\BlogController;
use App\Http\Controllers\Backend\Blog\BlogSubscribeController;
use App\Http\Controllers\Backend\Blog\BlogTagController;
use App\Http\Controllers\Backend\Brand\BrandController;
use App\Http\Controllers\Backend\BusinessProcesses\BusinessProcessesController;
use App\Http\Controllers\Backend\Faq\FaqController;
use App\Http\Controllers\Backend\Setting\AddressSettingController;
use App\Http\Controllers\Backend\Setting\EmailSettingController;
use App\Http\Controllers\Backend\Setting\GeneralSettingController;
use App\Http\Controllers\Backend\Setting\LogoSettingController;
use App\Http\Controllers\Backend\Setting\PluginSettingController;
use App\Http\Controllers\Backend\Setting\SettingController;
use App\Http\Controllers\Backend\Setting\SocialSettingController;

use App\Http\Controllers\Backend\Language\LanguageController;
use App\Http\Controllers\Backend\Pages\PageController;
use App\Http\Controllers\Backend\Pages\PageDetailController;
use App\Http\Controllers\Backend\Reference\ReferenceController;
use App\Http\Controllers\Backend\Section\SectionController;
use App\Http\Controllers\Backend\Service\ServiceController;
use App\Http\Controllers\Backend\Setting\SubscribeSettingController;
use App\Http\Controllers\Backend\Translation\TranslationController;
use App\Http\Controllers\Backend\Translation\TranslationContentController;

use App\Http\Controllers\Backend\UserManagement\UserManagementController;
use App\Http\Controllers\Form\FormController;
use App\Http\Controllers\Frontend\DetailPageController;
use App\Http\Controllers\Frontend\HomePageController;
use App\Http\Controllers\Frontend\SubDetailPageController;
use Illuminate\Support\Facades\Route;

/* EKLENTİLER */

/*
    - barryvdh/laravel-debugbar
    - opcodesio/log-viewer
    - anhskohbo/no-captcha
    - spatie/laravel-permission
    - intervention/image
    - propaganistas/laravel-phone
    - unisharp/laravel-filemanager
    - spatie/laravel-sitemap
*/


Route::prefix(RoutePrefixEnum::FILE_MANAGER->value)->middleware([
    'backend.login.check',
    'role:' . RoleEnum::SUPER_ADMIN->value . '|' . RoleEnum::ADMIN->value . '|' . RoleEnum::GUEST->value,
    'permission:' . PermissionEnum::fileManagerPermissions(),
    'throttle:admin-page'
])->group(function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

Route::prefix(RoutePrefixEnum::ADMIN_PANEL->value)->name('admin.')->group(function () {

    // Login
    Route::controller(BackendLoginController::class)->middleware('backend.login.page.visibility')->group(function () {
        Route::get('/login', 'index')->name('login.page');

        Route::post('/login/auth', 'login')->middleware('throttle:login')->name('login.auth');
    });

    // throttle işlemi bu alan için bir daha sonra düşünülüp bakılacak.
    Route::middleware(['backend.login.check', 'throttle:admin-page'])->group(function () {

        // Logout
        Route::controller(BackendLogoutController::class)->group(function () {
            Route::get('/logout', 'getLogout')->name('logout');
            Route::post('/logout', 'logout')->name('logout');
        });



        // Dashboard
        Route::controller(DashboardController::class)->group(function () {
            Route::get('/dashboard', 'index')->name('home');
        });



        // About
        Route::controller(AboutController::class)->middleware('permission:' . PermissionEnum::ABOUT_VIEW->value)->group(function () {
            Route::get('/about', 'index')->name('about');
            Route::post('/about/read-view', 'readView')->name('about.read.view');
            Route::post('/about/update', 'update')->middleware('permission:' . PermissionEnum::ABOUT_UPDATE->value)->name('about.update');
        });



        // Banners
        Route::controller(BannerController::class)->middleware('permission:' . PermissionEnum::BANNER_VIEW->value)->group(function () {
            Route::get('/banner', 'index')->name('banner');
            Route::post('/banner/read-view', 'readView')->name('banner.read.view');

            Route::post('/banner/create', 'create')->middleware('permission:' . PermissionEnum::BANNER_CREATE->value)->name('banner.create');
            Route::post('/banner/change-status', 'changeStatus')->middleware('permission:' . PermissionEnum::BANNER_UPDATE->value)->name('banner.change.status');
            Route::post('/banner/read', 'read')->middleware('permission:' . PermissionEnum::BANNER_UPDATE->value)->name('banner.read');
            Route::post('/banner/update', 'update')->middleware('permission:' . PermissionEnum::BANNER_UPDATE->value)->name('banner.update');

            Route::post('/banner/delete', 'delete')->middleware('permission:' . PermissionEnum::BANNER_DELETE->value)->name('banner.delete');
            Route::post('/banner/read-trashed', 'readTrashed')->middleware('permission:' . PermissionEnum::BANNER_DELETE->value)->name('banner.read.trashed');
            Route::post('/banner/trashed/restore', 'trashedRestore')->middleware('permission:' . PermissionEnum::BANNER_DELETE->value)->name('banner.trashed.restore');
            Route::post('/banner/trashed/remove', 'trashedRemove')->middleware('role:' . RoleEnum::SUPER_ADMIN->value . '|' . RoleEnum::ADMIN->value)->name('banner.trashed.remove');
        });



        // Services
        Route::controller(ServiceController::class)->middleware('permission:' . PermissionEnum::SERVICE_VIEW->value)->group(function () {
            Route::get('/service', 'index')->name('service');
            Route::post('/service/read-view', 'readView')->name('service.read.view');

            Route::post('/service/create', 'create')->middleware('permission:' . PermissionEnum::SERVICE_CREATE->value)->name('service.create');
            Route::post('/service/change-status', 'changeStatus')->middleware('permission:' . PermissionEnum::SERVICE_UPDATE->value)->name('service.change.status');
            Route::post('/service/read', 'read')->middleware('permission:' . PermissionEnum::SERVICE_UPDATE->value)->name('service.read');
            Route::post('/service/update', 'update')->middleware('permission:' . PermissionEnum::SERVICE_UPDATE->value)->name('service.update');

            Route::post('/service/delete', 'delete')->middleware('permission:' . PermissionEnum::SERVICE_DELETE->value)->name('service.delete');
            Route::post('/service/delete/image', 'deleteImage')->middleware('permission:' . PermissionEnum::SERVICE_DELETE->value)->name('service.delete.image');

            Route::post('/service/read-trashed', 'readTrashed')->middleware('permission:' . PermissionEnum::SERVICE_DELETE->value)->name('service.read.trashed');
            Route::post('/service/trashed/restore', 'trashedRestore')->middleware('permission:' . PermissionEnum::SERVICE_DELETE->value)->name('service.trashed.restore');
            Route::post('/service/trashed/remove', 'trashedRemove')->middleware('role:' . RoleEnum::SUPER_ADMIN->value . '|' . RoleEnum::ADMIN->value)->name('service.trashed.remove');
        });



        // Services
        Route::controller(BusinessProcessesController::class)->middleware('permission:' . PermissionEnum::BUSINESS_PROCESSES_VIEW->value)->group(function () {
            Route::get('/business-processes', 'index')->name('business.processes');
            Route::post('/business-processes/read-view', 'readView')->name('business.processes.read.view');

            Route::post('/business-processes/create', 'create')->middleware('permission:' . PermissionEnum::BUSINESS_PROCESSES_CREATE->value)->name('business.processes.create');
            Route::post('/business-processes/change-status', 'changeStatus')->middleware('permission:' . PermissionEnum::BUSINESS_PROCESSES_UPDATE->value)->name('business.processes.change.status');
            Route::post('/business-processes/read', 'read')->middleware('permission:' . PermissionEnum::BUSINESS_PROCESSES_UPDATE->value)->name('business.processes.read');
            Route::post('/business-processes/update', 'update')->middleware('permission:' . PermissionEnum::BUSINESS_PROCESSES_UPDATE->value)->name('business.processes.update');

            Route::post('/business-processes/delete', 'delete')->middleware('permission:' . PermissionEnum::BUSINESS_PROCESSES_DELETE->value)->name('business.processes.delete');
            Route::post('/business-processes/delete/image', 'deleteImage')->middleware('permission:' . PermissionEnum::BUSINESS_PROCESSES_DELETE->value)->name('business.processes.delete.image');

            Route::post('/business-processes/read-trashed', 'readTrashed')->middleware('permission:' . PermissionEnum::BUSINESS_PROCESSES_DELETE->value)->name('business.processes.read.trashed');
            Route::post('/business-processes/trashed/restore', 'trashedRestore')->middleware('permission:' . PermissionEnum::BUSINESS_PROCESSES_DELETE->value)->name('business.processes.trashed.restore');
            Route::post('/business-processes/trashed/remove', 'trashedRemove')->middleware('role:' . RoleEnum::SUPER_ADMIN->value . '|' . RoleEnum::ADMIN->value)->name('business.processes.trashed.remove');
        });




        // Blog Category
        Route::controller(BlogCategoryController::class)->middleware('permission:' . PermissionEnum::BLOG_CATEGORY_VIEW->value)->group(function () {
            Route::get('/blogs/category', 'index')->name('blog.category');
            Route::post('/blogs/category/read-view', 'readView')->name('blog.category.read.view');

            Route::post('/blogs/category/create', 'create')->middleware('permission:' . PermissionEnum::BLOG_CATEGORY_CREATE->value)->name('blog.category.create');
            Route::post('/blogs/category/change-status', 'changeStatus')->middleware('permission:' . PermissionEnum::BLOG_CATEGORY_UPDATE->value)->name('blog.category.change.status');
            Route::post('/blogs/category/read', 'read')->middleware('permission:' . PermissionEnum::BLOG_CATEGORY_UPDATE->value)->name('blog.category.read');
            Route::post('/blogs/category/update', 'update')->middleware('permission:' . PermissionEnum::BLOG_CATEGORY_UPDATE->value)->name('blog.category.update');

            Route::post('/blogs/category/delete', 'delete')->middleware('permission:' . PermissionEnum::BLOG_CATEGORY_DELETE->value)->name('blog.category.delete');
            Route::post('/blogs/category/read-trashed', 'readTrashed')->middleware('permission:' . PermissionEnum::BLOG_CATEGORY_DELETE->value)->name('blog.category.read.trashed');
            Route::post('/blogs/category/trashed/restore', 'trashedRestore')->middleware('permission:' . PermissionEnum::BLOG_CATEGORY_DELETE->value)->name('blog.category.trashed.restore');
            Route::post('/blogs/category/trashed/remove', 'trashedRemove')->middleware('role:' . RoleEnum::SUPER_ADMIN->value . '|' . RoleEnum::ADMIN->value)->name('blog.category.trashed.remove');
        });



        // Blog Tag
        Route::controller(BlogTagController::class)->middleware('permission:' . PermissionEnum::BLOG_TAG_VIEW->value)->group(function () {
            Route::get('/blogs/tag', 'index')->name('blog.tag');
            Route::post('/blogs/tag/read-view', 'readView')->name('blog.tag.read.view');
            Route::post('/blogs/tag/create', 'create')->middleware('permission:' . PermissionEnum::BLOG_TAG_CREATE->value)->name('blog.tag.create');
            Route::post('/blogs/tag/change-status', 'changeStatus')->middleware('permission:' . PermissionEnum::BLOG_TAG_UPDATE->value)->name('blog.tag.change.status');
            Route::post('/blogs/tag/read', 'read')->middleware('permission:' . PermissionEnum::BLOG_TAG_UPDATE->value)->name('blog.tag.read');
            Route::post('/blogs/tag/update', 'update')->middleware('permission:' . PermissionEnum::BLOG_TAG_UPDATE->value)->name('blog.tag.update');

            Route::post('/blogs/tag/delete', 'delete')->middleware('permission:' . PermissionEnum::BLOG_TAG_DELETE->value)->name('blog.tag.delete');
            Route::post('/blogs/tag/read-trashed', 'readTrashed')->middleware('permission:' . PermissionEnum::BLOG_TAG_DELETE->value)->name('blog.tag.read.trashed');
            Route::post('/blogs/tag/trashed/restore', 'trashedRestore')->middleware('permission:' . PermissionEnum::BLOG_TAG_DELETE->value)->name('blog.tag.trashed.restore');
            Route::post('/blogs/tag/trashed/remove', 'trashedRemove')->middleware('role:' . RoleEnum::SUPER_ADMIN->value . '|' . RoleEnum::ADMIN->value)->name('blog.tag.trashed.remove');
        });



        // Blogs Content
        Route::controller(BlogController::class)->middleware('permission:' . PermissionEnum::BLOG_VIEW->value)->group(function () {
            Route::get('/blogs', 'index')->name('blog');
            Route::post('/blogs/read-view', 'readView')->name('blog.read.view');
            Route::post('/blogs/create', 'create')->middleware('permission:' . PermissionEnum::BLOG_CREATE->value)->name('blog.create');
            Route::post('/blogs/change-status', 'changeStatus')->middleware('permission:' . PermissionEnum::BLOG_UPDATE->value)->name('blog.change.status');
            Route::post('/blogs/change-comment-status', 'changeCommentStatus')->middleware('permission:' . PermissionEnum::BLOG_UPDATE->value)->name('blog.change.comment.status');
            Route::post('/blogs/read', 'read')->middleware('permission:' . PermissionEnum::BLOG_UPDATE->value)->name('blog.read');
            Route::post('/blogs/update', 'update')->middleware('permission:' . PermissionEnum::BLOG_UPDATE->value)->name('blog.update');

            Route::post('/blogs/delete', 'delete')->middleware('permission:' . PermissionEnum::BLOG_DELETE->value)->name('blog.delete');
            Route::post('/blogs/read-trashed', 'readTrashed')->middleware('permission:' . PermissionEnum::BLOG_DELETE->value)->name('blog.read.trashed');
            Route::post('/blogs/trashed/restore', 'trashedRestore')->middleware('permission:' . PermissionEnum::BLOG_DELETE->value)->name('blog.trashed.restore');
            Route::post('/blogs/trashed/remove', 'trashedRemove')->middleware('role:' . RoleEnum::SUPER_ADMIN->value . '|' . RoleEnum::ADMIN->value)->name('blog.trashed.remove');
        });



        // Blogs Comment
        Route::controller(BlogCommentController::class)->middleware('permission:' . PermissionEnum::BLOG_COMMENT_VIEW->value)->group(function () {
            Route::get('/blogs/comment', 'index')->name('blog.comment');

            Route::post('/blogs/comment/confirm/read', 'readUnconfirmed')->middleware('permission:' . PermissionEnum::BLOG_COMMENT_CONFIRM->value)->name('blog.comment.confirm.read');
            Route::post('/blogs/comment/confirm/accept', 'acceptConfirm')->middleware('permission:' . PermissionEnum::BLOG_COMMENT_CONFIRM->value)->name('blog.comment.confirm.accept');
            Route::post('/blogs/comment/confirm/accept-update', 'acceptUpdateConfirm')->middleware('permission:' . PermissionEnum::BLOG_COMMENT_CONFIRM->value)->name('blog.comment.confirm.accept.update');
            Route::post('/blogs/comment/confirm/view', 'confirmView')->middleware('permission:' . PermissionEnum::BLOG_COMMENT_CONFIRM->value)->name('blog.comment.confirm.view');

            Route::post('/blogs/comment/read', 'read')->middleware('permission:' . PermissionEnum::BLOG_COMMENT_UPDATE->value)->name('blog.comment.read');
            Route::post('/blogs/comment/update', 'update')->middleware('permission:' . PermissionEnum::BLOG_COMMENT_UPDATE->value)->name('blog.comment.update');

            Route::post('/blogs/comment/reject', 'reject')->middleware('permission:' . PermissionEnum::BLOG_COMMENT_REJECT->value)->name('blog.comment.reject');

            Route::post('/blogs/comment/delete', 'delete')->middleware('permission:' . PermissionEnum::BLOG_COMMENT_DELETE->value)->name('blog.comment.delete');
            Route::post('/blogs/comment/read-trashed', 'readTrashed')->middleware('permission:' . PermissionEnum::BLOG_COMMENT_DELETE->value)->name('blog.comment.read.trashed');
            Route::post('/blogs/comment/trashed/restore', 'trashedRestore')->middleware('permission:' . PermissionEnum::BLOG_COMMENT_DELETE->value)->name('blog.comment.trashed.restore');
            Route::post('/blogs/comment/trashed/remove', 'trashedRemove')->middleware('role:' . RoleEnum::SUPER_ADMIN->value . '|' . RoleEnum::ADMIN->value)->name('blog.comment.trashed.remove');
        });



        // Blog Subscriber
        Route::controller(BlogSubscribeController::class)->middleware('permission:' . PermissionEnum::BLOG_CATEGORY_VIEW->value)->group(function () {
            Route::get('/blogs/subscribe', 'index')->name('blog.subscribe');
            Route::post('/blogs/subscribe/read-view', 'readView')->name('blog.subscribe.read.view');

            Route::post('/blogs/subscribe/change-status', 'changeStatus')->middleware('permission:' . PermissionEnum::BLOG_SUBSCRIBER_UPDATE->value)->name('blog.subscribe.change.status');
            Route::post('/blogs/subscribe/read', 'read')->middleware('permission:' . PermissionEnum::BLOG_SUBSCRIBER_UPDATE->value)->name('blog.subscribe.read');
            Route::post('/blogs/subscribe/update', 'update')->middleware('permission:' . PermissionEnum::BLOG_SUBSCRIBER_UPDATE->value)->name('blog.subscribe.update');

            Route::post('/blogs/subscribe/delete', 'delete')->middleware('permission:' . PermissionEnum::BLOG_SUBSCRIBER_DELETE->value)->name('blog.subscribe.delete');
            Route::post('/blogs/subscribe/read-trashed', 'readTrashed')->middleware('permission:' . PermissionEnum::BLOG_SUBSCRIBER_DELETE->value)->name('blog.subscribe.read.trashed');
            Route::post('/blogs/subscribe/trashed/restore', 'trashedRestore')->middleware('permission:' . PermissionEnum::BLOG_SUBSCRIBER_DELETE->value)->name('blog.subscribe.trashed.restore');
            Route::post('/blogs/subscribe/trashed/remove', 'trashedRemove')->middleware('role:' . RoleEnum::SUPER_ADMIN->value . '|' . RoleEnum::ADMIN->value)->name('blog.subscribe.trashed.remove');
        });



        // References
        Route::controller(ReferenceController::class)->middleware('permission:' . PermissionEnum::REFERENCE_VIEW->value)->group(function () {
            Route::get('/reference', 'index')->name('reference');
            Route::post('/reference/read-view', 'readView')->name('reference.read.view');

            Route::post('/reference/create', 'create')->middleware('permission:' . PermissionEnum::REFERENCE_CREATE->value)->name('reference.create');
            Route::post('/reference/change-status', 'changeStatus')->middleware('permission:' . PermissionEnum::REFERENCE_UPDATE->value)->name('reference.change.status');
            Route::post('/reference/read', 'read')->middleware('permission:' . PermissionEnum::REFERENCE_UPDATE->value)->name('reference.read');
            Route::post('/reference/update', 'update')->middleware('permission:' . PermissionEnum::REFERENCE_UPDATE->value)->name('reference.update');

            Route::post('/reference/delete', 'delete')->middleware('permission:' . PermissionEnum::REFERENCE_DELETE->value)->name('reference.delete');
            Route::post('/reference/delete/image', 'deleteImage')->middleware('permission:' . PermissionEnum::REFERENCE_DELETE->value)->name('reference.delete.image');

            Route::post('/reference/read-trashed', 'readTrashed')->middleware('permission:' . PermissionEnum::REFERENCE_DELETE->value)->name('reference.read.trashed');
            Route::post('/reference/trashed/restore', 'trashedRestore')->middleware('permission:' . PermissionEnum::REFERENCE_DELETE->value)->name('reference.trashed.restore');
            Route::post('/reference/trashed/remove', 'trashedRemove')->middleware('role:' . RoleEnum::SUPER_ADMIN->value . '|' . RoleEnum::ADMIN->value)->name('reference.trashed.remove');
        });



        // Brands
        Route::controller(BrandController::class)->middleware('permission:' . PermissionEnum::BRAND_VIEW->value)->group(function () {
            Route::get('/brands', 'index')->name('brand');
            Route::post('/brands/read-view', 'readView')->name('brand.read.view');

            Route::post('/brands/create', 'create')->middleware('permission:' . PermissionEnum::BRAND_CREATE->value)->name('brand.create');
            Route::post('/brands/change-status', 'changeStatus')->middleware('permission:' . PermissionEnum::BRAND_UPDATE->value)->name('brand.change.status');
            Route::post('/brands/read', 'read')->middleware('permission:' . PermissionEnum::BRAND_UPDATE->value)->name('brand.read');
            Route::post('/brands/update', 'update')->middleware('permission:' . PermissionEnum::BRAND_UPDATE->value)->name('brand.update');

            Route::post('/brands/delete', 'delete')->middleware('permission:' . PermissionEnum::BRAND_DELETE->value)->name('brand.delete');
            Route::post('/brands/read-trashed', 'readTrashed')->middleware('permission:' . PermissionEnum::BRAND_DELETE->value)->name('brand.read.trashed');
            Route::post('/brands/trashed/restore', 'trashedRestore')->middleware('permission:' . PermissionEnum::BRAND_DELETE->value)->name('brand.trashed.restore');
            Route::post('/brands/trashed/remove', 'trashedRemove')->middleware('role:' . RoleEnum::SUPER_ADMIN->value . '|' . RoleEnum::ADMIN->value)->name('brand.trashed.remove');
        });



        // Faqs
        Route::controller(FaqController::class)->middleware('permission:' . PermissionEnum::FAQ_VIEW->value)->group(function () {
            Route::get('/faq', 'index')->name('faq');
            Route::post('/faq/read-view', 'readView')->name('faq.read.view');

            Route::post('/faq/create', 'create')->middleware('permission:' . PermissionEnum::FAQ_CREATE->value)->name('faq.create');
            Route::post('/faq/change-status', 'changeStatus')->middleware('permission:' . PermissionEnum::FAQ_UPDATE->value)->name('faq.change.status');
            Route::post('/faq/read', 'read')->middleware('permission:' . PermissionEnum::FAQ_UPDATE->value)->name('faq.read');
            Route::post('/faq/update', 'update')->middleware('permission:' . PermissionEnum::FAQ_UPDATE->value)->name('faq.update');

            Route::post('/faq/delete', 'delete')->middleware('permission:' . PermissionEnum::FAQ_DELETE->value)->name('faq.delete');
            Route::post('/faq/read-trashed', 'readTrashed')->middleware('permission:' . PermissionEnum::FAQ_DELETE->value)->name('faq.read.trashed');
            Route::post('/faq/trashed/restore', 'trashedRestore')->middleware('permission:' . PermissionEnum::FAQ_DELETE->value)->name('faq.trashed.restore');
            Route::post('/faq/trashed/remove', 'trashedRemove')->middleware('role:' . RoleEnum::SUPER_ADMIN->value . '|' . RoleEnum::ADMIN->value)->name('faq.trashed.remove');
        });


        // Page
        Route::controller(PageController::class)->middleware('permission:' . PermissionEnum::PAGE_VIEW->value)->group(function () {
            Route::get('/pages', 'index')->name('pages');
            Route::post('/pages/read-view', 'readView')->name('pages.read.view');

            Route::post('/pages/get-add-view', 'getAddView')->middleware('permission:' . PermissionEnum::PAGE_CREATE->value)->name('pages.get.add.view');
            Route::post('/pages/create', 'create')->middleware('permission:' . PermissionEnum::PAGE_CREATE->value)->name('pages.create');

            Route::post('/pages/change-status', 'changeStatus')->middleware('permission:' . PermissionEnum::PAGE_UPDATE->value)->name('pages.change.status');
            Route::post('/pages/change-breadcrumb', 'changeBreadcrumb')->middleware('permission:' . PermissionEnum::PAGE_UPDATE->value)->name('pages.change.status');

            Route::post('/pages/read', 'read')->middleware('permission:' . PermissionEnum::PAGE_UPDATE->value)->name('pages.read');
            Route::post('/pages/update', 'update')->middleware('permission:' . PermissionEnum::PAGE_UPDATE->value)->name('pages.update');

            Route::post('/pages/delete', 'delete')->middleware('permission:' . PermissionEnum::PAGE_DELETE->value)->name('pages.delete');
            Route::post('/pages/read-trashed', 'readTrashed')->middleware('permission:' . PermissionEnum::PAGE_DELETE->value)->name('pages.read.trashed');
            Route::post('/pages/trashed/restore', 'trashedRestore')->middleware('permission:' . PermissionEnum::PAGE_DELETE->value)->name('pages.trashed.restore');

            Route::post('/pages/trashed/remove', 'trashedRemove')->middleware('role:' . RoleEnum::SUPER_ADMIN->value . '|' . RoleEnum::ADMIN->value)->name('pages.trashed.remove');
        });


        // Page Detail
        Route::controller(PageDetailController::class)->middleware('permission:' . PermissionEnum::PAGE_VIEW->value)->group(function () {
            Route::get('/pages/{page}/detail', 'detail')->name('pages.detail');
            Route::post('/pages/detail/check-status', 'checkStatus')->name('pages.detail.check.status');

            Route::post('/pages/detail/update', 'update')->middleware('permission:' . PermissionEnum::PAGE_UPDATE->value)->name('pages.detail.update');

            Route::post('/pages/sections/read-view', 'readView')->middleware('permission:' . PermissionEnum::PAGE_SECTION_VIEW->value)->name('pages.detail.section.read.view');
        });



        // Page Sections
        Route::controller(SectionController::class)->middleware('permission:' . PermissionEnum::PAGE_SECTION_VIEW->value)->group(function () {
            Route::get('/pages/sections', 'index')->name('pages.section');
            Route::post('/pages/sections/read-view', 'readView')->name('pages.section.read.view');

            Route::post('/pages/sections/get-dynamic-section', 'getDynamicSection')->middleware('permission:' . PermissionEnum::PAGE_SECTION_CREATE->value)->name('pages.section.get.dynamic.section');
            Route::post('/pages/sections/create', 'create')->middleware('permission:' . PermissionEnum::PAGE_SECTION_CREATE->value)->name('pages.section.create');

            Route::post('/pages/sections/create/dynamic', 'createDynamic')->middleware('permission:' . PermissionEnum::PAGE_SECTION_CREATE->value)->name('pages.section.create.dynamic');
            Route::post('/pages/sections/create/dynamic-image', 'createDynamicImage')->middleware('permission:' . PermissionEnum::PAGE_SECTION_CREATE->value)->name('pages.section.create.dynamic.image');
            Route::post('/pages/sections/create/dynamic-right', 'createDynamicRight')->middleware('permission:' . PermissionEnum::PAGE_SECTION_CREATE->value)->name('pages.section.create.dynamic.right');
            Route::post('/pages/sections/create/dynamic-left', 'createDynamicLeft')->middleware('permission:' . PermissionEnum::PAGE_SECTION_CREATE->value)->name('pages.section.create.dynamic.left');


            Route::post('/pages/sections/change-status', 'changeStatus')->middleware('permission:' . PermissionEnum::PAGE_SECTION_UPDATE->value)->name('pages.section.change.status');
            Route::post('/pages/sections/read', 'read')->middleware('permission:' . PermissionEnum::PAGE_SECTION_UPDATE->value)->name('pages.section.read');

            Route::post('/pages/sections/update/banner', 'updateBanner')->middleware('permission:' . PermissionEnum::PAGE_SECTION_UPDATE->value)->name('pages.section.update.banner');
            Route::post('/pages/sections/update/we-are', 'updateWeAre')->middleware('permission:' . PermissionEnum::PAGE_SECTION_UPDATE->value)->name('pages.section.update.we.are');
            Route::post('/pages/sections/update/about', 'updateAbout')->middleware('permission:' . PermissionEnum::PAGE_SECTION_UPDATE->value)->name('pages.section.update.about');
            Route::post('/pages/sections/update/mission-vision', 'updateMissionVision')->middleware('permission:' . PermissionEnum::PAGE_SECTION_UPDATE->value)->name('pages.section.update.mission.vision');
            Route::post('/pages/sections/update/service', 'updateService')->middleware('permission:' . PermissionEnum::PAGE_SECTION_UPDATE->value)->name('pages.section.update.service');
            Route::post('/pages/sections/update/business-processes', 'updateBusinessProcesses')->middleware('permission:' . PermissionEnum::PAGE_SECTION_UPDATE->value)->name('pages.section.update.business.processes');
            Route::post('/pages/sections/update/reference', 'updateReference')->middleware('permission:' . PermissionEnum::PAGE_SECTION_UPDATE->value)->name('pages.section.update.reference');
            Route::post('/pages/sections/update/blog', 'updateBlog')->middleware('permission:' . PermissionEnum::PAGE_SECTION_UPDATE->value)->name('pages.section.update.blog');
            Route::post('/pages/sections/update/brand', 'updateBrand')->middleware('permission:' . PermissionEnum::PAGE_SECTION_UPDATE->value)->name('pages.section.update.brand');
            Route::post('/pages/sections/update/faq', 'updateFaq')->middleware('permission:' . PermissionEnum::PAGE_SECTION_UPDATE->value)->name('pages.section.update.faq');
            Route::post('/pages/sections/update/contact-form', 'updateContactForm')->middleware('permission:' . PermissionEnum::PAGE_SECTION_UPDATE->value)->name('pages.section.update.contact.form');
            Route::post('/pages/sections/update/contact', 'updateContact')->middleware('permission:' . PermissionEnum::PAGE_SECTION_UPDATE->value)->name('pages.section.update.contact');
            Route::post('/pages/sections/update/footer', 'updateFooter')->middleware('permission:' . PermissionEnum::PAGE_SECTION_UPDATE->value)->name('pages.section.update.footer');
            Route::post('/pages/sections/update/privacy', 'updatePrivacy')->middleware('permission:' . PermissionEnum::PAGE_SECTION_UPDATE->value)->name('pages.section.update.privacy');
            Route::post('/pages/sections/update/terms', 'updateTerms')->middleware('permission:' . PermissionEnum::PAGE_SECTION_UPDATE->value)->name('pages.section.update.terms');

            Route::post('/pages/sections/update/dynamic', 'updateDynamic')->middleware('permission:' . PermissionEnum::PAGE_SECTION_UPDATE->value)->name('pages.section.update.dynamic');
            Route::post('/pages/sections/update/dynamic-image', 'updateDynamicImage')->middleware('permission:' . PermissionEnum::PAGE_SECTION_UPDATE->value)->name('pages.section.update.dynamic.image');
            Route::post('/pages/sections/update/dynamic-right-left', 'updateDynamicRightLeft')->middleware('permission:' . PermissionEnum::PAGE_SECTION_UPDATE->value)->name('pages.section.update.dynamic.right.left');

            Route::post('/pages/sections/delete', 'delete')->middleware('permission:' . PermissionEnum::PAGE_SECTION_DELETE->value)->name('pages.section.delete');
            Route::post('/pages/sections/read-trashed', 'readTrashed')->middleware('permission:' . PermissionEnum::PAGE_SECTION_DELETE->value)->name('pages.section.read.trashed');
            Route::post('/pages/sections/trashed/restore', 'trashedRestore')->middleware('permission:' . PermissionEnum::PAGE_SECTION_DELETE->value)->name('pages.section.trashed.restore');
            Route::post('/pages/sections/trashed/remove', 'trashedRemove')->middleware('role:' . RoleEnum::SUPER_ADMIN->value . '|' . RoleEnum::ADMIN->value)->name('pages.section.trashed.remove');
        });


        // Settings
        Route::middleware('permission:' . PermissionEnum::SETTINGS_VIEW->value)->group(function () {

            Route::controller(SettingController::class)->group(function () {
                Route::get('/settings', 'index')->name('setting');
            });

            Route::controller(SubscribeSettingController::class)->group(function () {
                Route::post('/settings/subscribe/update', 'update')->middleware('permission:' . PermissionEnum::SUBSCRIBE_CHANGE_STATUS->value)->name('setting.subscribe.update');
            });

            Route::controller(GeneralSettingController::class)->middleware('permission:' . PermissionEnum::GENERAL_VIEW->value)->group(function () {
                Route::get('/settings/general', 'general')->name('setting.general');
                Route::post('/settings/general/read-view', 'readView')->name('setting.general.read.view');

                Route::post('/settings/general/update', 'update')->middleware('permission:' . PermissionEnum::GENERAL_UPDATE->value)->name('setting.general.update');
            });

            Route::controller(AddressSettingController::class)->middleware('permission:' . PermissionEnum::ADDRESS_VIEW->value)->group(function () {
                Route::get('/settings/address', 'address')->name('setting.address');
                Route::post('/settings/address/read-view', 'readView')->name('setting.address.read.view');

                Route::post('/settings/address/update', 'update')->middleware('permission:' . PermissionEnum::ADDRESS_UPDATE->value)->name('setting.address.update');
            });

            Route::controller(SocialSettingController::class)->middleware('permission:' . PermissionEnum::SOCIAL_VIEW->value)->group(function () {
                Route::get('/settings/social', 'social')->name('setting.social');
                Route::post('/settings/social/read-view', 'readView')->name('setting.social.read.view');

                Route::post('/settings/update', 'update')->middleware('permission:' . PermissionEnum::SOCIAL_UPDATE->value)->name('setting.social.update');
            });

            Route::controller(LogoSettingController::class)->middleware('permission:' . PermissionEnum::LOGO_VIEW->value)->group(function () {
                Route::get('/settings/logo', 'logo')->name('setting.logo');
                Route::post('/settings/logo/read-view', 'readView')->name('setting.logo.read.view');

                Route::middleware('permission:' . PermissionEnum::LOGO_UPDATE->value)->group(function () {
                    Route::post('/settings/logo/favicon-update', 'favicon')->name('setting.logo.favicon.update');
                    Route::post('/settings/logo/header-white-update', 'headerWhite')->name('setting.logo.header.white.update');
                    Route::post('/settings/logo/header-dark-update', 'headerDark')->name('setting.logo.header.dark.update');
                    Route::post('/settings/logo/footer-white-update', 'footerWhite')->name('setting.logo.footer.white.update');
                    Route::post('/settings/logo/footer-dark-update', 'footerDark')->name('setting.logo.footer.dark.update');
                });
            });

            Route::controller(PluginSettingController::class)->middleware('permission:' . PermissionEnum::PLUGIN_VIEW->value)->group(function () {
                Route::get('/settings/plugin', 'plugin')->name('setting.plugin');
                Route::post('/settings/plugin/read-view', 'readView')->name('setting.plugin.read.view');

                Route::post('/settings/plugin/update', 'update')->middleware('permission:' . PermissionEnum::PLUGIN_UPDATE->value)->name('setting.plugin.update');
            });

            Route::controller(EmailSettingController::class)->middleware('permission:' . PermissionEnum::EMAIL_VIEW->value)->group(function () {
                Route::get('/settings/email', 'email')->name('setting.email');
                Route::post('/settings/email/read-view', 'readView')->name('setting.email.read.view');
                Route::post('/settings/email/test-mail', 'testMail')->name('setting.email.test');

                Route::post('/settings/email/update', 'update')->middleware('permission:' . PermissionEnum::EMAIL_UPDATE->value)->name('setting.email.update');
            });
        });



        // User Management
        Route::controller(UserManagementController::class)
            ->middleware(['role:' . RoleEnum::SUPER_ADMIN->value . '|' . RoleEnum::ADMIN->value . '|' . RoleEnum::GUEST->value])->group(function () {

                /* SAYFA */
                Route::get('/user-managements', 'index')->name('user.management');

                /* OLUŞTURMA */
                Route::post('/user-managements/create', 'create')
                    ->middleware(['role:' . RoleEnum::SUPER_ADMIN->value . '|' . RoleEnum::ADMIN->value])
                    ->name('user.management.create');

                /* Detay Görüntüleme */
                Route::post('/user-managements/read-active-view', 'readActiveView')
                    ->middleware(['role:' . RoleEnum::SUPER_ADMIN->value . '|' . RoleEnum::ADMIN->value])
                    ->name('user.management.active.read.view');

                Route::post('/user-managements/read-deleted-view', 'readDeletedView')
                    ->middleware(['role:' . RoleEnum::SUPER_ADMIN->value . '|' . RoleEnum::ADMIN->value])
                    ->name('user.management.deleted.read.view');

                Route::post('/user-managements/read-banned-view', 'readBannedView')
                    ->middleware(['role:' . RoleEnum::SUPER_ADMIN->value . '|' . RoleEnum::ADMIN->value])
                    ->name('user.management.banned.read.view');

                /* GÜNCELLEME */
                // Yetkili Bilgileri
                Route::post('/user-managements/update', 'update')
                    ->middleware(['role:' . RoleEnum::SUPER_ADMIN->value . '|' . RoleEnum::ADMIN->value/*  . '|' . RoleEnum::GUEST->value */])
                    ->name('user.management.update');

                // Yetkili Şifre
                Route::post('/user-managements/update-password', 'updatePassword')
                    ->middleware(['role:' . RoleEnum::SUPER_ADMIN->value . '|' . RoleEnum::ADMIN->value/*  . '|' . RoleEnum::GUEST->value */])
                    ->name('user.management.update.password');

                // Yetkili İzinleri
                Route::post('/user-managements/update-permission', 'updatePermission')
                    ->middleware(['role:' . RoleEnum::SUPER_ADMIN->value . '|' . RoleEnum::ADMIN->value])
                    ->name('user.management.update.permission');


                /* SİLME */
                // Yetkili Silme
                Route::post('/user-managements/delete', 'delete')
                    ->middleware(['role:' . RoleEnum::SUPER_ADMIN->value . '|' . RoleEnum::ADMIN->value])
                    ->name('user.management.delete');

                // Silinen Yetkiliyi Geri Getir
                Route::post('/user-managements/trashed/restore', 'trashedRestore')
                    ->middleware(['role:' . RoleEnum::SUPER_ADMIN->value . '|' . RoleEnum::ADMIN->value])
                    ->name('user.management.trashed.restore');

                /* BANLAMA */
                // Yetkili Banlama
                Route::post('/user-managements/banned', 'banned')
                    ->middleware(['role:' . RoleEnum::SUPER_ADMIN->value . '|' . RoleEnum::ADMIN->value])
                    ->name('user.management.banned');

                // Yetkili Ban Kaldırma
                Route::post('/user-managements/banned-restore', 'bannedRestore')
                    ->middleware(['role:' . RoleEnum::SUPER_ADMIN->value . '|' . RoleEnum::ADMIN->value])
                    ->name('user.management.banned.restore');

                /* LİSTELEME */
                // Aktif Yetkililer
                Route::post('/user-managements/get-by-active-user', 'getByActiveUser')->name('user.management.get.by.active.user');

                // Yetkilileri Aktif ve Yetki Denetiminden Geçirerek Getirir
                Route::post('/user-managements/get-by-active-authorize-user', 'getByActiveAuthorizedUser')
                    ->name('user.management.get.by.active.authorize.user');

                // Silinmiş Yetkililer
                Route::post('/user-managements/get-by-user-delete', 'getByDeleteUser')->name('user.management.get.by.delete.user');

                // Banşanmış Yetkililer
                Route::post('/user-managements/get-by-user-banned', 'getByBannedUser')->name('user.management.get.by.banned.user');
            });



        // Language
        Route::controller(LanguageController::class)->middleware('permission:' . PermissionEnum::LANGUAGE_VIEW->value)->group(function () {
            Route::get('/languages', 'index')->name('language');
            Route::post('/languages/read-view', 'readView')->middleware('role:' . RoleEnum::SUPER_ADMIN->value)->name('language.read.view');
            Route::post('/languages/read-delete-view', 'readDeleteView')->middleware('role:' . RoleEnum::SUPER_ADMIN->value)->name('language.delete.read.view');

            Route::post('/languages/create', 'create')->middleware('role:' . RoleEnum::SUPER_ADMIN->value)->name('language.create');
            Route::post('/languages/change-status', 'changeStatus')->middleware('permission:' . PermissionEnum::LANGUAGE_CHANGE_STATUS->value)->name('language.change.status');
            Route::post('/languages/read', 'read')->middleware('role:' . RoleEnum::SUPER_ADMIN->value)->name('language.read');
            Route::post('/languages/update', 'update')->middleware('role:' . RoleEnum::SUPER_ADMIN->value)->name('language.update');
            Route::post('/languages/delete', 'delete')->middleware('role:' . RoleEnum::SUPER_ADMIN->value)->name('language.delete');
            Route::post('/languages/trashed/restore', 'trashedRestore')->middleware('role:' . RoleEnum::SUPER_ADMIN->value)->name('language.trashed.restore');
        });



        // Translation
        Route::controller(TranslationController::class)->middleware('permission:' . PermissionEnum::STATIC_TEXT_VIEW->value)->group(function () {
            Route::get('/translation/{language}/detail', 'detail')->name('translation.detail');
            Route::post('/translation/check-status', 'checkStatus')->name('translation.check.status');
        });



        // Translation Content
        Route::controller(TranslationContentController::class)->middleware('permission:' . PermissionEnum::STATIC_TEXT_VIEW->value)->group(function () {
            Route::post('/translation/content/read-view', 'readView')->name('translation.content.read.view');

            Route::post('/translation/content/create', 'create')->middleware('role:' . RoleEnum::SUPER_ADMIN->value)->name('translation.content.create');
            Route::post('/translation/content/read', 'read')->middleware('permission:' . PermissionEnum::STATIC_TEXT_READ->value)->name('translation.content.read');
            Route::post('/translation/content/update', 'update')->middleware('permission:' . PermissionEnum::STATIC_TEXT_UPDATE->value)->name('translation.content.update');
            Route::post('/translation/content/delete', 'delete')->middleware('role:' . RoleEnum::SUPER_ADMIN->value)->name('translation.content.delete');
        });
    });
});



Route::prefix(RoutePrefixEnum::FRONTEND->value)->name('frontend.')->group(function () {

    Route::get(session('locale'), [HomePageController::class, 'home'])->name('home');

    Route::get(session('locale') . '/{slug}', [DetailPageController::class, 'detail'])->name('detail');

    Route::get(session('locale') . '/{subSlug}/{slug}', [SubDetailPageController::class, 'subDetail'])->name('sub.detail');

    Route::controller(FormController::class)->group(function () {
        Route::post(session('locale') . '/form/subscribe', 'subscribeForm')->middleware('throttle:form')->name('form.subscribe');
        Route::post(session('locale') . '/form/comment', 'commentForm')->middleware('throttle:form')->name('form.comment');

        Route::post(session('locale') . '/form/contact', 'contactForm')->middleware('throttle:form')->name('form.contact');
        Route::post(session('locale') . '/form/service', 'serviceForm')->middleware('throttle:form')->name('form.service');
        Route::post(session('locale') . '/form/reference', 'referenceForm')->middleware('throttle:form')->name('form.reference');
    });
});
