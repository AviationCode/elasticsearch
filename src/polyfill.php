<?php

if (! function_exists('array_key_first')) {
    function array_key_first(array $array) {
        foreach ($array as $key => $unused) {
            return $key;
        }

        return null;
    }
}