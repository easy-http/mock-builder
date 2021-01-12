<?php

namespace EasyHttp\MockBuilder\Contracts;

use EasyHttp\MockBuilder\Iterators\NotEmptyQueryParamsIterator;
use EasyHttp\MockBuilder\Iterators\EmptyQueryParamsIterator;

interface QueryParameterAggregate
{
    public function notEmptyQueryParamsIterator(): NotEmptyQueryParamsIterator;
    public function emptyQueryParamsIterator(): EmptyQueryParamsIterator;
}
