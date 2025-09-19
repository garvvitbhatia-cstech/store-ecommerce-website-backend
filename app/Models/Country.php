<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Country extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
	
	protected $table = 'countries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'country_name',
        'country_code',
        'phonecode',
		'phone_no_format',
		'zipcode_format',
		'flag_image',
		'ordering',
		'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'country_name',
        'country_code',
        'phonecode',
		'phone_no_format',
		'zipcode_format',
		'flag_image',
		'ordering',
		'status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
	 
}