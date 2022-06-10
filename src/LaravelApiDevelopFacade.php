<?php

namespace Dipeshsukhia\LaravelApiDevelop;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Dipeshsukhia\LaravelApiDevelop\Skeleton\SkeletonClass
 */
class LaravelApiDevelopFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-api-develop';
    }
}
