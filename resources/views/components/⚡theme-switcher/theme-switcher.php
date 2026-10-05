<?php

use Livewire\Component;

new class extends Component
{
    // label + [background, accent, text] preview colors (hardcoded, since
    // CSS variables only reflect the active theme)
    public const THEMES = [
        'default' => ['label' => 'Bronze', 'colors' => ['#191817', '#b7a477', '#ded8c9']],
        'verdant' => ['label' => 'Verdant', 'colors' => ['#121814', '#a3bb86', '#d6dccb']],
        'crimson' => ['label' => 'Crimson', 'colors' => ['#1a1213', '#c9998a', '#e0d3cc']],
        'frost' => ['label' => 'Frost', 'colors' => ['#12161a', '#9fb8cf', '#d3dbe3']],
        'amethyst' => ['label' => 'Amethyst', 'colors' => ['#16131a', '#b3a0cc', '#dbd5e3']],
    ];

    public string $theme = 'default';

    public function mount(): void
    {
        $saved = request()->cookie('theme');
        $this->theme = array_key_exists($saved, self::THEMES) ? $saved : 'default';
    }

    public function setTheme(string $theme): void
    {
        abort_unless(array_key_exists($theme, self::THEMES), 422);

        $this->theme = $theme;
        Cookie::queue('theme', $theme, 60 * 24 * 365);
    }

    public function render()
    {
        return $this->view(['themes' => self::THEMES]);
    }
};
