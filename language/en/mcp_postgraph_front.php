<?php

if (!defined('IN_PHPBB'))
{
    exit;
}

if (empty($lang) || !is_array($lang))
{
    $lang = array();
}

$lang = array_merge($lang, array(
    'LABEL_POST'           => 'post by day',
    'LABEL_POSTER'     => 'poster by day',
    'SELECT_MONTH'  => 'Select a mouth',
    'LAST_DAYS'     => 'last days',
));
