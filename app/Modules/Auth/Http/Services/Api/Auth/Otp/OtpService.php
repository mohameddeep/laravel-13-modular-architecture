<?php

namespace App\Modules\Auth\Http\Services\Api\Auth\Otp;

use App\Modules\Auth\Models\Otp;
use App\Modules\Auth\Repositories\OtpRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use RuntimeException;

class OtpService
{
    public function __construct(
        private readonly OtpRepositoryInterface $otpRepository,
    ) {}

    public function sendEmailOtp(string $email, string $userName = 'User', ?int $userId = null): Otp
    {
        $otp = $this->otpRepository->generateOtpForEmailAuth($email, $userId);

        try {
            // TODO: Replace with a real Mailable once you create one
            // Mail::to($email)->send(new \App\Modules\Auth\Mail\OtpEmail($otp->code, $userName));

            Log::info('Email OTP generated', [
                'otp_id'  => $otp->id,
                'email'   => $email,
                'user_id' => $userId,
                'code'    => $otp->code, // Remove in production
                'token'   => $otp->token,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send OTP email', ['email' => $email, 'error' => $e->getMessage()]);

            if (! config('app.debug', false)) {
                throw new RuntimeException('Failed to send OTP email. Please try again later.');
            }
        }

        return $otp;
    }

    public function verifyEmailOtpByToken(string $token, string $code): Otp|JsonResponse
    {
        $otp = $this->otpRepository->verifyEmailOtpByToken($token, $code);

        if (! $otp) {
            return responseFail(400, __('messages.Wrong OTP code or expired'));
        }

        $otp->delete();

        return $otp;
    }

    public function resendEmailOtp(string $token): JsonResponse
    {
        $existing = $this->otpRepository->findEmailOtpByToken($token);

        if (! $existing) {
            return responseFail(400, __('messages.Invalid or expired OTP token'));
        }

        $otp = $this->sendEmailOtp($existing->identifier, 'User', $existing->user_id);

        return responseSuccess(200, __('messages.OTP_Is_Send'), [
            'otp_token'          => $otp->token,
            'expires_in_minutes' => 5,
        ]);
    }

    public function sendPhoneOtp(string $phone, string $userName = 'User', ?int $userId = null): Otp
    {
        $otp = $this->otpRepository->generateOtpForPhoneAuth($phone, $userId);

        // TODO: integrate SMS provider (Twilio, Nexmo, etc.)
        Log::info('Phone OTP generated', [
            'otp_id'  => $otp->id,
            'phone'   => $phone,
            'user_id' => $userId,
            'code'    => $otp->code, // Remove in production
            'token'   => $otp->token,
        ]);

        return $otp;
    }

    public function verifyPhoneOtpByToken(string $token, string $code): Otp|JsonResponse
    {
        $otp = $this->otpRepository->verifyPhoneOtpByToken($token, $code);

        if (! $otp) {
            return responseFail(400, __('messages.Wrong OTP code or expired'));
        }

        $otp->delete();

        return $otp;
    }

    public function resendPhoneOtp(string $token): JsonResponse
    {
        $existing = $this->otpRepository->findPhoneOtpByToken($token);

        if (! $existing) {
            return responseFail(400, __('messages.Invalid or expired OTP token'));
        }

        $otp = $this->sendPhoneOtp($existing->identifier, 'User', $existing->user_id);

        return responseSuccess(200, __('messages.OTP_Is_Send'), [
            'otp_token'          => $otp->token,
            'expires_in_minutes' => 5,
        ]);
    }
}
