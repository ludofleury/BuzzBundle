# Playbloom Buzz Bundle

Provide a basic logger and an advanced profiler for Buzz
This work is a quick port of the [Playbloom GuzzleBundle](https://github.com/ludofleury/GuzzleBundle).
It may be improved with some features from the GuzzleBundle, yet I do not provide support, feel free to fork & PR.

* The basic logger use the default Symfony app logger, it's safe to use in your production environement.
* The advanced profiler is for debug purposes and will display a dedicated report available in the toolbar and in the Symfony Web Profiler

<img src="https://www.evernote.com/shard/s282/sh/0d175de7-29b6-4bd6-b0b0-4d14f7447489/ca61946cc7a5eeeb07bfe820c9037019/res/170ac2f9-0dcb-4d39-b73d-7fc6a6ce2cfa/skitch.png" witdh="280" height="175" alt="Buzz Symfony web profiler panel">
<img src="https://www.evernote.com/shard/s282/sh/5f8d3d3b-8f8f-411b-b41e-4ed399dce6e9/796213f2dfc5d3b489f95a9c5646103f/res/d55806d9-8055-40b2-8fd7-d1e1bf1b456d/skitch.png" witdh="280" height="175" alt="Buzz Symfony web profiler panel - request details">
<img src="https://www.evernote.com/shard/s282/sh/a5f1dc50-e4f7-4048-a84e-97f9c2190f30/1327fee5e07958e14d412351cc4917ae/res/99d80459-6a41-4faf-a7af-72710eab0e36/skitch.png" witdh="280" height="175" alt="Buzz Symfony web profiler panel - response details">

## Installation

Add the composer requirements (will be soon on packagist)
```javascript
{
    "require-dev": {
        "playbloom/buzz-bundle": "dev-master"
    },

    "repositories": [
        {
            "type": "git",
            "url": "git://github.com/ludofleury/BuzzBundle.git"
        }
    ]
}
```

Add the bundle to your Symfony app kernel
```php
<?php
    // in %your_project%/app/AppKernel.php
    $bundles[] = new Playbloom\Bundle\BuzzBundle\PlaybloomBuzzBundle();
?>
```

To enable the advanced profiler & the toolbar/web profiler panel, add this line to your `app/config/config_dev.yml`
```yml
playbloom_buzz:
    web_profiler: true
```

### Buzz client as a Symfony service

Buzz is a lightweight browser so the service declaration is straightforward. Just add the tag `playbloom_buzz.browser` and it will add the basic logger to your client(s). If the web_profiler is enabled in the current environement, it will also add the advanced profiler and display report on the Symfony toolbar/web profiler.

```xml
<service id="acme.browser" class="Buzz\Browser">
    <tag name="playbloom_buzz.browser" />
</service>
```

### Add the logger/profiler manually to a Buzz browser

To register the logger or profiler listener manually for a browser, you can retrieve theses services from the Symfony container.

```php
<?php

$browser = new \Buzz\Browser();

// basic logger service plugged & configured with the default Symfony app logger
$loggerListener = $container->get('playbloom_buzz.browser.listener.logger');
$browser->addListener($loggerListener);

// advanced profiler for developement and debug, requires web_profiler to be enabled
$profilerListener = $container->get('playbloom_buzz.browser.listener.profiler');
$client->addListener($profilerListener);

?>
```

## Limitation

* It doesn't handle the logging/profiling for a Buzz Client, you need to use a Buzz browser (buzz implementation limitation).
* The Message (request/response) from Buzz are really simple and so the information in the profiler.
* If you need a rock-solid HTTP client, try Guzzle and... the Playbloom GuzzleBundle !


## Credits

* Swagger for the UI
