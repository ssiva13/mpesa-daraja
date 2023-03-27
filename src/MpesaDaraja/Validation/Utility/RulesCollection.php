<?php
/**
 * Date 27/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Validation\Utility;

use Ssiva\MpesaDaraja\Validation\Rules\Required;

class RulesCollection extends \SplObjectStorage
{
    /**
     * @param $object
     * @param $info
     *
     * @return void
     */
    public function attach($object, $info = null)
    {
        if ($this->contains($object)) {
            return;
        }
        if ($object instanceof Required) {
            $rules = array();
            foreach ($this as $r) {
                $rules[] = $r;
                $this->detach($r);
            }
            array_unshift($rules, $object);
            foreach ($rules as $r) {
                parent::attach($r);
            }

            return;
        }

        return parent::attach($object);
    }

}
