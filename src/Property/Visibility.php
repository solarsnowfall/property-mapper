<?php

namespace Solarsnowfall\Property;

class Visibility
{
    const ALL = -1;

    const PRIVATE = \ReflectionProperty::IS_PRIVATE;

    const PROTECTED = \ReflectionProperty::IS_PRIVATE;

    const PUBLIC = \ReflectionProperty::IS_PUBLIC;
}