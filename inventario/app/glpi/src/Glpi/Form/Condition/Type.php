<?php

namespace Glpi\Form\Condition;

use CommonDBTM;
use Glpi\Form\Comment;
use Glpi\Form\Question;
use Glpi\Form\Section;

enum Type: string
{
    case QUESTION = 'question';
    case SECTION = 'section';
    case COMMENT = 'comment';

    /** @return class-string<CommonDBTM> */
    public function getItemtype(): string
    {
        return match ($this) {
            self::SECTION  => Section::class,
            self::QUESTION => Question::class,
            self::COMMENT  => Comment::class,
        };
    }
}
