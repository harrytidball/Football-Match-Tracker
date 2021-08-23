<?php

namespace FootballMatchTracker;
@date_default_timezone_set("GMT");

define ('GMAP_API_KEY', 'AIzaSyAxOVPWTUtOsCXB-CPQ7gvH0Z3ahsU32gU');

function errorHandler(int $errNo, string $errMsg, string $file, int $line) {
    echo "Football Match Tracker Application error handler got #[$errNo] occurred in [$file] at line [$line]: [$errMsg]";
}

set_error_handler('FootballMatchTracker\errorHandler');