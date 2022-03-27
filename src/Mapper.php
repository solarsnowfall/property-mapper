<?php

namespace Solarsnowfall\Property;

abstract class Mapper
{
    /**
     * @param array $properties
     * @param int $visibility
     * @return $this
     */
    public function importProperties(array $properties, int $visibility = Visibility::ACCESSIBLE): Mapper
    {
        foreach (static::listProperties($visibility) as $name)
            if (array_key_exists($name, $properties))
                $this->$name = $properties[$name];

        return $this;
    }

    /**
     * @param int $visibility
     * @return array
     */
    public function exportProperties(int $visibility = Visibility::ACCESSIBLE): array
    {
        $properties = [];

        foreach (static::listProperties($visibility) as $name)
            $properties[$name] = $this->$name;

        return $properties;
    }

    /**
     * @param int $visibility
     * @return array
     */
    public static function listProperties(int $visibility = Visibility::ACCESSIBLE): array
    {
        $reflection = new \ReflectionClass(get_called_class());

        $properties = [];

        foreach ($reflection->getProperties($visibility) as $property)
            if (!$property->isStatic())
                $properties[] = $property->name;

        return $properties;
    }
}