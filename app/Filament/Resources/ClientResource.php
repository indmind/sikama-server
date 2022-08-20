<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Models\Category;
use App\Models\Client;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('google_id')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('image_path')
                    ->image()
                    ->disk('public')
                    ->directory('images/clients')
                    ->label('Image'),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),
                // Forms\Components\Fieldset::make('Vendor')
                //     ->relationship('vendor')
                //     ->schema([
                //         Forms\Components\TextInput::make('name')
                //             ->required()
                //             ->maxLength(255),
                //         Forms\Components\TextInput::make('description')
                //             ->required()
                //             ->maxLength(255),
                //         Forms\Components\Select::make('category_id')
                //             ->required()
                //             ->options(Category::all()->pluck('name', 'id'))
                //             ->searchable()
                //             ->label('Category'),
                //     ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Image'),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\BooleanColumn::make('is_seller'),
                Tables\Columns\BooleanColumn::make('vendor.is_verified')
                    ->label('Is Verified Vendor')
                    ->default(false),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('phone')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->sortable()
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')->sortable()
                    ->dateTime(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'vendor' => 'Vendor',
                        'client' => 'Client',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['value'] === 'vendor') {
                            $query->whereHas('vendor');
                        } elseif ($data['value'] === 'client') {
                            $query->whereDoesntHave('vendor');
                        }
                    }),
                Tables\Filters\SelectFilter::make('verified_vendor')
                    ->options([
                        'true' => 'Verified',
                        'false' => 'Not Verified',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['value'] === 'true') {
                            $query->whereHas('vendor', function (Builder $query) {
                                $query->where('is_verified', true);
                            });
                        } elseif ($data['value'] === 'false') {
                            $query->whereHas('vendor', function (Builder $query) {
                                $query->where('is_verified', false);
                            });
                        }
                    }),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\VendorRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
