<?php

namespace App\Filament\Resources;

use App\Filament\Helpers\Actions;
use App\Filament\Resources\NewsResource\Pages;
use App\Filament\Resources\NewsResource\RelationManagers;
use App\Models\News;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use FilamentTiptapEditor\Enums\TiptapOutput;
use FilamentTiptapEditor\TiptapEditor;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class NewsResource extends Resource
{
//    use Translatable;

    protected static ?string $model = News::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('alias')
                    ->maxLength(255),
//                Forms\Components\TextInput::make('external_link')
//                    ->maxLength(255),
                Forms\Components\TextInput::make('title_meta')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255),
                TiptapEditor::make('content')
                    ->output(TiptapOutput::Html)
//                TinyEditor::make('content')
                    ->required()
//                    ->maxLength(16777215)
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('path_image')
                    ->image(),
                Forms\Components\Toggle::make('is_show_main')
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->required(),
                Forms\Components\Toggle::make('for_opt')
                    ->required(),
                Forms\Components\Toggle::make('for_retail')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('path_image')
                    ->disk('local2')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('alias')
                    ->searchable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('external_link')
                    ->searchable()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('title_meta')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->translateLabel(),
                Tables\Columns\IconColumn::make('is_show_main')
                    ->boolean()
                    ->translateLabel(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->translateLabel(),
                Tables\Columns\IconColumn::make('for_opt')
                    ->boolean()
                    ->translateLabel(),
                Tables\Columns\IconColumn::make('for_retail')
                    ->boolean()
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->translateLabel(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions(Actions::all())
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
            'index' => Pages\ListNews::route('/'),
            'create' => Pages\CreateNews::route('/create'),
            'edit' => Pages\EditNews::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('News!');
    }

    public static function getNavigationLabel(): string
    {
        return __('News');
    }
}
