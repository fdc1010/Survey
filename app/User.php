<?php

namespace App;

use Backpack\CRUD\CrudTrait;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
	use CrudTrait;
	use HasRoles;
    use Notifiable;
	protected $guard_name = 'web';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'imei', 'is_online','voter_id'
    ];
	
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
	
	public function setPasswordAttribute($password)
	{   
		$this->attributes['password'] = bcrypt($password); //encrypt($password);
	}
	public static function defaultUsers($cnt)
    {
        $data = [           
             [ 'name'=>'Admin','email'=>'admin@gmail.com','password'=>'admin'],
             [ 'name'=>'User 1','email'=>'user1@gmail.com','password'=>'user1'],
             [ 'name'=>'User 2','email'=>'user2@gmail.com','password'=>'user2'],
             [ 'name'=>'User 3','email'=>'user3@gmail.com','password'=>'user3']
		];
		
		return $data[$cnt];
    }
	/**
	 * Roll API Key
	 */
	public function rollApiKey(){
	   do{
		  $this->api_token = str_random(60);
		  //$this->is_online = 1;
	   }while($this->where('api_token', $this->api_token)->exists());
	   $this->save();
	}
}
