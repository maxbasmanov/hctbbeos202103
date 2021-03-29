<?php

namespace App\Models\Redmine;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Project extends Model
{
    //use HasFactory;
	public $timestamps = false;
	
	protected $table = 'projects';

	public function __construct(array $attributes = [])
	{
		$this->table = config('database.redmine') . '.' . $this->table;
		parent::__construct($attributes);
	}

    public static function getClosedUnpaidProjects()
	{
		return Project::select(
									DB::raw('projects.*'),
									DB::raw('custom_values.value as projectPayed')
								)
							->leftJoin(config('database.redmine') . '.custom_values as custom_values', function ($join) {
								$join->on('custom_values.customized_id', '=', 'projects.id')
									 ->where('custom_values.customized_type', '=', 'Project')
									 ->where('custom_values.custom_field_id', '=', 2);
							})
							->where('projects.status', '=', 5)
							->where('custom_values.value', '=', '0')
							->get();
	}
}
