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

namespace RobiNN\SVGAvatar;

use Stringable;

class SVGAvatar implements Stringable {
    final public const VERSION = '1.2.1';

    private string $name = ' ';

    /**
     * @var array<int, string>
     */
    private array $backgrounds = [];

    private string $text_color = 'auto';

    private int $size = 48;

    private bool $circle = false;

    private int $radius = 0;

    private string $class = '';

    private int $brightness = 50;

    private int $uniqueness = 3;

    private bool $base64 = false;

    /**
     * Set name.
     */
    public function name(string $name): self {
        $this->name = $name;

        return $this;
    }

    /**
     * Set avatar size.
     */
    public function size(int $pixels): self {
        $this->size = $pixels;

        return $this;
    }

    /**
     * Display as a circle.
     */
    public function circle(): self {
        $this->circle = true;

        return $this;
    }

    /**
     * Set border radius.
     */
    public function radius(int $size): self {
        $this->radius = $size;

        return $this;
    }

    /**
     * Set css class.
     */
    public function class(string $class): self {
        $this->class = $class;

        return $this;
    }

    /**
     * Set colors.
     *
     * @param array<int, string> $backgrounds
     */
    public function setColors(array $backgrounds, string $text_color = 'auto'): self {
        $this->backgrounds = $backgrounds;
        $this->text_color = $text_color;

        return $this;
    }

    /**
     * Output as base64.
     */
    public function toBase64(): self {
        $this->base64 = true;

        return $this;
    }

    /**
     * Set color brightness.
     *
     * @param int $brightness 0-100
     */
    public function brightness(int $brightness): self {
        $this->brightness = $brightness;

        return $this;
    }

    /**
     * Set color uniqueness.
     *
     * @param int $uniqueness 1-10
     */
    public function uniqueness(int $uniqueness): self {
        $this->uniqueness = $uniqueness;

        return $this;
    }

    /**
     * Create initials.
     */
    private function initials(string $name): string {
        $words = explode(' ', $name);

        if (count($words) >= 2) {
            $first = mb_substr($words[0], 0, 1, 'UTF-8');
            $last = mb_substr(end($words), 0, 1, 'UTF-8');

            return mb_strtoupper($first.$last, 'UTF-8');
        }

        $first_char = mb_substr($name, 0, 1, 'UTF-8');

        return mb_strtoupper($first_char, 'UTF-8');
    }

    /**
     * Generate avatar.
     */
    private function generate(): string {
        if (count($this->backgrounds) > 0) {
            $background = Colors::getRandomColor($this->name, $this->backgrounds);
            $text_color = $this->text_color === 'auto' ? Colors::getReadableColor($background) : $this->text_color;
        } else {
            $background = Colors::stringToColor($this->name, $this->uniqueness, $this->brightness);
            $text_color = Colors::getReadableColor($background);
        }

        $name = $this->name !== '' ? $this->initials($this->name) : '';

        return $this->svg($name, $background, $text_color);
    }

    /**
     * Generate SVG.
     */
    private function svg(string $text, string $background, string $text_color): string {
        $size = $this->size;
        $class = $this->class !== '' ? ' class="'.$this->class.'"' : '';

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="'.$size.'" height="'.$size.'" viewBox="0 0 '.$size.' '.$size.'"'.$class.'>';

        $half = $size / 2;

        if ($this->circle && $this->radius === 0) {
            $svg .= '<circle cx="'.$half.'" cy="'.$half.'" r="'.$half.'" fill="'.$background.'"/>';
        } else {
            $radius = $this->radius !== 0 ? 'rx="'.$this->radius.'" ' : '';
            $svg .= '<rect '.$radius.'width="'.$size.'" height="'.$size.'" fill="'.$background.'"/>';
        }

        $style = 'style="line-height:1" alignment-baseline="middle" text-anchor="middle" dominant-baseline="central"';

        if ($text !== '') {
            $svg .= '<text font-size="'.round($half).'" fill="'.$text_color.'" x="50%" y="50%" dy=".1em" '.$style.'>'.$text.'</text>';
        } else {
            $svg .= '<foreignObject x="'.($half / 2).'" y="'.($half / 2).'" width="'.$half.'" height="'.$half.'">'.
                '<svg width="100%" height="100%" viewBox="0 0 48 48">'.
                '<path fill="'.$text_color.'" d="M44.9 48h-5.8c0-8.4-6.8-15.1-15.1-15.1S8.9 39.8 8.9 48H3.1c0-8 4.4-14.9 11-18.5-4-3-6.6-7.8-'.
                '6.6-13.1C7.5 7.4 15 0 24 0s16.5 7.4 16.5 16.5c0 5.4-2.6 10-6.6 13.1 6.6 3.5 11 10.4 11 18.4zM23.8 5.6c-5.8 0-10.6 4.8-10.6 10.6S18 '.
                '26.9 23.8 26.9s10.6-4.8 10.6-10.6S29.6 5.6 23.8 5.6z"/>'.
                '</svg></foreignObject>';
        }

        return $svg.'</svg>';
    }

    /**
     * Svg to base64.
     */
    private function svgToBase64(string $svg): string {
        return 'data:image/svg+xml;base64,'.base64_encode($svg);
    }

    public function __toString(): string {
        $svg = $this->generate();

        if ($this->base64) {
            return $this->svgToBase64($svg);
        }

        return $svg;
    }
}
