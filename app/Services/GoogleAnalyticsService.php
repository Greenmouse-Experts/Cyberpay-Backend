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

    public function getVisitors()
    {
        $analytics = new AnalyticsData($this->client);

        $propertyID = 'properties/455514869';

        $request = new RunReportRequest([
            'dimensions' => [
                ['name' => 'city']
            ],
            'metrics' => [
                ['name' => 'activeUsers']
            ],
            'dateRanges' => [
                ['startDate' => '2023-01-01', 'endDate' => '2024-12-31']
            ]
        ]);

        $response = $analytics->properties->runReport($propertyID, $request);
        return $response->toSimpleObject();
    }

    public function getBounceRate()
    {
        $analytics = new AnalyticsData($this->client);

        $propertyID = 'properties/455514869';

        $request = new RunReportRequest([
            'dimensions' => [
                ['name' => 'city']
            ],
            'metrics' => [
                ['name' => 'bounceRate']
            ],
            'dateRanges' => [
                ['startDate' => '2023-01-01', 'endDate' => '2024-12-31']
            ]
        ]);

        $response = $analytics->properties->runReport($propertyID, $request);
        return $response->toSimpleObject();
    }

    public function getUserBehavior()
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
                ['startDate' => '2023-01-01', 'endDate' => '2024-12-31']
            ]
        ]);

        $response = $analytics->properties->runReport($propertyID, $request);
        return $response->toSimpleObject();
    }

    public function getConversions()
    {
        $analytics = new AnalyticsData($this->client);

        $propertyID = 'properties/455514869';

        $request = new RunReportRequest([
            'dimensions' => [
                ['name' => 'conversionName']
            ],
            'metrics' => [
                ['name' => 'conversions']
            ],
            'dateRanges' => [
                ['startDate' => '2023-01-01', 'endDate' => '2024-12-31']
            ]
        ]);

        $response = $analytics->properties->runReport($propertyID, $request);
        return $response->toSimpleObject();
    }

    public function getComprehensiveReport()
    {
        $analytics = new AnalyticsData($this->client);
        $propertyID = 'properties/455514869';

        $request = new RunReportRequest([
            'dimensions' => [
                ['name' => 'city'],
                ['name' => 'pagePath'] // Add other dimensions as needed
            ],
            'metrics' => [
                ['name' => 'activeUsers'],
                ['name' => 'bounceRate'],
                ['name' => 'engagedSessions'],
                ['name' => 'averageSessionDuration'],
                ['name' => 'conversions'] // Add other metrics as needed
            ],
            'dateRanges' => [
                ['startDate' => '2023-01-01', 'endDate' => '2024-12-31']
            ]
        ]);

        $response = $analytics->properties->runReport($propertyID, $request);
        return $response->toSimpleObject();
    }

    public function getFilteredReport()
    {
        $analytics = new AnalyticsData($this->client);
        $propertyID = 'properties/455514869';

        $request = new RunReportRequest([
            'dimensions' => [
                ['name' => 'city'],
                ['name' => 'pagePath']
            ],
            'metrics' => [
                ['name' => 'activeUsers'],
                ['name' => 'bounceRate']
            ],
            'dateRanges' => [
                ['startDate' => '2023-01-01', 'endDate' => '2024-12-31']
            ],
            'dimensionFilter' => [
                'filter' => [
                    'fieldName' => 'city',
                    'stringFilter' => [
                        'value' => 'Lagos'
                    ]
                ]
            ]
        ]);

        $response = $analytics->properties->runReport($propertyID, $request);
        return $response->toSimpleObject();
    }

    public function getBasicReport()
    {
        $analytics = new AnalyticsData($this->client);
        $propertyID = 'properties/455514869';

        $request = new RunReportRequest([
            'metrics' => [
                ['name' => 'activeUsers']
            ],
            'dateRanges' => [
                ['startDate' => '2023-01-01', 'endDate' => '2024-12-31']
            ]
        ]);

        $response = $analytics->properties->runReport($propertyID, $request);
        return $response->toSimpleObject();
    }

    public function getBasicAnalyticsData()
    {
        $analytics = new AnalyticsData($this->client);
        $propertyID = 'properties/455514869';

        $request = new RunReportRequest([
            'dimensions' => [
                ['name' => 'city']
            ],
            'metrics' => [
                ['name' => 'activeUsers']
            ],
            'dateRanges' => [
                ['startDate' => '2023-01-01', 'endDate' => '2024-12-31']
            ]
        ]);

        try {
            $response = $analytics->properties->runReport($propertyID, $request);

            // Check if rows are returned
            if (isset($response->rows)) {
                return $response->toSimpleObject();
            } else {
                return 'No data returned for the selected query.';
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


}
