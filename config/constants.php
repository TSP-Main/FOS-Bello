<?php
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
];
