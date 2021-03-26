<?php

namespace App\Models\Redmine;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use App\Models\Redmine\User;

class Project extends Model
{
    //use HasFactory;

	protected $table = 'projects';

	public function __construct(array $attributes = [])
	{
		$this->table = config('database.redmine') . '.' . $this->table;
		parent::__construct($attributes);
	}

/*	public function users()
	{
		return $this
			->belongsTo(User::class, 'poster_id', 'user_id');
	}
*/
}
