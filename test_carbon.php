<?php
require __DIR__.'/vendor/autoload.php';
use Carbon\Carbon;

$start = Carbon::parse('2026-03-24 16:44:58');
$end   = Carbon::parse('2026-03-24 16:45:19');

echo 'end->diffInSeconds(start)=' . $end->diffInSeconds($start) . "\n";
echo 'end->diffInSeconds(start,false)=' . $end->diffInSeconds($start,false) . "\n";
echo 'end->diffInMinutes(start)=' . $end->diffInMinutes($start) . "\n";
echo 'end->diffInHours(start)=' . $end->diffInHours($start) . "\n";
echo 'start->diffInHours(end)=' . $start->diffInHours($end) . "\n";

echo 'end->floatDiffInHours(start)=' . $end->floatDiffInHours($start) . "\n";
echo 'end->floatDiffInHours(start,false)=' . $end->floatDiffInHours($start,false) . "\n";
