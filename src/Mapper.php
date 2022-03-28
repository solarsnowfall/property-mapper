<?php

namespace Solarsnowfall\Property;

use Solarsnowfall\String\Convention;

abstract class Mapper
{
    const MAGIC_GETTERS = false;

    const MAGIC_SETTERS = false;

    const PROPERTY_CONVENTION = null;

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        $prefix = substr(strtolower($name), 0, 3);

        if ($prefix === 'get' && !static::MAGIC_GETTERS || $prefix === 'set' && !static::MAGIC_SETTERS)
            trigger_error("Call to undefined method " . get_called_class() . "::{$name}()", E_USER_ERROR);

        $property = ltrim(substr($name, 3), '_');

        $property = Convention::convert($property, static::PROPERTY_CONVENTION);

        if (!property_exists($this, $property))
            trigger_error("Call to undefined method " . get_called_class() . "::{$name}()", E_USER_ERROR);

        if ($prefix === 'get')
            return $this->$property;

        if (!count($arguments))
            throw new \ArgumentCountError("Too few arguments to function " . get_called_class() . "::{$name}()");

        $this->$property = $arguments[0];

        return $this;
    }

    /**
     * @param int $visibility
     * @return $this
     */
    public function destroyProperties(int $visibility = Visibility::ACCESSIBLE): Mapper
    {
        foreach (static::listProperties($visibility) as $name)
            unset($this->$name);

        return $this;
    }

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

    /**
     * @param array $properties
     * @return $this
     */
    public function unsetProperties(array $properties): Mapper
    {
        foreach ($properties as $name)
            unset($this->$name);

        return $this;
    }
}