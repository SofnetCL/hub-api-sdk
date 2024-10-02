<?php

namespace HubSdk;

enum UserPrivilege: int
{
    case ROOT = 0;
    case USER = 1;
    case REGISTER = 2;
    case ADMIN = 6;
    case SUPERVISOR = 14;
}
