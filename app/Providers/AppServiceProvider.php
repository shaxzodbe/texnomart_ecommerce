<?php

namespace App\Providers;

use App\Events\OtpGenerated;
use App\Events\OtpRegenerated;
use App\Interfaces\Logistics\LogisticsInterface;
use App\Interfaces\Payments\PaymentGatewayInterface;
use App\Interfaces\Sms\SmsServiceInterface;
use App\Listeners\OtpGeneratedListener;
use App\Listeners\OtpRegeneratedListener;
use App\Models\Order;
use App\Models\Policies\OrderPolicy;
use App\Models\Policies\UserPolicy;
use App\Models\User;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;
use App\Services\Logistics\YandexLogisticsService;
use App\Services\Payments\YandexPaymentService;
use App\Services\Sms\TwilioSMSService;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(LogisticsInterface::class, YandexLogisticsService::class);
        $this->app->bind(PaymentGatewayInterface::class, YandexPaymentService::class);
        $this->app->bind(SmsServiceInterface::class, TwilioSmsService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Order::class, OrderPolicy::class);
        Event::listen(
            OtpGenerated::class,
            OtpGeneratedListener::class
        );
        Event::listen(
            OtpRegenerated::class,
            OtpRegeneratedListener::class
        );
    }
}
