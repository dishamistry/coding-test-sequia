<?php

function saveCurrencyConversionToCsv($amount, $from, $to, $convertedAmount, $filename)
{
    $initialAmountWithCurrencyCode = round($amount, 2) . ' ' . $from;
    $convertedAmountWithCurrencyCode = round($convertedAmount, 2) . ' ' . $to;
    $inputArray = array($initialAmountWithCurrencyCode, $convertedAmountWithCurrencyCode);

    print_r("<br/>" . $amount . " " . $from . " equals " . round($convertedAmount, 2) . "   " . $to . "<hr/><br/>");

    $filename = $filename . '.csv';
    if (!file_exists($filename)) {
        $f = fopen($filename, 'w');
        if ($f === false) {
            die('==> Error opening the file ' . $filename);
        }
        $headerArray = array('Initial Amount', 'Converted Amount');
        fputcsv($f, $headerArray);
        fclose($f);
    }

    $f = fopen($filename, 'a');
    if ($f === false) {
        die('==> Error opening the file ' . $filename);
    }
    fputcsv($f, $inputArray);
    fclose($f);
}