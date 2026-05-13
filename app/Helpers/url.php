<?php

function url($path = '')
{
    $configClass = class_exists('LinkHub\Libraries\Config') ? 'LinkHub\Libraries\Config' : 'App\Libraries\Config';
    $baseUrl = $configClass::get('app.url', 'http://localhost');
    return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
}

function asset($path)
{
    return url('assets/' . ltrim($path, '/'));
}
