<?php

namespace App\Services\Captcha\Providers;

use App\Interfaces\VerifyProviderInterface;
use App\Models\Service;
use App\Services\Captcha\VerificationService;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use JetBrains\PhpStorm\ArrayShape;

class TextProvider implements VerifyProviderInterface
{


    public function __construct(private Service $service)
    {}

    public function verify()
    {
        // TODO: Implement verify() method.
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
        $text = Str::random(6);
        $verification = (new VerificationService())->add($this, $text, $this->service);
        $image = Image::make(storage_path('app/public/background.jpg'));
        $image->text(Str::random(6), 155, 16, function ($font) {
            $font->file(storage_path('app/public/font.ttf'));
            $font->size(36);
            $font->align('center');
            $font->valign('top');
            $font->angle(0);
        });


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
