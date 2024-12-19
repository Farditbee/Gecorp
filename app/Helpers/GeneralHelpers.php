<?php

if (!function_exists('set_active_menu')) {
    function set_active_menu($routeName, $activeClass = 'active')
    {
        return request()->routeIs($routeName) ? $activeClass : '';
    }
}
