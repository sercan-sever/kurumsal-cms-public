<?php

declare(strict_types=1);

namespace App\Traits\Model;

use App\Enums\Defaults\StatusEnum;

trait HasStatusInput
{
    /**
     * @return string
     */
    public function getStatusInput(): string
    {
        $checked = $this->status->value == StatusEnum::ACTIVE->value ? 'checked' : '';

        return '<label class="form-check form-switch form-check-custom form-check-solid cst-status justify-content-center">
                    <input class="form-check-input cst-datatable-status" name="status" type="checkbox" ' .  $checked . ' />
                </label>';
    }

    /**
     * @return string
     */
    public function getStatusBadgeHtml(): string
    {
        $comment = $this->isActiveStatus() ? 'Aktif' : 'Pasif';
        $color   = $this->isActiveStatus() ? 'success' : 'danger';

        return '<span class="badge badge-' . $color  . ' p-3">' . $comment . '</span>';
    }

    /**
     * @return bool
     */
    public function isActiveStatus(): bool
    {
        return $this->status->value == StatusEnum::ACTIVE->value;
    }
}
