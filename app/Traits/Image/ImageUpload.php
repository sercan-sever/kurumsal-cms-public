<?php

declare(strict_types=1);

namespace App\Traits\Image;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;

trait ImageUpload
{
    /**
     * @return array<string, string>
     */
    private array $result = [
        'image' => "",
        'type'  => "",
    ];


    /**
     * @return array<string, string>
     */
    private array $data = [
        'name' => "",
        'file' => "",
        'type' => "",
    ];


    /**
     * @param UploadedFile $file
     * @param string $path
     * @param int $width
     * @param int $height
     *
     * @return array
     */
    public function imageUpload(UploadedFile $file, string $path, int $width = 400, int $height = 400): array
    {
        try {
            $fileInfo = $this->getFileInfo(file: $file, path: $path);

            $img = ImageManager::imagick()->read($file);

            $img->resize($width, $height)->resizeCanvas($width, $height);

            if (!File::exists(public_path('images/' . $path))) {
                File::makeDirectory(public_path('images/' . $path));
            }

            $folder = $img->save(path: public_path($fileInfo['file']));

            if (!empty($folder)) {
                $this->result['image'] = $fileInfo['file'];
                $this->result['type'] = $fileInfo['type'];
            }

            return $this->result;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->result;
        }
    }


    /**
     *
     * @param UploadedFile $file
     *
     * @return array<string, string>
     */
    private function getFileInfo(UploadedFile $file, string $path): array
    {
        // $this->data['original_name'] = $file->getClientOriginalName();
        $this->data['name'] = $file->hashName();
        $this->data['type'] = $file->extension();
        $this->data['file'] = 'images/' . $path . '/' . str($this->data['name'])->slug() . '-' . createUniqueKey() . '.' . $this->data['type'];

        return $this->data;
    }
}
