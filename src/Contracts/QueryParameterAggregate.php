<?php

namespace EasyHttp\MockBuilder\Contracts;

use EasyHttp\MockBuilder\Iterators\ArrayIterator;
use EasyHttp\MockBuilder\Iterators\NotEmptyArrayValuesIterator;
use EasyHttp\MockBuilder\Iterators\EmptyArrayValuesIterator;

interface QueryParameterAggregate
{
    public function notEmptyQueryParamsIterator(): NotEmptyArrayValuesIterator;
    public function emptyQueryParamsIterator(): EmptyArrayValuesIterator;
    public function missingQueryParamsIterator(): ArrayIterator;
}
