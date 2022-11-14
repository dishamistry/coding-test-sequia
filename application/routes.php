<?phP

$router->get('', 'ConverterController@index');
$router->post('convertCurrency', 'ConverterController@convertCurrency');