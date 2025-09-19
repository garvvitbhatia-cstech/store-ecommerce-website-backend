<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class City extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
	
	protected $table = 'cities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'country_id',
        'state_id',
        'city',
		'description',
		'heading',
		'slug',
		'banner',
		'seo_title',
		'seo_description',
		'seo_keywords',
		'status',
		'front_status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'country_id',
        'state_id',
        'city',
		'description',
		'heading',
		'slug',
		'banner',
		'seo_title',
		'seo_description',
		'seo_keywords',
		'status',
		'front_status'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
	 
}
