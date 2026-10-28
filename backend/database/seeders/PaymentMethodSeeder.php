<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            ['code' => 'qris', 'name' => 'QRIS'],
            ['code' => 'speedcash', 'name' => 'SpeedCash'],
            ['code' => 'bri', 'name' => 'BRI'],
            ['code' => 'mandiri', 'name' => 'Mandiri'],
            ['code' => 'bsi', 'name' => 'BSI'],
            ['code' => 'permata', 'name' => 'Permata'],
            ['code' => 'fastpay', 'name' => 'FastPay'],
            ['code' => 'indomaret', 'name' => 'Indomaret'],
            ['code' => 'bni', 'name' => 'BNI'],
            ['code' => 'bca', 'name' => 'BCA'],
            ['code' => 'alfamart', 'name' => 'Alfamart'],
            ['code' => 'ovo', 'name' => 'OVO'],
        ];

        foreach ($methods as $method) {
            PaymentMethod::updateOrCreate(
                ['code' => $method['code']],
                [
                    'name' => $method['name'],
                    'is_active' => true,
                ]
            );
        }
    }
}
