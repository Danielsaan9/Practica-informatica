<?php

namespace Glpi\Knowbase;

enum EditorActionType: string
{
    case LOAD_SIDE_PANEL = 'LOAD_SIDE_PANEL';
    case TOGGLE_VALUE = 'TOGGLE_VALUE';
    case TOGGLE_FAVORITE = 'TOGGLE_FAVORITE';
    case DELETE_ARTICLE = 'DELETE_ARTICLE';
    case OPEN_MODAL = 'OPEN_MODAL';
}
