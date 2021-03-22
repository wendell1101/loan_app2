<?php
function formatDecimal($num)
{
    return  number_format((float)$num, 2, '.', '');
}
