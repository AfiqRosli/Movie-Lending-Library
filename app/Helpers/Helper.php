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

    public static function latenessChargeDisplay($lateness_charge) {
        // check EXACTLY null
        if ($lateness_charge === null) {
            return '-';
        }

        // check EXACTLY 0
        if ($lateness_charge === 0) {
            return '$0.00 BND';
        }

        if ($lateness_charge > 0) {
            $inDollars = ($lateness_charge / 100);
            $dollarsWithCents = number_format((float)$inDollars, 2, '.', '');

            return '$' . $dollarsWithCents . ' BND';
        }
    }
}
