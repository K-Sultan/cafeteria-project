<?php

class View
{
    public static function render($viewName, $data = [])
    {
        extract($data);
        // __DIR__ points to [project]/utility. 
        // We go up one level (..) then into app/views
        $path = __DIR__ . "/../app/views/" . $viewName . ".php";

        if (file_exists($path)) {
            require_once $path;
        } else {
            // Debugging: This will tell you exactly where it's looking
            die("View Error: Could not find file at " . $path);
        }
    }

    public static function renderComponent($componentName, $data = [])
    {
        extract($data);
        $path = __DIR__ . "/../app/views/components/" . $componentName . ".php";

        if (file_exists($path)) {
            include $path;
        } else {
            echo "";
        }
    }
}
