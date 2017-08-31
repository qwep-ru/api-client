<?php

require __DIR__ . '/vendor/autoload.php';

$app = new \Slim\App();

/**
 * Index page
 */
$app->get('/', function (\Slim\Http\Request $request, \Slim\Http\Response $response, $args) {
    $pageParameters = [
        'title' => 'Главная',
        'results' => [],
        'requests' => [],
        'responses' => []
    ];

    $settings = json_decode(file_get_contents(__DIR__ . '/settings.json'), true);

    if ($request->getParam('input') && $request->getParam('action') == 'search') {
        $requestJson = [
            'Request' => [
                'requestData' => [
                    'article' => $request->getParam('input'),
                    'accounts' => [],
                    'vendors' => [],
                    'type' => $settings['type']
                ]
            ]
        ];

        foreach ($settings['accounts'] as $k => $v) {
            $requestJson['Request']['requestData']['accounts'][] = ['id' => $v];
        }

        foreach ($settings['vendors'] as $k => $v) {
            $requestJson['Request']['requestData']['vendors'][] = ['id' => $v];
        }

        $requestJson = json_encode($requestJson);

        $client = new GuzzleHttp\Client();

        $responseFromApi = $client->request('POST', $settings['host'] . '/userapi/search', [
            'headers' => ['Authorization' => 'Bearer ' . $settings['token']],
            'body' => $requestJson
        ]);

        $responseJson = $responseFromApi->getBody()->getContents();

        $pageParameters['results'] = json_decode($responseJson, true)['Response']['entity']['results'];

        /**
         * Fix json below
         */

        foreach ($pageParameters['results'] as $resultKey => $resultValue) {
            foreach ($resultValue['items'] as $itemKey => $itemValue) {
                $pageParameters['results'][$resultKey]['items'][$itemKey]['price'] = json_decode($itemValue['price'],
                    true)['Value'];
                $pageParameters['results'][$resultKey]['items'][$itemKey]['quantity'] = json_decode($itemValue['quantity'],
                    true)['Value'];
            }
        }

        $pageParameters['requests'][] = json_encode(json_decode($requestJson, true), JSON_PRETTY_PRINT);
        $pageParameters['responses'][] = json_encode(json_decode($responseJson, true), JSON_PRETTY_PRINT);
    } else if ($request->getParam('input') && $request->getParam('action') == 'results') {
        $requestJson = [
            'Request' => [
                'requestData' => [
                    'searchId' => $request->getParam('input')
                ]
            ]
        ];

        $requestJson = json_encode($requestJson);

        $client = new GuzzleHttp\Client();

        $responseFromApi = $client->request('POST', $settings['host'] . '/userapi/search/results', [
            'headers' => ['Authorization' => 'Bearer ' . $settings['token']],
            'body' => $requestJson
        ]);

        $responseJson = $responseFromApi->getBody()->getContents();

        $pageParameters['results'] = json_decode($responseJson, true)['Response']['entity']['results'];

        /**
         * Fix json below
         */

        foreach ($pageParameters['results'] as $resultKey => $resultValue) {
            foreach ($resultValue['items'] as $itemKey => $itemValue) {
                $pageParameters['results'][$resultKey]['items'][$itemKey]['price'] = json_decode($itemValue['price'],
                    true)['Value'];
                $pageParameters['results'][$resultKey]['items'][$itemKey]['quantity'] = json_decode($itemValue['quantity'],
                    true)['Value'];
            }
        }

        $pageParameters['requests'][] = json_encode(json_decode($requestJson, true), JSON_PRETTY_PRINT);
        $pageParameters['responses'][] = json_encode(json_decode($responseJson, true), JSON_PRETTY_PRINT);
    } else if ($request->getParam('input') && $request->getParam('action') == 'updates') {
        $requestJson = [
            'Request' => [
                'requestData' => [
                    'searchId' => $request->getParam('input')
                ]
            ]
        ];

        $requestJson = json_encode($requestJson);

        $client = new GuzzleHttp\Client();

        $responseFromApi = $client->request('POST', $settings['host'] . '/userapi/search/updates', [
            'headers' => ['Authorization' => 'Bearer ' . $settings['token']],
            'body' => $requestJson
        ]);

        $responseJson = $responseFromApi->getBody()->getContents();

        $pageParameters['results'] = json_decode($responseJson, true)['Response']['entity']['results'];

        /**
         * Fix json below
         */

        foreach ($pageParameters['results'] as $resultKey => $resultValue) {
            foreach ($resultValue['items'] as $itemKey => $itemValue) {
                $pageParameters['results'][$resultKey]['items'][$itemKey]['price'] = json_decode($itemValue['price'],
                    true)['Value'];
                $pageParameters['results'][$resultKey]['items'][$itemKey]['quantity'] = json_decode($itemValue['quantity'],
                    true)['Value'];
            }
        }

        $pageParameters['requests'][] = json_encode(json_decode($requestJson, true), JSON_PRETTY_PRINT);
        $pageParameters['responses'][] = json_encode(json_decode($responseJson, true), JSON_PRETTY_PRINT);
    } else if ($request->getParam('clarification')) {
        $requestJson = [
            'Request' => [
                'requestData' => [
                    'clarification' => $request->getParam('clarification'),
                    'type' => $settings['type']
                ]
            ]
        ];

        $requestJson = json_encode($requestJson);

        $client = new GuzzleHttp\Client();

        $responseFromApi = $client->request('POST', $settings['host'] . '/userapi/search', [
            'headers' => ['Authorization' => 'Bearer ' . $settings['token']],
            'body' => $requestJson
        ]);

        $responseJson = $responseFromApi->getBody()->getContents();

        $pageParameters['results'] = json_decode($responseJson, true)['Response']['entity']['results'];

        /**
         * Fix json below
         */

        foreach ($pageParameters['results'] as $resultKey => $resultValue) {
            foreach ($resultValue['items'] as $itemKey => $itemValue) {
                $pageParameters['results'][$resultKey]['items'][$itemKey]['price'] = json_decode($itemValue['price'], true)['Value'];
                $pageParameters['results'][$resultKey]['items'][$itemKey]['quantity'] = json_decode($itemValue['quantity'], true)['Value'];
            }
        }

        $pageParameters['requests'][] = json_encode(json_decode($requestJson, true), JSON_PRETTY_PRINT);
        $pageParameters['responses'][] = json_encode(json_decode($responseJson, true), JSON_PRETTY_PRINT);
    }

    return $this->view->render($response, 'index.html', $pageParameters);
})->setName('index');

