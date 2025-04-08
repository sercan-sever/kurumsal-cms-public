<?php

declare(strict_types=1);

namespace App\Traits\Model;

use Illuminate\Support\Facades\File;

trait HasImage
{
    /**
     * @return string
     */
    public function getImage(): string
    {
        if (!empty($this->image) && File::exists(public_path($this->image))) {
            return asset($this->image);
        }

        return asset('backend/assets/media/svg/files/blank-image.svg');
    }


    /**
     * @return string
     */
    public function getImageHtml(): string
    {
        $html = '<span class="symbol symbol-50px ms-4">
                    <img class="rounded-1" src="{$image}">
                </span>';

        if (!empty($this->image) && File::exists(public_path($this->image))) {
            $html = str_replace('{$image}', asset($this->image), $html);

            return $html;
        }

        $html = str_replace('{$image}', asset('backend/assets/media/svg/files/blank-image.svg'), $html);

        return $html;
    }


    /**
     * @return string
     */
    public function getOtherImage(): string
    {
        if (!empty($this->other_image) && File::exists(public_path($this->other_image))) {
            return asset($this->other_image);
        }

        return asset('backend/assets/media/svg/files/blank-image.svg');
    }

    /**
     * @return string
     */
    public function getOtherImageHtml(): string
    {
        $html = '<span class="symbol symbol-50px ms-4">
                    <img class="rounded-1" src="{$image}">
                </span>';

        if (!empty($this->other_image) && File::exists(public_path($this->other_image))) {
            $html = str_replace('{$image}', asset($this->other_image), $html);

            return $html;
        }

        $html = str_replace('{$image}', asset('backend/assets/media/svg/files/blank-image.svg'), $html);

        return $html;
    }
}
