<?php

namespace App\Services\Db\Orders;

use App\Models\Stage;
use App\Support\Arr;
use App\Support\Str;

class StageFieldsBaseService
{
    protected string $fieldsProperty = 'fields';

    protected array $_props = [];

    /**
     * Get array value from fields array data.
     */
    protected function getArray(array $array, array|string $keyPath, mixed $default = []): ?array
    {
        $value = Arr::getValue($array, $keyPath);

        return $value !== null ? (array)$value : null;
    }

    /**
     * Get array of values from fields array data.
     */
    protected function getArrayList(array $array, array|string $keyPath, mixed $default = []): ?array
    {
        $value = Arr::getValue($array, $keyPath);

        return $value !== null ? (array)array_values($value) : null;
    }

    /**
     * Get boolean value from fields array data.
     */
    protected function getBool(array $array, array|string $keyPath, mixed $default = null): ?bool
    {
        $value = Arr::getValue($array, $keyPath, $default);

        return $value !== null ? (bool)$value : null;
    }

    /**
     * Whether exists field within array data.
     */
    protected function getExists(array $array, array|string $keyPath): bool
    {
        return Arr::keyExists($array, $keyPath);
    }

    /**
     * Get string value from fields array data.
     */
    protected function getString(array $array, array|string $keyPath, mixed $default = null): ?string
    {
        $value = Arr::getValue($array, $keyPath, $default);

        return $value !== null ? (string)$value : null;
    }

    /**
     * Fills stage's fields values.
     */
    public function fill(Stage &$stage, $requestData, bool $fillEmptyProps = false): void
    {
        $stageFields = $fillEmptyProps ? [] : $stage->{$this->fieldsProperty};

        foreach ($this->_props as $prop) {
            $method = 'fill' . Str::toCamelCase($prop, '_');

            if (!isset($requestData[$prop]) && !$fillEmptyProps) {
                continue;
            }

            $this->$method($stageFields, $requestData, $fillEmptyProps);
        }

        $stage->{$this->fieldsProperty} = $stageFields;
    }
}
