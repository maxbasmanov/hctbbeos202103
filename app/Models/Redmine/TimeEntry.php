<?php

namespace App\Models\Redmine;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use App\Models\Redmine\User;

class TimeEntry extends Model
{
    //use HasFactory;

	protected $table = 'time_entries';

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
