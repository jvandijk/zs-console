Zend Server Web API console
=======================

Introduction
------------
This application enables users to execute maintenance on multiple Zend Servers
at ones.
It is based on a Zend Framework 2 module structure and a work in
progress.


Configuration
------------
Create a &lt;name&gt;.local.php file in the autoload folder. Include the
following structure:

<pre><code>
&lt;?php
<?php

return array(
    'zsconsole' => array(
        'servers' => array(
            'local' => array(
                'name' => '&lt;Your name definition&gt;',
                'host' => '&lt;hostname or ip:port&gt;', // no protocol
                'user' => '&lt;zend server api name&gt;',
                'apikey' => '&lt;zend server api key&gt;',
                'cluster' => true, // optional key
            ),
        ),
    ),
);
</code></pre>
