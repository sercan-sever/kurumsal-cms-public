<?php

declare(strict_types=1);

namespace App\Services\Backend\Reference;

use App\Interfaces\Reference\ReferenceServiceReferenceManagerInterface;
use App\Models\Reference;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ReferenceServiceReferenceManage implements ReferenceServiceReferenceManagerInterface
{
    /**
     * @param Model $reference
     * @param array $services
     *
     * @return Reference|null
     */
    public function updateOrCreateContent(Model $reference, array $services): ?Reference
    {
        try {
            $reference->services()->sync($services);

            $reference->load(['services']);

            return $reference;
        } catch (\Exception $exception) {
            Log::error("ReferenceServiceReferenceManage (updateOrCreateContent) : ", context: [$exception->getMessage()]);
            return null;
        }
    }
}
