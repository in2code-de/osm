<?php
declare(strict_types=1);
namespace In2code\Osm\Utility;

use TYPO3\CMS\Backend\Routing\Exception\ResourceNotFoundException;
use TYPO3\CMS\Backend\Routing\Exception\RouteNotFoundException;
use TYPO3\CMS\Backend\Routing\Router;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class BackendUtility
{
    /**
     * @throws RouteNotFoundException
     */
    public static function createEditUri(string $tableName, int $identifier, bool $addReturnUrl = true): string
    {
        $uriParameters = [
            'edit' => [
                $tableName => [
                    $identifier => 'edit'
                ]
            ]
        ];
        if ($addReturnUrl) {
            $uriParameters['returnUrl'] = self::getReturnUrl();
        }

        return self::getRoute('record_edit', $uriParameters);
    }

    /**
     * Get return URL from current request
     *
     * @throws RouteNotFoundException
     */
    protected static function getReturnUrl(): string
    {
        return self::getRoute(self::getModuleName(), self::getCurrentParameters());
    }

    /**
     * @throws RouteNotFoundException
     */
    public static function getRoute(string $route, array $parameters = []): string
    {
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        return (string)$uriBuilder->buildUriFromRoute($route, $parameters);
    }

    /**
     * Get module name or route as fallback
     */
    protected static function getModuleName(): string
    {
        $moduleName = 'record_edit';
        if (($GLOBALS['TYPO3_REQUEST']->getQueryParams()['route'] ?? null) !== null) {
            $routePath = (string)($GLOBALS['TYPO3_REQUEST']->getQueryParams()['route'] ?? null);
            $router = GeneralUtility::makeInstance(Router::class);
            try {
                $route = $router->match($routePath);
                $moduleName = $route->getOption('_identifier');
            } catch (ResourceNotFoundException $exception) {
                unset($exception);
            }
        }

        return $moduleName;
    }

    /**
     * Get all GET/POST params without module name and token
     */
    public static function getCurrentParameters(array $getParameters = []): array
    {
        if ($getParameters === []) {
            $getParameters = $GLOBALS['TYPO3_REQUEST']->getQueryParams();
        }

        $parameters = [];
        $ignoreKeys = [
            'M',
            'moduleToken',
            'route',
            'token'
        ];
        foreach ($getParameters as $key => $value) {
            if (in_array($key, $ignoreKeys)) {
                continue;
            }

            $parameters[$key] = $value;
        }

        return $parameters;
    }
}
