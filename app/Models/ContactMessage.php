<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Public contact form submission from the contact page.
 */
class ContactMessage extends Model
{
    use HasFactory;

    protected $guarded = [];
}
