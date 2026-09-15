<?php

namespace App\Services;

use App\Models\ChartOfAccount;
use App\Models\JournalEntry;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class JournalEntryService
{
    public function create(array $data, array $lines): JournalEntry
    {
        if (count($lines) < 2) {
            throw new InvalidArgumentException(
                'A journal entry must have at least two lines.'
            );
        }

        $totalDebit = collect($lines)->sum('debit');
        $totalCredit = collect($lines)->sum('credit');

        if (bccomp((string) $totalDebit, (string) $totalCredit, 2) !== 0) {
            throw new InvalidArgumentException(
                'Total debit must equal total credit.'
            );
        }

        $laboratoryId = $data['laboratory_id'];

        foreach ($lines as $line) {
            $account = ChartOfAccount::find($line['account_id']);

            if (! $account) {
                throw new InvalidArgumentException(
                    'One of the selected accounts does not exist.'
                );
            }

            if ($account->laboratory_id !== $laboratoryId) {
                throw new InvalidArgumentException(
                    'All accounts must belong to the same laboratory.'
                );
            }

            $debit = $line['debit'] ?? 0;
            $credit = $line['credit'] ?? 0;

            if ($debit > 0 && $credit > 0) {
                throw new InvalidArgumentException(
                    'A journal line cannot have both debit and credit.'
                );
            }

            if ($debit == 0 && $credit == 0) {
                throw new InvalidArgumentException(
                    'A journal line must have either a debit or credit amount.'
                );
            }
        }

        return DB::transaction(function () use ($data, $lines) {

            $entry = JournalEntry::create($data);

            foreach ($lines as $line) {
                $entry->lines()->create($line);
            }

            return $entry->load('lines');
        });
    }
}