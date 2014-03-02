<?php
return array(
    'view_manager' => array(
        'display_not_found_reason' => false,
        'display_exceptions' => true,
        'not_found_template' => 'error/404',
        'not_allowed_template' => 'error/401',
        'exception_template' => 'error/500',

        'template_map' => array(
            //'error/401' => '/../view/error/401.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/500' => __DIR__ . '/../view/error/500.phtml'

        )
    )
);