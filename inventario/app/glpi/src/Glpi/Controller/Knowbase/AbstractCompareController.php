<?php

namespace Glpi\Controller\Knowbase;

use Glpi\Controller\AbstractController;
use Glpi\RichText\RichText;
use Ssddanbrown\HtmlDiff\Diff;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class AbstractCompareController extends AbstractController
{
    protected function compare(
        string $old_answer,
        string $new_answer,
    ): JsonResponse {
        // KB revisions may contain video placeholders — diff them against
        // their rendered iframe form so video-only edits show a meaningful
        // delta instead of two empty <div> shells.
        $rich_text_options = ['text_maxsize' => 0];
        $old_answer = RichText::getEnhancedHtml($old_answer, $rich_text_options);
        $new_answer = RichText::getEnhancedHtml($new_answer, $rich_text_options);

        // Normalize content
        $old_answer = $this->normalizeHtml($old_answer);
        $new_answer = $this->normalizeHtml($new_answer);

        $content_diff = (new Diff($old_answer, $new_answer))->build();
        return new JsonResponse([
            'content_diff' => $content_diff,
        ]);
    }

    private function normalizeHtml(string $html): string
    {
        // Replace UTF-8 non-breaking space (0xC2 0xA0) and HTML entity
        return str_replace(["\xC2\xA0", '&nbsp;'], ' ', $html);
    }
}
