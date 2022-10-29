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

namespace Tests;

use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionMethod;
use RobiNN\SVGAvatar\SVGAvatar;

class SVGAvatarTest extends TestCase {
    private SVGAvatar $avatar;

    /**
     * @var array<int, string>
     */
    private array $colors = [
        '#f44336', '#e91e63', '#9c27b0', '#673ab7', '#3f51b5', '#2196f3', '#f59e0b', '#0ea5e9',
        '#009688', '#4caf50', '#8bc34a', '#cddc39', '#ffc107', '#ff9800', '#ff5722', '#e11d48',
        '#701a75', '#475569', '#ce93d8', '#b39ddb', '#9fa8da', '#00bcd4', '#ffab91', '#10b981',
    ];

    protected function setUp(): void {
        $this->avatar = new SVGAvatar();
    }

    /**
     * Call private method.
     *
     * @param object $object
     * @param string $name
     * @param mixed  ...$args
     *
     * @return mixed
     * @throws ReflectionException
     */
    protected static function callMethod(object $object, string $name, ...$args): mixed {
        $method = new ReflectionMethod($object, $name);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $args);
    }

    /**
     * @throws ReflectionException
     */
    public function testGetRandomColor(): void {
        $this->assertSame('#f44336', self::callMethod($this->avatar, 'getRandomColor', 'RobiNN', $this->colors));
    }

    /**
     * @throws ReflectionException
     */
    public function testStringToColor(): void {
        $this->assertSame('#3832a8', self::callMethod($this->avatar, 'stringToColor', 'RobiNN'));
    }

    /**
     * @throws ReflectionException
     */
    public function testGetReadableColor(): void {
        $this->assertSame('#fff', self::callMethod($this->avatar, 'getReadableColor', '#3832a8'));
        $this->assertSame('#fff', self::callMethod($this->avatar, 'getReadableColor', '#000000'));
        $this->assertSame('#000', self::callMethod($this->avatar, 'getReadableColor', '#ffffff'));
    }

    public function testSquareAvatar(): void {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">'.
            '<rect x="0" y="0" rx="0" width="48" height="48" fill="#3832a8"/>'.
            '<text font-size="24" fill="#fff" x="50%" y="50%" dy=".1em" '.
            'style="line-height:1" alignment-baseline="middle" text-anchor="middle" dominant-baseline="central">R</text></svg>';

        $this->assertSame($svg, $this->avatar->name('RobiNN')->__toString());
    }

    public function testCircleAvatar(): void {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">'.
            '<circle cx="24" cy="24" r="24" fill="#3832a8"/>'.
            '<text font-size="24" fill="#fff" x="50%" y="50%" dy=".1em" '.
            'style="line-height:1" alignment-baseline="middle" text-anchor="middle" dominant-baseline="central">R</text></svg>';

        $this->assertSame($svg, $this->avatar->name('RobiNN')->circle()->__toString());
    }

    public function testSqareAvatarWithDefinedColors(): void {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">'.
            '<rect x="0" y="0" rx="0" width="48" height="48" fill="#f44336"/>'.
            '<text font-size="24" fill="#fff" x="50%" y="50%" dy=".1em" '.
            'style="line-height:1" alignment-baseline="middle" text-anchor="middle" dominant-baseline="central">R</text></svg>';

        $this->assertSame($svg, $this->avatar->name('RobiNN')->setColors($this->colors)->__toString());
    }

    public function testCircleAvatarWithDefinedColors(): void {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">'.
            '<circle cx="24" cy="24" r="24" fill="#f44336"/>'.
            '<text font-size="24" fill="#000" x="50%" y="50%" dy=".1em" '.
            'style="line-height:1" alignment-baseline="middle" text-anchor="middle" dominant-baseline="central">R</text></svg>';

        $this->assertSame($svg, $this->avatar->name('RobiNN')->setColors($this->colors, '#000')->circle()->__toString());
    }

    public function testAvatarWithRadius(): void {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">'.
            '<rect x="0" y="0" rx="10" width="48" height="48" fill="#3832a8"/>'.
            '<text font-size="24" fill="#fff" x="50%" y="50%" dy=".1em" '.
            'style="line-height:1" alignment-baseline="middle" text-anchor="middle" dominant-baseline="central">R</text></svg>';

        $this->assertSame($svg, $this->avatar->name('RobiNN')->radius(10)->__toString());
    }

    public function testAvatarWithCustomSize(): void {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 64 64">'.
            '<rect x="0" y="0" rx="0" width="64" height="64" fill="#3832a8"/>'.
            '<text font-size="32" fill="#fff" x="50%" y="50%" dy=".1em" '.
            'style="line-height:1" alignment-baseline="middle" text-anchor="middle" dominant-baseline="central">R</text></svg>';

        $this->assertSame($svg, $this->avatar->name('RobiNN')->size(64)->__toString());
    }
}
