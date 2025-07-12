<?php
// router.php
if (preg_match('/^\/api\//', $_SERVER['REQUEST_URI'])) {
    // Route API requests to the correct file outside public/
    $apiPath = __DIR__ . '/../' . ltrim($_SERVER['REQUEST_URI'], '/');
    if (file_exists($apiPath)) {
        require $apiPath;
        return true;
    } else {
        http_response_code(404);
        echo 'API endpoint not found.';
        return false;
    }
}
// For everything else, use the default server behavior
return false; 