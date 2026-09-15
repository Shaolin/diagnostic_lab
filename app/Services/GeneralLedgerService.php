<?php

namespace App\Services;

use App\Models\JournalEntryLine;
use Illuminate\Database\Eloquent\Builder;

class GeneralLedgerService
{
    public function query(
        int $laboratoryId,
        ?int $branchId = null,
        ?int $accountId = null,
        ?string $from = null,
        ?string $to = null
    ): Builder {
        return JournalEntryLine::query()
            ->with([
                'journalEntry',
                'account',
            ])
            ->whereHas('journalEntry', function (Builder $query) use (
                $laboratoryId,
                $branchId,
                $from,
                $to
            ) {
                $query->where('laboratory_id', $laboratoryId)
                    ->where('status', 'posted');

                if ($branchId !== null) {
                    $query->where('branch_id', $branchId);
                }

                if ($from !== null) {
                    $query->whereDate('entry_date', '>=', $from);
                }

                if ($to !== null) {
                    $query->whereDate('entry_date', '<=', $to);
                }
            })
            ->when(
                $accountId !== null,
                fn (Builder $query) =>
                    $query->where('account_id', $accountId)
            )
            ->join(
                'journal_entries',
                'journal_entry_lines.journal_entry_id',
                '=',
                'journal_entries.id'
            )
            ->orderBy('journal_entries.entry_date')
            ->orderBy('journal_entries.id')
            ->select('journal_entry_lines.*');
    }
}