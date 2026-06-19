<?php

declare(strict_types=1);

namespace Core;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpFoundation\Request;

class Router
{
    private static RouteCollection $routes;

    public static function init(): void
    {
        self::$routes = new RouteCollection();
        self::registerRoutes();
    }

    private static function registerRoutes(): void
    {
        // Category routes
        self::$routes->add('category_index', new Route('/categories', [
            '_controller' => [Modules\Category\CategoryController::class, 'index']
        ]));
        self::$routes->add('category_show', new Route('/categories/{id}', [
            '_controller' => [Modules\Category\CategoryController::class, 'show']
        ]));
        self::$routes->add('category_store', new Route('/categories', [
            '_controller' => [Modules\Category\CategoryController::class, 'store'],
            '_methods' => ['POST']
        ]));
        self::$routes->add('category_update', new Route('/categories/{id}', [
            '_controller' => [Modules\Category\CategoryController::class, 'update'],
            '_methods' => ['PUT', 'PATCH']
        ]));
        self::$routes->add('category_destroy', new Route('/categories/{id}', [
            '_controller' => [Modules\Category\CategoryController::class, 'destroy'],
            '_methods' => ['DELETE']
        ]));

        // Combo routes
        self::$routes->add('combo_index', new Route('/combos', [
            '_controller' => [Modules\Combo\ComboController::class, 'index']
        ]));
        self::$routes->add('combo_show', new Route('/combos/{id}', [
            '_controller' => [Modules\Combo\ComboController::class, 'show']
        ]));
        self::$routes->add('combo_store', new Route('/combos', [
            '_controller' => [Modules\Combo\ComboController::class, 'store'],
            '_methods' => ['POST']
        ]));
        self::$routes->add('combo_update', new Route('/combos/{id}', [
            '_controller' => [Modules\Combo\ComboController::class, 'update'],
            '_methods' => ['PUT', 'PATCH']
        ]));
        self::$routes->add('combo_destroy', new Route('/combos/{id}', [
            '_controller' => [Modules\Combo\ComboController::class, 'destroy'],
            '_methods' => ['DELETE']
        ]));

        // Gallery routes
        self::$routes->add('gallery_index', new Route('/gallery', [
            '_controller' => [Modules\Gallery\GalleryController::class, 'index']
        ]));
        self::$routes->add('gallery_show', new Route('/gallery/{id}', [
            '_controller' => [Modules\Gallery\GalleryController::class, 'show']
        ]));
        self::$routes->add('gallery_store', new Route('/gallery', [
            '_controller' => [Modules\Gallery\GalleryController::class, 'store'],
            '_methods' => ['POST']
        ]));
        self::$routes->add('gallery_update', new Route('/gallery/{id}', [
            '_controller' => [Modules\Gallery\GalleryController::class, 'update'],
            '_methods' => ['PUT', 'PATCH']
        ]));
        self::$routes->add('gallery_destroy', new Route('/gallery/{id}', [
            '_controller' => [Modules\Gallery\GalleryController::class, 'destroy'],
            '_methods' => ['DELETE']
        ]));

        // PriceRange routes
        self::$routes->add('price_range_index', new Route('/price-ranges', [
            '_controller' => [Modules\PriceRange\PriceRangeController::class, 'index']
        ]));
        self::$routes->add('price_range_show', new Route('/price-ranges/{id}', [
            '_controller' => [Modules\PriceRange\PriceRangeController::class, 'show']
        ]));
        self::$routes->add('price_range_store', new Route('/price-ranges', [
            '_controller' => [Modules\PriceRange\PriceRangeController::class, 'store'],
            '_methods' => ['POST']
        ]));
        self::$routes->add('price_range_update', new Route('/price-ranges/{id}', [
            '_controller' => [Modules\PriceRange\PriceRangeController::class, 'update'],
            '_methods' => ['PUT', 'PATCH']
        ]));
        self::$routes->add('price_range_destroy', new Route('/price-ranges/{id}', [
            '_controller' => [Modules\PriceRange\PriceRangeController::class, 'destroy'],
            '_methods' => ['DELETE']
        ]));

        // Product routes
        self::$routes->add('product_index', new Route('/products', [
            '_controller' => [Modules\Product\ProductController::class, 'index']
        ]));
        self::$routes->add('product_show', new Route('/products/{id}', [
            '_controller' => [Modules\Product\ProductController::class, 'show']
        ]));
        self::$routes->add('product_store', new Route('/products', [
            '_controller' => [Modules\Product\ProductController::class, 'store'],
            '_methods' => ['POST']
        ]));
        self::$routes->add('product_update', new Route('/products/{id}', [
            '_controller' => [Modules\Product\ProductController::class, 'update'],
            '_methods' => ['PUT', 'PATCH']
        ]));
        self::$routes->add('product_destroy', new Route('/products/{id}', [
            '_controller' => [Modules\Product\ProductController::class, 'destroy'],
            '_methods' => ['DELETE']
        ]));

        // Promotion routes
        self::$routes->add('promotion_index', new Route('/promotions', [
            '_controller' => [Modules\Promotion\PromotionController::class, 'index']
        ]));
        self::$routes->add('promotion_show', new Route('/promotions/{id}', [
            '_controller' => [Modules\Promotion\PromotionController::class, 'show']
        ]));
        self::$routes->add('promotion_store', new Route('/promotions', [
            '_controller' => [Modules\Promotion\PromotionController::class, 'store'],
            '_methods' => ['POST']
        ]));
        self::$routes->add('promotion_update', new Route('/promotions/{id}', [
            '_controller' => [Modules\Promotion\PromotionController::class, 'update'],
            '_methods' => ['PUT', 'PATCH']
        ]));
        self::$routes->add('promotion_destroy', new Route('/promotions/{id}', [
            '_controller' => [Modules\Promotion\PromotionController::class, 'destroy'],
            '_methods' => ['DELETE']
        ]));

        // Settings routes
        self::$routes->add('settings_index', new Route('/settings', [
            '_controller' => [Modules\Settings\SettingsController::class, 'index']
        ]));
        self::$routes->add('settings_show', new Route('/settings/{id}', [
            '_controller' => [Modules\Settings\SettingsController::class, 'show']
        ]));
        self::$routes->add('settings_store', new Route('/settings', [
            '_controller' => [Modules\Settings\SettingsController::class, 'store'],
            '_methods' => ['POST']
        ]));
        self::$routes->add('settings_update', new Route('/settings/{id}', [
            '_controller' => [Modules\Settings\SettingsController::class, 'update'],
            '_methods' => ['PUT', 'PATCH']
        ]));
        self::$routes->add('settings_destroy', new Route('/settings/{id}', [
            '_controller' => [Modules\Settings\SettingsController::class, 'destroy'],
            '_methods' => ['DELETE']
        ]));
    }

    public static function dispatch(Request $request): array
    {
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
        }
    }

    public static function getRoutes(): RouteCollection
    {
        return self::$routes;
    }
}
