<?php

namespace Glpi\Form\QuestionType;

/**
 * Marker interface for question types whose formatRawAnswer() returns
 * already-safe HTML that must NOT be automatically escaped.
 *
 * Question types that do NOT implement this interface will have their
 * formatted answer automatically HTML-escaped by Answer::getFormattedAnswer().
 */
interface RawAnswerIsHtmlInterface {}
