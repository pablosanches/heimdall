<?php

namespace PabloSanches;

use Exception;
use PabloSanches\Annotations;

/**
 * Magic class that validates other amazing classes
 */
abstract class Heimdall
{
    /**
     * Class that magic will happen
     */
    private static $_class;

    /**
     * Default messages
     */
    private static $_defaultMessages = array(
        'required' => 'Campo {field} é obrigatório.',
        'type' => array(
            'string' => 'Campo {field} precisa ser uma string.',
            'date' => 'Campo {field} precisa ser uma data válida no formato (d/m/Y).',
            'email' => 'Campo {field} precisa ser um e-mail válido.',
            'phone' => 'Campo {field} precisa ser um telefone válido.',
            'number' => 'Campo {field} precisa ser um número.',
            'chosen' => 'Campo {field} precisa ser uma das opções {instruction}.'
        ),
        'maxlength' => 'Campo {field} precisa ter no máximo {instruction} caracteres.',
        'minlength' => 'Campo {field} precisa ter no mínimo {instruction} caracteres.',
    );

    /**
     * Start the magic
     */
    public function validate($class)
    {

        if (!is_object($class)) {
            throw new Exception("You must to pass an valid object.");
        }

        self::$_class = $class;

        $Annotation = new Annotations($class);
        $requirements = $Annotation->getPropertiesAnnotations();
        $rulesResults = array();
        if (!empty($requirements)) {
            foreach ($requirements as $field => $rules) {
                if (array_key_exists('required', $requirements[$field]) || !empty(self::$_class->$field)) {
                    self::validateRules($field, $rules, $requirements, $rulesResults);
                }
            }
        }

        if (empty($rulesResults)) {
            return true;
        } else {
            return $rulesResults;
        }
    }
    
    /**
     * Start validating all fields
     */
    private static function validateRules($field, array $rules, array $requirements, array &$rulesResults)
    {
        if (!empty($rules)) {
            $result = array();

            foreach ($rules as $rule => $instruction) {

                switch ($rule) {
                    case 'required':
                        if (!self::isRequired($field)) {
                            $result[$field][$rule]['message'] = self::getMessage($field, $rule, $instruction, $requirements);
                        }
                    break;

                    case 'type':
                        self::isType($field, $instruction, $result, $requirements);
                    break;

                    case 'maxlength':
                        if (!self::maxlength($field, $instruction)) {
                            $result[$field][$rule]['message'] = self::getMessage($field, $rule, $instruction, $requirements);
                        }
                    break;

                    case 'minlength':
                        if (!self::minlength($field, $instruction)) {
                            $result[$field][$rule]['message'] = self::getMessage($field, $rule, $instruction, $requirements);
                        }
                    break;
                }
            }

            $rulesResults = array_merge($rulesResults, $result);
        }
    }

    /**
     * Sets the default message when the message has not been passed.
     */
    private static function getMessage($field, $rule, $instruction, $requirements)
    {
        if (array_key_exists('message', $requirements[$field])) {
            return $requirements[$field]['message'];
        } else {
            if ($rule == 'type') {
                if ($instruction == 'chosen') {
                    $options = substr($requirements[$field][$rule], 6, strlen($requirements[$field][$rule]));
                    return strtr(self::$_defaultMessages[$rule][$instruction], array(
                        '{field}' => $field,
                        '{instruction}' => $options
                    ));
                } else {
                    return strtr(self::$_defaultMessages[$rule][$instruction], array(
                        '{field}' => $field,
                        '{instruction}' => $instruction
                    ));
                }
            } else {
                return strtr(self::$_defaultMessages[$rule], array(
                    '{field}' => $field,
                    '{instruction}' => $instruction
                ));
            }
        }
    }

    /**
     * Validade all "types" fields by your expecific method
     */
    private static function isType($field, $instruction, array &$result, array $requirements)
    {
        switch ($instruction) {
            case 'string':
                if (!is_string(self::$_class->{$field})) {
                    $result[$field]['type']['message'] = self::getMessage($field, 'type', $instruction, $requirements);
                }
            break;

            case 'date':
                if (!self::isDate($field)) {
                    $result[$field]['type']['message'] = self::getMessage($field, 'type', $instruction, $requirements);
                }
            break;

            case 'email':
                if (!self::isEmail($field)) {
                    $result[$field]['type']['message'] = self::getMessage($field, 'type', $instruction, $requirements);
                }
            break;

            case 'phone':
                if (!self::isPhone($field)) {
                    $result[$field]['type']['message'] = self::getMessage($field, 'type', $instruction, $requirements);
                }
            break;

            case 'number':
                if (!self::isNumber($field)) {
                    $result[$field]['type']['message'] = self::getMessage($field, 'type', $instruction, $requirements);
                }
            break;

            default:
                if (substr($instruction, 0, 6) == 'chosen') {
                    $instructionChosen = substr($instruction, 0, 6);
                    if (!self::isChosen($field, $instruction)) {
                        $result[$field]['type']['message'] = self::getMessage($field, 'type', $instructionChosen, $requirements);
                    }
                }
            break;
        }
    }

    /**
     * Check if the field has been filled.
     */
    private static function isRequired($field, $instruction = '')
    {
        return !empty(self::$_class->{$field});
    }

    /**
     * Check if the value in the field has a maximum length
     */
    private static function maxlength($field, $instruction)
    {
        return (strlen(self::$_class->{$field}) <= $instruction);
    }

    /**
     * Check if the value in the field has minimum length
     */
    private static function minlength($field, $instruction)
    {
        return (strlen(self::$_class->{$field}) >= $instruction);
    }

    /**
     * Check if the value in the field its a valid email
     */
    private static function isEmail($field)
    {
        return filter_var(self::$_class->{$field}, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Check if the value in the field its a phone number
     * Can be a residential (10 chars) or as cellphone (11)
     */
    private static function isPhone($field)
    {
        $phone = preg_replace("/[^A-Za-z0-9]/", "", self::$_class->{$field});
        $phoneLength = strlen($phone);

        return ($phoneLength == 10 || $phoneLength == 11);
    }

    /**
     * Check if the value in the field its a valid date
     * Format (d/m/Y)
     */
    private static function isDate($field)
    {
        if (!empty(self::$_class->{$field})) {
            $date = \DateTime::createFromFormat('d/m/Y', self::$_class->{$field});
            if ($date) {
                return checkdate($date->format('m'), $date->format('d'), $date->format('Y'));
            }
        }

        return false;
    }

    /**
     * Check if the value in field its a number
     */
    private static function isNumber($field)
    {
        return is_numeric(self::$_class->{$field});
    }

    /**
     * Check if the value in field its in instruction of field.
     * Ex: [M,F]
     */
    private static function isChosen($field, $instruction)
    {
        $options = substr($instruction, 6, strlen($instruction));
        $options = strtr($options, array('[' => '', ']' => ''));
        $options = explode(',', $options);
        
        if (is_array($options)) {
            return in_array(self::$_class->{$field}, $options);
        }

        return false;
    }
}