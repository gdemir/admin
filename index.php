<?php

require_once  'lib/base.php';     // for fat-free
require_once  'inc/lib.php';      // for plug-in
require_once  'cfg/init.php';     // for db connect
require_once  'cfg/process.php';  // for db connect





F3::route("GET  /*",          'Account->login');  // login page
F3::route("POST /login",      'Account->auth');   // login action
F3::route('GET  /logout',     'Account->logout'); // logout action

F3::route('GET  /home',       'Page->home');      // home page
F3::route("GET  /captcha",    'Captcha->jester'); // captcha
F3::route('POST /table',      'Datas->table');    // table action

F3::route("GET  /info",       'Page->info');      // info page

F3::route("GET  /create",     'Page->create');    // new page
F3::route("POST /save",       'Datas->save');     // new action

F3::route("GET  /find",       'Page->find');      // find page
F3::route("POST /find",       'Datas->find');     // find action

F3::route("GET  /review",     'Page->review');    // review page

F3::route("GET  /edit/@key",  'Datas->edit');      // edit page
F3::route("GET  /show/@key",  'Datas->show');      // show page
F3::route("GET  /erase/@key", 'Datas->erase');     // del page

F3::route("POST /update",     'Datas->update');   // update action

F3::route("GET  /download",   'Page->download');  // csv download page
F3::route("GET  /upload",     'Page->upload');    // csv upload page

F3::route("POST /download",   'Datas->download'); // csv download action
F3::route("POST /upload",     'Datas->upload');   // csv upload action

F3::run();

?>
