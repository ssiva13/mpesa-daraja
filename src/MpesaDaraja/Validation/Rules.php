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
    
    public function rule(): string
    {
        return match ($this) {
            self::REQUIRED => 'required',
            self::REQUIRED_IF => 'required_if:?',
            self::STRING => 'alpha_numeric',
            self::NUMERIC => 'integer',
            self::DATE => 'date',
            self::DATETIME => 'datetime',
            self::TIME => 'time',
            self::GT => 'gt',
            self::GTE => 'gte',
            self::LT => 'lt',
            self::LTE => 'lte',
            self::EQUAL => 'eq',
        };
    }
}
