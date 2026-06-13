<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\City;
use App\Models\VehicleType;
use App\Models\InterCityRoute;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        Role::firstOrCreate(
            ['name' => 'admin'],
            ['description' => 'Administrator']
        );
        Role::firstOrCreate(
            ['name' => 'driver'],
            ['description' => 'Driver/Captain']
        );
        Role::firstOrCreate(
            ['name' => 'customer'],
            ['description' => 'Customer']
        );

        // Create cities
        $cities = [
            [
                'name' => 'Karachi',
                'urdu_name' => 'کراچی',
                'slug' => 'karachi',
                'code' => 'KHI',
                'latitude' => 24.8607,
                'longitude' => 67.0011,
                'region' => 'Sindh',
                'base_fare' => 50,
                'per_km_rate' => 15,
                'per_minute_rate' => 2,
            ],
            [
                'name' => 'Lahore',
                'urdu_name' => 'لاہور',
                'slug' => 'lahore',
                'code' => 'LHR',
                'latitude' => 31.5497,
                'longitude' => 74.3436,
                'region' => 'Punjab',
                'base_fare' => 60,
                'per_km_rate' => 14,
                'per_minute_rate' => 2,
            ],
            [
                'name' => 'Islamabad',
                'urdu_name' => 'اسلام آباد',
                'slug' => 'islamabad',
                'code' => 'ISB',
                'latitude' => 33.6844,
                'longitude' => 73.0479,
                'region' => 'Capital',
                'base_fare' => 70,
                'per_km_rate' => 16,
                'per_minute_rate' => 2.5,
            ],
            [
                'name' => 'Faisalabad',
                'urdu_name' => 'فیصل آباد',
                'slug' => 'faisalabad',
                'code' => 'FSD',
                'latitude' => 31.4181,
                'longitude' => 72.3458,
                'region' => 'Punjab',
                'base_fare' => 40,
                'per_km_rate' => 12,
                'per_minute_rate' => 1.5,
            ],
            [
                'name' => 'Peshawar',
                'urdu_name' => 'پشاور',
                'slug' => 'peshawar',
                'code' => 'PEW',
                'latitude' => 34.0151,
                'longitude' => 71.5249,
                'region' => 'KP',
                'base_fare' => 45,
                'per_km_rate' => 13,
                'per_minute_rate' => 1.8,
            ],
            [
                'name' => 'Quetta',
                'urdu_name' => 'کویٹہ',
                'slug' => 'quetta',
                'code' => 'QTA',
                'latitude' => 30.1798,
                'longitude' => 66.9750,
                'region' => 'Balochistan',
                'base_fare' => 50,
                'per_km_rate' => 14,
                'per_minute_rate' => 2,
            ],
            [
                'name' => 'Rawalpindi',
                'urdu_name' => 'راولپنڈی',
                'slug' => 'rawalpindi',
                'code' => 'RWP',
                'latitude' => 33.5731,
                'longitude' => 73.1896,
                'region' => 'Punjab',
                'base_fare' => 50,
                'per_km_rate' => 13,
                'per_minute_rate' => 2,
            ],
            [
                'name' => 'Multan',
                'urdu_name' => 'ملتان',
                'slug' => 'multan',
                'code' => 'MUL',
                'latitude' => 30.1575,
                'longitude' => 71.4454,
                'region' => 'Punjab',
                'base_fare' => 35,
                'per_km_rate' => 11,
                'per_minute_rate' => 1.5,
            ],
            [
                'name' => 'Hyderabad',
                'urdu_name' => 'حیدرآباد',
                'slug' => 'hyderabad',
                'code' => 'HYD',
                'latitude' => 25.3548,
                'longitude' => 68.3639,
                'region' => 'Sindh',
                'base_fare' => 40,
                'per_km_rate' => 12,
                'per_minute_rate' => 1.8,
            ],
        ];

        foreach ($cities as $city) {
            City::firstOrCreate(['code' => $city['code']], $city);
        }

        // Create vehicle types
        $vehicleTypes = [
            // Ride types
            [
                'name' => 'Bike',
                'urdu_name' => 'موٹرسائیکل',
                'slug' => 'bike',
                'category' => 'ride',
                'min_capacity' => 1,
                'max_capacity' => 1,
                'commission_rate' => 20,
                'sort_order' => 1,
            ],
            [
                'name' => 'Rickshaw',
                'urdu_name' => 'رکشہ',
                'slug' => 'rickshaw',
                'category' => 'ride',
                'min_capacity' => 1,
                'max_capacity' => 3,
                'commission_rate' => 15,
                'sort_order' => 2,
            ],
            [
                'name' => 'Car',
                'urdu_name' => 'کار',
                'slug' => 'car',
                'category' => 'ride',
                'min_capacity' => 1,
                'max_capacity' => 4,
                'commission_rate' => 15,
                'sort_order' => 3,
            ],
            [
                'name' => 'Premium Car',
                'urdu_name' => 'پریمیم کار',
                'slug' => 'premium-car',
                'category' => 'ride',
                'min_capacity' => 1,
                'max_capacity' => 4,
                'commission_rate' => 15,
                'sort_order' => 4,
            ],
            // Delivery types
            [
                'name' => 'Bike Delivery',
                'urdu_name' => 'موٹرسائیکل ڈیلیوری',
                'slug' => 'bike-delivery',
                'category' => 'delivery',
                'min_capacity' => 1,
                'max_capacity' => 1,
                'commission_rate' => 12,
                'max_weight_kg' => 10,
                'sort_order' => 1,
            ],
            [
                'name' => 'Small Truck',
                'urdu_name' => 'چھوٹا ٹرک',
                'slug' => 'small-truck',
                'category' => 'delivery',
                'min_capacity' => 1,
                'max_capacity' => 1,
                'commission_rate' => 10,
                'max_weight_kg' => 500,
                'sort_order' => 2,
            ],
            [
                'name' => 'Large Truck',
                'urdu_name' => 'بڑا ٹرک',
                'slug' => 'large-truck',
                'category' => 'delivery',
                'min_capacity' => 1,
                'max_capacity' => 1,
                'commission_rate' => 8,
                'max_weight_kg' => 1000,
                'sort_order' => 3,
            ],
            [
                'name' => 'Van',
                'urdu_name' => 'وین',
                'slug' => 'van',
                'category' => 'delivery',
                'min_capacity' => 1,
                'max_capacity' => 1,
                'commission_rate' => 10,
                'max_weight_kg' => 800,
                'sort_order' => 4,
            ],
        ];

        foreach ($vehicleTypes as $type) {
            VehicleType::firstOrCreate(['slug' => $type['slug']], $type);
        }

        // Create inter-city routes
        $routes = [
            ['from_city_id' => 1, 'to_city_id' => 2, 'distance_km' => 1250, 'estimated_duration_hours' => 20, 'base_fare' => 5000, 'per_km_rate' => 20, 'base_fare_delivery' => 3000, 'per_km_rate_delivery' => 15],
            ['from_city_id' => 1, 'to_city_id' => 3, 'distance_km' => 1450, 'estimated_duration_hours' => 22, 'base_fare' => 5500, 'per_km_rate' => 22, 'base_fare_delivery' => 3500, 'per_km_rate_delivery' => 17],
            ['from_city_id' => 2, 'to_city_id' => 3, 'distance_km' => 350, 'estimated_duration_hours' => 6, 'base_fare' => 1500, 'per_km_rate' => 12, 'base_fare_delivery' => 1000, 'per_km_rate_delivery' => 10],
            ['from_city_id' => 2, 'to_city_id' => 5, 'distance_km' => 500, 'estimated_duration_hours' => 8, 'base_fare' => 2000, 'per_km_rate' => 15, 'base_fare_delivery' => 1200, 'per_km_rate_delivery' => 12],
        ];

        foreach ($routes as $route) {
            InterCityRoute::firstOrCreate(
                ['from_city_id' => $route['from_city_id'], 'to_city_id' => $route['to_city_id']],
                $route
            );
        }
    }
}
