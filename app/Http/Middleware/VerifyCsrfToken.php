<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/v2/category/createorupdate',
        'api/v2/category/delete',

        'api/v2/product/createorupdate',
        'api/v2/product/delete',
        'api/v2/product/price',
        'api/v2/product/amount',
        'api/v2/files/upload',

        'api/v2/brands/createorupdate',

        'api/v2/characteristics/createorupdate',

        'api/v2/analogs/createorupdate',

        'api/v2/scheme/createorupdate',

        'api/v2/users/delete',
        'api/v2/users/createorupdate',
        'api/v2/employee/createorupdate',
        'api/v2/users/blocked',

        'api/v2/partners/createorupdate',
        'api/v2/partners/delete',

        'api/v2/agreement/createorupdate',
        'api/v2/agreement/delete',

        'api/v2/debt/createorupdate',

        'api/v2/discounts/createorupdate',
        'api/v2/discounts/agreements',
        'api/v2/discounts/products',

        'api/v2/sync/categories',
        'api/v2/sync/brands',
        'api/v2/sync/characteristics',
        'api/v2/sync/schemes',
        'api/v2/sync/analogs',
        'api/v2/sync/delete',
        'api/v2/sync/products',
        'api/v2/currency',
        'api/v2/sync/files',
        'api/v2/sync/debts',
        'api/v2/sync/price',
        'api/v2/sync/amount',
        'api/v2/sync/discounts',
        'api/v2/sync/users',
        'api/v2/sync/employees',
        'api/v2/sync/partners',
    ];
}
