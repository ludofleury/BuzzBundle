# Playbloom Buzz Bundle

Provide a basic logger and an advanced profiler for Buzz
This work is a quick port of the [Playbloom GuzzleBundle](https://github.com/ludofleury/GuzzleBundle).
It may be improved with some features from the GuzzleBundle, yet I do not provide support, feel free to fork & PR.

* The basic logger use the default Symfony app logger, it's safe to use in your production environement.
* The advanced profiler is for debug purposes and will display a dedicated report available in the toolbar and in the Symfony Web Profiler

<img src="https://www.evernote.com/shard/s282/sh/277e57ef-f26a-439d-a5f9-8a0751851e20/3df8314d3e5cf04d365a8917fabe55ba/res/c44d1991-8877-4e96-8e4e-6bea7e8bcecd/skitch.png" witdh="280" height="175" alt="Buzz Symfony web profiler panel">
<img src="https://www.evernote.com/shard/s282/sh/2107da61-7004-4af3-9f1e-e3d391ea7cc2/e7d12a0bf28b3446e47c7ab6233893e1/res/80659fd4-e159-478a-9835-d32fa7763fab/skitch.png" witdh="280" height="175" alt="Buzz Symfony web profiler panel - request details">
<img src="https://www.evernote.com/shard/s282/sh/9ae49202-7697-4cf2-8dce-4db74e8470c7/a0d8616e44e913ae803e2de52fb76e82/res/e7600916-e62a-4ef7-bcca-c55b551f8f92/skitch.png" witdh="280" height="175" alt="Buzz Symfony web profiler panel - response details">

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

## Limitations

* It doesn't handle the logging/profiling for a Buzz Client, you need to use a Buzz browser (buzz implementation limitation).
* The Message (request/response) from Buzz are really simple and so the informations in the profiler.
* If you need a rock-solid HTTP client, try [Guzzle](http://guzzlephp.org/) and... the [Playbloom GuzzleBundle](https://github.com/ludofleury/GuzzleBundle) !


## Credits

* Swagger for the UI
