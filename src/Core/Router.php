<?php

declare(strict_types=1);

namespace Core;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\HttpFoundation\Request;

class Router
{
    private static ?RouteCollection $routes = null;

    public static function init(): void
    {
        self::$routes = new RouteCollection();
        self::registerRoutes();
    }

    private static function registerRoutes(): void
    {
        self::resource('category', '/categories', \Modules\Category\CategoryController::class);
        self::resource('combo', '/combos', \Modules\Combo\ComboController::class);
        self::resource('gallery', '/gallery', \Modules\Gallery\GalleryController::class);
        self::resource('price_range', '/price-ranges', \Modules\PriceRange\PriceRangeController::class);
        self::resource('product', '/products', \Modules\Product\ProductController::class);
        self::resource('product_price', '/product-prices', \Modules\ProductPrice\ProductPriceController::class);
        self::resource('promotion', '/promotions', \Modules\Promotion\PromotionController::class);
        self::resource('settings', '/settings', \Modules\Settings\SettingsController::class);
    }

    private static function resource(string $name, string $uri, string $controller): void
    {
        self::$routes->add("{$name}_index", new Route(
            path: $uri,
            defaults: ['_controller' => [$controller, 'index']],
            methods: ['GET'],
        ));
        self::$routes->add("{$name}_show", new Route(
            path: "{$uri}/{id}",
            defaults: ['_controller' => [$controller, 'show']],
            methods: ['GET'],
        ));
        self::$routes->add("{$name}_store", new Route(
            path: $uri,
            defaults: ['_controller' => [$controller, 'store']],
            methods: ['POST'],
        ));
        self::$routes->add("{$name}_update", new Route(
            path: "{$uri}/{id}",
            defaults: ['_controller' => [$controller, 'update']],
            methods: ['PUT', 'PATCH'],
        ));
        self::$routes->add("{$name}_destroy", new Route(
            path: "{$uri}/{id}",
            defaults: ['_controller' => [$controller, 'destroy']],
            methods: ['DELETE'],
        ));
    }

    public static function dispatch(Request $request): array
    {
        if (self::$routes === null) {
            self::init();
        }

        $context = new RequestContext();
        $context->fromRequest($request);

        $matcher = new UrlMatcher(self::$routes, $context);

        try {
            $parameters = $matcher->match($request->getPathInfo());
            $controller = $parameters['_controller'];
            unset($parameters['_controller'], $parameters['_route']);

            return [
                'controller' => $controller,
                'params' => $parameters
            ];
        } catch (ResourceNotFoundException $e) {
            return [
                'error' => 'Route not found',
                'status' => 404
            ];
        } catch (MethodNotAllowedException $e) {
            return [
                'error' => 'Method not allowed',
                'status' => 405
            ];
        }
    }

    public static function getRoutes(): RouteCollection
    {
        if (self::$routes === null) {
            self::init();
        }

        return self::$routes;
    }
}
