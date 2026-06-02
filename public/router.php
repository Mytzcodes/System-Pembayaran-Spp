<?php
/**
 * Simple Router using PATH_INFO
 * Best practice: Clean URLs without /public/
 */

class Router {
    private $routes = [];
    private $basePath = '';
    
    public function __construct($basePath = '') {
        $this->basePath = rtrim($basePath, '/');
    }
    
    /**
     * Add GET route
     */
    public function get($path, $callback) {
        $this->addRoute('GET', $path, $callback);
    }
    
    /**
     * Add POST route
     */
    public function post($path, $callback) {
        $this->addRoute('POST', $path, $callback);
    }
    
    /**
     * Add route for any method
     */
    private function addRoute($method, $path, $callback) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback
        ];
    }
    
    /**
     * Dispatch request
     */
    public function dispatch() {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            
            // Get path from PATH_INFO or REQUEST_URI
            $path = $this->getPath();
            
            foreach ($this->routes as $route) {
                if ($route['method'] !== $method) {
                    continue;
                }
                
                $pattern = $this->convertToRegex($route['path']);
                
                if (preg_match($pattern, $path, $matches)) {
                    array_shift($matches); // Remove full match
                    
                    // Call callback with parameters
                    return call_user_func_array($route['callback'], $matches);
                }
            }
            
            // 404 Not Found
            $this->show404();
        } catch (Exception $e) {
            $this->show500($e);
        }
    }
    
    /**
     * Show 404 error page
     */
    private function show404() {
        http_response_code(404);
        if (file_exists(__DIR__ . '/errors/404.php')) {
            require __DIR__ . '/errors/404.php';
        } else {
            echo '<!DOCTYPE html><html><head><title>404</title></head><body><h1>404 - Page Not Found</h1></body></html>';
        }
        exit;
    }
    
    /**
     * Show 500 error page
     */
    private function show500($exception = null) {
        http_response_code(500);
        if (file_exists(__DIR__ . '/errors/500.php')) {
            require __DIR__ . '/errors/500.php';
        } else {
            echo '<!DOCTYPE html><html><head><title>500</title></head><body><h1>500 - Server Error</h1></body></html>';
        }
        
        // Log error
        if ($exception) {
            error_log($exception->getMessage());
        }
        exit;
    }
    
    /**
     * Get current path from PATH_INFO or REQUEST_URI
     */
    private function getPath() {
        // Try PATH_INFO first (best practice)
        if (isset($_SERVER['PATH_INFO'])) {
            return $_SERVER['PATH_INFO'];
        }
        
        // Fallback to REQUEST_URI
        $uri = $_SERVER['REQUEST_URI'];
        
        // Remove query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }
        
        // Remove base path
        if ($this->basePath && strpos($uri, $this->basePath) === 0) {
            $uri = substr($uri, strlen($this->basePath));
        }
        
        // Remove /public/ if present
        $uri = str_replace('/public', '', $uri);
        
        return $uri ?: '/';
    }
    
    /**
     * Convert route path to regex pattern
     */
    private function convertToRegex($path) {
        // Convert :param to regex capture group
        $pattern = preg_replace('/\/:([^\/]+)/', '/([^/]+)', $path);
        
        // Escape forward slashes
        $pattern = str_replace('/', '\/', $pattern);
        
        return '/^' . $pattern . '$/';
    }
    
    /**
     * Redirect to URL
     */
    public static function redirect($url, $code = 302) {
        header("Location: $url", true, $code);
        exit;
    }
}
