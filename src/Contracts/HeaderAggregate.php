<?php

namespace EasyHttp\MockBuilder\Contracts;

use EasyHttp\MockBuilder\Iterators\NotEmptyArrayValuesIterator;

interface HeaderAggregate
{
    public function notEmptyHeadersIterator(): NotEmptyArrayValuesIterator;
}
