<?php

namespace Glpi\Toolbox;

use DOMDocument;
use DOMXPath;
use Glpi\RichText\RichText;
use Search;

use function Safe\preg_replace;

class DataExport
{
    /**
     * Normalize a value for text export (PDF, CSV, ...).
     *
     * @param string $value
     *
     * @return string
     */
    public static function normalizeValueForTextExport(string $value): string
    {
        if (RichText::isRichTextHtmlContent($value)) {
            libxml_use_internal_errors(true); // Silent errors
            $document = new DOMDocument();
            $document->loadHTML(
                '<?xml encoding="utf-8" ?><div>' . $value . '</div>',
                LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
            );

            // Remove invisible contents (tooltips for instance)
            $xpath = new DOMXPath($document);
            $invisible_elements = $xpath->query('//div[contains(@class, "invisible")]');
            foreach ($invisible_elements as $element) {
                $element->parentNode->removeChild($element);
            }

            // Remove FontAwesome and Table icons that does not contains any text
            $icons_elements = $xpath->query('//*[contains(@class, "fa-") or contains(@class, "ti-")]');
            foreach ($icons_elements as $element) {
                if (trim($element->textContent) === '') {
                    $element->parentNode->removeChild($element);
                }
            }

            $value = $document->saveHTML();

            // Transform into simple text
            $value = RichText::getTextFromHtml($value, true, true);

            // Remove extra spacing
            $spacing_chars = [
                '\s', // any basic spacing char
                '\x{C2A0}', // unicode value of decoded &nbsp;
            ];
            $spacing_chars_pattern = '(' . implode('|', $spacing_chars) . ')+';
            $value = preg_replace('/^' . $spacing_chars_pattern . '/u', '', $value);
            $value = preg_replace('/' . $spacing_chars_pattern . '$/u', '', $value);
        } else {
            // Be sure to remove unexpected HTML tags.
            // This is necessary because the rendering methods (`giveItem()`, `getSpecificValueToDisplay()`, ...)
            // do not always adapt their output to the rendering context.
            $value = \strip_tags($value);

            // Decode entities.
            // This is necessary because the rendering methods (`giveItem()`, `getSpecificValueToDisplay()`, ...)
            // are actually always providing an escaped value.
            $value = \html_entity_decode($value);
        }

        $value = preg_replace('/' . Search::LBBR . '/', "\n", $value);
        $value = preg_replace('/' . Search::LBHR . '/', "\n\n---\n\n", $value);

        return $value;
    }
}
