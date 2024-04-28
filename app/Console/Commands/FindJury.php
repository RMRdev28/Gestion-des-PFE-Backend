<?php

namespace App\Console\Commands;

use App\Models\Pfe;
use App\Models\Prof;
use Illuminate\Console\Command;

class FindJury extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pfe:find-jury';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to find jury For PFE';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $validators = Prof::with(['categories'])->where('isValidator', 1)->get();
        $pfes = Pfe::with(['categories'])->where('year', 'thisYear')->where('status', 'valide')->get();
        foreach ($pfes as $pfe) {
            $pfeCategories = $pfe->categories->pluck('id')->toArray();
            $validatingProfs = [];

            foreach ($validators as $validator) {
                $validatorCategories = $validator->categories->pluck('id')->toArray();

                if ((count(array_intersect($pfeCategories, $validatorCategories)) > 0) && ($pfe->idEns != $validator->id) && ($pfe->jury1 != $validator->id) && ($validator->juryForPfe <= 4)) {
                    $validatingProfs[] = $validator;
                    if (count($validatingProfs) == 2) {
                        break;
                    }
                }
            }
            if (count($validatingProfs) == 0) {
                $validatingProfs = $validators->random(2);
            }
            if ($pfe->jury1 == null) {
                $pfe->jury1 = $validatingProfs[0]->id;
            }

            $pfe->jury2 = $validatingProfs[1]->id;
            $pfe->save();

        }

        $this->info("ALL PFE HAVE JURY");
    }
}
