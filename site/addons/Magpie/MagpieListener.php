<?php

namespace Statamic\Addons\Magpie;

use Statamic\Extend\Listener;

class MagpieListener extends Listener
{
    public $events = [
        'magpie.finished' => 'handleBackup'
    ];

    public function handleBackup($process)
    {
        // Create an array of the commands that we
        // would normally issue in the console.
        $commands[] = 'git add .';
        $commands[] = 'git commit -a -m "Automated backup"';
        $commands[] = 'git push';

        // The implode function will add the ` && `
        // between each command so we don't have to.
        $process->setCommandLine(implode(' && ', $commands))->run();
    }
}
