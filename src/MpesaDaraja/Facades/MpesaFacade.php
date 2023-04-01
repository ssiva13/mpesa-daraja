<?php

namespace Ssiva\MpesaDaraja\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Date 01/04/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */
class MpesaFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'mpesa-daraja';
    }
}