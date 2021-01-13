<?php

namespace EasyHttp\MockBuilder\Helpers;

class GuzzleHeaderParser
{
    public static function parse(array $headers): array
    {
        $_headers = [];

        foreach ($headers as $key => $value) {
            $_headers[$key] = array_shift($value);
        }

        return $_headers;
    }
}
