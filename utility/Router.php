<?php 


class Router
{
    private $routes = [];

    private function register($path, $method, $handler)
    {
        $this->routes["$method"]["$path"] = [
            'handler' => $handler
        ];
    }

    public function get($path, $handler)
    {
        $this->register($path, 'GET', $handler);
    }

    public function post($path, $handler)
    {
        $this->register($path, 'POST', $handler);
    }

    public function resolve()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            // echo "Requested Path: $path <br>";
  
        $path = str_replace("/cafeteria", "", $path);
        if (isset($this->routes["$method"]["$path"])) {

            $handler = $this->routes["$method"]["$path"];
            
               
            $class = $handler['handler'][0];
            $method = $handler['handler'][1];
           

            if(class_exists($class) && method_exists($class, $method)) {
               
             // [ClassName::class, 'methodName']
               
             $instance = new $class();

               $instance->$method();

            }else{
                echo "Handler not found";
            }
            

        }else{
             echo "404 Not Found";
        }
     
       
    }
}