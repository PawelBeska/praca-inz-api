<?php

namespace App\Services\Captcha\Providers;

use App\Interfaces\VerifyProviderInterface;
use App\Models\Service;
use App\Models\Verification;
use App\Services\Captcha\VerificationService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Intervention\Image\Gd\Font;
use Intervention\Image\Gd\Shapes\RectangleShape;
use JetBrains\PhpStorm\ArrayShape;

class TextProvider implements VerifyProviderInterface
{


    public function __construct(private Service $service, private FormRequest|Request $request)
    {
    }

    public function verify(Verification $verification, Request $request): bool
    {
        if (
            !$verification->active &&
            $request->ip() == $verification->ip_address &&
            $verification->service_id == $this->service->id &&
            !$verification->valid_until->isPast())
            return false;

        (new VerificationService($verification))->setActive(false);
        return Hash::check($request->get('text'), $verification->control);
    }

    /**
     * @return array
     */
    #[ArrayShape([
        'image' => "string",
        'token' => "\Ramsey\Uuid\UuidInterface"
    ])]
    public function generate(): array
    {
        $text = Str::random(8);
        $verification = (new VerificationService())->add(
            $this,
            $text,
            $this->service,
            $this->request->ip()
        );
        $image = Image::make(storage_path('app/public/background.jpg'));

        $letters = str_split($text);

        for ($x = 0; $x < count($letters); $x++) {
            $image->text($letters[$x], 40 + ($x * random_int(17,20)), 16 + random_int(0, 10), function (Font $font) {
                $font->file(storage_path('app/public/font.ttf'));
                $font->size(30);
                $font->align('center');
                $font->valign('top');
                $font->angle(random_int(-45, 45));
            });
        }
        for ($x = 0; $x < 10; $x++) {
            $image->rectangle(10 + ($x * 30), random_int(100, 150), 40 + ($x * 30), random_int(10, 50), function (RectangleShape $shape) {
                $shape->background("rgba(0,0,0, " . random_int(5, 7)/10 . ")");
                $shape->border(null);
            });
        }

        for ($x = 0; $x < 10; $x++) {
            $image->rectangle(10 + ($x * 30),0 , 40 + ($x * 30), random_int(100, 150), function (RectangleShape $shape) use ($x) {
                $shape->background("rgba(".random_int($x*20,200).",".random_int($x*20,200).",".random_int($x*20,200).", " . random_int(1, 5)/10 . ")");
                $shape->border(null);
            });
        }
        return [
            'image' => $image->encode('data-url')->getEncoded(),
            'token' => $verification->uuid,
        ];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return 'text';
    }
}
