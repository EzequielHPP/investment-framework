# Investment Framework
This is a custom built framework just to practice and get a deeper understanding on Frameworks.
___
By Ezequiel Pereira (24/05/2021)
___

This is an offline application with a File Database (json files) located on `system/db/*.json`.

This is intended as just practice and not designed for production.

## Structure
- Events
  - Event classes that can be triggered across the platform
- Listeners
  - Listeners that react to Event calls
- Models
  - Database (not really) models
- Services
  - Libraries for multiple actions on the platform
- system
  - Core of the system
    - db (json files), 
    - db_backup (the files that are restored everytime for testing)
    - Exceptions that are thrown across the system
    - Interfaces for Listeners and Models
    - Base Listener actions (extension for the platform listeners)
    - Base Model actions (extension for the platform models)
    - Boot script for the platform (autoloader)
    - Boot script for PhpUnit (it resets the DB values and calls the normal boot script)
    - Constants. What the name implies 😀
    - Event trigger function
    - Log function to print out messages
    - ModelInitializer, that runs on the constructor, that sets the fields for each model load
- Tests
  - Contains the PHPUnit Tests
- index.php
  - Not really used. This was more for me to execute the scripts while I was building this.
- phpunit
  - The PHPUnit library
- phpunit.xml 
  - Configuration File
  
---
## Models

All the models have basic functionality. `where`, `find`, `get` and `first`.
- where
  - You can use it as follows:
  ```php
  $model->where('field','=','something')->where('otherField','=','somethingElse');
  ```
  It's accomulative. So you can keep on adding where's.
  
- find
  - this will find an entry based on an ID
  
- get
  - This retrieves an array of entries matching the criteria, or all of the entries on the DB
  
- first
  - Returns the first match, or first entry of the DB
  
---
## Events
This ar actions (events) that should trigger a reaction of Listeners for a given action.

These actions / reactions are set under `Events/kernel.php`.

They are setup in this configuration:
```php
return [
    EventClassName::class => [
        ListenerClassName::class    
    ]
];
```
You write say to the system for the given event, execute the following listeners.

---
## How to run 🚀

### PHPUnit
On the command line run:
```shell
php phpunit Tests
```
3 Tests with 8 assertions

You can run the application and force it to run commands.

Just edit the `index.php` file and can run any, but not restricted to, the following.

#### To create investors
<small>*Or any other model entry</small>
```php
$investor = (new \Models\Investor());
$investor->name = 'John Doe';
$investor->save();
event(new \Events\InvestorCreated($investor->id));
```
That would generate a new Investor, and the event would create the Investor wallet.

#### Add funds to an Investor
```php
$investor = (new \Models\Investor())->find(1);
$investor->wallet()->addFunds(1000);
```
Investor ID 1 was given as an example. This would add £1000 to the Investors Wallet
This is the same way as the End Of Month Interest Calculation ads ££ to the wallet. 

#### Check if investor has the amount available to use
```php
$investor = (new \Models\Investor())->find(1);
if($investor->checkForFunds(1000)){
    echo "Success you have enough funds";
} else {
    echo "Sorry. You don't have enough funds for this transaction.";
}
```
This is how on the "Create transaction" I check if the Investor has funds available

#### Make an investment
This you would call the service `InvestmentService` with the function `invest`, like so:
```php
try{
    $investmentService = (new \Services\InvestmentService());
    $investor = (new \Models\Investor())->find(1);
    $investment = $investmentService->invest($investor->id, $tranche->id, $amount, $date);
} catch (\Throwable $exception){
    (new \system\Log())->error($exception);
}
```
This would return a boolean or throw an exception with the correct message 
