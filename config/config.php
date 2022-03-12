<?php

declare(strict_types=1);

return [

    // Manage autoload migrations
    'autoload_migrations' => true,

    // IDs obscuration rotations, higher number means more secure, and less efficient
    // For random IDs every request you can use `random_int(1, 999)` (not recommended)
    'obscure' => 1,

    // Global Route Override
    'route' => [

        // Prefix routes with {locale} whenever applicable
        // Changing this option require re-caching routes if already cached
        'locale_prefix' => false,

        // Redirect standard routes to its localized alternative whenever applicable
        // 'route.locale_prefix' must be true for this option to work
        'locale_redirect' => false,

        // Automatically add a trailing slash to the end of all routes
        'trailing_slash' => false,

    ],

    // Adminarea Configuration
    'adminarea' => [

        // Adminarea Idle Timeout
        'idle_timeout' => [

            'enforce' => false,
            'warning' => 30,

            'keepalive_enforce' => false,
            'keepalive_interval' => 300,

        ],

    ],

    'models' => [
        'accessarea' => \Cortex\Foundation\Models\Accessarea::class,
    ],

    'tables' => [
        'media' => 'media',
        'activity_log' => 'activity_log',
        'notifications' => 'notifications',
        'temporary_uploads' => 'temporary_uploads',
        'accessareas' => 'accessareas',
        'accessibles' => 'accessibles',
        'cache_records' => 'cache_records',
        'cache_locks' => 'cache_locks',
    ],

    // Media storage config
    'media' => [
        'size' => '1024', // KB
        'disk' => 's3-public',
        'mimetypes' => 'image/jpeg,image/gif,image/png',
    ],

    // Datatables
    'datatables' => [

        // Available button actions. When calling an action, the value will be used as the
        // function name. If you want to add or disable an action, overload and modify this config.
        'actions' => ['print', 'csv', 'excel', 'pdf', 'delete', 'activate', 'deactivate', 'revoke'],

        'options' => [
            'dom' => "<'row'<'col-sm-8'B><'col-sm-4'f>> <'row'r><'row'<'col-sm-12't>> <'row'<'col-sm-5'i><'col-sm-7'p>>",
            'select' => '{"style":"multi"}',
            'order' => [[1, 'asc']],
            'mark' => true,
            'keys' => false,
            'retrieve' => true,
            'autoWidth' => false,
            'fixedHeader' => true,
            'responsive' => true,
            'stateSave' => false,
            'scrollX' => false,
            'pageLength' => 10,
            'lengthMenu' => [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
        ],

        'buttons' => [
            'create' => true,
            'import' => true,
            'create_popup' => false,

            'reset' => true,
            'reload' => true,
            'showSelected' => true,

            'print' => true,
            'export' => true,

            'bulkDelete' => true,
            'bulkActivate' => false,
            'bulkDeactivate' => false,
            'bulkRevoke' => false,

            'colvis' => true,
            'pageLength' => true,
        ],

        'imports' => [
            'xlsx',
            'xlsm',
            'xltx',
            'xltm',
            'xls',
            'xlt',
            'csv',
            'tsv',
        ],

        'chunk_size' => 1000,
        'batch_size' => 1000,

    ],

];
