<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Redmine\Project;
use App\Models\Redmine\CustomValue;
use App\Models\Redmine\TimeEntry;
use App\Http\Controllers\API\EOS\v2\EosController;

class payForProjects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:payforprojects
							{--sleep=3 : Number of seconds to sleep before start}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'pay for closed projects time entries';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
		sleep( $this->option('sleep') );
		
		$unpaidProjects = Project::getClosedUnpaidProjects();
		
		if ($unpaidProjects->isEmpty())
		{
			$this->info('There are no unpaid Projects!');
			
		} else {
			
			$this->info('Found unpaid Projects!');
			
			foreach ($unpaidProjects as $unpaidProject)
			{
				$timeEntries = TimeEntry::getProjectTimeEntries($unpaidProject->id);
				
				/*
				Project object
				{
					"workers":[
						{
							"hours":0.5,
							"account":"mehosimjvkkw"
						},{
							"hours": 1.5,
							"account": "hfjuzmpajyyi"
						}],
					"project_id":12
				}
				*/
				
				$project 				= new \stdClass();
				$project->project_id 	= $unpaidProject->id;
				$project->project_name	= $unpaidProject->name;
				$project->project_alias	= $unpaidProject->identifier;
				$project->workers		= array();
				
				$haveEmptyWallets = false;
				foreach ($timeEntries as $timeEntry)
				{
					if (empty($timeEntry->userWallet)){
						$haveEmptyWallets = true;
					}
					
					$userObject				= new \stdClass();
					$userObject->hours		= $timeEntry->totalHours;
					$userObject->account	= $timeEntry->userWallet;
					
					$project->workers[]		= $userObject;
				}
				
				if ($haveEmptyWallets)
				{
					//reactivate project
					$unpaidProject->status = 1;
					$unpaidProject->save();
					
					$this->error('Unpaid Project '.$unpaidProject->id.' has empty wallets!');
					
				} else {
					//send to smartContract
					EosController::project_finallize($project);
					
					//mark project as paid
					$projectPaymentStatus			= CustomValue::getProjectPaymentStatus($unpaidProject->id);
					$projectPaymentStatus->value	= 1;
					$projectPaymentStatus->save();
					
					$this->info('Project '.$unpaidProject->id.' was sent to smart contract!');
				}
			}
		}
		
    }
}
