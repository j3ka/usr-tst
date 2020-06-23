<?php

namespace Lib\Form;

abstract class AbstractForm
{
    /**
     * @var array
     */
    private array $errors = [];

    /**
     * @var array
     */
    private array $clearedData = [];

    /**
     * @return array
     */
    abstract public function getTypes(): array;

    public function validate(array $inputData): void
    {
        foreach ($inputData as $fieldName => $fieldValue) {
            if (!isset($this->getTypes()[$fieldName])) {
                $this->errors[] = new FormError(
                    'Field "'.htmlspecialchars($fieldName)
                    .'" does not exists in form "'
                    .get_class($this).'"'
                );
                continue;
            }
            $type = $this->getTypes()[$fieldName];
            $value = $this->clearData($type, $fieldValue);
            $this->clearedData[$fieldName] = $value;
            if (!$this->validateType($type, $value)) {
                $this->errors[] = new FormError('Invalid value for field "'.$fieldName.'": "'.htmlspecialchars($value).'"');
            }
        }
    }

    public function isValid(): bool
    {
        return count($this->getErrors()) === 0;
    }

    public function getClearedData(): array
    {
        return $this->clearedData;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    private function validateType(TypeInterface $type, $value): bool
    {
        return $type->validate($value);
    }

    private function clearData(TypeInterface $type, $value)
    {
        return $type->clearData($value);
    }
}
