<?php

namespace App\DTO\Backend\Pages;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class PageCreateDTO implements BaseDTOInterface
{
    /**
     * @param int|null $topPage
     * @param UploadedFile|null $image
     * @param int $sorting
     * @param string $design
     * @param string $menu
     * @param string $status
     * @param string $breadcrumb
     * @param array $languages
     *
     * @return void
     */
    public function __construct(
        public readonly ?int $topPage,
        public readonly ?UploadedFile $image,
        public readonly int $sorting,
        public readonly string $design,
        public readonly string $menu,
        public readonly string $status,
        public readonly string $breadcrumb,
        public readonly array $languages,
    ) {
        //
    }

    /**
     * @param Request $request
     *
     * @return self
     */
    public static function fromRequest(Request $request): self
    {
        $valid = $request->validated();

        $topPage    = $valid['top_page'] ?? null;
        $image      = $valid['image'] ?? null;
        $sorting    = $valid['sorting'];
        $design     = $valid['design'];
        $menu       = $valid['menu'];
        $status     = $valid['status'];
        $breadcrumb = $valid['breadcrumb'];

        unset(
            $valid['top_page'],
            $valid['image'],
            $valid['sorting'],
            $valid['design'],
            $valid['menu'],
            $valid['status'],
            $valid['breadcrumb'],
        );

        return new self(
            topPage: $topPage,
            image: $image,
            sorting: $sorting,
            design: $design,
            menu: $menu,
            status: $status,
            breadcrumb: $breadcrumb,
            languages: $valid,
        );
    }
}
