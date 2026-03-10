<?php 


class View{
    public static function render($view, $data = []) {
         $view = strtolower($view);
         extract($data);

        include  "app/views/" . $view . ".php";

    }

    public static function renderComponent($component, $data = []) {
        $component = strtolower($component);
        extract($data);
       include  "app/views/components/" . $component . ".php";

}

}