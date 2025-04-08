<?php

namespace App\DTO\Backend\Settings\Subscribe;

use App\Interfaces\DTO\BaseDTOInterface;
use Illuminate\Http\Request;

class SubscribeUpdateDTO implements BaseDTOInterface
{
    /**
     * @param string $status
     *
     * @return void
     */
    public function __construct(
        public readonly string $status,
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

        return new self(
            status: $valid['status'],
        );
    }
}
