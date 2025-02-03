<?php

function convert_date($value)
{
    return date('d-M-Y', strtotime($value));
}

function convert_days($value)
{
    if ($value > 1) {
    return $value . " Days";
    } else {
        return $value . " Day";
    }
}

function convert_price($value)
{
    return "Rp. " . number_format($value, 0, ',', '.') . ",-";
}

function late_return($name, $days)
{
    return $name . " past the deadline by " . $days;
}