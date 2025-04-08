<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Defaults\StatusEnum;
use App\Enums\Pages\Section\PageSectionEnum;
use App\Models\Section;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PageSectionSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run(): void
    {
        $user = User::first();
        $now = Carbon::now();

        // Banner
        Section::query()->updateOrCreate(
            ['section_type' => PageSectionEnum::BANNER->value],
            [
                'title'      => PageSectionEnum::BANNER->label(),
                'slug'       => str(PageSectionEnum::BANNER->label())->slug(),
                'default'    => StatusEnum::ACTIVE->value,
                'sorting'    => 1,
                'created_by' => $user?->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );


        // We Are
        Section::query()->updateOrCreate(
            ['section_type' => PageSectionEnum::WHO_WE_ARE->value],
            [
                'title'      => PageSectionEnum::WHO_WE_ARE->label(),
                'slug'       => str(PageSectionEnum::WHO_WE_ARE->label())->slug(),
                'default'    => StatusEnum::ACTIVE->value,
                'sorting'    => 2,
                'created_by' => $user?->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        Section::query()->updateOrCreate(
            ['section_type' => PageSectionEnum::WHO_WE_ARE_TWO->value],
            [
                'title'      => PageSectionEnum::WHO_WE_ARE_TWO->label(),
                'slug'       => str(PageSectionEnum::WHO_WE_ARE_TWO->label())->slug(),
                'default'    => StatusEnum::ACTIVE->value,
                'sorting'    => 3,
                'created_by' => $user?->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );


        // About
        Section::query()->updateOrCreate(
            ['section_type' => PageSectionEnum::ABOUT->value],
            [
                'title'      => PageSectionEnum::ABOUT->label(),
                'slug'       => str(PageSectionEnum::ABOUT->label())->slug(),
                'default'    => StatusEnum::ACTIVE->value,
                'sorting'    => 4,
                'created_by' => $user?->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        Section::query()->updateOrCreate(
            ['section_type' => PageSectionEnum::ABOUT_TWO->value],
            [
                'title'      => PageSectionEnum::ABOUT_TWO->label(),
                'slug'       => str(PageSectionEnum::ABOUT_TWO->label())->slug(),
                'default'    => StatusEnum::ACTIVE->value,
                'sorting'    => 5,
                'created_by' => $user?->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );


        // Mission & Vision
        Section::query()->updateOrCreate(
            ['section_type' => PageSectionEnum::MISSION_VISION->value],
            [
                'title'      => PageSectionEnum::MISSION_VISION->label(),
                'slug'       => str(PageSectionEnum::MISSION_VISION->label())->slug(),
                'default'    => StatusEnum::ACTIVE->value,
                'sorting'    => 6,
                'created_by' => $user?->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        Section::query()->updateOrCreate(
            ['section_type' => PageSectionEnum::MISSION_VISION_TWO->value],
            [
                'title'      => PageSectionEnum::MISSION_VISION_TWO->label(),
                'slug'       => str(PageSectionEnum::MISSION_VISION_TWO->label())->slug(),
                'default'    => StatusEnum::ACTIVE->value,
                'sorting'    => 7,
                'created_by' => $user?->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );


        // Service
        Section::query()->updateOrCreate(
            ['section_type' => PageSectionEnum::SERVICE->value],
            [
                'title'      => PageSectionEnum::SERVICE->label(),
                'slug'       => str(PageSectionEnum::SERVICE->label())->slug(),
                'default'    => StatusEnum::ACTIVE->value,
                'sorting'    => 8,
                'created_by' => $user?->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        Section::query()->updateOrCreate(
            ['section_type' => PageSectionEnum::SERVICE_GRID->value],
            [
                'title'      => PageSectionEnum::SERVICE_GRID->label(),
                'slug'       => str(PageSectionEnum::SERVICE_GRID->label())->slug(),
                'default'    => StatusEnum::ACTIVE->value,
                'sorting'    => 9,
                'created_by' => $user?->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        Section::query()->updateOrCreate(
            ['section_type' => PageSectionEnum::SERVICE_SLIDER->value],
            [
                'title'      => PageSectionEnum::SERVICE_SLIDER->label(),
                'slug'       => str(PageSectionEnum::SERVICE_SLIDER->label())->slug(),
                'default'    => StatusEnum::ACTIVE->value,
                'sorting'    => 10,
                'created_by' => $user?->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        // Business Processes
        Section::query()->updateOrCreate(
            ['section_type' => PageSectionEnum::BUSINESS_PROCESSES->value],
            [
                'title'      => PageSectionEnum::BUSINESS_PROCESSES->label(),
                'slug'       => str(PageSectionEnum::BUSINESS_PROCESSES->label())->slug(),
                'default'    => StatusEnum::ACTIVE->value,
                'sorting'    => 11,
                'created_by' => $user?->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );


        // Reference
        Section::query()->updateOrCreate(
            ['section_type' => PageSectionEnum::REFERENCE->value],
            [
                'title'      => PageSectionEnum::REFERENCE->label(),
                'slug'       => str(PageSectionEnum::REFERENCE->label())->slug(),
                'default'    => StatusEnum::ACTIVE->value,
                'sorting'    => 12,
                'created_by' => $user?->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        Section::query()->updateOrCreate(
            ['section_type' => PageSectionEnum::REFERENCE_GRID->value],
            [
                'title'      => PageSectionEnum::REFERENCE_GRID->label(),
                'slug'       => str(PageSectionEnum::REFERENCE_GRID->label())->slug(),
                'default'    => StatusEnum::ACTIVE->value,
                'sorting'    => 13,
                'created_by' => $user?->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        Section::query()->updateOrCreate(
            ['section_type' => PageSectionEnum::REFERENCE_SLIDER->value],
            [
                'title'      => PageSectionEnum::REFERENCE_SLIDER->label(),
                'slug'       => str(PageSectionEnum::REFERENCE_SLIDER->label())->slug(),
                'default'    => StatusEnum::ACTIVE->value,
                'sorting'    => 14,
                'created_by' => $user?->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );


        // Blog
        Section::query()->updateOrCreate(
            ['section_type' => PageSectionEnum::BLOG->value],
            [
                'title'      => PageSectionEnum::BLOG->label(),
                'slug'       => str(PageSectionEnum::BLOG->label())->slug(),
                'default'    => StatusEnum::ACTIVE->value,
                'sorting'    => 15,
                'created_by' => $user?->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        Section::query()->updateOrCreate(
            ['section_type' => PageSectionEnum::BLOG_LATEST->value],
            [
                'title'      => PageSectionEnum::BLOG_LATEST->label(),
                'slug'       => str(PageSectionEnum::BLOG_LATEST->label())->slug(),
                'default'    => StatusEnum::ACTIVE->value,
                'sorting'    => 16,
                'created_by' => $user?->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );


        //Brand
        Section::query()->updateOrCreate(
            ['section_type' => PageSectionEnum::BRAND->value],
            [
                'title'      => PageSectionEnum::BRAND->label(),
                'slug'       => str(PageSectionEnum::BRAND->label())->slug(),
                'default'    => StatusEnum::ACTIVE->value,
                'sorting'    => 17,
                'created_by' => $user?->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        Section::query()->updateOrCreate(
            ['section_type' => PageSectionEnum::BRAND_SLIDER->value],
            [
                'title'      => PageSectionEnum::BRAND_SLIDER->label(),
                'slug'       => str(PageSectionEnum::BRAND_SLIDER->label())->slug(),
                'default'    => StatusEnum::ACTIVE->value,
                'sorting'    => 18,
                'created_by' => $user?->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );


        // Faq
        Section::query()->updateOrCreate(
            ['section_type' => PageSectionEnum::FAQ->value],
            [
                'title'      => PageSectionEnum::FAQ->label(),
                'slug'       => str(PageSectionEnum::FAQ->label())->slug(),
                'default'    => StatusEnum::ACTIVE->value,
                'sorting'    => 19,
                'created_by' => $user?->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        Section::query()->updateOrCreate(
            ['section_type' => PageSectionEnum::FAQ_TWO->value],
            [
                'title'      => PageSectionEnum::FAQ_TWO->label(),
                'slug'       => str(PageSectionEnum::FAQ_TWO->label())->slug(),
                'default'    => StatusEnum::ACTIVE->value,
                'sorting'    => 20,
                'created_by' => $user?->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );


        // Contact Form
        Section::query()->updateOrCreate(
            ['section_type' => PageSectionEnum::CONTACT_FORM->value],
            [
                'title'      => PageSectionEnum::CONTACT_FORM->label(),
                'slug'       => str(PageSectionEnum::CONTACT_FORM->label())->slug(),
                'default'    => StatusEnum::ACTIVE->value,
                'sorting'    => 21,
                'created_by' => $user?->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );


        // Contact
        Section::query()->updateOrCreate(
            ['section_type' => PageSectionEnum::CONTACT->value],
            [
                'title'      => PageSectionEnum::CONTACT->label(),
                'slug'       => str(PageSectionEnum::CONTACT->label())->slug(),
                'default'    => StatusEnum::ACTIVE->value,
                'sorting'    => 22,
                'created_by' => $user?->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        Section::query()->updateOrCreate(
            ['section_type' => PageSectionEnum::CONTACT_TWO->value],
            [
                'title'      => PageSectionEnum::CONTACT_TWO->label(),
                'slug'       => str(PageSectionEnum::CONTACT_TWO->label())->slug(),
                'default'    => StatusEnum::ACTIVE->value,
                'sorting'    => 23,
                'created_by' => $user?->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );


        // Footer
        Section::query()->updateOrCreate(
            ['section_type' => PageSectionEnum::FOOTER->value],
            [
                'title'      => PageSectionEnum::FOOTER->label(),
                'slug'       => str(PageSectionEnum::FOOTER->label())->slug(),
                'default'    => StatusEnum::ACTIVE->value,
                'sorting'    => 24,
                'created_by' => $user?->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        Section::query()->updateOrCreate(
            ['section_type' => PageSectionEnum::FOOTER_TWO->value],
            [
                'title'      => PageSectionEnum::FOOTER_TWO->label(),
                'slug'       => str(PageSectionEnum::FOOTER_TWO->label())->slug(),
                'default'    => StatusEnum::ACTIVE->value,
                'sorting'    => 25,
                'created_by' => $user?->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );


        // Privacy Policy
        Section::query()->updateOrCreate(
            ['section_type' => PageSectionEnum::PRIVACY_POLICY->value],
            [
                'title'      => PageSectionEnum::PRIVACY_POLICY->label(),
                'slug'       => str(PageSectionEnum::PRIVACY_POLICY->label())->slug(),
                'default'    => StatusEnum::ACTIVE->value,
                'sorting'    => 26,
                'created_by' => $user?->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );


        // Terms & Condition
        Section::query()->updateOrCreate(
            ['section_type' => PageSectionEnum::TERMS_CONDITION->value],
            [
                'title'      => PageSectionEnum::TERMS_CONDITION->label(),
                'slug'       => str(PageSectionEnum::TERMS_CONDITION->label())->slug(),
                'default'    => StatusEnum::ACTIVE->value,
                'sorting'    => 27,
                'created_by' => $user?->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }
}
