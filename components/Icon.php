<?php


class Icon
{
    public static function show($icon)
    {
        $class = 'glyphicon glyphicon-'.$icon;
        return '<i class="'.$class.'"></i> ';
    }
}