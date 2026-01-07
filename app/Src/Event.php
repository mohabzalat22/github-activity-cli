<?php

namespace App;

class Event
{
    private  $green  = "\033[32m";
    private $user;
    public function __construct($user)
    {
        $this->user = $user;
    }
    public function getUserEvents()
    {
        $url = "https://api.github.com/users/$this->user/events";
        $headers = [
            'User-Agent: Mohab-CLI',
            'Accept: application/json',
        ];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $data = curl_exec($ch);

        if (curl_errno($ch)) {
            echo "error: " . curl_error($ch);
        }
        return json_decode($data, true);
    }
    public function parseEvents($events)
    {
        $output = [];
        foreach ($events as $event) {
            $repo = $event["repo"]["name"];
            $type = $event["type"];

            switch ($type) {
                case "PushEvent":
                    $count = isset($event["payload"]["commits"]) ? count($event["payload"]["commits"]) : 1;
                    $output[] = "$this->green - Pushed $count commit" . ($count > 1 ? 's' : '') . " to $repo\n";
                    break;

                case "IssuesEvent":
                    $action = $event["payload"]["action"];
                    $output[] = "$this->green - $action a new issue in $repo\n";
                    break;

                case "PullRequestEvent":
                    $action = $event["payload"]["action"];
                    $output[] = "$this->green - $action a pull request in $repo\n";
                    break;

                case 'WatchEvent':
                    $output[] = "$this->green - Starred $repo\n";
                    break;
                case 'ForkEvent':
                    $output[] = "$this->green - Forked $repo\n";
                    break;
                default:
                    // other events
                    $output[] = "$this->green - $type in $repo\n";
                    break;
            }
        }
        return $output;
    }
}
