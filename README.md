# google-analytics-bundle _(SamuelGaBundle)_
__Google Analytics Bundle for Symfony__
---
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
- tracking_id: your Google Analytics tracker code
- exclude_paths: a regex string which filters out paths that should not be tracked by Google Analytics
```yaml
samuel_ga:
    tracking_id: "UA-..."
    exclude_paths: '(^\/(_profiler|_wdt|admin).+)'
```
##License
MIT

__*Samuel Moncarey*__
