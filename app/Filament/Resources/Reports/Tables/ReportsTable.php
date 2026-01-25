<?php

namespace App\Filament\Resources\Reports\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\LinkColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Filters\SelectFilter;

class ReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([

                TextColumn::make('id')->sortable(),

                TextColumn::make('name')
                    ->label('Reporter Name')
                    ->limit(30)
                    ->wrap(),

                TextColumn::make('category')
                    ->wrap(),

                TextColumn::make('location')
                    ->wrap(),

                TextColumn::make('constituency')
                    ->sortable()
                    ->wrap(),

                TextColumn::make('description')
                    ->limit(50)
                    ->wrap(),

                ImageColumn::make('image')
                    ->label('Image')
                    ->disk('public')
                    ->height(70)
                    ->square(),


                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                    'pending' => 'warning',
                'verified' => 'success',
                'rejected' => 'danger',
                })
                ->sortable(),

                TextColumn::make('contact')
                    ->wrap(),

                TextColumn::make('url')
                    ->wrap(),



                TextColumn::make('created_at')
                    ->label('Submitted At')
                    ->dateTime('d M Y H:i'),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'pending' => 'Pending',
                    'verified' => 'Verified',
                    'rejected' => 'Rejected',
                ])
                ->default('pending'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
