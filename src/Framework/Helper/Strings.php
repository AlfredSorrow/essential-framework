<?php

namespace Framework\Helper;

function jsonForPrint($json)
{
    return json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
}
