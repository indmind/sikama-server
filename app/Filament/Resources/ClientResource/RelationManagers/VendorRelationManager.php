<?php

namespace App\Filament\Resources\ClientResource\RelationManagers;

use App\Models\Category;
use App\Models\Client;
use App\Models\Vendor;
use Closure;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VendorRelationManager extends RelationManager
{
    protected static string $relationship = 'vendor';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('category_id')
                    ->required()
                    ->options(Category::all()->pluck('name', 'id'))
                    ->searchable()
                    ->label('Category'),
                Forms\Components\Repeater::make('vendor_images')
                    ->relationship('images')
                    ->schema([
                        Forms\Components\FileUpload::make('image_path')
                            ->image()
                            ->disk('public')
                            ->directory('images/vendors')
                            ->label('Image')
                            ->required(),
                    ]),

                // Use action to verify
                // Forms\Components\Hidden::make('verified_by'),
                // Forms\Components\Toggle::make('is_verified')
                //     ->label('Verified')
                //     ->reactive()
                //     ->afterStateUpdated(
                //         fn (callable $set, $state) => $set('verified_by', $state ? auth()->user()->id : null)
                //     ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\BooleanColumn::make('is_verified'),
                Tables\Columns\TextColumn::make('verifiedBy.name'),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category'),
            ])

            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make()
            ])
            ->actions([
                // Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('verify')
                    ->action(fn (Vendor $record) => $record->verify())
                    ->requiresConfirmation()
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->hidden(fn (Vendor $record) => $record->is_verified),
                Tables\Actions\Action::make('unverify')
                    ->action(fn (Vendor $record) => $record->unverify())
                    ->requiresConfirmation()
                    ->color('danger')
                    ->icon('heroicon-o-x')
                    ->hidden(fn (Vendor $record) => !$record->is_verified),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
