<?php

namespace App\Console\Commands;

use App\Overdue;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CountOverdueTasks extends Command
{
    protected $signature = 'tasks:count-overdue';
    protected $description = 'Count overdue tasks in the Todoist API';

    private $guzzle;

    public function __construct(Client $guzzle)
    {
        parent::__construct();
        $this->guzzle = $guzzle;
    }

    public function handle()
    {
        // Find all overdue tasks in the Todoist API
        $token = config('services.todoist.api_token');
        $response = $this->guzzle->get('https://api.todoist.com/rest/v1/tasks?filter=overdue&token=' . $token);
        $data = json_decode((string) $response->getBody());
        $count = count($data);

        // Store count as an daily entry
        Log::info('Counted overdue tasks', compact('count'));
        $entry = Overdue::firstOrCreate(['date' => Carbon::yesterday()]);
        $entry->count = $count;
        $entry->save();
    }
}
