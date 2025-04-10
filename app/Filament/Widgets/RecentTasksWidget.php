<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Filament\Tables\Columns\TextColumn;

class RecentTasksWidget extends TableWidget
{
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Task::query()->latest()->limit(5))
            ->columns([
                TextColumn::make('user.name'),
                TextColumn::make('title'),
                TextColumn::make('is_completed')
                    ->formatStateUsing(fn (bool $state) => $state ? '✅ Completed' : '🟡 Pending'),
                TextColumn::make('created_at')->dateTime(),
            ]);
    }
}
