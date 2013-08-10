<?php
return array(
    'modules' => array(
        'Intellimage\\Ic',
    ),
    'module_listener_options' => array(
        'config_glob_paths'    => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
        'module_paths' => array(
            'Intellimage\\Ic' => dirname(__DIR__ . '/../'),
        ),
    ),
);

