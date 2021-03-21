<?php

namespace App\Helpers;

class Helper
{
    static function colors($status)
	{
		$code = substr($status, 0, 1);

        switch ($code) {
        	case 4:
        		return 'text-warning';
        		break;
			case 5:
	        	return 'text-danger';
	        	break;
        	default:
        		return 'text-default';
        		break;
        }
    }
}
