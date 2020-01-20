# Log Console Helper
helper to display and manipulate messages in console with php

## how to use?
is simple, you can download the master branch or install package via composer
```
  composer require filipemansano/log-helper
```

get instance

```php
require 'vendor/autoload.php';
use FilipeMansano\Log;
$log = Log::getInstance();
```

and now, print your messages in console
```php
$log->printMessage("This is a simple text");
$log->printMessage("----------------------");
$log->printMessage("now the font color is red!", Log::FOREGROUND_RED);
$log->printMessage("----------------------");
$log->printMessage("now the back color is green and the font is white!", Log::FOREGROUND_WHITE, Log::BACKGROUND_GREEN);
$log->printMessage("----------------------");
$log->printMessage("in ", Log::FOREGROUND_GREEN, Log::BACKGROUND_BLACK,0);
$log->printMessage("the ", Log::FOREGROUND_YELLOW, Log::BACKGROUND_BLACK,0);
$log->printMessage("same ", Log::FOREGROUND_BLUE, Log::BACKGROUND_BLACK,0);
$log->printMessage("line ", Log::FOREGROUND_MAGENTA, Log::BACKGROUND_BLACK,0);
$log->printMessage("multiples ", Log::FOREGROUND_BLACK, Log::BACKGROUND_RED,0);
$log->printMessage("color ", Log::FOREGROUND_WHITE, Log::BACKGROUND_MAGENTA,0);
$log->printMessage("scheme ", Log::FOREGROUND_LIGHT_MAGENTA, Log::BACKGROUND_LIGHT_GREY,1);
$log->printMessage("----------------------");
$log->printError("printing a example of exception", (new \Exception("Error Description", 500)));
$log->printMessage("----------------------");
```
### output
![Image of output](https://i.imgur.com/peasodT.png)


## Replace Text in console
you can replace the last text printed with another, a comum case is display percent of loading, e.g.

```php
for ($i=1; $i <= 100; $i++) {   
    $log->printMessage("Text replace ({$i}%)", Log::FOREGROUND_WHITE, Log::BACKGROUND_BLACK, Log::REPLACE_LAST_MESSAGE);
    usleep(50000);
}

$log->printMessage("Text replace ", Log::FOREGROUND_WHITE, Log::BACKGROUND_BLACK, Log::REPLACE_LAST_MESSAGE);
$log->printMessage("[DONE]", Log::FOREGROUND_GREEN);
```
