<?php
/**
 * Date 26/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */

namespace Ssiva\MpesaDaraja\Validation;

enum Rules
{
    case REQUIRED;
    case REQUIRED_IF;
    case STRING;
    case NUMERIC;
    case DATE;
    case DATETIME;
    case TIME;
    case GT;
    case GTE;
    case LT;
    case LTE;
    case EQUAL;
    case EXISTS_IN;
    case PHONE;
    case MAX;
    case MIN;
    case URL;

    public function rule(): string
    {
        return match ($this) {
            self::STRING => 'alpha_numeric',
            self::DATE => 'date',
            self::DATETIME => 'date_time',
            self::EXISTS_IN => 'exists',
            self::GTE => 'greater_than_equal',
            self::NUMERIC => 'integer',
            self::LTE => 'less_than_equal',
            self::MAX => 'max',
            self::MIN => 'min',
            self::PHONE => 'phone',
            self::REQUIRED => 'required',
            self::URL => 'url',
            self::REQUIRED_IF => 'required_if',
    
            self::TIME => 'time',
            self::GT => 'gt',
            self::LT => 'lt',
            self::EQUAL => 'eq',
        };
    }
}
