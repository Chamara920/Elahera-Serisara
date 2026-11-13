<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class QuickAdminActionsWidget extends Widget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 7;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.widgets.quick-admin-actions';
}
