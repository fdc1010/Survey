<?php

return array(


    'pdf' => array(
        'enabled' => true,
        'binary'  => '/usr/bin/wkhtmltopdf', //'/usr/local/bin/wkhtmltopdf',
        'timeout' => 3600,
        'options' => array(),
        'env'     => array(),
    ),
    'image' => array(
        'enabled' => true,
        'binary'  => '/usr/bin/wkhtmltoimage', //'/usr/local/bin/wkhtmltoimage',
        'timeout' => 3600,
        'options' => array(),
        'env'     => array(),
    ),


);
