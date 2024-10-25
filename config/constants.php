<?php

// Order Status
define('ACCEPTED', 1);
define('REJECTED', 2);
define('DELIVERED', 3);
define('CANCELED', 4);


return [
    'SOFTWARE_MANAGER' => 1,
    'SUPER_ADMIN' => 2,
    'SHOP_ADMIN' => 3,
    
    'USER_ROLES_NAME' => [
        1 => 'Software Manager',
        2 => 'Super Admin',
        3 => 'Shop Admin'
    ],

    'CATEGORY_STATUSES' => [
        'ACTIVE' => 1,
        'INACTIVE' => 2,
        'DRAFT' => 3,
    ],

    'CATEGORY_TYPES' => [
        'CATEGORY' => 1,
        'SUB_CATEGORY' => 2,
      ],

    'YES_NO' => [
        1 => 'Yes',
        2 => 'No',
    ],

    'PRODUCT_OPTIONS_TYPE' => [
        1 => 'Radio',
        2 => 'Checkbox',
    ],

    'ACCEPTED' => ACCEPTED,
    'REJECTED' => REJECTED,
    'DELIVERED' => DELIVERED,
    'CANCELED' => CANCELED,

    'ORDER_STATUS' => [
        ACCEPTED => 'Accepted',
        REJECTED => 'Rejected',
        DELIVERED => 'Delivered',
        CANCELED => 'Canceled',
    ],

    'ACTIVE_RESTAURANT' => 1,
    'IN_ACTIVE_RESTAURANT' => 2,
    'INCOMING_RESTAURANT' => 3,
    'REJECTED_RESTAURANT' => 4,

    'PACKAGES' => [
        1 => 'Basic',
        2 => 'Delux',
        3 => 'Premium',
    ],

    'PLAN' => [
        1 => 'Monthly',
        2 => 'Yearly',
    ],
];
