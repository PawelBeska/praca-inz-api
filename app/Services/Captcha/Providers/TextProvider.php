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


    public function __construct(private Service $service, private array|FormRequest|Request $request)
    {
    }

    public function verify(Verification $verification, array|FormRequest|Request $request): bool
    {
        if (getType($request) == "array") {
            (new VerificationService($verification))->setActive(false);
            return Hash::check($request['text'], $verification->control);
        } else if (
            !$verification->active &&
            $request->ip() == $verification->ip_address &&
            $verification->service_id == $this->service->id &&
            !$verification->valid_until->isPast())
            return false;

        (new VerificationService($verification))->setActive(false);
        return Hash::check($request->get('text'), $verification->control);
    }

    private function generateString($length = 8): string
    {
        $characters = '123456789abcdefghjkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * @return array
     * @throws \Exception
     */
    #[ArrayShape([
        'image' => "string",
        'token' => "\Ramsey\Uuid\UuidInterface"
    ])]
    public function generate(): array
    {
        $text = $this->generateString();

        if (gettype($this->request) == "array")
            $verification = (new VerificationService())->add(
                $this,
                $text,
                $this->service,
                "127.0.0.1"
            );
        else
            $verification = (new VerificationService())->add(
                $this,
                $text,
                $this->service,
                $this->request->ip()
            );
        $image = Image::make(storage_path('app/public/background.jpg'));

        $letters = str_split($text);

        for ($x = 0; $x < count($letters); $x++) {
            $image->text($letters[$x], 80 + ($x * random_int(18, 20)), 16 + random_int(0, 10), function (Font $font) {
                $font->file(storage_path('app/public/font.ttf'));
                $font->size(30);
                $font->align('center');
                $font->valign('top');
                $font->angle(random_int(-45, 45));
            });
        }


        $rectangle_width = 1;
        $rectangle_height = 1;

        for ($z = 0; $z < 60; $z++) {
            for ($x = 0; $x < 600; $x++) {
                $image->rectangle(($x * $rectangle_width), $rectangle_height * $z, $rectangle_width * ($x + 1), $rectangle_height * ($z + 1), function (RectangleShape $shape) use ($x) {

                    if ($x % 2 == 0)
                        $shape->background("rgba(0,0,0, " . random_int(1, 2) / 10 . ")");
                    else
                        $shape->background("rgba(255,255,255,0.5)");

                    $shape->border(null);

                });
            }
        }
        return [
            'image' => $image->encode('data-url')->getEncoded(),
            'token' => $verification->uuid
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
