<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class OneTimeCreateMQExchangeScheme extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:one-time-create-m-q-exchange-scheme';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create MQ Exchange Scheme for comments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //cretae  exchange  'potap.comment.ex'
        $connection = new AMQPStreamConnection(env('MQ_HOST'), env('MQ_PORT'), env('MQ_USER'), env('MQ_PASS'), env('MQ_VHOST'));
        $channel = $connection->channel();

        $channel->exchange_declare('potap.comment.ex', 'direct', false, true, false);
        $channel->exchange_declare('potap.notify.ex', 'fanout', false, true, false);
        $channel->exchange_bind('potap.notify.ex', 'potap.comment.ex', 'notify');


        //create queue 'potap.comments.validate'
        $channel->queue_declare('potap.comments.validate', false, true, false, false);
        $channel->queue_bind('potap.comments.validate', 'potap.comment.ex', 'validation');


        //create queue potap.comments.notify_tel
        $channel->queue_declare('potap.comments.notify_tel', false, true, false, false);
        $channel->queue_bind('potap.comments.notify_tel', 'potap.notify.ex', '');

        //create queue potap.comments.notify_mail
        $channel->queue_declare('potap.comments.notify_mail', false, true, false, false);
        $channel->queue_bind('potap.comments.notify_mail', 'potap.notify.ex', '');

        $channel->close();
        $connection->close();

        $this->info('Exchange and queues created successfully and bound to each other');
        $this->info('Set QUEUE_CONNECTION to rabbitmq in .ENV');
        $this->info('Run the following commands in supervisor to handle comments saving via RabbitMQ:');
        $this->info('php artisan queue:work --queue=potap.comments.validate');
        $this->info('php artisan queue:work --queue=potap.comments.notify_tel');
        $this->info('php artisan queue:work --queue=potap.comments.notify_mail');
    }
}
