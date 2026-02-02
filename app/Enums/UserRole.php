<?php

namespace App\Enums;

enum UserRole: string
{
    case SUPER_ADMIN = 'super_admin';
    case OWNER = 'owner';
    case CASHIER = 'cashier';
    case KITCHEN = 'kitchen';
    case WAITER = 'waiter';
    case CUSTOMER = 'customer';

    public function label(): string
    {
        return match($this) {
            self::SUPER_ADMIN => 'Super Admin',
            self::OWNER => 'Owner',
            self::CASHIER => 'Kasir',
            self::KITCHEN => 'Dapur',
            self::WAITER => 'Pelayan',
            self::CUSTOMER => 'Pelanggan',
        };
    }
}
