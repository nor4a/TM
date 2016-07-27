<?php

// We defined the web service functions to install.
$functions = array(
        //study programs
        'local_bookdup_list' => array(
                'classname'   => 'local_bookdup_webservice',
                'methodname'  => 'list_all',
                'classpath'   => 'local/bookdup/classes/WebService.php',
                'description' => 'Return list of all records',
                'type'        => 'read', 
        ),
        'local_bookdup_create' => array(
                'classname'   => 'local_bookdup_webservice',
                'methodname'  => 'create',
                'classpath'   => 'local/bookdup/classes/WebService.php',
                'description' => 'Return id of the created record',
                'type'        => 'write', 
        ),
        'local_bookdup_delete' => array(
                'classname'   => 'local_bookdup_webservice',
                'methodname'  => 'delete',
                'classpath'   => 'local/bookdup/classes/WebService.php',
                'description' => 'Return id of the deleted record 0 ir not found',
                'type'        => 'write', 
        ),        
);

// We define the services to install as pre-build services. A pre-build service is not editable by administrator.
$services = array(
        'BookDup web services' => array(
                'functions' => array ('local_bookdup_list',
                                      'local_bookdup_create',
                                      'local_bookdup_delete'                                                                    
                                     ),
                'restrictedusers' => 0,
                'enabled' => 1,
        )
);
