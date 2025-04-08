<?php

declare(strict_types=1);

namespace App\Traits\Model;

use App\Enums\Defaults\StatusEnum;

trait HasBreadcrumbInput
{
    /**
     * @return string
     */
    public function getBreadcrumbInput(): string
    {
        $checked = $this->breadcrumb->value == StatusEnum::ACTIVE->value ? 'checked' : '';

        return '<label class="form-check form-switch form-check-custom form-check-solid cst-status justify-content-center">
                    <input class="form-check-input cst-datatable-breadcrumb" name="breadcrumb" type="checkbox" ' .  $checked . ' />
                </label>';
    }

    /**
     * @return string
     */
    public function getBreadcrumbBadgeHtml(): string
    {
        $comment = $this->isActiveBreadcrumb() ? 'Aktif' : 'Pasif';
        $color   = $this->isActiveBreadcrumb() ? 'success' : 'danger';

        return '<span class="badge badge-' . $color  . ' p-3">' . $comment . '</span>';
    }

    /**
     * @return bool
     */
    public function isActiveBreadcrumb(): bool
    {
        return $this->breadcrumb->value == StatusEnum::ACTIVE->value;
    }
}
