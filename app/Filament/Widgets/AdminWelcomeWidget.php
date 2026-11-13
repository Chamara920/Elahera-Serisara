<?php

namespace App\Filament\Widgets;

use App\Models\BusRoute;
use App\Models\BusStop;
use App\Models\Destination;
use App\Models\DestinationCategory;
use App\Models\EmergencyContact;
use App\Models\User;
use Filament\Widgets\Widget;

class AdminWelcomeWidget extends Widget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 0;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.widgets.admin-welcome';

    public function getViewData(): array
    {
        return [
            'destinationsCount' => Destination::count(),
            'routesCount' => BusRoute::count(),
            'busStopsCount' => BusStop::count(),
            'hubsCount' => BusStop::where('is_hub', true)->count(),
            'categoriesCount' => DestinationCategory::count(),
            'emergencyCount' => EmergencyContact::count(),
            'usersCount' => User::count(),
        ];
    }
}
