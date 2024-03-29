<?php

namespace Riyu\Validation;

use Riyu\Helpers\Errors\AppException;

class Validation
{
    use Validator;

    /**
     * Make validation
     * 
     * @param array $data
     * @param array $rules
     * @return array|bool
     */
    public static function make(array $data, array $rules)
    {
        $errors = [];
        if (!is_array($data) || !is_array($rules)) {
            return false;
        }
        self::$data = $data;
        foreach ($rules as $field => $rule) {
            $rules = explode('|', $rule);
            foreach ($rules as $rule) {
                $rule = explode(':', $rule);
                $ruleName = $rule[0];
                $ruleValue = $rule[1] ?? null;
                if (method_exists(self::class, $ruleName)) {
                    $error = self::$ruleName($data[$field], $field, $ruleValue);
                    if ($error) {
                        $errors[$field][] = $error;
                    }
                } else {
                    throw new AppException("Rule $ruleName not found");
                }
            }
        }
        return $errors;
    }

    /**
     * Custom error message
     * 
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @return array|bool
     */
    public static function message(array $data, array $rules, array $messages)
    {
        if (!is_array($data) || !is_array($rules) || !is_array($messages)) {
            return false;
        }
        $errors = [];
        self::$data = $data;
        foreach ($rules as $field => $rule) {
            $rules = explode('|', $rule);
            foreach ($rules as $rule) {
                $rule = explode(':', $rule);
                $ruleName = $rule[0];
                $ruleValue = $rule[1] ?? null;
                if (method_exists(self::class, $ruleName)) {
                    $error = self::$ruleName($data[$field], $field, $ruleValue);
                    if ($error) {
                        if (array_key_exists($ruleName, $messages)) {
                            $messages[$ruleName] = self::replace($messages[$ruleName], $field, $ruleName, $ruleValue);
                            $errors[$field][] = $messages[$ruleName];
                        } else {
                            $errors[$field][] = $error;
                        }
                    }
                } else {
                    throw new AppException("Rule $ruleName not found");
                }
            }
        }
        return $errors;
    }

    public static function __callStatic($name, $arguments)
    {
        if (method_exists(self::class, $name)) {
            return self::$name(...$arguments);
        }
    }

    public function __call($name, $arguments)
    {
        if (method_exists(self::class, $name)) {
            return self::$name(...$arguments);
        }
    }

    /**
     * Get first error
     * 
     * @param array $errors
     * @return string|null
     */
    public static function first($errors)
    {
        if (is_array($errors)) {
            foreach ($errors as $error) {
                return $error[0];
            }
        }
    }

    /**
     * Get all errors
     * 
     * @param array $errors
     * @return array|null
     */
    public static function all($errors)
    {
        if (is_array($errors)) {
            return $errors;
        }
    }

    public static function replace($message, $field, $rule, $value)
    {
        $message = str_replace(':field', $field, $message);
        try {
            $message = str_replace(":$rule", $value, $message);
        } catch (\Throwable $th) {
        }
        return $message;
    }
}
