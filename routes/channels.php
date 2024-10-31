<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('company.{companyId}', function ($user, $companyId) {
    // Check if the user belongs to the company
    return $user->company_id === (int) $companyId;
});
