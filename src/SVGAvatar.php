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

class SVGAvatar {
    public const VERSION = '1.1.1';

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

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return $this
     */
    public function name(string $name): self {
        $this->name = $name;

        return $this;
    }

    /**
     * Create initials.
     *
     * @param string $name
     *
     * @return string
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
     * Render avatar.
     *
     * @return string
     */
    public function __toString(): string {
        if (count($this->backgrounds) > 0) {
            $background = $this->getRandomColor($this->name, $this->backgrounds);
            $text_color = $this->text_color === 'auto' ? $this->getReadableColor($background) : $this->text_color;
        } else {
            $background = $this->stringToColor($this->name);
            $text_color = $this->getReadableColor($background);
        }

        $name = $this->name !== '' ? $this->initials($this->name) : '';

        return $this->svg($name, $background, $text_color);
    }

    /**
     * Set avatar size.
     *
     * @param int $pixels
     *
     * @return $this
     */
    public function size(int $pixels): self {
        $this->size = $pixels;

        return $this;
    }

    /**
     * Display as a circle.
     *
     * @return $this
     */
    public function circle(): self {
        $this->circle = true;

        return $this;
    }

    /**
     * Set border radius.
     *
     * @param int $size
     *
     * @return $this
     */
    public function radius(int $size): self {
        $this->radius = $size;

        return $this;
    }

    /**
     * Set css class.
     *
     * @param string $class
     *
     * @return $this
     */
    public function class(string $class): self {
        $this->class = $class;

        return $this;
    }

    /**
     * Set colors.
     *
     * @param array<int, string> $backgrounds
     * @param string             $text_color
     *
     * @return $this
     */
    public function setColors(array $backgrounds, string $text_color = 'auto'): self {
        $this->backgrounds = $backgrounds;
        $this->text_color = $text_color;

        return $this;
    }

    /**
     * Generate SVG.
     *
     * @param string $text
     * @param string $background
     * @param string $text_color
     *
     * @return string
     */
    protected function svg(string $text, string $background, string $text_color): string {
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

        $svg .= '</svg>';

        return $svg;
    }

    /**
     * Get random color from a defined array.
     *
     * @param string             $string
     * @param array<int, string> $colors
     *
     * @return string
     */
    protected function getRandomColor(string $string, array $colors): string {
        $number = ord($string[0]);

        $i = 1;
        while ($i < strlen($string)) {
            $number += ord($string[$i]);
            $i++;
        }

        return $colors[$number % count($colors)];
    }

    /**
     * Set color brightness.
     *
     * @param int $brightness 0-100
     *
     * @return $this
     */
    public function brightness(int $brightness): self {
        $this->brightness = $brightness;

        return $this;
    }

    /**
     * Set color uniqueness.
     *
     * @param int $uniqueness 1-10
     *
     * @return $this
     */
    public function uniqueness(int $uniqueness): self {
        $this->uniqueness = $uniqueness;

        return $this;
    }

    /**
     * Generate a unique color based on string.
     *
     * @param string $string
     *
     * @return string
     */
    protected function stringToColor(string $string): string {
        $hash = sha1($string);
        $colors = [];

        // Convert hash into 3 decimal values between 0 and 255
        for ($i = 0; $i < 3; $i++) {
            $rgb_channel = round((
                    hexdec(substr($hash, $this->uniqueness * $i, $this->uniqueness)) /
                    hexdec(str_pad('', $this->uniqueness, 'F'))
                ) * 255);

            $rgb_channel = (int) max([$rgb_channel, $this->brightness]);

            // Convert RGB channel to HEX channel
            $colors[] = str_pad(dechex($rgb_channel), 2, '0', STR_PAD_LEFT);
        }

        return '#'.implode('', $colors);
    }

    /**
     * Get readable text color (black/white) based on background.
     *
     * @param string $hex
     *
     * @return string
     */
    private function getReadableColor(string $hex): string {
        $hex = ltrim($hex, '#');

        [$red, $green, $blue] = $this->getRgbFromHex($hex);

        $r = hexdec($red) * 299;
        $g = hexdec($green) * 587;
        $b = hexdec($blue) * 114;

        $is_light = (($r + $g + $b) / 1000) > 130;

        return '#'.($is_light ? '000' : 'fff');
    }

    /**
     * Get RGB from HEX.
     *
     * @param string $hex
     *
     * @return string[]
     */
    private function getRgbFromHex(string $hex): array {
        switch (strlen($hex)) {
            case 3:
                [$red, $green, $blue] = str_split($hex);

                $red .= $red;
                $green .= $green;
                $blue .= $blue;
                break;
            case 6:
            default:
                [$red, $green, $blue] = str_split($hex, 2);
        }

        return [$red, $green, $blue];
    }
}
