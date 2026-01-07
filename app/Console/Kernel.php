<?php

namespace Console;

use App\Event;

class Kernel
{
    public function run($argv)
    {
        foreach ($argv as $key => $value) {
            if ($value == "github-activity") {
                $user = $argv[$key + 1];

                if (!$user) {
                    echo "please provide username .";
                    exit(1);
                }
                $event = new Event($user);
                $data = $event->parseEvents($event->getUserEvents());
                foreach ($data as $eventData) {
                    echo $eventData;
                }
            }
        }
    }
}
