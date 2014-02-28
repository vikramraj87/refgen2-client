<?php
// todo: Find a better solution for self url
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$queryStr = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
if(!empty($queryStr) && $url == '/search') {
    $qPairs = array();
    $q = array();

    $qPairs = explode('&', $queryStr);
    foreach($qPairs as $pair) {
        list($k, $v) = explode('=', $pair, 2);
        $q[$k] = $v;
    }

    if(isset($q['term'])) {
        $url .= '/' . $q['term'];
    }

}
$redirectUrl = $url;

return array(
    'navigation' => array(
        'default' => array(
            'citation' => array(
                'label' => 'Citations',
                'title' => 'Manage citations',
                'uri' => '#',
                'resource' => 'Citation\Controller\Index',
                'pages' => array(
                    'new_list' => array(
                        'label' => 'New list',
                        'title' => 'Clear the existing list and create a blank list',
                        'uri'   => '/citation/new?redirect=' . $redirectUrl,
                        'resource' => 'Citation\Controller\Index',
                        'privilege' => 'new'
                    ),
                    'open_list' => array(
                        'label' => 'Open list',
                        'title' => 'Open an existing list of articles',
                        'uri'   => '/citation/open?redirect=' . $redirectUrl,
                        'resource' => 'Citation\Controller\Index',
                        'privilege' => 'open'
                    ),
                    'save_list' => array(
                        'label' => 'Save list',
                        'title' => 'Save changes to the active list',
                        'uri'   => '/citation/save?redirect=' . $redirectUrl,
                        'resource' => 'Citation\Controller\Index',
                        'privilege' => 'save'
                    ),
                    'save_list_as' => array(
                        'label' => 'Save list as',
                        'title' => 'Save the active list in different/new name',
                        'uri'   => '/citation/save-as?redirect=' . $redirectUrl,
                        'resource' => 'Citation\Controller\Index',
                        'privilege' => 'save-as'
                    ),
                    'delete_list' => array(
                        'label' => 'Delete list',
                        'title' => 'Delete the active list permanently',
                        'uri'   => '/citation/delete?redirect=' . $redirectUrl,
                        'resource' => 'Citation\Controller\Index',
                        'privilege' => 'delete'
                    )
                ),
            ),
            'account' => array(
                'label' => 'Account',
                'title' => 'Manage your account',
                'uri'   => '/user/profile',
                'pages' => array(
                    'login' => array(
                        'label' => 'Log In',
                        'uri' => 'user/login',
                        'resource' => 'User\Controller\User',
                        'privilege' => 'login'
                    ),
                    'logout' => array(
                        'label' => 'Log Out',
                        'uri' => 'user/logout',
                        'resource' => 'User\Controller\User',
                        'privilege' => 'logout'
                    ),
                    'register' => array(
                        'label' => 'Register',
                        'uri' => 'user/sign-up',
                        'resource' => 'User\Controller\User',
                        'privilege' => 'sign-up'
                    )
                )
            )
        )
    )
);