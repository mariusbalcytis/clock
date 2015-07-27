# Clock PHP library

## What's that?

1. `ClockInterface` with single method `getCurrentDateTime` (`@returns \DateTime`)

    Why not just `new \DateTime()`? Because sometimes you need to adjust the time.
    Depending on global context (read `new \DateTime()`) is just wrong,
    as you cannot change the behaviour by dependency injection, and thus configuration.
    
    Few examples: 1) unit tests - you need to mock the clock
    2) you need to alter the timezone and you don't want to change php.ini settings (see `TimeZonedClock`)
    
2. Classes in `Condition` namespace for checking and prioritizing time conditions.

### Clock
 
`RealTimeClock` - gives the real, unaltered `\DateTime`

`TimeZonedClock` - gets time from another `ClockInterface` (usually `RealTimeClock`) and sets pre-configured timezone.

### Condition

`TimeCondition` - POJO/Value object, which contains available time restrictions:

1. From time, in seconds, inclusive
2. Until time, in seconds, exclusive. Can overlap 00:00 with from time
3. Weekday. `0` (Sunday) to `6` (Saturday)
4. Day of month (`1` to `31`)
5. Month (`1` to `12`). Can be specified only with day of month
6. Year (for example `2015`). Can be specified only with month and day of month

You can implement `TimeConditionInterface` and use this library with your own objects, for example any Doctrine Entity.

`ConditionPriorityResolver` - gives priority for any `TimeConditionInterface`.
Larger priority means that condition is more concrete, thus should "win" if choosing between several.

`TimeConditionChecker` - returns whether given `TimeConditionInterface` matches given `\DateTime`

`CurrentTimeConditionChecker` - returns whether given `TimeConditionInterface` matches current time

`ConditionalValueResolver` - resolves most concrete time condition matching current time and gives related value.

Example:

```php
<?php

$resolver = new ConditionalValueResolver(
    new ConditionPriorityResolver(),
    new CurrentTimeConditionChecker(new RealTimeClock(), new TimeConditionChecker())
);

// order of the items in the array does not matter, as they're ordered by priorities
$resolver->resolveValue(array(
    // default price 20 EUR
    (new ConditionalValue())->setTimeCondition(
        (new TimeCondition())
    )->setValue('20 EUR'),
    // from 16:00 price is 30 EUR
    (new ConditionalValue())->setTimeCondition(
        (new TimeCondition())->setFromTime(16 * 3600)   
    )->setValue('30 EUR'),
    // 17:00 - 18:00 we have a happy hour
    (new ConditionalValue())->setTimeCondition(
        (new TimeCondition())->setFromTime(17 * 3600)->setUntilTime(18 * 3600) 
    )->setValue('15 EUR'),
    // 23:00 - 03:00 price is bigger
    (new ConditionalValue())->setTimeCondition(
        (new TimeCondition())->setFromTime(23 * 3600)->setUntilTime(03 * 3600) 
    )->setValue('40 EUR'),
    // price is 50 EUR on Sundays. This wins over all above rules
    (new ConditionalValue())->setTimeCondition(
        (new TimeCondition())->setWeekday(0) 
    )->setValue('50 EUR'),
    // this overwrites price from 20:00 until 00:00
    (new ConditionalValue())->setTimeCondition(
        (new TimeCondition())->setWeekday(0)->setFromTime(20 * 3600) 
    )->setValue('30 EUR'),
    // first day on every month - cheap one. Wins over all above
    (new ConditionalValue())->setTimeCondition(
        (new TimeCondition())->setDay(1) 
    )->setValue('10 EUR'),
    // day of New Year is special
    (new ConditionalValue())->setTimeCondition(
        (new TimeCondition())->setDay(1)->setMonth(1) 
    )->setValue('100 EUR'),
    // Easter etc.
    (new ConditionalValue())->setTimeCondition(
        (new TimeCondition())->setDay(5)->setMonth(4)->setYear(2015) 
    )->setValue('50 EUR'),
));
```

## Installing

```
composer require maba/clock
```

## Running tests

[![Travis status](https://travis-ci.org/mariusbalcytis/clock.svg?branch=master)](https://travis-ci.org/mariusbalcytis/clock)
[![Coverage Status](https://coveralls.io/repos/mariusbalcytis/clock/badge.svg?branch=master&service=github)](https://coveralls.io/github/mariusbalcytis/clock?branch=master)

```
composer install
vendor/bin/phpunit
```
