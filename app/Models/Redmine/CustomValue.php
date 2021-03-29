<?php

namespace App\Models\Redmine;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CustomValue extends Model
{
    //use HasFactory;
	public $timestamps = false;
	
	protected $table = 'custom_values';

	public function __construct(array $attributes = [])
	{
		$this->table = config('database.redmine') . '.' . $this->table;
		parent::__construct($attributes);
	}

	public static function getProjectPaymentStatus($projectId)
	{
		return CustomValue::select('*')
							->where('customized_type', '=', 'Project')
							->where('custom_field_id', '=', 2)
							->where('customized_id', '=', $projectId)
							->first();
	}

}
