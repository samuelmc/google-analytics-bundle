# google-analytics-bundle _(SamuelGaBundle)_
__Google Analytics Bundle for Symfony__

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/6fc924c2-6411-4ca0-a0d6-a994b6bc77a3/big.png)](https://insight.sensiolabs.com/projects/6fc924c2-6411-4ca0-a0d6-a994b6bc77a3)

This bundle adds the Google Analytics code to every page except those that match the "exclude_path" parameter. 

## Installation
composer require samuelmc/google-analytics-bundle ([view on packagist](https://packagist.org/packages/samuelmc/google-analytics-bundle))

add the bundle in AppKernel:
```php
new Samuel\GaBundle\SamuelGaBundle()
```
import the services in config.yml: 
```yaml
imports:
    - { resource: security.yml }
    - { resource: services.yml }
    ...
    - { resource: "@SamuelGaBundle/Resources/config/services.yml" }
```
## Configuration

there are two parameters to configure under samuel_ga:
- tracking_id: your Google Analytics tracker code.
- exclude_paths: a regex string which filters out paths that should not be tracked by Google Analytics. Default: '(^\/(_profiler|_wdt).+)'
```yaml
samuel_ga:
    tracking_id: "UA-..."
    exclude_paths: '(^\/(_profiler|_wdt).+)'
```
##License
MIT

__*Samuel Moncarey*__
