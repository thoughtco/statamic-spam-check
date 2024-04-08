# Statamic Span Cgeck

This addon checks any form submission content against the [Spam Check API](https://apilayer.com/marketplace/spamchecker-api#authentication).

## Installation

Install by composer: `composer require thoughtco/statamic-spam-check`

Add your API key in your .env under the key: `STATAMIC_SPAM_CHECK_API_KEY`

eg

```
STATAMIC_SPAM_CHECK_API_KEY="my-key"
```

## Configuration

By default all Form Submissions will be checked for the presence of a `text` or `textarea` field, and if found a check will be run.

If you want to override this, publish the config:

`php artisan vendor:publish --tag=statamic-spam-check`

You then have the option to specify an array of specific forms to check, what field handle to check for and whether you want to fail silently.

## Testing during development

If you want to test responses during development you can use the `STATAMIC_SPAM_CHECK_TEST_MODE` env value.

Setting it to `disable` will prevent the addon from running.

Setting it to `fail` with throw a validation error, or fail silently, depending on what the `fail_silently` config value is set to.
