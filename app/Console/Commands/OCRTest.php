<?php

namespace App\Console\Commands;

use App\Models\Service;
use App\Models\User;
use App\Models\Verification;
use App\Services\Captcha\Captcha;
use App\Services\Services\ServicesService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use thiagoalessio\TesseractOCR\TesseractOCR;

class OCRTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ocr:test {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testing the captcha with OCR';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $service = (new ServicesService())->assignData(
            [
                "uuid" => Str::uuid(),
                "name" => "OCR_TEST",
                "type" => Service::TYPE_TEXT,
                "status" => Service::STATUS_ACTIVE,
                "valid_until" => Carbon::now()->addDay()
            ],
            User::first()
        );
        $good_answer = 0;
        $bad_answer = 0;


        for ($x = 0; $x < 1000; $x++) {
            try {
                $captcha = (new Captcha($this->argument("type"), $service, []))->getVerifyProvider()->generate();

                $ocr = new TesseractOCR();
                $ocr->withoutTempFiles();
                $file = file_get_contents($captcha['image']);
                $ocr->imageData($file, strlen($file));

                $text =preg_replace('/[ \t]+/', '', preg_replace('/[\r\n]+/', "", $ocr->run()));
                if ((new Captcha($this->argument("type"), $service, []))->getVerifyProvider()->verify(Verification::firstWhere('uuid', $captcha['token']), [
                    'text' => $text
                ]))
                    $good_answer++;
                else $bad_answer++;
            } catch (Exception $e) {
                $bad_answer++;
            }
            $this->table([
                'Dobre odpowiedzi',
                'Złe odpowiedzi',
                'Znaleziony tekst',
                'Tekst do znalezienia'
            ], [
                [
                    $good_answer,
                    $bad_answer,
                    $text,
                    $captcha['text']
                ],
            ]);
        }

        $this->table([
            'Dobre odpowiedzi',
            'Złe odpowiedzi',
        ], [
            [
                $good_answer,
                $bad_answer,

            ],
        ]);
        $service->delete();

    }
}
