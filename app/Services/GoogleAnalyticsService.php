<?php

namespace App\Services;

use Exception;
use Google\Client;
use Google\Service\AnalyticsData;
use Google\Service\AnalyticsData\RunReportRequest;

class GoogleAnalyticsService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setAuthConfig(storage_path('app/analytics/cyberpay-433314-eca07f7d2255.json'));
        $this->client->addScope('https://www.googleapis.com/auth/analytics.readonly');
    }

    public function getVisitors($startDate, $endDate, $city)
    {
        $analytics = new AnalyticsData($this->client);

        $propertyID = 'properties/455514869';

        $request = new RunReportRequest([
            'dimensions' => [
                ['name' => $city]
            ],
            'metrics' => [
                ['name' => 'activeUsers']
            ],
            'dateRanges' => [
                ['startDate' => $startDate, 'endDate' => $endDate]
            ]
        ]);

        $response = $analytics->properties->runReport($propertyID, $request);
        return $response->toSimpleObject();
    }

    public function getBounceRate($startDate, $endDate, $city)
    {
        $analytics = new AnalyticsData($this->client);

        $propertyID = 'properties/455514869';

        $request = new RunReportRequest([
            'dimensions' => [
                ['name' => $city]
            ],
            'metrics' => [
                ['name' => 'bounceRate']
            ],
            'dateRanges' => [
                ['startDate' => $startDate, 'endDate' => $endDate]
            ]
        ]);

        $response = $analytics->properties->runReport($propertyID, $request);
        return $response->toSimpleObject();
    }

    public function getUserBehavior($startDate, $endDate)
    {
        $analytics = new AnalyticsData($this->client);

        $propertyID = 'properties/455514869';

        $request = new RunReportRequest([
            'dimensions' => [
                ['name' => 'pagePath']
            ],
            'metrics' => [
                ['name' => 'engagedSessions'],
                ['name' => 'averageSessionDuration']
            ],
            'dateRanges' => [
                ['startDate' => $startDate, 'endDate' => $endDate]
            ]
        ]);

        $response = $analytics->properties->runReport($propertyID, $request);
        return $response->toSimpleObject();
    }
}
