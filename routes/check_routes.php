<?php

use Illuminate\Support\Facades\Route;

$routes = Route::getRoutes();
$productRoutes = [];

foreach ($routes as $route) {
    $name = $route->getName();
    $uri = $route->uri();
    $methods = implode('|', $route->methods());
    
    if (strpos($name, 'products.') === 0) {
        $productRoutes[] = [
            'name' => $name,
            'uri' => $uri,
            'methods' => $methods
        ];
    }
}

echo "Product Routes:\n";
print_r($productRoutes);
