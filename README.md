Zend Server Web API console
=======================

Introduction
------------
This application enables users to execute maintenance on multiple Zend Servers
at one.
It is based on a Zend Framework 2 beta 3 module structure and a work in
progress.


Configuration
------------
Create a &lt;name&gt;.local.config.php file in the autoload folder. Include the
following structure:

<pre><code>
&lt;?php
$servers = array(
    'local' => array(
        'name' => '&lt;Your name definition&gt;',
        'host' => '&lt;hostname or ip:port&gt;', // no protocol
        'user' => '&lt;zend server api name&gt;',
        'apikey' => '&lt;zend server api key&gt;',
        'cluster' => true, // optional key
    ),
);

return array(
    'di' => array(
        'instance' => array(
            'ZsConsole\Service\ZendServer' => array(
                'parameters' => array(
                    'servers' => $servers,
                )
            ),
        ),
    ),
);
</code></pre>
