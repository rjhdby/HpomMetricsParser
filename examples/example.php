<?php
use hpom\MetricParser;

include_once __DIR__ . '/../src/MetricParser.php';

$rawMetric = 'SYNTAX_VERSION 5


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
                DESCRIPTION "Assert Failed"
                CONDITION_ID "b1c954ce-defd-71e2-1f00-ac19028f0000"
                CONDITION
                        TEXT "Assert Failed"
                SET
                        SEVERITY Critical
                        MSGTYPE "sms"
                        TEXT "<$LOGFILE>: <$MSG_TEXT>"
                        NOTIFICATION
                DESCRIPTION "Server Started"
                CONDITION_ID "f81ba2ea-b31b-71e3-1216-ac19cb0e0000"
                CONDITION
                        TEXT "Server Started"
                SET
                        SEVERITY Critical
                        MSGTYPE "sms"
                        TEXT "<$LOGFILE>: <$MSG_TEXT>"
                        NOTIFICATION
                DESCRIPTION "Bad Primary Chunk"
                CONDITION_ID "17b31fc6-1e68-71e5-0f4e-ac19028f0000"
                CONDITION
                        TEXT "Bad Primary Chunk"
                SET
                        SEVERITY Critical
                        MSGTYPE "sms"
                        TEXT "<$LOGFILE>: <$MSG_TEXT>"
                        NOTIFICATION';

$parser = new MetricParser($rawMetric);
print_r($parser->parse());