<?php

namespace App\View\Columns;

use Rappasoft\LaravelLivewireTables\Views\Columns\ImageColumn;

class ScreenshotColumn extends ImageColumn
{
    protected string $view = 'livewire-tables::includes.columns.screenshot';
}
