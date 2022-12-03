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

    /**
     * Call private method.
     *
     * @throws ReflectionException
     */
    protected static function callMethod(object $object, string $name, mixed ...$args): mixed {
        return (new ReflectionMethod($object, $name))->invokeArgs($object, $args);
    }

    protected function setUp(): void {
        $this->avatar = new SVGAvatar();
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
    public function testAvatarColorBrightness(): void {
        $this->assertSame('#5a5aa8', self::callMethod($this->avatar->brightness(90), 'stringToColor', 'RobiNN'));
    }

    /**
     * @throws ReflectionException
     */
    public function testAvatarColorUniqueness(): void {
        $this->assertSame('#3883d0', self::callMethod($this->avatar->uniqueness(7), 'stringToColor', 'RobiNN'));
    }

    /**
     * @throws ReflectionException
     */
    public function testGetRgbFromHex(): void {
        $this->assertEqualsCanonicalizing(['ff', 'ff', 'ff'], self::callMethod($this->avatar, 'getRgbFromHex', 'fff'));

        $this->assertEqualsCanonicalizing(['38', '32', 'a8'], self::callMethod($this->avatar, 'getRgbFromHex', '3832a8'));
    }

    /**
     * @throws ReflectionException
     */
    public function testGetReadableColor(): void {
        $this->assertSame('#fff', self::callMethod($this->avatar, 'getReadableColor', '#3832a8'));
        $this->assertSame('#fff', self::callMethod($this->avatar, 'getReadableColor', '#000000'));
        $this->assertSame('#000', self::callMethod($this->avatar, 'getReadableColor', '#ffffff'));
    }

    /**
     * @throws ReflectionException
     */
    public function testInitials(): void {
        $this->assertSame('R', self::callMethod($this->avatar, 'initials', 'RobiNN'));
        $this->assertSame('RK', self::callMethod($this->avatar, 'initials', 'Róbert Kelčák'));
        $this->assertSame('RK', self::callMethod($this->avatar, 'initials', 'Róbert RobiNN Kelčák'));
    }

    public function testSquareAvatar(): void {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">'.
            '<rect width="48" height="48" fill="#3832a8"/>'.
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
            '<rect width="48" height="48" fill="#f44336"/>'.
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
            '<rect rx="10" width="48" height="48" fill="#3832a8"/>'.
            '<text font-size="24" fill="#fff" x="50%" y="50%" dy=".1em" '.
            'style="line-height:1" alignment-baseline="middle" text-anchor="middle" dominant-baseline="central">R</text></svg>';

        $this->assertSame($svg, $this->avatar->name('RobiNN')->radius(10)->__toString());
    }

    public function testAvatarWithCustomSize(): void {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 64 64">'.
            '<rect width="64" height="64" fill="#3832a8"/>'.
            '<text font-size="32" fill="#fff" x="50%" y="50%" dy=".1em" '.
            'style="line-height:1" alignment-baseline="middle" text-anchor="middle" dominant-baseline="central">R</text></svg>';

        $this->assertSame($svg, $this->avatar->name('RobiNN')->size(64)->__toString());
    }

    public function testAvatarWithClass(): void {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" class="avatar">'.
            '<rect width="48" height="48" fill="#3832a8"/>'.
            '<text font-size="24" fill="#fff" x="50%" y="50%" dy=".1em" '.
            'style="line-height:1" alignment-baseline="middle" text-anchor="middle" dominant-baseline="central">R</text></svg>';

        $this->assertSame($svg, $this->avatar->name('RobiNN')->class('avatar')->__toString());
    }

    public function testAvatarWithoutName(): void {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">'.
            '<rect width="48" height="48" fill="#b88c32"/>'.
            '<foreignObject x="12" y="12" width="24" height="24">'.
            '<svg width="100%" height="100%" viewBox="0 0 48 48">'.
            '<path fill="#000" d="M44.9 48h-5.8c0-8.4-6.8-15.1-15.1-15.1S8.9 39.8 8.9 48H3.1c0-8 4.4-14.9 '.
            '11-18.5-4-3-6.6-7.8-6.6-13.1C7.5 7.4 15 0 24 0s16.5 7.4 16.5 16.5c0 5.4-2.6 10-6.6 13.1 6.6 3.5 '.
            '11 10.4 11 18.4zM23.8 5.6c-5.8 0-10.6 4.8-10.6 10.6S18 26.9 23.8 26.9s10.6-4.8 10.6-10.6S29.6 5.6 23.8 5.6z"/>'.
            '</svg></foreignObject></svg>';

        $this->assertSame($svg, $this->avatar->__toString());
    }

    public function testBase64(): void {
        $svg = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0OCIgaGVpZ2h0PSI0OCI'.
            'gdmlld0JveD0iMCAwIDQ4IDQ4Ij48cmVjdCB3aWR0aD0iNDgiIGhlaWdodD0iNDgiIGZpbGw9IiMzODMyYTgiLz48dGV4dCBmb250LXNpemU9IjI0'.
            'IiBmaWxsPSIjZmZmIiB4PSI1MCUiIHk9IjUwJSIgZHk9Ii4xZW0iIHN0eWxlPSJsaW5lLWhlaWdodDoxIiBhbGlnbm1lbnQtYmFzZWxpbmU9Im1pZG'.
            'RsZSIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZG9taW5hbnQtYmFzZWxpbmU9ImNlbnRyYWwiPlI8L3RleHQ+PC9zdmc+';

        $this->assertSame($svg, $this->avatar->name('RobiNN')->toBase64()->__toString());
    }
}
