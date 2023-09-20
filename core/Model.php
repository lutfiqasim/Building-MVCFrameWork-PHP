<?php
namespace app\core;

/**
 * Class Model: General Model
 * 
 * This abstract class serves as a base for models used in the application.
 * Models are used to validate and manage data before interacting with the database.
 * 
 * @author Lutfi 
 * @package app\core
 */
abstract class Model
{
    // Constants for validation rules
    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_MATCH = 'match';
    public const RULE_UNIQUE = 'unique';

    /**
     * Summary of loadData
     * Load data from an associative array into model attributes.
     *
     * @param mixed $data
     * @return void
     */
    public function loadData($data)
    {
        foreach ($data as $key => $value) {
            // Check if the property exists in the model and assign the value
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * Summary of rules
     * Define validation rules for model attributes.
     *
     * @return array
     */
    abstract public function rules(): array;

    public function labels(): array
    {
        return [];
    }

    public function getLabel($attribute)
    {
        return $this->labels()[$attribute] ?? $attribute;
    }

    /**
     * Summary of errors
     * @var array
     */
    public array $errors = [];

    /**
     * Summary of validate
     * Validate model attributes based on defined rules.
     *
     * @return bool
     */
    public function validate()
    {
        foreach ($this->rules() as $attribute => $rules) {
            $value = $this->{$attribute};
            foreach ($rules as $rule) {
                $ruleName = $rule;
                if (!is_string($ruleName)) {
                    // Extract the rule name from the rule definition
                    $ruleName = $rule[0];
                }
                if ($ruleName === self::RULE_REQUIRED && !$value) {
                    $this->addErrorForRule($attribute, self::RULE_REQUIRED);
                }
                if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addErrorForRule($attribute, self::RULE_EMAIL);
                }
                if ($ruleName === self::RULE_MIN && strlen($value) < $rule['min']) {
                    $this->addErrorForRule($attribute, self::RULE_MIN, $rule);
                }
                if ($ruleName === self::RULE_MAX && strlen($value) > $rule['max']) {
                    $this->addErrorForRule($attribute, self::RULE_MAX, $rule);
                }
                if ($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']}) {
                    $rule['match'] = $this->getLabel($rule['match']);
                    $this->addErrorForRule($attribute, self::RULE_MATCH, $rule);
                }

                if ($ruleName === self::RULE_UNIQUE) {
                    $className = $rule['class'];
                    $uniqueAttr = $rule['attribute'] ?? $attribute;
                    // var_dump($className,$value,$uniqueAttr);
                    $tableName = $className::tableName();
                    $sql = "SELECT * FROM $tableName WHERE $uniqueAttr = :attr";
                    $statement = Application::$app->db->prepare($sql);
                    $statement->bindParam(":attr", $value, \PDO::PARAM_STR);
                    $statement->execute();
                    $record = $statement->fetchObject();
                    if ($record) {
                        $this->addErrorForRule($attribute, self::RULE_UNIQUE, ['field' => $this->getLabel($attribute)]);
                    }
                }
            }
        }
        return empty($this->errors);
    }

    /**
     * Summary of addErrorForRule
     * Add an error message for a specific attribute.
     *
     * @param string $attribute
     * @param string $rule
     * @param mixed $params
     * @return void
     */
    private function addErrorForRule(string $attribute, string $rule, $params = [])
    {
        $message = $this->errorMessages()[$rule] ?? "";
        foreach ($params as $key => $value) {
            $message = str_replace("{{$key}}", $value, $message); // Replace placeholders in the error message
        }
        $this->errors[$attribute][] = $message;
    }
    /**
     * Summary of addError
     * @param string $attribute
     * @param string $message
     * @return void
     * Add error without rules
     */
    protected function addError(string $attribute, string $message)
    {
        $this->errors[$attribute][] = $message;
    }
    /**
     * Summary of errorMessages
     * Define error messages for validation rules.
     *
     * @return array
     */
    public function errorMessages()
    {
        return [
            self::RULE_REQUIRED => 'This field is required',
            self::RULE_EMAIL => 'This field must be a valid email address',
            self::RULE_MIN => 'Minimum length for this field is {min}',
            self::RULE_MAX => 'Maximum length for this field is {max}',
            self::RULE_MATCH => 'This field must match {match}',
            self::RULE_UNIQUE => 'An account with this {field} already exists',
        ];
    }

    /**
     * Check if an attribute has errors.
     *
     * @param string $attribute
     * @return bool
     */
    public function hasError($attribute)
    {
        return $this->errors[$attribute] ?? false;
    }

    /**
     * Get the first error message for an attribute.
     *
     * @param string $attribute
     * @return mixed
     */
    public function getFirstError($attribute)
    {
        return $this->errors[$attribute][0] ?? false;
    }
}