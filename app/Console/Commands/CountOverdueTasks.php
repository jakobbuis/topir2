<?php

namespace App\Console\Commands;

use App\Models\Event;
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
        $response = $this->guzzle->get('https://api.todoist.com/rest/v2/tasks?filter=overdue', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);
        $data = json_decode((string) $response->getBody());
        $count = count($data);

        // Store count as an daily entry
        Log::info('Counted overdue tasks', compact('count'));
        Event::create([
            'data' => (object) [
                'event_name' => 'topir:overdue-count',
                'date' => Carbon::yesterday()->format('Y-m-d'),
                'overdue' => $count,
            ],
        ]);
    }
}
