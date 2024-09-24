<?php

namespace HubSdk;

enum DeviceOperation: string
{
    case Reboot = 'reboot';
    case Reload = 'reload';
    case Check = 'check';
    case CrearLogs = 'clear-log';
    case CrearData = 'clear-data';
    case Unalarm = 'unalarm';
    case Unlock = 'unlock';
    case Info = 'info';
    case Log = 'log';
}
