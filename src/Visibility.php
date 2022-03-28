<?php

namespace Solarsnowfall\Property;

class Visibility
{
    const ALL = -1;

    const ACCESSIBLE = \ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED;

    const PRIVATE = \ReflectionProperty::IS_PRIVATE;

    const PROTECTED = \ReflectionProperty::IS_PROTECTED;

    const PUBLIC = \ReflectionProperty::IS_PUBLIC;
}