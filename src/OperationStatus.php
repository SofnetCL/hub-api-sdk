<?php

namespace HubSdk;

enum OperationStatus: string
{
    case Waiting = 'waiting';
    case Executing = 'executing';
    case Executed = 'executed';
    case Failed = 'failed';
}
