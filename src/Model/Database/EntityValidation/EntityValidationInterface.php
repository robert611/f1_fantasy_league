<?php 

namespace App\Model\Database\EntityValidation;

interface EntityValidationInterface
{
    public function validateEntityData(array $entityData): bool;

    public function addValidationError(string $validationError): void;

    public function getValidationErrors(): array;
}