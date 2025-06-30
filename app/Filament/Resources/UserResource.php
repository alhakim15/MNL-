<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?string $navigationLabel = 'Users (View Only)';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Personal Information')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('first_name')
                                    ->label('First Name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('last_name')
                                    ->label('Last Name')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                        Forms\Components\TextInput::make('name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255)
                            ->helperText('This will be auto-generated from first and last name'),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(User::class, 'email', ignoreRecord: true)
                            ->maxLength(255),
                    ]),

                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('date_of_birth')
                                    ->label('Date of Birth')
                                    ->nullable(),
                                Forms\Components\Select::make('gender')
                                    ->options([
                                        'Laki-laki' => 'Laki-laki',
                                        'Perempuan' => 'Perempuan',
                                    ])
                                    ->nullable(),
                            ]),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->nullable()
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('profile_photo')
                            ->label('Profile Photo')
                            ->image()
                            ->directory('profile-photos')
                            ->nullable(),
                    ]),

                Forms\Components\Section::make('Account Settings')
                    ->schema([
                        Forms\Components\Select::make('role')
                            ->options([
                                'user' => 'User',
                                'admin' => 'Admin',
                            ])
                            ->required()
                            ->default('user'),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required(fn(string $context): bool => $context === 'create')
                            ->dehydrated(fn(?string $state): bool => filled($state))
                            ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                            ->maxLength(255)
                            ->helperText('Leave blank to keep current password when editing'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_photo')
                    ->label('Photo')
                    ->circular()
                    ->defaultImageUrl(fn(): string => 'https://ui-avatars.com/api/?name=' . urlencode('User') . '&color=7F9CF5&background=EBF4FF'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Full Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-m-envelope'),
                Tables\Columns\BadgeColumn::make('role')
                    ->colors([
                        'secondary' => 'user',
                        'success' => 'admin',
                    ])
                    ->icons([
                        'heroicon-o-user' => 'user',
                        'heroicon-o-shield-check' => 'admin',
                    ]),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->toggleable()
                    ->icon('heroicon-m-phone'),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->label('Birth Date')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\BadgeColumn::make('gender')
                    ->colors([
                        'primary' => 'Laki-laki',
                        'secondary' => 'Perempuan',
                    ])
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Registered')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('M j, Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->options([
                        'user' => 'User',
                        'admin' => 'Admin',
                    ]),
                SelectFilter::make('gender')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ]),
                Filter::make('created_this_month')
                    ->query(fn(Builder $query): Builder => $query->whereMonth('created_at', now()->month))
                    ->label('Registered This Month'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // No bulk actions for read-only view
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::count() > 10 ? 'success' : 'primary';
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email', 'first_name', 'last_name', 'phone'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string|\Illuminate\Contracts\Support\Htmlable
    {
        return $record->name;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Email' => $record->email,
            'Role' => ucfirst($record->role),
        ];
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
            'index' => Pages\ListUsers::route('/'),
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }
}
