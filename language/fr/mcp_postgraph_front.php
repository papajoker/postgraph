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
    'LABEL_POST'           => 'posts par jour',
    'LABEL_POSTER'     => 'posteurs par jour',
    'LABEL_USER'     => 'nouveaux utilisateurs',
    'SELECT_MONTH'  => 'Sélectionner un mois',
    'LAST_DAYS'     => 'derniers jours',
));
