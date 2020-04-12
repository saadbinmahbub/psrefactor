<?php
$binLookupURL = 'https://lookup.binlist.net/';
$latestExchangeRatesURL = 'https://api.exchangeratesapi.io/latest';
$rows = explode("\n", file_get_contents($argv[1]));

foreach ($rows as $row) {
    if (empty($row)) break;

    $bin = getBin($row);
    $amount = getAmount($row);
    $currency = getCurrency($row);

    $binLookupResults = searchBin($bin);
    $rate = getRate($currency);

    $amountFixed = getAmountFixed($currency, $rate, $amount);

    $output = $amountFixed * (isEu($binLookupResults->country->alpha2) ? 0.01 : 0.02);
    echo round_up($output, 2);
    print "\n";
}

function getAmountFixed($currency, $rate, $amount)
{
    if ($currency != 'EUR' || $rate > 0) {
        return $amount / $rate;
    }
    return $amount;
}

function searchBin($bin)
{
    global $binLookupURL;
    $binResults = file_get_contents($binLookupURL . $bin);
    if (!$binResults)
        die('error!');
    return json_decode($binResults);
}

function getRate($currency)
{
    global $latestExchangeRatesURL;
    $rateResults = file_get_contents($latestExchangeRatesURL);
    if($currency == 'EUR') return 1;
    return json_decode($rateResults, true)['rates'][$currency];
}

function getBin($row)
{
    $data = json_decode($row);
    return $data->bin;
}

function getAmount($row)
{
    $data = json_decode($row);
    return $data->amount;
}

function getCurrency($row)
{
    $data = json_decode($row);
    return $data->currency;
}

// function extractValueFromColonSeparatedString($input) {
//     $separateByColon = explode(':', $input);
//     return trim($separateByColon[1], '"');
// }

function isEu($country)
{
    switch ($country) {
        case 'AT':
        case 'BE':
        case 'BG':
        case 'CY':
        case 'CZ':
        case 'DE':
        case 'DK':
        case 'EE':
        case 'ES':
        case 'FI':
        case 'FR':
        case 'GR':
        case 'HR':
        case 'HU':
        case 'IE':
        case 'IT':
        case 'LT':
        case 'LU':
        case 'LV':
        case 'MT':
        case 'NL':
        case 'PO':
        case 'PT':
        case 'RO':
        case 'SE':
        case 'SI':
        case 'SK':
            return true;
        default:
            return false;
    }
}

// https://stackoverflow.com/questions/8239600/rounding-up-to-the-second-decimal-place
function round_up($value, $precision)
{
    $pow = pow(10, $precision);
    $value = (ceil($pow * $value) + ceil($pow * $value - ceil($pow * $value))) / $pow;
    return number_format($value, 2);
}