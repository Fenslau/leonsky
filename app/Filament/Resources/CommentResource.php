<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CommentResource\Pages;
use App\Filament\Resources\CommentResource\RelationManagers;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Scopes\ActiveScope;
use Filament\Forms;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';
    protected static ?string $modelLabel = 'Комментарий';
    protected static ?string $pluralModelLabel = 'Комментарии';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                MorphToSelect::make('commentable')
                    ->label('Источник')
                    ->types([
                        MorphToSelect\Type::make(Article::class)
                            ->label('Статья')
                            ->titleAttribute('title'),
                        MorphToSelect\Type::make(Comment::class)
                            ->label('Комментарий')
                            ->titleAttribute('text')
                            ->getOptionLabelFromRecordUsing(fn(Comment $record): string => strip_tags($record->text)),
                    ])
                    ->required(),
                Select::make('user_id')
                    ->label('Автор')
                    ->disabled(fn(?Model $record): bool => !request()->user()->isAdmin())
                    ->relationship('user', 'name')
                    ->required()
                    ->default(request()->user()->id)
                    ->selectablePlaceholder(false),
                TextInput::make('link')
                    ->label('Ссылка')
                    ->disabled()
                    ->default(null),
                RichEditor::make('text')
                    ->label('Текст')
                    ->fileAttachmentsDirectory('comment-images')
                    ->toolbarButtons([
                        'attachFiles',
                        'blockquote',
                        'bold',
                        'italic',
                        'underline',
                    ])
                    ->columnSpan('full'),
                Toggle::make('is_active')
                    ->label('Активен')
                    ->disabled(fn(?Model $record): bool => !request()->user()->isAdmin())
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Автор')
                    ->sortable()
                    ->limit(16)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (Str::length($state) <= $column->getCharacterLimit()) {
                            return null;
                        }
                        return $state;
                    })
                    ->searchable(),
                TextColumn::make('text')
                    ->label('Текст')
                    ->sortable()
                    ->limit(32)
                    ->html()
                    ->searchable(),
                ToggleColumn::make('is_active')
                    ->label('Активен')
                    ->disabled(fn(Model $record): bool => !request()->user()->isAdmin())
                    ->sortable(),
                TextColumn::make('deleted_at')
                    ->label('Удалено')
                    ->date()
                    ->timeTooltip()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Создано')
                    ->date()
                    ->timeTooltip()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('updated_at')
                    ->label('Изменено')
                    ->date()
                    ->timeTooltip()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->recordClasses(fn(Model $record) => match ($record->isActive() && !$record->trashed()) {
                false => 'bg-gray-100',
                default => null,
            })
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListComments::route('/'),
            'create' => Pages\CreateComment::route('/create'),
            'edit' => Pages\EditComment::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
                ActiveScope::class,
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
