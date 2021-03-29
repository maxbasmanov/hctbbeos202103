<?php

namespace App\Models\Redmine;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TimeEntry extends Model
{
    //use HasFactory;
	public $timestamps = false;

	protected $table = 'time_entries';

	public function __construct(array $attributes = [])
	{
		$this->table = config('database.redmine') . '.' . $this->table;
		parent::__construct($attributes);
	}

	public static function getProjectTimeEntries($projectId)
	{
		return TimeEntry::select(
									DB::raw('sum(time_entries.hours) as totalHours'),
									DB::raw("(select cv.value from " . config('database.redmine') . ".custom_values as cv
												where cv.customized_type = 'Principal' 
													and cv.custom_field_id = 1
													and cv.customized_id = time_entries.user_id) as userWallet") 
							)
					->where('time_entries.project_id', '=', $projectId)
					->groupBy('time_entries.user_id')
					->get();
	}

}
