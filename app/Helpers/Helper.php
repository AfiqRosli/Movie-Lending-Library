<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

class Helper
{
    public static function formatDate($date) {
        return date('j M Y', strtotime($date));
    }
}
