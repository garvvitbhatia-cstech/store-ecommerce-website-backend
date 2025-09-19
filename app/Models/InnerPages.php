<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InnerPages extends Model
{
    use HasFactory;
	
	protected $table = 'inner_pages';
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
		'title',
		'description',
		'seo_title',
		'seo_description',
		'seo_keyword',
		'robot_tags',
		'banner',
		'banner_status',
		'status',
		'heading',
		'sub_heading',
		'edit_heading',
		'edit_sub_heading',
		'edit_description'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
	
}
