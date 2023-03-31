<?php
/**
 * Date 26/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Validation;

interface ValidatorInterface
{
    public function add($param, $rules);

    public function remove($param, $rules = true);

    public function validate(array $params = []);
}
