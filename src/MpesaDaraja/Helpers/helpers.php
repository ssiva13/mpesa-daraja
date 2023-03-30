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
     * @param string $unit
     * @param \Carbon\Carbon|null $time
     *
     * @return \Carbon\Carbon
     */
    function addInterval($value, string $unit = 'seconds', Carbon $time = null): Carbon
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

if (!function_exists('cacheStore')) {
    /**
     * @return \Ssiva\MpesaDaraja\Http\Core\Cache
     */
    function cacheStore(): Cache
    {
        return new Cache(\configStore());
    }
}

if (!function_exists('configStore')) {
    /**
     * @return \Ssiva\MpesaDaraja\Http\Core\Config
     */
    function configStore(): Config
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

if (! function_exists('now')) {
    function now($format = ''): string
    {
        if($format){
            return Carbon::now()->format($format);
        }
        return Carbon::now();
    }
}

if (! function_exists('today')) {
    function today($format = ''): string
    {
        if($format){
            return Carbon::today()->format($format);
        }
        return Carbon::today();
    }
}

if (! function_exists('parseDate')) {
    function parseDate($string = ''): string
    {
        return Carbon::parse($string);
    }
}

if (! function_exists('authToken')) {
    function authToken($app = 'default'): string
    {
        return cacheStore()->get("{$app}_mpesa_access_token");
    }
}

if (! function_exists('formatParams')) {
    function formatParams($array = []): array
    {
        $modified_keys = array_map("modify_keys", array_keys($array), $array);
        // use array_combine() to create a new array with modified keys
        return  array_combine($modified_keys, $array);
    }
}

if (! function_exists('modify_keys')) {
    function modify_keys($key, $value): string
    {
        return ucwords($key);
    }
}

if (! function_exists('computeSecurityCredential')) {
    /**
     * @throws \Exception
     */
    function computeSecurityCredential($initiatorPass, $securityCert): string
    {
        // laravel and lumen
        $pubKeyFile =  \base_path($securityCert);
        if(!is_file($pubKeyFile)){
            throw new \Exception("Please provide a valid public key file");
        }
        $pubKey = file_get_contents($pubKeyFile);
        openssl_public_encrypt($initiatorPass, $encrypted, $pubKey, OPENSSL_PKCS1_PADDING);
        return base64_encode($encrypted);
    }
}
if (! function_exists('getConfigParams')) {
    function getConfigParams($type): array
    {
        $shortCode = configStore()->get("mpesa.account.short_code");
        $resultCallback = configStore()->get("mpesa.account.result_url");
        $timeoutCallback = configStore()->get("mpesa.account.timeout_url");
        $initiator = configStore()->get("mpesa.account.initiator_name");
        $commandId = configStore()->get("mpesa.account.$type.default_command_id");
        $initiatorPass = configStore()->get("mpesa.account.security_credential");
        $securityCert = configStore()->get("mpesa.account.security_cert");
        $identifierType = configStore()->get("mpesa.account.identifier_type");
        
        return [
            $shortCode, $resultCallback, $timeoutCallback,
            $initiator, $commandId, $initiatorPass,
            $securityCert, $identifierType
        ];
    }
}

