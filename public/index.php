<?php //-->
/*
 * This file is part a custom application package.
 * (c) 2011-2012 Openovate Labs
 */

require __DIR__.'/../app/App.php';

// app()->test();

//
// app('registry')->set('x', 'sample');
//
// var_dump(app('registry'));

App::i()

->defaultPaths()
->defaultDatabases()
->trigger('config')
->defaultTimezone('Asia/Manila')
->trigger('init')
->defaultSession()
->trigger('session')
->defaultRouting()
->trigger('request')
->server()
->trigger('response')
->render();
;
