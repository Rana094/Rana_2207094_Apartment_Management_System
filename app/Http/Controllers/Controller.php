<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller
{
    /**
     * Gives all controllers access to $this->authorize() for policy checks.
     */
    use AuthorizesRequests;
}
