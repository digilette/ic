<?php
return array(
    'IC' => array(
        'disable_usage' => false,    // set to true to disable showing available ZFTool commands in Console.
    ),

    // -----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=-----=

    'controllers' => array(
        'invokables' => array(
            'Ic_Create'   => 'Intellimage\Ic\Controller\CreateController',
        ),
    ),

    'console' => array(
        'router' => array(
            'routes' => array(
                'ic-create-module' => array(
                    'options' => array(
                        'route'    => 'create module [--admin|-a]:admin [--minimal|-m]:minimal <name> [<path>]',
                        'defaults' => array(
                            'controller' => 'Ic_Create',
                            'action'     => 'module',
                        ),
                    ),
                ),
            ),
        ),
    ),
);
