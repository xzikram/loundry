<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TenantResource\Pages;
use App\Models\Central\Tenant;
use App\Enums\TenantStatus;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationLabel = 'Tenants';

    protected static ?string $pluralModelLabel = 'Tenants';

    protected static ?string $modelLabel = 'Tenant';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id')
                    ->required()
                    ->disabled(fn (string $operation): bool => $operation !== 'create')
                    ->unique(ignoringRecord: true)
                    ->label('Tenant Domain ID (Subdomain)'),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoringRecord: true)
                    ->helperText('Used for routing URL (e.g. slug.kliin.id)'),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),
                Select::make('status')
                    ->options([
                        TenantStatus::ACTIVE->value => 'Active',
                        TenantStatus::TRIAL->value => 'Trial',
                        TenantStatus::EXPIRED->value => 'Expired',
                        TenantStatus::SUSPENDED->value => 'Suspended',
                    ])
                    ->required()
                    ->default(TenantStatus::TRIAL->value),
                DateTimePicker::make('trial_ends_at')
                    ->label('Trial Ends At')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->searchable()
                    ->sortable()
                    ->label('Subdomain'),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (TenantStatus $state): string => $state->color())
                    ->sortable(),
                TextColumn::make('trial_ends_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                Action::make('suspend')
                    ->label('Suspend')
                    ->color('warning')
                    ->icon('heroicon-o-pause-circle')
                    ->visible(fn (Tenant $record): bool => $record->status !== TenantStatus::SUSPENDED)
                    ->action(function (Tenant $record) {
                        $record->update(['status' => TenantStatus::SUSPENDED->value]);
                        Notification::make()
                            ->title('Tenant suspended successfully')
                            ->success()
                            ->send();
                    }),
                Action::make('activate')
                    ->label('Activate')
                    ->color('success')
                    ->icon('heroicon-o-play-circle')
                    ->visible(fn (Tenant $record): bool => $record->status === TenantStatus::SUSPENDED)
                    ->action(function (Tenant $record) {
                        $record->update(['status' => TenantStatus::ACTIVE->value]);
                        Notification::make()
                            ->title('Tenant activated successfully')
                            ->success()
                            ->send();
                    }),
                DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/create'),
            'edit' => Pages\EditTenant::route('/{record}/edit'),
        ];
    }
}
