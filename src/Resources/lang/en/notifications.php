<?php

return [

    'mail' => [

        'subject' => ':fire: Possible attack on :domain',
        'message' => 'A possible :middleware attack on :domain has been detected from :ip address. The following URL has been affected:<br><br>:url<br><br>Regards',

    ],

    'slack' => [

        'message' => 'A possible attack on :domain has been detected.',

    ],

];
