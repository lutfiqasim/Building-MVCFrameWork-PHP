<?php
namespace app\core;

/**
 * Class Model:General Model
 * 
 * @author Lutfi 
 * @package app\core
 * 
 */
abstract class Model
{
    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_MATCH = 'match';

    /**
     * Summary of loadData
     * @param mixed $data
     * @return void
     */
    public function loadData($data)
    {
        foreach ($data as $key => $value) {
            //Check for property name with the attributes of the class
            //eg: in RegisterModel checks for its attributes if exist with same names
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }
    /**
     * Summary of rules
     * @return array
     */
    abstract public function rules(): array;

    /**
     * Summary of errors
     * @var array
     */
    public array $errors = [];
    /**
     * Summary of validate
     * @return bool
     */
    public function validate()
    {
        foreach ($this->rules() as $attribute => $rules) {
            $value = $this->{$attribute};
            foreach ($rules as $rule) {
                $ruleName = $rule;
                if (!is_string($ruleName)) {
                    //rule comes from the RegisterModel rules
                    $ruleName = $rule[0];
                }
                if ($ruleName === self::RULE_REQUIRED && !$value) {
                    $this->addError($attribute, self::RULE_REQUIRED);
                }
                if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($attribute, self::RULE_EMAIL);
                }
                if ($ruleName === self::RULE_MIN && strlen($value) < $rule['min']) {
                    $this->addError($attribute, self::RULE_MIN, $rule);
                }
                if ($ruleName === self::RULE_MAX && strlen($value) > $rule['max']) {
                    $this->addError($attribute, self::RULE_MAX, $rule);
                }
                if ($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']}) {
                    $this->addError($attribute, self::RULE_MATCH, $rule);
                }


            }
        }
        return empty($this->errors);
    }
    /**
     * Summary of addError
     * @param string $attribute
     * @param string $rule
     * @param mixed $params
     * @return void
     */
    private function addError(string $attribute, string $rule, $params = [])
    {
        $message = $this->errorMessages()[$rule] ?? "";
        foreach ($params as $key => $value) {
            $message = str_replace("{{$key}}", $value, $message); //key can be min or max
        }
        $this->errors[$attribute][] = $message;
    }
    /**
     * Summary of errorMessages
     * @return array
     */
    public function errorMessages()
    {
        return [
            self::RULE_REQUIRED => 'This field is required',
            self::RULE_EMAIL => 'This field must be valid email address',
            self::RULE_MIN => 'Min length of this field must be {min}',
            self::RULE_MAX => 'MAX length of this field must be {max}',
            self::RULE_MATCH => 'This field must be the same as {match}',
        ];
    }

    public function hasError($attribute)
    {
        return $this->errors[$attribute] ?? false;
    }

    public function getFirstError($attribute)
    {
        return $this->errors[$attribute][0] ??false;
    }
}