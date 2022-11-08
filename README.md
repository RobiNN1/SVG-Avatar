<h1 align="center">SVG avatar generator</h1>
<p align="center"><img src=".github/img/preview.svg" height="48" alt="Preview"></p>

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

> If you do not set name, the user icon will be displayed.
> This can be useful for guests.

## Methods

```php
// Custom size, default is 48px.
$avatar->size(64);

// Display as a circle.
$avatar->circle();

// Border radius.
$avatar->radius(10);

// CSS class.
$avatar->class('avatar');

// Custom colors. By default, background colors are generated from name.
$avatar->setColors(['#f44336', '#e91e63', '#9c27b0', '#673ab7', '#3f51b5', '#2196f3',]);
// You can set the text color if needed. Default is 'auto' which sets '#fff' or '#000'.
$avatar->setColors([...], '#000');

// Color brightness, between 0-100, 50 is default. Doesn't work with setColors().
$avatar->brightness(80);

// Color uniqueness, between 1-10, 3 is default. Doesn't work with setColors().
$avatar->uniqueness(7);
```

## Requirements

- PHP >= 8.1
- mbstring extension

## Testing

PHPUnit

```
composer test
```

PHPStan

```
composer phpstan
```
