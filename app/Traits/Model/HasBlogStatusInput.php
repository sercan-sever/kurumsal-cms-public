<?php

declare(strict_types=1);

namespace App\Traits\Model;

use App\Enums\Defaults\StatusEnum;

trait HasBlogStatusInput
{
    /**
     * @return string
     */
    public function getCommentStatusInput(): string
    {
        $checked = $this->comment_status->value == StatusEnum::ACTIVE->value ? 'checked' : '';

        return '<label class="form-check form-switch form-check-custom form-check-solid cst-status justify-content-center">
                    <input class="form-check-input cst-datatable-comment-status" name="status" type="checkbox" ' .  $checked . ' />
                </label>';
    }

    /**
     * @return string
     */
    public function getCommentStatusBadgeHtml(): string
    {
        $comment = $this->isActiveCommentStatus() ? 'Aktif' : 'Pasif';
        $color   = $this->isActiveCommentStatus() ? 'success' : 'danger';

        return '<span class="badge badge-' . $color  . ' p-3">' . $comment . '</span>';
    }

    /**
     * @return bool
     */
    public function isActiveCommentStatus(): bool
    {
        return $this->comment_status->value == StatusEnum::ACTIVE->value;
    }
}
