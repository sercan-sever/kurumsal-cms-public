<?php

declare(strict_types=1);

namespace App\Traits\Image;

use Illuminate\Support\Facades\File;

trait ImageDelete
{
    /**
     * @param string|null $image
     *
     * @return bool
     */
    public function imageDelete(?string $image): bool
    {
        $result = false;

        try {
            $path = public_path($image);

            if (File::exists($path)) {

                @unlink($path);

                $result = true;
            }

            return $result;
        } catch (\Exception $exception) {

            return $result;
        }
    }
}
