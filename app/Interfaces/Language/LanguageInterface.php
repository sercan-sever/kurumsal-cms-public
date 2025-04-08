<?php

declare(strict_types=1);

namespace App\Interfaces\Language;

use App\Interfaces\Base\BaseBackendInterface;
use App\Models\Language;
use Illuminate\Support\Collection;

interface LanguageInterface extends BaseBackendInterface
{
    /**
     * @return Collection
     */
    public function getAllLanguages(): Collection;

    /**
     * @return Collection
     */
    public function getAllActiveStatusLanguages(): Collection;

    /**
     * @param int $id
     *
     * @return Language|null
     */
    public function readLanguage(int $id): ?Language;

    /**
     * @param string $code
     *
     * @return Language|null
     */
    public function readLanguageByCode(string $code): ?Language;

    /**
     *  @return int
     */
    public function deactivateAllDefaultLanguages(): int;
}
