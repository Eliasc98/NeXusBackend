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
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\Filter;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)->schema([
                    TextInput::make('fullname')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('username')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('email')
                        ->required()
                        ->email()
                        ->unique(ignoreRecord: true),
                    TextInput::make('password')
                        ->password()
                        ->required()
                        ->minLength(6)
                        ->dehydrateStateUsing(fn ($state) => bcrypt($state)) // Hash password
                        ->label('Password'),
                    TextInput::make('contact_number')
                        ->label('Contact Number')
                        ->tel()
                        ->maxLength(15),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fullname')->label('Name'),
                Tables\Columns\TextColumn::make('username')->label('Username'),
                Tables\Columns\TextColumn::make('email')->label('Email'),
                Tables\Columns\TextColumn::make('contact_number')->label('Contact Number'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Created'),
            ])
            ->filters([
                Filter::make('fullname')
                    ->form([
                        TextInput::make('fullname')->label('Name'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['fullname'], fn ($q) => $q->where('fullname', 'like', '%' . $data['fullname'] . '%'));
                    }),
    
                Filter::make('email')
                    ->form([
                        TextInput::make('email')->label('Email'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['email'], fn ($q) => $q->where('email', 'like', '%' . $data['email'] . '%'));
                    }),
    
                Filter::make('contact_number')
                    ->form([
                        TextInput::make('contact_number')->label('Contact Number'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['contact_number'], fn ($q) => $q->where('contact_number', 'like', '%' . $data['contact_number'] . '%'));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->where('role', 1);
    }
}
