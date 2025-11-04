<?php

namespace App\Exceptions;

use Illuminate\Validation\ValidationException;

class CustomValidationException extends ValidationException
{
    /**
     * Create a new validation exception instance.
     */
    public function __construct($validator, $response = null, $errorBag = 'default')
    {
        parent::__construct($validator, $response, $errorBag);
    }

    /**
     * Get the validation error messages with custom formatting
     */
    public function getFormattedErrors(): array
    {
        $errors = $this->errors();
        $formatted = [];

        foreach ($errors as $field => $messages) {
            $formatted[$field] = [
                'message' => $messages[0],
                'field' => $field,
                'code' => $this->getErrorCode($field, $messages[0]),
            ];
        }

        return $formatted;
    }

    /**
     * Get error code based on field and message
     */
    protected function getErrorCode(string $field, string $message): string
    {
        if (str_contains($message, 'required')) {
            return 'REQUIRED';
        }

        if (str_contains($message, 'email')) {
            return 'INVALID_EMAIL';
        }

        if (str_contains($message, 'unique')) {
            return 'ALREADY_EXISTS';
        }

        if (str_contains($message, 'min:')) {
            return 'TOO_SHORT';
        }

        if (str_contains($message, 'max:')) {
            return 'TOO_LONG';
        }

        if (str_contains($message, 'confirmed')) {
            return 'CONFIRMATION_MISMATCH';
        }

        return 'VALIDATION_ERROR';
    }
}
