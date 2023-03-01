<?php
/**
 * Date 01/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

use Carbon\Carbon;
use Ssiva\MpesaDaraja\Http\Core\Cache;
use Ssiva\MpesaDaraja\Http\Core\Config;

if (!function_exists('addInterval')) {
    /**
     * @param $value
     * @param $unit
     * @param \Carbon\Carbon|null $time
     *
     * @return \Carbon\Carbon
     */
    function addInterval($value, $unit = 'seconds', Carbon $time = null): Carbon
    {
        $time =  $time ?: Carbon::now();
        switch ($unit){
            case 'years':
                return $time->addYears($value);
            case 'months':
                return $time->addMonths($value);
            case 'week':
                return $time->addWeeks($value);
            case 'days':
                return $time->addDays($value);
            case 'hours':
                return $time->addHours($value);
            case 'minutes':
                return $time->addMinutes($value);
            default:
                return $time->addSeconds($value);
        }
    }
}

if (!function_exists('cache')) {
    /**
     * @return \Ssiva\MpesaDaraja\Http\Core\Cache
     */
    function cache(): Cache
    {
        return new Cache(\config());
    }
}

if (!function_exists('config')) {
    /**
     * @return \Ssiva\MpesaDaraja\Http\Core\Config
     */
    function config(): Config
    {
        return new Config();
    }
}
if (! function_exists('approot_path')) {
    function approot_path($file = ''): string
    {
        return __DIR__."/../../$file";
    }
}

