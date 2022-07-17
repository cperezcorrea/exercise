<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;


class SendMultiplePosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:multipleposts {numPosts}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends multiple posts';

    /**
     * This process solves question 5, 
     * even if one post fail the others are sent
     * 
     */
    public function handle()
    {
        $client = new Client();
        $numPosts = $this->arguments()['numPosts'];
        
        $requests = function ($total) {
            $uri = 'https://atomic.incfile.com/fakepost';
            for ($i = 0; $i < $total; $i++) {
                yield new Request('POST', $uri);
            }
        };

        $pool = new Pool($client, $requests($numPosts), [
            'concurrency' => 5,
            'fulfilled' => function (Response $response, $index) {
                echo "Post $index sent \n";
            },
            'rejected' => function (RequestException $reason, $index) {
                echo "Post $index not sent \n";
            },
        ]);

        $promise = $pool->promise();
        $promise->wait();
        return 0;
    }
}
