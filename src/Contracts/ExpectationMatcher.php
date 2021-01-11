<?php

namespace EasyHttp\MockBuilder\Contracts;

use EasyHttp\MockBuilder\Expectation;

interface ExpectationMatcher
{
    public static function from(Expectation $expectation): callable;
}
