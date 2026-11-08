<?php

namespace Glpi\Toolbox;

use function Safe\preg_match;

class SanitizedStringsDecoder
{
    private const CHARS_MAPPING = [
        '<'  => '&#60;',
        '>'  => '&#62;',
        '&'  => '&#38;',
    ];

    private const LEGACY_CHARS_MAPPING = [
        '<'  => '&lt;',
        '>'  => '&gt;',
    ];

    /**
     * Decode HTML special chars.
     */
    public function decodeHtmlSpecialChars(string $value): string
    {
        $mapping = [];

        if (
            // A value was HTML encoded in GLPI 10.0.x if
            // - it does not contains `<`, `>` and `&` not followed by an HTML entity identifier;
            // - it contains any entity used to encode HTML special chars during sanitization process.
            preg_match('/(<|>|(&(?!#?[a-z0-9]+;)))/i', $value) === 0
            && preg_match('/(' . implode('|', array_values(self::CHARS_MAPPING)) . ')/', $value) === 1
        ) {
            $mapping = self::CHARS_MAPPING;
        } elseif (
            // A value was HTML encoded in GLPI <= 9.5 if
            // - it does not contains `<` and `>`;
            // - it contains `&lt;` or `&gt;`.
            preg_match('/(<|>)/i', $value) === 0
            && preg_match('/(' . implode('|', array_values(self::LEGACY_CHARS_MAPPING)) . ')/', $value) === 1
        ) {
            $mapping = self::LEGACY_CHARS_MAPPING;

            if (preg_match('/&lt;img\s+(alt|src|width)=&quot;/', $value)) {
                // In some cases (at least on some ITIL followups, quotes have been converted too,
                // probably due to a misusage of encoding process.
                // Result is that quotes were encoded too (i.e. `&lt:img src=&quot;/front/document.send.php`)
                // and should be decoded too.
                $mapping['"'] = '&quot;';
            }
        }

        if ($mapping !== []) {
            $value = str_replace(array_values($mapping), array_keys($mapping), $value);
        }

        return $value;
    }

    /**
     * Decode HTML special chars in completename field value.
     */
    public function decodeHtmlSpecialCharsInCompletename(string $value): string
    {
        $separator = '>';

        return implode(
            $separator,
            array_map(
                fn(string $chunk) => $this->decodeHtmlSpecialChars($chunk),
                explode($separator, $value)
            )
        );
    }
}
