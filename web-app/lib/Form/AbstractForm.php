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

    /**
     * @param array $inputData
     */
    public function validate(array $inputData): void
    {
        if (empty($inputData) || count($this->getTypes()) > count($inputData)) {
            foreach ($this->getTypes() as $fieldName => $type) {
                $this->errors[] = new FormError('Field "'.$fieldName.'" missing in form');
            }
            return;
        }
        foreach ($inputData as $fieldName => $fieldValue) {
            if (!isset($this->getTypes()[$fieldName])) {
                $this->errors[] = new FormError(
                    'Field "'.htmlspecialchars($fieldName)
                    .'" should not exists in form "'
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

    /**
     * @param string|null $field
     * @return array
     */
    public function getClearedData(string $field = null): array
    {
        if (null !== $field && isset($this->clearData[$field])) {
            return $this->clearedData[$field];
        }

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
