<?php

namespace App\Filament\Resources\Tickets\Pages;

use App\Filament\Resources\Tickets\TicketResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Traits\TicketAutoLogger;

class EditTicket extends EditRecord
{
    use TicketAutoLogger;

    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $original = $this->record->getOriginal();

        foreach ($data as $field => $newValue) {

            // Abaikan attachments (karena array)
            if ($field === 'attachments') {
                continue;
            }

            $oldValue = $original[$field] ?? null;

            if ($oldValue != $newValue) {
                \App\Models\TicketLog::create([
                    'ticket_id' => $this->record->id,
                    'user_id'   => auth()->id(),
                    'action'    => "Updated {$field}",
                    'old_value' => is_array($oldValue) ? json_encode($oldValue) : $oldValue,
                    'new_value' => is_array($newValue) ? json_encode($newValue) : $newValue,
                ]);
            }
        }

        // Attachment field jangan dilog di sini
        $this->logTicketChanges($this->record, $data, ['attachments']);

        return $data;
    }


    protected function afterSave(): void
    {
        $record = $this->record;

        // File baru dari form
        $newFiles = $this->data['attachments'] ?? [];

        // File lama dari database (koleksi)
        $oldFiles = $record->attachments()->pluck('file_path')->toArray();

        // --- LOGGING PERUBAHAN ATTACHMENTS ---

        $added = array_values(array_diff($newFiles, $oldFiles));
        $removed = array_values(array_diff($oldFiles, $newFiles));

        // Hanya log jika ada perubahan file
        if (!empty($added) || !empty($removed)) {
            \App\Models\TicketLog::create([
                'ticket_id' => $record->id,
                'user_id'   => auth()->id(),
                'action'    => 'Updated attachments',
                'old_value' => json_encode($oldFiles),
                'new_value' => json_encode($newFiles),
                'notes'     => json_encode([
                    'added'   => $added,
                    'removed' => $removed,
                ]),
            ]);
        }

        // Log attachment changes (added/removed)
        $this->logAttachmentChanges($record, $newFiles);

        // --- UPDATE ATTACHMENTS ACTUAL DATA ---
        
        // Hapus semua data lama
        $record->attachments()->delete();

        // Insert ulang file (replacement)
        foreach ($newFiles as $file) {
            $record->attachments()->create([
                'file_path'   => $file,
                'file_name'   => basename($file),
                'file_type'   => \Storage::mimeType($file),
                'file_size'   => \Storage::size($file),
                'uploaded_by' => auth()->id(),
            ]);
        }
    }

}
