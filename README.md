# Class for parse HP OpenView Operations Manager metrics
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
![PHP: 7.0](https://img.shields.io/badge/PHP-7.0-green.svg)

## Project structure
    .
    ├── src
    │   └── MetricParser.php      # Parser class file 
    ├── examples
    │   └── example.php           # Use example
    ├── LICENSE.txt               # License
    └── README.md                 # This text

## Using

```php
$parser = new MetricParser($rawMetric);
$array = $parser->parse();
```

Where ```$rawMetric``` must be a string contains value of ```policy_body``` field of ```opc_op.opc_policy_body``` table. 

## Rules
1) All tags with inner content parsed as array with two keys ```[ '_value' => '', 0 => [ ] ]```, even if they don't have explicit value. In this case element with key ```_value``` set to empty string. Element with key ```0``` contains inner content. See ```LOGFILE``` and ```MSGCONDITIONS``` tags.
2) Repeatable sequences are determined by the first level tag. See ```MSGCONDITIONS```. Start of each sequence are determined by tag  ```DESCRIPTION```.

Input text
```
SYNTAX_VERSION 5


LOGFILE "online_log_clients_db"
        DESCRIPTION "log online_clients-db.log"
        LOGPATH "/usr/informix/LOGS/online_clients-db.log"
        INTERVAL "5m"
        CHSET ASCII
        FROM_LAST_POS
        SEVERITY Critical
        NODE IP 172.19.22.150  "clients-db.moscow.alfaintra.net"
        APPLICATION "DB"
        MSGGRP "Informix"
        OBJECT "OnlineLog"
        MSGCONDITIONS
                DESCRIPTION "out of virtual"
                CONDITION_ID "405966b8-fcd0-71e1-0c09-ac19be040000"
                CONDITION
                        TEXT "out of virtual"
                SET
                        SEVERITY Critical
                        MSGTYPE "sms"
                        TEXT "<$LOGFILE>: <$MSG_TEXT>"
                        NOTIFICATION
                DESCRIPTION "Server Stopped"
                CONDITION_ID "4059678a-fcd0-71e1-0c09-ac19be040000"
                CONDITION
                        TEXT "Server Stopped"
                SET
                        SEVERITY Critical
                        MSGTYPE "sms"
                        TEXT "<$LOGFILE>: <$MSG_TEXT>"
                        NOTIFICATION
```

Output array
```$xslt
array (
  'SYNTAX_VERSION' => '5',
  'LOGFILE' => 
  array (
    '_value' => 'online_log_clients_db',
    0 => 
    array (
      'DESCRIPTION' => 'log online_clients-db.log',
      'LOGPATH' => '/usr/informix/LOGS/online_clients-db.log',
      'INTERVAL' => '5m',
      'CHSET' => 'ASCII',
      'FROM_LAST_POS' => '',
      'SEVERITY' => 'Critical',
      'NODE' => 'IP 172.19.22.150  "clients-db.moscow.alfaintra.net',
      'APPLICATION' => 'DB',
      'MSGGRP' => 'Informix',
      'OBJECT' => 'OnlineLog',
      'MSGCONDITIONS' => 
      array (
        '_value' => '',
        0 => 
        array (
          0 => 
          array (
            'DESCRIPTION' => 'out of virtual',
            'CONDITION_ID' => '405966b8-fcd0-71e1-0c09-ac19be040000',
            'CONDITION' => 
            array (
              '_value' => '',
              0 => 
              array (
                'TEXT' => 'out of virtual',
              ),
            ),
            'SET' => 
            array (
              '_value' => '',
              0 => 
              array (
                'SEVERITY' => 'Critical',
                'MSGTYPE' => 'sms',
                'TEXT' => '<$LOGFILE>: <$MSG_TEXT>',
                'NOTIFICATION' => '',
              ),
            ),
          ),
          1 => 
          array (
            'DESCRIPTION' => 'Server Stopped',
            'CONDITION_ID' => '4059678a-fcd0-71e1-0c09-ac19be040000',
            'CONDITION' => 
            array (
              '_value' => '',
              0 => 
              array (
                'TEXT' => 'Server Stopped',
              ),
            ),
            'SET' => 
            array (
              '_value' => '',
              0 => 
              array (
                'SEVERITY' => 'Critical',
                'MSGTYPE' => 'sms',
                'TEXT' => '<$LOGFILE>: <$MSG_TEXT>',
                'NOTIFICATION' => '',
              ),
            ),
          ),
        ),
      ),
    ),
  ),
)
```