/**
 * Vendors page
 */
$app->get('/vendors', function (\Slim\Http\Request $request, \Slim\Http\Response $response, $args) {
    $pageParameters = [
        'title' => 'Поставщики',
        'results' => [],
        'requests' => [],
        'responses' => []
    ];

    $settings = json_decode(file_get_contents(__DIR__ . '/settings.json'), true);

    $requestJson = [];

    $requestJson = json_encode($requestJson);

    $client = new GuzzleHttp\Client();

    $responseFromApi = $client->request('GET', $settings['host'] . '/userapi/vendors', [
        'headers' => ['Authorization' => 'Bearer ' . $settings['token']]
    ]);

    $responseJson = $responseFromApi->getBody()->getContents();

    $pageParameters['results'] = json_decode($responseJson, true)['Response']['entity']['vendors'];

    $pageParameters['requests'][] = json_encode(json_decode($requestJson, true), JSON_PRETTY_PRINT);
    $pageParameters['responses'][] = json_encode(json_decode($responseJson, true), JSON_PRETTY_PRINT);

    return $this->view->render($response, 'vendors.html', $pageParameters);
})->setName('vendors');

/**
 * Accounts page
 */
$app->get('/accounts', function (\Slim\Http\Request $request, \Slim\Http\Response $response, $args) {
    $pageParameters = [
        'title' => 'Аккаунты',
        'results' => [],
        'requests' => [],
        'responses' => []
    ];

    $settings = json_decode(file_get_contents(__DIR__ . '/settings.json'), true);

    $requestJson = [];

    $requestJson = json_encode($requestJson);

    $client = new GuzzleHttp\Client();

    if ($request->getParam('vid') && $request->getParam('login') && $request->getParam('password') && $request->getParam('parameters')) {
        $intermediateRequestJson = [
            'Request' => [
                'requestData' => [
                    'accounts' => [[
                        'vid' => $request->getParam('vid'),
                        'login' => $request->getParam('login'),
                        'password' => $request->getParam('password'),
                        'parameters' => $request->getParam('parameters')
                    ]]
                ]
            ]
        ];

        $intermediateRequestJson = json_encode($intermediateRequestJson);

        $intermediateResponseFromApi = $client->request('POST', $settings['host'] . '/userapi/accounts/add', [
            'headers' => ['Authorization' => 'Bearer ' . $settings['token']],
            'body' => $intermediateRequestJson
        ]);

        $intermediateResponseJson = $intermediateResponseFromApi->getBody()->getContents();

        $pageParameters['requests'][] = json_encode(json_decode($intermediateRequestJson, true), JSON_PRETTY_PRINT);
        $pageParameters['responses'][] = json_encode(json_decode($intermediateResponseJson, true), JSON_PRETTY_PRINT);
    } else if ($request->getParam('delete')) {
        $intermediateRequestJson = [
            'Request' => [
                'requestData' => [
                    'accounts' => [[
                        'id' => $request->getParam('delete')
                    ]]
                ]
            ]
        ];

        $intermediateRequestJson = json_encode($intermediateRequestJson);

        $intermediateResponseFromApi = $client->request('POST', $settings['host'] . '/userapi/accounts/delete', [
            'headers' => ['Authorization' => 'Bearer ' . $settings['token']],
            'body' => $intermediateRequestJson
        ]);

        $intermediateResponseJson = $intermediateResponseFromApi->getBody()->getContents();

        $pageParameters['requests'][] = json_encode(json_decode($intermediateRequestJson, true), JSON_PRETTY_PRINT);
        $pageParameters['responses'][] = json_encode(json_decode($intermediateResponseJson, true), JSON_PRETTY_PRINT);
    }

    $responseFromApi = $client->request('GET', $settings['host'] . '/userapi/accounts/get', [
        'headers' => ['Authorization' => 'Bearer ' . $settings['token']]
    ]);

    $responseJson = $responseFromApi->getBody()->getContents();

    $pageParameters['results'] = json_decode($responseJson, true)['Response']['entity']['accounts'];

    $pageParameters['requests'][] = json_encode(json_decode($requestJson, true), JSON_PRETTY_PRINT);
    $pageParameters['responses'][] = json_encode(json_decode($responseJson, true), JSON_PRETTY_PRINT);

    return $this->view->render($response, 'accounts.html', $pageParameters);
})->setName('accounts');

