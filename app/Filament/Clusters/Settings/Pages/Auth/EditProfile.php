<?php

namespace App\Filament\Clusters\Settings\Pages\Auth;

use App\Filament\Clusters\Settings\SettingsCluster;
use Filament\Pages\Page;

class EditProfile extends Page
{
    protected string $view = 'filament.clusters.settings.pages.auth.edit-profile';

    protected static ?string $cluster = SettingsCluster::class;
}
