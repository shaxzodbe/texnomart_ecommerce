<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\RegisterOrLoginRequest;
use App\Http\Requests\Api\V1\VerifyOtpRequest;
use App\Http\Resources\UserResource;
use App\Http\Responses\JsonApiResponse;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Sms\OtpService;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    protected UserRepositoryInterface $userRepository;

    protected OtpService $otpService;

    public function __construct(
        UserRepositoryInterface $userRepository,
        OtpService $otpService
    ) {
        $this->userRepository = $userRepository;
        $this->otpService = $otpService;
    }

    /**
     * Used to for both Register & Login
     *
     * @return \App\Http\Responses\JsonApiResponse
     */
    public function registerOrLogin(RegisterOrLoginRequest $request)
    {
        $validated = $request->validated();
        $phoneNumber = $validated['phone'];

        $this->userRepository->firstOrCreate(
            ['phone' => $phoneNumber],
        );

        try {
            $this->otpService->generateOtp($phoneNumber);

            return new JsonApiResponse(
                [
                    'success' => true,
                    'message' => 'OTP sent to your phone number.',
                ],
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return new JsonApiResponse(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                ],
                Response::HTTP_TOO_MANY_REQUESTS
            );
        }
    }

    /**
     * Used to verify otp
     */
    public function verifyOtp(VerifyOtpRequest $request): JsonApiResponse
    {
        $phoneNumber = $request->input('phone');
        $otp = $request->input('otp');

        if ($this->otpService->verifyOtp($phoneNumber, $otp)) {
            $user = $this->userRepository->findByPhone($phoneNumber);
            $token = auth()->login($user);

            return new JsonApiResponse(
                [
                    'success' => true,
                    'message' => 'OTP verified successfully.',
                    'user' => new UserResource($user),
                    'authorisation' => [
                        'token' => $this->respondWithToken($token),
                    ],
                ],
                Response::HTTP_OK
            );
        }

        return new JsonApiResponse(
            [
                'success' => false,
                'message' => 'Invalid or expired OTP.',
            ],
            Response::HTTP_UNAUTHORIZED
        );
    }

    /**
     * Get the token array structure.
     *
     *
     * @return array
     */
    private function respondWithToken(string $token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ];
    }
}
