<?php
/*
 * Default settings for fof-front
 */

return array(
    'site' => array(
        'name'  => 'Kohata + Bootstrap',
        'title_suffix' => ' | suffix (change in application/config/app.php)'
    ),
    // Default meta information. will be used for other then post page
    'meta' => array(
        'keywords' => 'These, are, dummy, keywords, change, it, from, application/config/app.php',
        'description' => 'Dummy description. Change it from application/config/app.php'
    ),
    'item_per_page' => array(
        'default' => 15,
        'user' => 20,
    ),
);
