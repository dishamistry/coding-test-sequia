<?php
require 'functions.php';
class Converter
{
    public static function convertCurrency($amount = 1, $from = 'AUD', $to = 'NZD')
    {
        try {
            $convertedAmount = 0;
            $isConverted = true;
            $data = require 'config.php';

            $availableCurrencies = array_keys($data);
            array_push($availableCurrencies, "AUD");
            if (!in_array($from, $availableCurrencies) || !in_array($to, $availableCurrencies)) {
                echo "Please enter valid currency" . "<hr/><br/>";
                $isConverted = false;
                return false;
            }

            if (isset($data) && isset($data[$to])) {
                $conversionRate = $data[$to]['rate'];
                $convertedAmount = $amount * $conversionRate;
            } else if (isset($data) && !isset($data[$to])) {
                $conversionRate = $data[$from]['rate'];
                $convertedAmount = $amount / $conversionRate;
            } else {
                echo "Conversion Not Available <hr/><br/>";
                $isConverted = false;
                return false;
            }

            if ($isConverted) {
                saveCurrencyConversionToCsv($amount,$from,$to,$convertedAmount,'conversion');
            }
        } catch (\Exception $e) {
            echo 'Exception Message: ' . $e->getMessage();
            return false;
        }
    }
}

Converter::convertCurrency(100, 'AUD', 'USD');
Converter::convertCurrency(100, 'USD', 'AUD');
Converter::convertCurrency(100, 'NZD', 'AUD');