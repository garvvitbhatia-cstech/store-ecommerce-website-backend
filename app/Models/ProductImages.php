<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImages extends Model
{
    use HasFactory;
	
	protected $table = 'product_images';
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
		'image_name',
		'image_alt',
		'image_title',
        'ordering',
		'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'product_id',
		'image_name',
		'image_alt',
		'image_title',
        'ordering',
		'status'
    ];
	
}