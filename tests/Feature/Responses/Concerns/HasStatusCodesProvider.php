<?php

namespace EasyHttp\MockBuilder\Tests\Feature\Responses\Concerns;

trait HasStatusCodesProvider
{
    public function statusCodesProvider(): array
    {
        return [
            'Status code 200' => [200],
            'Status code 500' => [500],
            'Status code 404' => [404],
        ];
    }
}
