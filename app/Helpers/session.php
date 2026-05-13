<?php

function session($key = null, $value = null)
{
    if (is_null($key)) {
        return \App\Libraries\Session::class;
    }
    if (is_null($value)) {
        return \App\Libraries\Session::get($key);
    }
    \App\Libraries\Session::set($key, $value);
}

function auth()
{
    return \App\Libraries\Session::get('user');
}

function auth_check()
{
    return \App\Libraries\Session::has('user');
}
