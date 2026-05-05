<?php

namespace App\Modules\Auth\Repositories;

use App\Modules\Base\Repositories\RepositoryInterface;
use App\Modules\Auth\Models\Otp;

interface OtpRepositoryInterface extends RepositoryInterface
{
    public function generateOtpForEmailAuth(string $email, ?int $userId = null): Otp;

    public function verifyEmailOtpByToken(string $token, string $code): ?Otp;

    public function findEmailOtpByToken(string $token): ?Otp;

    public function generateOtpForPhoneAuth(string $phone, ?int $userId = null): Otp;

    public function verifyPhoneOtpByToken(string $token, string $code): ?Otp;

    public function findPhoneOtpByToken(string $token): ?Otp;

    public function verifyEmailOtp(string $email, string $code): ?Otp;

    public function verifyPhoneOtp(string $phone, string $code): ?Otp;
}
