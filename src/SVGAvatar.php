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
    public const VERSION = '1.0.0';

    private string $name;

    private string $initials;

    /**
     * @var array<int, string>
     */
    private array $backgrounds = [];

    private string $text_color = '#fff';

    private int $size = 48;

    private bool $circle = false;

    private int $radius = 0;

    private string $class = '';

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return $this
     */
    public function name(string $name): self {
        $this->name = $name;

        if (function_exists('mb_substr') && function_exists('mb_strtoupper')) {
            $first_char = mb_substr($name, 0, 1, 'UTF-8');
            $this->initials = mb_strtoupper($first_char, 'UTF-8');
        } else {
            $this->initials = strtoupper($name[0]);
        }

        return $this;
    }

    /**
     * Render avatar.
     *
     * @return string
     */
    public function __toString(): string {
        if (count($this->backgrounds) > 0) {
            $background = $this->getRandomColor($this->name, $this->backgrounds);
            $text_color = $this->text_color;
        } else {
            $background = $this->stringToColor($this->name);
            $text_color = $this->getReadableColor($background);
        }

        return $this->svg($this->initials, $background, $text_color);
    }

    /**
     * Set avatar size.
     *
     * @param int $size
     *
     * @return $this
     */
    public function size(int $size): self {
        $this->size = $size;

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
     * @return $this
     */
    public function radius(int $size): self {
        $this->radius = $size;

        return $this;
    }

    /**
     * Set css class.
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
     * @param array<int, string> $bg
     * @param string             $text
     *
     * @return $this
     */
    public function setColors(array $bg, string $text = '#fff'): self {
        $this->backgrounds = $bg;
        $this->text_color = $text;

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
    protected function svg(string $text, string $background, string $text_color = '#fff'): string {
        $size = $this->size;

        $class = '';
        if ($this->class !== '') {
            $class = ' class="'.$this->class.'"';
        }

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="'.$size.'" height="'.$size.'" viewBox="0 0 '.$size.' '.$size.'"'.$class.'>';

        $half = $size / 2;

        if ($this->circle && $this->radius === 0) {
            $svg .= '<circle cx="'.$half.'" cy="'.$half.'" r="'.$half.'" fill="'.$background.'"/>';
        } else {
            $svg .= '<rect x="0" y="0" rx="'.$this->radius.'" width="'.$size.'" height="'.$size.'" fill="'.$background.'"/>';
        }

        $style = 'style="line-height:1" alignment-baseline="middle" text-anchor="middle" dominant-baseline="central"';
        $svg .= '<text font-size="'.round($half).'" fill="'.$text_color.'" x="50%" y="50%" dy=".1em" '.$style.'>'.$text.'</text>';
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
     * Generate a unique color based on string.
     *
     * @param string $string
     * @param int    $brightness Between 0 and 100.
     * @param int    $uniqueness Between 2-10, determines how unique each color will be.
     *
     * @return string
     */
    protected function stringToColor(string $string, int $brightness = 50, int $uniqueness = 3): string {
        $hash = sha1($string);
        $colors = [];

        // Convert hash into 3 decimal values between 0 and 255
        for ($i = 0; $i < 3; $i++) {
            $rgb_channel = round((
                    hexdec(substr($hash, $uniqueness * $i, $uniqueness)) /
                    hexdec(str_pad('', $uniqueness, 'F'))
                ) * 255);

            $rgb_channel = (int) max([$rgb_channel, $brightness]);

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
    public function getReadableColor(string $hex): string {
        $hex = str_replace('#', '', $hex);

        $r = hexdec(substr($hex, 0, 2)) * 299;
        $g = hexdec(substr($hex, 2, 2)) * 587;
        $b = hexdec(substr($hex, 4, 2)) * 114;

        $is_light = (($r + $g + $b) / 1000) > 130;

        return '#'.($is_light ? '000' : 'fff');
    }
}
