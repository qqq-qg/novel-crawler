<?php

function get_web_site($url)
{
    $urlArr = parse_url($url);
    if (empty($urlArr)) {
        return '';
    }
    return ($urlArr['scheme'] ?? 'http') . '://' . ($urlArr['host'] ?? '');
}
