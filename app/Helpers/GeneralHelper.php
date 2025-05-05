<?php

if (!function_exists('getInitials')) {
    function getInitials($name) {
        $words = explode(' ', trim($name));
        if (count($words) == 1) {
            return strtoupper(substr($words[0], 0, 1));
        }

        $firstInitial = strtoupper(substr($words[0], 0, 1));
        $lastInitial = strtoupper(substr(end($words), 0, 1));

        return $firstInitial . $lastInitial;
    }
}