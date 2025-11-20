<?php

namespace App\Filament\Resources\Tickets\Pages;

use App\Filament\Resources\Tickets\TicketResource;
use Filament\Resources\Pages\CreateRecord;
use App\Traits\TicketAutoLogger;

class CreateTicket extends CreateRecord
{
    use TicketAutoLogger;

    protected static string $resource = TicketResource::class;

    protected function afterCreate(): void
    {
        $record = $this->record;

        // Ambil daftar file dari form
        $files = $this->data['attachments'] ?? [];

        \App\Models\TicketLog::create([
            'ticket_id' => $this->record->id,
            'user_id' => auth()->id(),
            'action' => 'Ticket created',
            'old_value' => null,
            'new_value' => json_encode([
                'title'       => $record->title,
                'description' => $record->description,
                'status'      => $record->status,
                'priority_id' => $record->priority_id,
                'category_id' => $record->category_id,
                'attachments' => $files,
            ]),
        ]);

        

        foreach ($files as $file) {
            $record->attachments()->create([
                'file_path' => $file,
                'file_name' => basename($file),
                'file_type' => \Storage::mimeType($file),
                'file_size' => \Storage::size($file),
                'uploaded_by' => auth()->id(),
            ]);
        }

        // Log attachment additions only
        $this->logAttachmentChanges($record, $files);
    }

}
