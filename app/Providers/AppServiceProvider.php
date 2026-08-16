<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Customer;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Order;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Policies\CustomerPolicy;
use App\Policies\ContactPolicy;
use App\Policies\ProductPolicy;
use App\Policies\OrderPolicy;
use App\Policies\VehiclePolicy;
use App\Policies\DriverPolicy;
use App\Models\Route;
use App\Policies\RoutePolicy;
use App\Models\TrackingUpdate;
use App\Policies\TrackingUpdatePolicy;
use App\Models\Document;
use App\Policies\DocumentPolicy;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Customer::class, CustomerPolicy::class);
        Gate::policy(Contact::class, ContactPolicy::class);
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(Order::class, OrderPolicy::class);
        Gate::policy(Vehicle::class, VehiclePolicy::class);
        Gate::policy(Driver::class, DriverPolicy::class);
        Gate::policy(Route::class, RoutePolicy::class);
        Gate::policy(TrackingUpdate::class, TrackingUpdatePolicy::class);
        Gate::policy(Document::class, DocumentPolicy::class);
    }
}