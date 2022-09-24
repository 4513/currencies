# Currencies
Based on ISO-4217

## Implementation

```bash
composer require mibo/currencies
```

## Usage

To find a currency:

```php
$factory = new \MiBo\Engine\Utils\Currencies\ISO\ISOCurrencyProvider();

// Get By Alphabetic Code ("EUR")
$currency = $factory->findByAlphabeticalCode("EUR");

// Get By Numerical Code ("012")
$currency = $factory->findByNumericalCode("123");

// Get By Country Name
// Returns an array of currencies
$currencies = $factory->findByCountry("CZECHIA");
```

To change list of available currencies to  
- add custom currencies;
- allow only some currencies;
- change currencies' data
the list(s) can be overwritten:
```php
$factory->getLoader()->setResources(\MiBo\Engine\Utils\Currencies\ISO\ISOListLoader::SOURCE_LOCAL); 
```

### Find by country

One should try to avoid looking for a currency by a country.  
While other functions stops looping through lists when the needed currency is found, the `getCurrencyByCountry` function keeps looking for currency until the end of last file in list. This might prolong the process when one knows that a country uses only one currency and knows a code of the currency.  


## Currency Class

```php
$currency = new \MiBo\Engine\Utils\Currencies\ISO\ISOCurrency(/*...*/);

$currency->getAlphabeticalCode(); // "EUR"
$currency->getNumericalCode(); // "978"
$currency->getName(); // "Euro"
$currency->getMinorUnitRate(); // 2|null
```  

### Minor Unit Rate
Is used to specify, how many digits there are for the currency after decimal point.  
Few of the currencies do not have a minor unit.  
An example for minor unit can be "cent" for "Euro".