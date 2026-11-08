<?php

namespace Twig\Components\Alert;

abstract class AbstractAlert
{
    /**
     * Var is exposed in the template, for the backward compatibility.
     * So type can be used with {{ component('Alert', {color: 'warning') }}
     *
     * @var 'primary'|'secondary'|'success'|'danger'|'warning'|'info'|'light'|'dark'
     */
    public string $color = 'info';

    public ?string $heading = null;

    public string $content = '';

    public bool $dismissible = false;

    public string $icon = '';

    public bool $important = false;

    public ?string $link_url = null;

    public ?string $link_text = null;

    public bool $link_blank = false;

    public function getClasses(): string
    {
        $classes = ['alert', 'alert-' . $this->color];

        if ($this->dismissible) {
            $classes = [...$classes, 'alert-dismissible', 'fade', 'show'];
        }

        if ($this->important) {
            $classes[] = 'alert-important';
        }

        return implode(' ', $classes);
    }

    public function getResolvedIcon(): string
    {
        if ($this->icon !== '') {
            return $this->icon;
        }

        return match ($this->color) {
            'success' => 'ti ti-check',
            'warning' => 'ti ti-alert-triangle',
            'danger' => 'ti ti-exclamation-circle',
            default => 'ti ti-info-circle',
        };
    }
}
