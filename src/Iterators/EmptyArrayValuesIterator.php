<?php

namespace EasyHttp\MockBuilder\Iterators;

class EmptyArrayValuesIterator extends ArrayIterator
{
    public function __construct(array $collection)
    {
        $this->filterNotNullParameters($collection);
        parent::__construct($collection);
    }

    private function filterNotNullParameters(array &$collection)
    {
        $collection = array_filter(
            $collection,
            function ($value) {
                return empty($value);
            }
        );
    }
}
