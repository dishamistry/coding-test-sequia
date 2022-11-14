<?php

namespace application\controllers;

use application\core\Helper;

class ConverterController
{
    public function index()
    {
        try {
            $convertedCurrencyHistory = Helper::csvToArray('conversion.csv');
            array_shift($convertedCurrencyHistory);
            return require "application/views/index.php";
        } catch (\Exception $e) {
            echo 'Exception Message: ' . $e->getMessage();
            return false;
        }
    }

    public function convertCurrency()
    {
        try {
            $amount = $_POST['amount'];
            $from = trim(strtoupper($_POST['fromCurrency']));
            $to = trim(strtoupper($_POST['toCurrency']));
            $convertedAmount = 0;
            $isConverted = true;
            $conversionRate = Helper::getConfig('config')['currencyRates'];
            $availableCurrencies = array_keys($conversionRate);
            array_push($availableCurrencies, "AUD");

            if ($from !== 'AUD' && $to !== 'AUD') {
                header("Location: /?message=Conversion Must contain AUD");
                $isConverted = false;
                return false;
            }

            if (!in_array($from, $availableCurrencies) || !in_array($to, $availableCurrencies)) {
                header("Location: /?message=Please enter valid currency");
                $isConverted = false;
                return false;
            }

            if (isset($conversionRate) && !isset($conversionRate[$to]) && !isset($conversionRate[$from])) {
                header("Location: /?message=Conversion Rate Not Available");
                $isConverted = false;
                return false;
            }

            $convertedAmount = (isset($conversionRate) && !isset($conversionRate[$to]) && isset($conversionRate[$from])) ? ($amount / $conversionRate[$from]['rate']) : ($amount * $conversionRate[$to]['rate']);
            if ($isConverted) {
                $this->saveCurrencyConversionToCsv($amount, $from, $to, $convertedAmount, 'conversion.csv');
            }
            header('Location: /');
        } catch (\Exception $e) {
            echo 'Exception Message: ' . $e->getMessage();
            return false;
        }
    }

    public function saveCurrencyConversionToCsv($amount, $from, $to, $convertedAmount, $filename)
    {
        try {
            $initialAmountWithCurrencyCode = round($amount, 2) . ' ' . $from;
            $convertedAmountWithCurrencyCode = round($convertedAmount, 2) . ' ' . $to;
            $inputArray = array($initialAmountWithCurrencyCode, $convertedAmountWithCurrencyCode);

            if (!file_exists($filename)) {
                $file = fopen($filename, 'w');
                if ($file === false)
                    header("Location: /?message=Error opening the file $filename");

                $headerArray = array('Initial Amount', 'Converted Amount');
                fputcsv($file, $headerArray);
                fclose($file);
            }

            $file = fopen($filename, 'a');
            if ($file === false)
                header("Location: /?message=Error opening the file $filename");

            fputcsv($file, $inputArray);
            fclose($file);
        } catch (\Exception $e) {
            echo 'Exception Message: ' . $e->getMessage();
            return false;
        }
    }
}