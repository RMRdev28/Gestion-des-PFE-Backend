<?php

namespace App\Console\Commands;

use App\Models\Pfe;
use App\Models\Prof;
use App\Models\ValidationPfe;
use Illuminate\Console\Command;

class FindValidators extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pfe:find-validators';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this commande is to find validator of pfe subject';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $validators = Prof::with(['categories'])->where('isValidator', 1)->get();
        $pfes = Pfe::with(['categories'])->where('year', 'thisYear')->where('status', 'pasencore')->get();

        foreach ($pfes as $pfe) {
            $pfeCategories = $pfe->categories->pluck('id')->toArray();
            $validatingProfs = [];

            foreach ($validators as $validator) {
                $validatorCategories = $validator->categories->pluck('id')->toArray();

                if ((count(array_intersect($pfeCategories, $validatorCategories)) > 0) && ($pfe->idEns != $validator->id)) {
                    $validatingProfs[] = $validator;
                    if (count($validatingProfs) == 2) {
                        break;
                    }
                }
            }
            if (count($validatingProfs) == 0) {
                $validatingProfs = $validators->random(2);
            }
            foreach ($validatingProfs as $validatingProf) {
                $valid = new ValidationPfe();
                $valid->idPfe = $pfe->id;
                $valid->idProf = $validatingProf->id;
                $valid->save();
            }
        }

        $this->info("ALL PFE HAVE VALIDATORS");
    }
}
