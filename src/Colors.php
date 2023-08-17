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

class Colors {
    /**
     * Get random color from a defined array.
     *
     * @param array<int, string> $colors
     */
    public static function getRandomColor(string $string, array $colors): string {
        $number = array_sum(array_map('ord', str_split($string)));

        return $colors[$number % count($colors)];
    }

    /**
     * Generate a unique color based on string.
     */
    public static function stringToColor(string $string, int $uniqueness, int $brightness): string {
        $hash = sha1($string);
        $colors = [];

        // Convert hash into 3 decimal values between 0 and 255
        for ($i = 0; $i < 3; $i++) {
            $rgb_channel = round(
                hexdec(substr($hash, $uniqueness * $i, $uniqueness)) /
                hexdec(str_pad('', $uniqueness, 'F')) * 255
            );

            $rgb_channel = (int) max([$rgb_channel, $brightness]);

            // Convert RGB channel to HEX channel
            $colors[] = str_pad(dechex($rgb_channel), 2, '0', STR_PAD_LEFT);
        }

        return '#'.implode('', $colors);
    }

    /**
     * Get readable text color (black/white) based on a background.
     */
    public static function getReadableColor(string $hex): string {
        $hex = ltrim($hex, '#');
        [$red, $green, $blue] = self::getRgbFromHex($hex);

        $is_light = (
                hexdec($red) * 299 +
                hexdec($green) * 587 +
                hexdec($blue) * 114
            ) > 130000;

        return $is_light ? '#000' : '#fff';
    }

    /**
     * Get RGB from HEX.
     *
     * @return array<string>
     */
    public static function getRgbFromHex(string $hex): array {
        if (strlen($hex) === 3) {
            [$red, $green, $blue] = str_split($hex);

            $red .= $red;
            $green .= $green;
            $blue .= $blue;
        } else {
            [$red, $green, $blue] = str_split($hex, 2);
        }

        return [$red, $green, $blue];
    }
}
