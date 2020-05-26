<?php

declare(strict_types=1);

namespace Roave\BetterReflection\Reflection\StringCast;

use Roave\BetterReflection\Reflection\ReflectionClassConstant;
use function assert;
use function gettype;
use function is_scalar;
use function sprintf;

/**
 * @internal
 */
final class ReflectionClassConstantStringCast
{
    public static function toString(ReflectionClassConstant $constantReflection) : string
    {
        $value = $constantReflection->getValue();
        assert($value === null || is_scalar($value));

        return sprintf(
            "Constant [ %s %s %s ] { %s }\n",
            self::visibilityToString($constantReflection),
            gettype($value),
            $constantReflection->getName(),
            (string) $value
        );
    }

    private static function visibilityToString(ReflectionClassConstant $constantReflection) : string
    {
        if ($constantReflection->isProtected()) {
            return 'protected';
        }

        if ($constantReflection->isPrivate()) {
            return 'private';
        }

        return 'public';
    }
}
