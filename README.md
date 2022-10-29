# SVG avatar generator

![Screenshot](.github/img/screenshot.png)


![Visitor Badge](https://visitor-badge.laobi.icu/badge?page_id=RobiNN1.SVG-Avatar)

## Installation

```
composer require robinn/svg-avatar
```

## Usage

```php
use RobiNN\SVGAvatar\SVGAvatar;

$avatar = new SVGAvatar();

echo $avatar->name('RobiNN');
```

Custom size, default is 48px

```php
echo $avatar->name('RobiNN')->size(64);
```

Display as circle

```php
echo $avatar->name('RobiNN')->circle();
```

Border radius

```php
echo $avatar->name('RobiNN')->radius(10);
```

By default, backgroud colors are generated from name.
However, it is possible to set custom colors

```php
$avatar->setColors([
    '#f44336', '#e91e63', '#9c27b0', '#673ab7', '#3f51b5', '#2196f3',
]);
echo $avatar->name('RobiNN');

// If needed, can set text color. Default is '#fff'
echo $avatar->name('RobiNN')->setColors([...], '#000');
```

CSS class

```php
echo $avatar->name('RobiNN')->class('avatar');
```

## Requirements

- PHP >= 8.1

## Testing

PHPUnit

```
composer test
```

PHPStan

```
composer phpstan
```
