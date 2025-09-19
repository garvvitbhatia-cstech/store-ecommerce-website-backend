<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
	
	protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'password',
		'contact',
		'address',
		'otp',
		'status',
		'country',
		'state',
		'city',
		'zipcode'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
	public function GetRecordById($id){
		return $this::where('id', $id)->first();
	}
	public function UpdateRecord($Details){
		$Record = $this::where('id', $Details['id'])->update($Details);
		return true;
	}
	public function CreateRecord($Details){
		$Record = $this::create($Details);
		return $Record;
	}
	public function ExistingEmail($email){
		return $this::where('email',$email)->where('status','!=', 3)->exists();
	}
    public function ExistingPhone($phone){
		return $this::where('contact',$phone)->where('status','!=', 3)->exists();
	}
	public function ExistingEmailUpdate($email, $id){
		return $this::where('email',$email)->where('id','!=', $id)->where('status','!=', 3)->exists();
	}
	public function GetByUsername($username){
		$User = $this::where('email', '=', $username)->orWhere('contact', '=', $username)->get();
		if(count($User)){

			return $User[0];
		}else{
			return false;
		}
	}
	 
}
