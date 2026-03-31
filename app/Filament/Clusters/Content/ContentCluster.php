<?php

namespace App\Filament\Clusters\Content;

use App\Models\User;
use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;

class ContentCluster extends Cluster
{
    protected static ?string $navigationLabel = 'Content Management';

    // 2. Use a document or folder icon
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-folder-open';

    // 3. Set the order (1 will put it at the top of the group/list)
    protected static ?int $navigationSort = 1;

    // protected static ?string $clusterBreadcrumb = 'Content';
    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::End;

    public static function getClusterBreadcrumb(): ?string
    {
        return 'Content ('.User::count().' users)';
    }
}
