<?php

namespace App\Traits;

use App\Models\TicketLog;
use Illuminate\Support\Arr;

trait TicketAutoLogger
{
    protected function logTicketChanges($record, array $data, array $ignored = [])
    {
        $original = $record->getOriginal();

        foreach ($data as $field => $newValue) {

            // Skip jika field di-ignore
            if (in_array($field, $ignored)) {
                continue;
            }

            $oldValue = $original[$field] ?? null;

            // Jika ada perubahan
            if ($oldValue != $newValue) {

                TicketLog::create([
                    'ticket_id' => $record->id,
                    'user_id'   => auth()->id(),
                    'action'    => "Updated {$field}",
                    'old_value' => is_array($oldValue) ? json_encode($oldValue) : $oldValue,
                    'new_value' => is_array($newValue) ? json_encode($newValue) : $newValue,
                ]);
            }
        }
    }

    protected function logAttachmentChanges($record, array $newFiles)
    {
        $oldFiles = $record->attachments()->pluck('file_path')->toArray();

        $added = array_values(array_diff($newFiles, $oldFiles));
        $removed = array_values(array_diff($oldFiles, $newFiles));

        if (!empty($added) || !empty($removed)) {

            TicketLog::create([
                'ticket_id' => $record->id,
                'user_id'   => auth()->id(),
                'action'    => "Updated attachments",
                'old_value' => json_encode($oldFiles),
                'new_value' => json_encode($newFiles),
                'notes'     => json_encode([
                    'added'   => $added,
                    'removed' => $removed,
                ]),
            ]);
        }
    }
}
