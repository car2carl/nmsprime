<?php

return[
    'link' => 'Dashboard.index',
    'MenuItems' => [
        'Config Page' => [
            'link' => 'Config.index',
            'icon' => 'fa-book',
            'class' => App\GlobalConfig::class,
        ],
        'Log' => [
            'link' => 'GuiLog.index',
            'icon' => 'fa-history',
            'class' => App\GuiLog::class,
        ],
    ],
];
