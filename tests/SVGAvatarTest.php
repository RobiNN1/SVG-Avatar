<?php
/**
 * This file is part of the RobiNN\SVG-Avatar package.
 * Copyright (c) Róbert Kelčák (https://kelcak.com/)
 */

declare(strict_types=1);

namespace RobiNN\SVGAvatar\Tests;

use PHPUnit\Framework\TestCase;
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

    public function testSquareAvatar(): void {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">'.
            '<rect width="48" height="48" fill="#3832a8"/>'.
            '<text font-size="24" fill="#fff" x="50%" y="50%" dy=".1em" '.
            'style="line-height:1" alignment-baseline="middle" text-anchor="middle" dominant-baseline="central">R</text></svg>';

        $this->assertSame($svg, $this->avatar->name('RobiNN')->__toString());
    }

    public function testCircleAvatar(): void {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">'.
            '<circle cx="24" cy="24" r="24" fill="#e049a3"/>'.
            '<text font-size="24" fill="#fff" x="50%" y="50%" dy=".1em" '.
            'style="line-height:1" alignment-baseline="middle" text-anchor="middle" dominant-baseline="central">RK</text></svg>';

        $this->assertSame($svg, $this->avatar->name('Róbert Kelčák')->circle()->__toString());
    }

    public function testSqareAvatarWithDefinedColors(): void {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">'.
            '<rect width="48" height="48" fill="#475569"/>'.
            '<text font-size="24" fill="#fff" x="50%" y="50%" dy=".1em" '.
            'style="line-height:1" alignment-baseline="middle" text-anchor="middle" dominant-baseline="central">RK</text></svg>';

        $this->assertSame($svg, $this->avatar->name('Róbert RobiNN Kelčák')->setColors($this->colors)->__toString());
    }

    public function testCircleAvatarWithDefinedColors(): void {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">'.
            '<circle cx="24" cy="24" r="24" fill="#f44336"/>'.
            '<text font-size="24" fill="#000" x="50%" y="50%" dy=".1em" '.
            'style="line-height:1" alignment-baseline="middle" text-anchor="middle" dominant-baseline="central">R</text></svg>';

        $this->assertSame($svg, $this->avatar->name('RobiNN')->setColors($this->colors, '#000')->circle()->__toString());
    }

    public function testAvatarWithUniqueness(): void {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">'.
            '<rect width="48" height="48" fill="#3883d0"/>'.
            '<text font-size="24" fill="#fff" x="50%" y="50%" dy=".1em" '.
            'style="line-height:1" alignment-baseline="middle" text-anchor="middle" dominant-baseline="central">R</text></svg>';

        $this->assertSame($svg, $this->avatar->name('RobiNN')->uniqueness(7)->__toString());
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
