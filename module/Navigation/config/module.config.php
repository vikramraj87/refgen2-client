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

//$redirectUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
return array(
    'navigation' => array(
        'default' => array(
            'citation' => array(
                'label' => 'Citations',
                'title' => 'Manage citations',
                'route' => 'citation/get',
                'pages' => array(
                    'new_list' => array(
                        'label' => 'New list',
                        'title' => 'Clear the existing list and create a blank list',
                        'uri'   => '/citation/new?redirect=' . $redirectUrl
                    ),
                    'open_list' => array(
                        'label' => 'Open list',
                        'title' => 'Open an existing list of articles',
                        'uri'   => '/citation/open?redirect=' . $redirectUrl
                    ),
                    'save_list' => array(
                        'label' => 'Save list',
                        'title' => 'Save changes to the active list',
                        'uri'   => '/citation/save?redirect=' . $redirectUrl
                    ),
                    'save_list_as' => array(
                        'label' => 'Save list as',
                        'title' => 'Save the active list in different/new name',
                        'uri'   => '/citation/save-as?redirect=' . $redirectUrl
                    ),
                    'delete_list' => array(
                        'label' => 'Delete list',
                        'title' => 'Delete the active list permanently',
                        'uri'   => '/citation/delete?redirect=' . $redirectUrl
                    )
                ),
            ),
            'account' => array(
                'label' => 'Account',
                'title' => 'Manage your account',
                'uri'   => '/user/profile',
                'pages' => array(
                    'profile' => array(
                        'label' => 'Profile',
                        'title' => 'Edit/View your profile',
                        'uri' => 'user/profile'
                    ),
                    'logout' => array(
                        'label' => 'Log Out',
                        'uri' => 'user/logout'
                    )
                )
            )
        )
    )
);