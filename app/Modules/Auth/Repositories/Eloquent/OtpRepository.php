<?php

namespace App\Modules\Auth\Repositories\Eloquent;

use App\Modules\Auth\Models\Otp;
use App\Modules\Auth\Repositories\OtpRepositoryInterface;
use App\Modules\Base\Repositories\Eloquent\Repository;
use Carbon\Carbon;
use Illuminate\Support\Str;

class OtpRepository extends Repository implements OtpRepositoryInterface
{
    public function __construct(Otp $model)
    {
        parent::__construct($model);
    }

    private function generateCode(): string
    {
        return (string) random_int(1000, 9999);
    }

    public function generateOtpForEmailAuth(string $email, ?int $userId = null): Otp
    {
        $this->model->newQuery()
            ->where('identifier', $email)
            ->where('type', 'email')
            ->delete();

        return $this->model->newQuery()->create([
            'user_id'    => $userId,
            'identifier' => $email,
            'code'       => $this->generateCode(),
            'type'       => 'email',
            'token'      => Str::random(60),
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);
    }

    public function verifyEmailOtpByToken(string $token, string $code): ?Otp
    {
        return $this->model->newQuery()
            ->where('token', $token)
            ->where('type', 'email')
            ->where('code', $code)
            ->where('expires_at', '>', Carbon::now())
            ->first();
    }

    public function findEmailOtpByToken(string $token): ?Otp
    {
        return $this->model->newQuery()
            ->where('token', $token)
            ->where('type', 'email')
            ->where('expires_at', '>', Carbon::now())
            ->first();
    }

    public function generateOtpForPhoneAuth(string $phone, ?int $userId = null): Otp
    {
        $this->model->newQuery()
            ->where('identifier', $phone)
            ->where('type', 'phone')
            ->delete();

        return $this->model->newQuery()->create([
            'user_id'    => $userId,
            'identifier' => $phone,
            'code'       => $this->generateCode(),
            'type'       => 'phone',
            'token'      => Str::random(60),
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);
    }

    public function verifyPhoneOtpByToken(string $token, string $code): ?Otp
    {
        return $this->model->newQuery()
            ->where('token', $token)
            ->where('type', 'phone')
            ->where('code', $code)
            ->where('expires_at', '>', Carbon::now())
            ->first();
    }

    public function findPhoneOtpByToken(string $token): ?Otp
    {
        return $this->model->newQuery()
            ->where('token', $token)
            ->where('type', 'phone')
            ->where('expires_at', '>', Carbon::now())
            ->first();
    }

    public function verifyEmailOtp(string $email, string $code): ?Otp
    {
        return $this->model->newQuery()
            ->where('identifier', $email)
            ->where('type', 'email')
            ->where('code', $code)
            ->where('expires_at', '>', Carbon::now())
            ->first();
    }

    public function verifyPhoneOtp(string $phone, string $code): ?Otp
    {
        return $this->model->newQuery()
            ->where('identifier', $phone)
            ->where('type', 'phone')
            ->where('code', $code)
            ->where('expires_at', '>', Carbon::now())
            ->first();
    }
}
