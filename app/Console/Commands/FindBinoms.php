<?php

namespace App\Console\Commands;

use App\Mail\FindBinomForYou;
use App\Mail\NoBinomFound;
use App\Mail\sendRecomandation;
use App\Models\Binom;
use App\Models\User;
use App\Traits\SendEmailTrait;
use Gemini;
use Illuminate\Console\Command;

class FindBinoms extends Command
{
    use SendEmailTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pfe:find-binoms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This commande to find binoms for students who ask for binoms';

    /**
     * Execute the console command. this function  to send email to student
     */
    public function handle()
    {
        $bool = false;
        while (!$bool) {
            $users = User::where('haveBinom', -1)->where('typeUser', 0)->get();
            $groupedUsers = $users->groupBy(function ($item, $key) {
                return $item['level'] . '-' . $item['specialite'];
            });
            foreach ($groupedUsers as $group) {
                while (count($group) >= 2) {
                    $randomUsers = $group->random(2);
                    $binom = new Binom();
                    $binom->type = "valide";
                    $binom->idEtu1 = $randomUsers[0]->id;
                    $binom->idEtu2 = $randomUsers[1]->id;
                    if ($binom->save()) {
                        $randomUsers[0]->haveBinom = 1;
                        $randomUsers[1]->haveBinom = 1;
                        $emailFinBinom = new FindBinomForYou($randomUsers[1], $randomUsers[0]);
                        $this->sendEmail($randomUsers[0]->email, $emailFinBinom);
                        $randomUsers[0]->save();
                        $emailFinBinom = new FindBinomForYou($randomUsers[0], $randomUsers[1]);
                        $this->sendEmail($randomUsers[1]->email, $emailFinBinom);
                        $randomUsers[1]->save();
                        $client = Gemini::client(env('GOOGLE_API_KEY'));
                        $result = $client->geminiPro()->generateContent("Pourriez-vous me recommander des sujets de PFE ? Je suis étudiant en troisième année de licence à l'USHTB, spécialité ISIL (Ingénierie des Systèmes d'Information et Logiciel). S'il vous plaît, donnez les sujets de manière concise sous format de html.");
                        $recomndation = new sendRecomandation($randomUsers[0], $result->text());
                        $this->sendEmail($randomUsers[0]->email, $recomndation);
                        $recomndation = new sendRecomandation($randomUsers[1], $result->text());
                        $this->sendEmail($randomUsers[1]->email, $recomndation);
                        $group = $group->diff($randomUsers);
                    }
                }
                if (count($group) == 1) {
                    $user = $group[0];
                    $sorryEmail = new NoBinomFound($user);
                    $this->sendEmail($user->email,$sorryEmail);
                }
            }
        }
        $this->info("THE SYSTEM IS DONE");
    }



}
