<?php
/**
 * Hele eenvoudige JSON-schema validator, voldoende voor de subset
 * die we gebruiken in MCP tool inputSchema (type, properties, required,
 * additionalProperties, enum, minLength, maxLength, minimum, maximum,
 * minItems, items).
 */

declare(strict_types=1);

namespace PolitiekPraat\MCP;

final class SchemaValidator
{
    /** @return string[] validation errors, empty when valid */
    public static function validate($value, array $schema, string $path = '$'): array
    {
        $errors = [];

        $type = $schema['type'] ?? null;
        if ($type !== null && !self::matchesType($value, $type)) {
            $errors[] = "$path moet van type $type zijn";
            return $errors;
        }

        if (isset($schema['enum']) && !in_array($value, $schema['enum'], true)) {
            $errors[] = "$path moet één van [" . implode(',', (array) $schema['enum']) . '] zijn';
        }

        if ($type === 'string' && is_string($value)) {
            if (isset($schema['minLength']) && mb_strlen($value) < (int) $schema['minLength']) {
                $errors[] = "$path minimaal {$schema['minLength']} tekens";
            }
            if (isset($schema['maxLength']) && mb_strlen($value) > (int) $schema['maxLength']) {
                $errors[] = "$path maximaal {$schema['maxLength']} tekens";
            }
        }

        if (($type === 'integer' || $type === 'number') && is_numeric($value)) {
            if (isset($schema['minimum']) && $value < $schema['minimum']) {
                $errors[] = "$path >= {$schema['minimum']} vereist";
            }
            if (isset($schema['maximum']) && $value > $schema['maximum']) {
                $errors[] = "$path <= {$schema['maximum']} vereist";
            }
        }

        if ($type === 'array' && is_array($value)) {
            if (isset($schema['minItems']) && count($value) < (int) $schema['minItems']) {
                $errors[] = "$path minimaal {$schema['minItems']} items";
            }
            if (isset($schema['items']) && is_array($schema['items'])) {
                foreach ($value as $i => $item) {
                    $errors = array_merge($errors, self::validate($item, $schema['items'], $path . "[$i]"));
                }
            }
        }

        if ($type === 'object' && is_array($value)) {
            foreach (($schema['required'] ?? []) as $req) {
                if (!array_key_exists($req, $value)) {
                    $errors[] = "$path.$req is verplicht";
                }
            }
            $props = $schema['properties'] ?? [];
            if (is_object($props)) {
                $props = (array) $props;
            }
            foreach ($value as $key => $v) {
                if (isset($props[$key]) && is_array($props[$key])) {
                    $errors = array_merge($errors, self::validate($v, $props[$key], $path . '.' . $key));
                } elseif (isset($schema['additionalProperties']) && $schema['additionalProperties'] === false) {
                    $errors[] = "$path.$key is niet toegestaan";
                }
            }
        }

        return $errors;
    }

    private static function matchesType($value, string $type): bool
    {
        switch ($type) {
            case 'string':  return is_string($value);
            case 'integer': return is_int($value);
            case 'number':  return is_int($value) || is_float($value);
            case 'boolean': return is_bool($value);
            case 'array':   return is_array($value) && array_keys($value) === range(0, count($value) - 1);
            case 'object':  return is_array($value);
            case 'null':    return $value === null;
        }
        return true;
    }
}
