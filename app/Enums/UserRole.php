<?php

namespace App\Enums;

enum UserRole: string
{
    case OWNER = 'owner';
    case CASHIER = 'cashier';
    case KITCHEN = 'kitchen';
    case WAITER = 'waiter';
    case CUSTOMER = 'customer';

    public function label(): string
    {
        return match($this) {
            self::OWNER => 'Owner',
            self::CASHIER => 'Kasir',
            self::KITCHEN => 'Dapur',
            self::WAITER => 'Pelayan',
            self::CUSTOMER => 'Pelanggan',
        };
    }
}
