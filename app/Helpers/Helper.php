<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

class Helper
{
    public static function formatDate($date) {
        return date('j M Y', strtotime($date));
    }

    public static function formatDateDB($date) {
        return date('Y-m-d H:i:s', strtotime($date));
    }
}
