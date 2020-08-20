<?php

namespace LamaLama\LaravelBuckaroo;

use Illuminate\Support\Facades\Facade;

/**
 * @see \LamaLama\LaravelBuckaroo\Skeleton
 */
class SkeletonFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'skeleton';
    }
}
