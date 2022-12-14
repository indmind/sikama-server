<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VendorResource\Pages;
use App\Filament\Resources\VendorResource\RelationManagers;
use App\Models\Category;
use App\Models\Vendor;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class VendorResource extends Resource
{
    protected static ?string $model = Vendor::class;

    protected static ?string $navigationIcon = 'heroicon-o-library';

    public static function canCreate(): bool
    {
        return false;
    }

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
                    ->collapsible()
                    ->grid()
                    ->schema([
                        Forms\Components\FileUpload::make('image_path')
                            ->image()
                            ->disk('public')
                            ->directory('images/vendors')
                            ->label('Image')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\BooleanColumn::make('is_verified'),
                Tables\Columns\BooleanColumn::make('is_active'),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('description')->searchable(),
                Tables\Columns\TextColumn::make('seller.name')->searchable(),
                Tables\Columns\TextColumn::make('category.name')->searchable(),
                Tables\Columns\TextColumn::make('verifiedBy.name')->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('isActive')->options([
                    1 => 'Yes',
                    0 => 'No',
                ])->column('is_active'),
                Tables\Filters\SelectFilter::make('category')->relationship('category', 'name'),
                Tables\Filters\SelectFilter::make('isVerified')->options([
                    1 => 'Yes',
                    0 => 'No',
                ])->column('is_verified'),
                Tables\Filters\SelectFilter::make('verifiedBy')->relationship('verifiedBy', 'name'),
            ])
            ->actions([
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
                    ->hidden(fn (Vendor $record) => ! $record->is_verified),

                // for debug only
                Tables\Actions\Action::make('open')
                    ->action(fn (Vendor $record) => $record->update(['is_active' => true]))
                    ->requiresConfirmation()
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->hidden(fn (Vendor $record) => $record->is_active),
                Tables\Actions\Action::make('close')
                    ->action(fn (Vendor $record) => $record->update(['is_active' => false]))
                    ->requiresConfirmation()
                    ->color('danger')
                    ->icon('heroicon-o-x')
                    ->hidden(fn (Vendor $record) => ! $record->is_active),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ProductsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVendors::route('/'),
            'create' => Pages\CreateVendor::route('/create'),
            'edit' => Pages\EditVendor::route('/{record}/edit'),
        ];
    }
}
