<?php

declare(strict_types=1);

namespace App\Traits\Model;

trait HasCrudUserAt
{
    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return !empty($this->created_at) ? $this->created_at->format('d-m-Y H:i') : '';
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return !empty($this->updated_at) ? $this->updated_at->format('d-m-Y H:i') : '';
    }

    /**
     * @return string
     */
    public function getBannedAt(): string
    {
        return !empty($this->banned_at) ? $this->banned_at->format('d-m-Y H:i') : '';
    }

    /**
     * @return string
     */
    public function getDeletedAt(): string
    {
        return !empty($this->deleted_at) ? $this->deleted_at->format('d-m-Y H:i') : '';
    }
}
