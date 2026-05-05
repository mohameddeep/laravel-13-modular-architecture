<?php

namespace App\Modules\Auth\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates that the value is either:
 *   - an E.164 phone number (e.g. +201234567890), or
 *   - a valid email address.
 */
class UsernameIsPhoneOrEmail implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $username = trim((string) $value);

        $isPhone = (bool) preg_match('/^\+[1-9]\d{7,15}$/', $username);
        $isEmail = (bool) filter_var($username, FILTER_VALIDATE_EMAIL);

        if (! $isPhone && ! $isEmail) {
            $fail(__('messages.Username must be a valid phone number (E.164 format) or email address'));
        }
    }
}
