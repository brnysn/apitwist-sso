<?php

namespace Brnysn\ApiTwistSSO\Commands;

use Illuminate\Console\Command;

class ApiTwistSSOCommand extends Command
{
    public $signature = 'apitwist-sso';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
