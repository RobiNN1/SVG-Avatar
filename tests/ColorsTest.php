<?php
/**
 * This file is part of SVG-Avatar.
 *
 * Copyright (c) Róbert Kelčák (https://kelcak.com/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace RobiNN\SVGAvatar\Tests;

use PHPUnit\Framework\TestCase;
use RobiNN\SVGAvatar\Colors;

class ColorsTest extends TestCase {
    public function testGetRandomColor(): void {
        $this->assertSame('#f44336', Colors::getRandomColor('RobiNN', [
            '#f44336', '#3f51b5', '#f59e0b', '#0ea5e9',
        ]));
    }

    public function testStringToColor(): void {
        $this->assertSame('#3832a8', Colors::stringToColor('RobiNN', 3, 50));
        $this->assertSame('#5a5aa8', Colors::stringToColor('RobiNN', 3, 90));
        $this->assertSame('#3883d0', Colors::stringToColor('RobiNN', 7, 50));
    }

    public function testGetRgbFromHex(): void {
        $this->assertEqualsCanonicalizing(['ff', 'ff', 'ff'], Colors::getRgbFromHex('fff'));
        $this->assertEqualsCanonicalizing(['38', '32', 'a8'], Colors::getRgbFromHex('3832a8'));
    }

    public function testGetReadableColor(): void {
        $this->assertSame('#fff', Colors::getReadableColor('#3832a8'));
        $this->assertSame('#fff', Colors::getReadableColor('#000000'));
        $this->assertSame('#000', Colors::getReadableColor('#ffffff'));
    }
}
