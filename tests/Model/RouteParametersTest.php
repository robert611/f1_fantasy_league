<?php 

namespace App\Test\Database;

use App\Model\Router\RouteParameters;
use PHPUnit\Framework\TestCase;

final class RouteParametersTest extends TestCase
{
    private RouteParameters $routeParameters;

    public function setUp(): void
    {
        $this->routeParameters = new RouteParameters();
    }

    /**
    * @dataProvider getCorrectTestUrls
    */
    public function test_if_urls_are_correct_and_return_correct_arguments($url, $routes, $expectedArguments, $matchingRouteIndex)
    {        
        $routeData = $this->routeParameters->getRawUriAndMatchingRoute($url, $routes);

        $route = $routeData['route'];

        $parameters = $this->routeParameters->getParameters($url, $routes);

        $this->assertEquals($route, $routes[$matchingRouteIndex]);
        $this->assertEquals($expectedArguments, $parameters);
    }

    /**
    * @dataProvider getWrongTestUrls
    */
    public function test_if_unexisted_url_will_be_treated_as_404($url, $routes, $expectedArguments, $matchingRouteIndex)
    {
        $routeData = $this->routeParameters->getRawUriAndMatchingRoute($url, $routes);

        $this->assertFalse($routeData);
    }

    public function getCorrectTestUrls(): array
    {
        return [
            [
                'uri' => '/8',
                'routes' => ['/{race_id}' => ['controller' => 'IndexController', 'method' => 'index']],
                'expected_arguments' => ['8'],
                'matching_route_index' => '/{race_id}'
            ],
            [
                'uri' => '/8/tomy',
                'routes' => ['/{race_id}/{username}' => ['controller' => 'IndexController', 'method' => 'index']],
                'expected_arguments' => ['8', 'tomy'],
                'matching_route_index' => '/{race_id}/{username}'
            ],
            [
                'uri' => '/photo/6/delete',
                'routes' => ['/photo/{id}/delete' => ['controller' => 'IndexController', 'method' => 'index']],
                'expected_arguments' => ['6'],
                'matching_route_index' => '/photo/{id}/delete'
            ],
            [
                'uri' => '/photo/6/1/delete',
                'routes' => ['/photo/{id}/{user_id}/delete' => ['controller' => 'IndexController', 'method' => 'index']],
                'expected_arguments' => ['6', '1'],
                'matching_route_index' => '/photo/{id}/{user_id}/delete'
            ],
            [
                'uri' => '/login',
                'routes' => ['/login' => ['controller' => 'IndexController', 'method' => 'index']],
                'expected_arguments' => [],
                'matching_route_index' => '/login'
            ],
            [
                'uri' => '/api/ranking/find/shows/to/compare',
                'routes' => ['/api/ranking/find/shows/to/compare' => ['controller' => 'IndexController', 'method' => 'index']],
                'expected_arguments' => [],
                'matching_route_index' => '/api/ranking/find/shows/to/compare'
            ],
            [
                'uri' => '/admin/show/delete/episode/dr_house/15',
                'routes' => ['/admin/show/delete/episode/{showDatabaseTableName}/{episodeId}' => ['controller' => 'IndexController', 'method' => 'index']],
                'expected_arguments' => ['dr_house', '15'],
                'matching_route_index' => '/admin/show/delete/episode/{showDatabaseTableName}/{episodeId}'
            ],
            [
                'uri' => '/admin/show/episodes/list/the_100',
                'routes' => ['/admin/show/episodes/list/{showDatabaseTableName}' => ['controller' => 'IndexController', 'method' => 'index']],
                'expected_arguments' => ['the_100'],
                'matching_route_index' => '/admin/show/episodes/list/{showDatabaseTableName}'
            ],
            [
                'uri' => '/show/lost/200',
                'routes' => ['/show/{showTableName}/{episodeId}' => ['controller' => 'IndexController', 'method' => 'index']],
                'expected_arguments' => ['lost', '200'],
                'matching_route_index' => '/show/{showTableName}/{episodeId}'
            ],
            [
                'uri' => '/show/lost/',
                'routes' => ['/show/{showTableName}/{episodeId}' => ['controller' => 'IndexController', 'method' => 'index']],
                'expected_arguments' => ['lost', ''],
                'matching_route_index' => '/show/{showTableName}/{episodeId}'
            ],
            [
                'uri' => '/driver/1',
                'routes' => ['/driver/{id}' => ['controller' => 'IndexController', 'method' => 'index']],
                'expected_arguments' => ['1'],
                'matching_route_index' => '/driver/{id}'
            ],
            [
                'uri' => '/driver/1245/edit',
                'routes' => ['/driver/{id}/edit' => ['controller' => 'IndexController', 'method' => 'index']],
                'expected_arguments' => ['1245'],
                'matching_route_index' => '/driver/{id}/edit'
            ],
            [
                'uri' => '/10/simulate/race',
                'routes' => ['/{id}/simulate/race' => ['controller' => 'IndexController', 'method' => 'index']],
                'expected_arguments' => ['10'],
                'matching_route_index' => '/{id}/simulate/race'
            ],
            [
                'uri' => '/homepage/8/tomy',
                'routes' => ['/{race_id}/{username}' => ['controller' => 'IndexController', 'method' => 'index']],
                'expected_arguments' => ['homepage', '8/tomy'],
                'matching_route_index' => '/{race_id}/{username}'
            ],
        ];
    }

    public function getWrongTestUrls(): array
    {
        return [
            [
                'uri' => '/race/8',
                'routes' => ['/{race_name}/{race_id}/{race_date}' => ['controller' => 'IndexController', 'method' => 'index']],
                'expected_arguments' => ['8'],
                'matching_route_index' => '/{race_name}/{race_id}/{race_date}'
            ],
            [
                'uri' => '/driver/1245/edit/test',
                'routes' => ['/driver/{id}/edit' => ['controller' => 'IndexController', 'method' => 'index']],
                'expected_arguments' => ['1245'],
                'matching_route_index' => '/driver/{id}/edit'
            ],
            [
                'uri' => '/10/simulate/race/test',
                'routes' => ['/{id}/simulate/race' => ['controller' => 'IndexController', 'method' => 'index']],
                'expected_arguments' => ['10'],
                'matching_route_index' => '/{id}/simulate/race'
            ],
            [
                'uri' => '/login',
                'routes' => ['/register' => ['controller' => 'IndexController', 'method' => 'index']],
                'expected_arguments' => [],
                'matching_route_index' => '/register'
            ]
        ];
    }
}