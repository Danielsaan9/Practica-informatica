<?php

namespace Glpi\Form;

use function Safe\preg_match;
use function Safe\preg_replace;

/**
 * Utility class to provide the end user input name
 */
final class EndUserInputNameProvider
{
    public const END_USER_INPUT_NAME = 'answers_%d';
    public const END_USER_INPUT_NAME_REGEX = '/^_?answers_(\d+)$/';

    /**
     * Get the end user input name for a given question
     *
     * @param Question $question
     * @return string
     */
    public function getEndUserInputName(Question $question): string
    {
        return sprintf(self::END_USER_INPUT_NAME, $question->getID());
    }

    public function getFiles(array $inputs, array $answers): array
    {
        $files = [
            'filename'    => [],
            'prefix'      => [],
            'tag'         => [],
            'question_id' => [],
        ];

        foreach (array_keys($answers) as $answer_id) {
            if (
                isset($inputs["_answers_$answer_id"])
                && isset($inputs["_prefix_answers_$answer_id"])
                && isset($inputs["_tag_answers_$answer_id"])
            ) {
                foreach (array_keys($inputs["_answers_$answer_id"]) as $i) {
                    $files['filename'][]    = $inputs["_answers_$answer_id"][$i];
                    $files['prefix'][]      = $inputs["_prefix_answers_$answer_id"][$i];
                    $files['tag'][]         = $inputs["_tag_answers_$answer_id"][$i];
                    $files['question_id'][] = (int) $answer_id;
                }
            }
        }

        return $files;
    }

    /**
     * Get the answers submitted by the end user
     * The answers are indexed by question ID
     *
     * @param array $inputs The inputs submitted by the end user
     * @return array
     */
    public function getAnswers(array $inputs): array
    {
        $filteredAnswers = self::filterAnswers($inputs);
        $reindexedAnswers = self::reindexAnswers($filteredAnswers);

        return $reindexedAnswers;
    }

    /**
     * Filter the answers submitted by the end user
     * Only the answers that match the end user input name pattern are kept
     *
     * @param array $answers
     * @return array
     */
    private function filterAnswers(array $answers): array
    {
        // Remove files
        foreach (array_keys($answers) as $key) {
            foreach (["_$key", "_prefix_$key", "_tag_$key"] as $extra_file_info) {
                if (isset($answers[$extra_file_info])) {
                    unset($answers["_$key"]);
                }
            }
        }

        return array_filter(
            $answers,
            fn($key) => preg_match(self::END_USER_INPUT_NAME_REGEX, $key) === 1,
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * Reindex the answers submitted by the end user
     * The answers are indexed by question ID
     *
     * @param array $answers
     * @return array
     */
    private function reindexAnswers(array $answers): array
    {
        return array_reduce(
            array_keys($answers),
            function ($carry, $key) use ($answers) {
                $question_id = (int) preg_replace(self::END_USER_INPUT_NAME_REGEX, '$1', $key);
                $carry[$question_id] = $answers[$key];
                return $carry;
            },
            []
        );
    }
}
