<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListTrashedUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getTableQuery(): Builder 
    {
        return parent::getTableQuery()
            ->onlyTrashed();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('restore')
                ->label('Restore Selected')
                ->icon('heroicon-o-arrow-uturn-left')
                ->requiresConfirmation()
                ->action(function (array $arguments) {
                    $records = $this->getTable()->getSelectedRecords();
                    
                    if (empty($records)) {
                        $this->notify('warning', 'No records selected');
                        return;
                    }
                    
                    foreach ($records as $record) {
                        $record->restore();
                    }
                    
                    $this->getTable()->clearSelection();
                    $this->notify('success', count($records) . ' records restored');
                }),
                
            Actions\Action::make('forceDelete')
                ->label('Permanently Delete')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function (array $arguments) {
                    $records = $this->getTable()->getSelectedRecords();
                    
                    if (empty($records)) {
                        $this->notify('warning', 'No records selected');
                        return;
                    }
                    
                    foreach ($records as $record) {
                        $record->forceDelete();
                    }
                    
                    $this->getTable()->clearSelection();
                    $this->notify('success', count($records) . ' records permanently deleted');
                })
        ];
    }
}