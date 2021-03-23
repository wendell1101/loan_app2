<?php
function formatDate($date)
{
    $d = new DateTime($date);
    return $d->format("F j \, Y \, g:ia \ ");
}

function shortDate($date)
{
    $d = new DateTime($date);
    return $d->format("F j \, Y ");
}
