<?php

function current_user(){
    return app('Modules\Users\Repositories\AuthInterface')->check();
}

function current_user_groups($all=false){
    $user = current_user();
    if($user){
        $groups = $user->groups();
        return ($all) ? $groups : $groups->first();
    }
    return false;
}

function is_admin_group(){
    $group = current_user_groups();
    if($group && $group->name == 'Admin') return true;
    return false;
}

function current_user_email(){
    $user = current_user();
    if($user){
        return $user->email;
    }
    return '';
}

function is_user_current($user_id)
{
    $user = current_user();
    if (isset($user)){
        if ($user_id == current_user()->id)
        {
            return true;
        }
    }
    return false;
}

function generate_password(){
    $password = substr(md5(rand()), 0, 7);
    return $password;
}

function has_access($permission)
{
    return app('Modules\Users\Repositories\AuthInterface')->hasAccess($permission);
}

function format_date($date = null)
{
    if(!is_null($date) && $date != '0000-00-00'){
        $date = new DateTime($date);
        return $date->format('d/m/Y');
    }
    return null;
}
function format_datetime($date = null)
{
    $date = new DateTime($date);
    return $date->format('d/m/Y H:i');
}


/**
 * Return the database time
 * @param null $userDate
 * @return null|string
 */
function unformat_date($userDate = null)
{
    if ($userDate)
    {
        $date = DateTime::createFromFormat('d/m/Y', $userDate);

        return $date->format('Y-m-d');
    }

    return '0000-00-00';
}

function unformat_datetime($userDate = null)
{
    if ($userDate)
    {
        $date = DateTime::createFromFormat('d/m/Y H:i', $userDate);

        return $date->format('Y-m-d H:i');
    }

    return '0000-00-00';
}
