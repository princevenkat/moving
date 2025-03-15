<?php

namespace App\Enums;

enum PermissionsEnum: string
{
    case ApproveVendors = 'ApproveVendors';
    case SellServices = 'SellServices';
    case BuyServices = 'BuyServices';
}