/**
 * Regions page
 */
$app->get('/regions', function (\Slim\Http\Request $request, \Slim\Http\Response $response, $args) {
    $pageParameters = [
        'title' => 'Регионы',
        'results' => [],
        'requests' => [],
        'responses' => []
    ];

    $settings = json_decode(file_get_contents(__DIR__ . '/settings.json'), true);

    $requestJson = [];

    $requestJson = json_encode($requestJson);

    $client = new GuzzleHttp\Client();

    $responseFromApi = $client->request('GET', $settings['host'] . '/userapi/geo/regions', [
        'headers' => ['Authorization' => 'Bearer ' . $settings['token']]
    ]);

    $responseJson = $responseFromApi->getBody()->getContents();

    $pageParameters['results'] = json_decode($responseJson, true)['Response']['entity']['regions'];

    $pageParameters['requests'][] = json_encode(json_decode($requestJson, true), JSON_PRETTY_PRINT);
    $pageParameters['responses'][] = json_encode(json_decode($responseJson, true), JSON_PRETTY_PRINT);

    return $this->view->render($response, 'regions.html', $pageParameters);
})->setName('regions');

/**
 * Cities page
 */
$app->get('/cities', function (\Slim\Http\Request $request, \Slim\Http\Response $response, $args) {
    $pageParameters = [
        'title' => 'Города',
        'results' => [],
        'requests' => [],
        'responses' => []
    ];

    $settings = json_decode(file_get_contents(__DIR__ . '/settings.json'), true);

    $requestJson = [];

    $requestJson = json_encode($requestJson);

    $client = new GuzzleHttp\Client();

    $responseFromApi = $client->request('GET', $settings['host'] . '/userapi/geo/cities', [
        'headers' => ['Authorization' => 'Bearer ' . $settings['token']]
    ]);

    $responseJson = $responseFromApi->getBody()->getContents();

    $pageParameters['results'] = json_decode($responseJson, true)['Response']['entity']['cities'];

    $pageParameters['requests'][] = json_encode(json_decode($requestJson, true), JSON_PRETTY_PRINT);
    $pageParameters['responses'][] = json_encode(json_decode($responseJson, true), JSON_PRETTY_PRINT);

    return $this->view->render($response, 'cities.html', $pageParameters);
})->setName('cities');

/**
 * Settings page
 */
$app->get('/settings', function (\Slim\Http\Request $request, \Slim\Http\Response $response, $args) {
    $pageParameters = [
        'title' => 'Настройки',
        'results' => [],
        'requests' => [],
        'responses' => []
    ];

    $settings = json_decode(file_get_contents(__DIR__ . '/settings.json'), true);

    $pageParameters['results'] = $settings;

    if ($request->getParam('host') || $request->getParam('token') || $request->getParam('accounts') || $request->getParam('vendors') || $request->getParam('type')) {
        $settings = [
            'host' => $request->getParam('host'),
            'token' => $request->getParam('token'),
            'accounts' => explode(',', $request->getParam('accounts')),
            'vendors' => explode(',', $request->getParam('vendors')),
            'type' => $request->getParam('type')
        ];

        file_put_contents(__DIR__ . '/settings.json', json_encode($settings));
    }

    return $this->view->render($response, 'settings.html', $pageParameters);
})->setName('settings');

/**
 * Webhook log
 */
$app->get('/webhook', function (\Slim\Http\Request $request, \Slim\Http\Response $response, $args) {
    $pageParameters = [
        'title' => 'Лог хука',
        'results' => [],
        'requests' => [],
        'responses' => []
    ];

    if ($request->getParam('clear')) {
        file_put_contents(__DIR__ . '/updates.log', '');
    }

    $settings = json_decode(file_get_contents(__DIR__ . '/settings.json'), true);

    $pageParameters['results'] = file_get_contents(__DIR__ . '/updates.log');

    return $this->view->render($response, 'webhook.html', $pageParameters);
})->setName('webhook');

/**
 * Updates catcher — catch incoming updates from API QWEP
 */
$app->post('/updates', function (\Slim\Http\Request $request, \Slim\Http\Response $response, $args) {
    file_put_contents(__DIR__ . '/updates.log', PHP_EOL . PHP_EOL . PHP_EOL . date("Y-m-d H:i:s") . ' : ' . $request->getBody(), FILE_APPEND);

    return $response->withStatus(200);
})->setName('updates');

$container = $app->getContainer();

$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/templates', []);

    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};

$app->run();