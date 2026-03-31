<?php

namespace App\Filament\Exports;

use App\Models\User;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class UserExporter extends Exporter
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('name'),
            ExportColumn::make('email'),
            ExportColumn::make('email_verified_at'),
            ExportColumn::make('phone'),
            ExportColumn::make('status'),
            ExportColumn::make('ban_reason'),
            ExportColumn::make('profile_photo'),
            ExportColumn::make('interests'),
            ExportColumn::make('preferences'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
            ExportColumn::make('deleted_at'),
            ExportColumn::make('two_factor_secret'),
            ExportColumn::make('two_factor_recovery_codes'),
            ExportColumn::make('two_factor_confirmed_at'),
            ExportColumn::make('role'),
            ExportColumn::make('terms_accepted'),
            ExportColumn::make('bio'),
            ExportColumn::make('notes'),
            ExportColumn::make('address'),
            ExportColumn::make('label_color'),
            ExportColumn::make('trust_score'),
            ExportColumn::make('custom_css'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your user export has completed and '.Number::format($export->successful_rows).' '.str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
