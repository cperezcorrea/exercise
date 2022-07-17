<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;

class SendPost extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:post';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a single post';

    /**
     * This process solves question 4,
     * it will be trying to send the post until success
     * 
     */
    public function handle()
    {
        $statusCode = 0; 
        $client = new Client(['base_uri' => 'https://atomic.incfile.com/']);
        while ($statusCode != 200) {
            $response = $client->request('POST', 'fakepost');
            $statusCode = $response->getStatusCode();
        }
        echo "Post sent \n";
        return 0;
    }
}
