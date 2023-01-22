<?php

namespace App\Services\Captcha\Providers;

use App\Dto\CaptchaGenerationDto;
use App\Dto\CaptchaVerificationDto;
use App\Enums\VerificationTypeEnum;
use App\Interfaces\DtoInterface;
use App\Services\Captcha\Dto\TextDto;
use App\Services\Captcha\VerifyRules\ActiveRule;
use App\Services\Captcha\VerifyRules\HashRule;
use App\Services\Captcha\VerifyRules\IpAddressRule;
use App\Services\Captcha\VerifyRules\NotExpiredRule;
use App\Services\Captcha\VerifyRules\PrivateKeyRule;
use App\Services\Captcha\VerifyRules\ServiceIdRule;
use Carbon\Carbon;
use Closure;
use Intervention\Image\Facades\Image;
use Intervention\Image\Gd\Font;

class TextProvider extends VerificationProvider
{
    public function handle(CaptchaGenerationDto $captchaGenerationDto, Closure $next)
    {
        if (!$this->active($captchaGenerationDto)) {
            return $next($captchaGenerationDto);
        }

        return $this->generate($captchaGenerationDto);
    }

    public function verify(CaptchaVerificationDto $captchaVerificationDto): bool
    {
        return (bool)$captchaVerificationDto->pipeThrough(
            [
                PrivateKeyRule::class,
                ActiveRule::class,
                ServiceIdRule::class,
                IpAddressRule::class,
                NotExpiredRule::class,
                HashRule::class
            ]
        )->thenReturn();
    }

    public function generate(CaptchaGenerationDto $captchaGenerationDto): DtoInterface
    {
        $text = $this->generateString();

        $verification = $this->verificationService->add(
            $text,
            VerificationTypeEnum::TEXT,
            $captchaGenerationDto
        );

        $image = $this->generateImage($text);

        return new TextDto(
            $verification,
            $image->encode('data-url')->getEncoded(),
        );
    }

    protected function active(CaptchaGenerationDto $captchaGenerationDto): bool
    {
        return $this->verificationRepository->getVerificationCountByIpAndDate(
                $captchaGenerationDto->ipAddress,
                Carbon::now()->subHour(),
                Carbon::now()->addHour()
            ) < VerificationTypeEnum::TEXT->getConfig('max_attempts');
    }

    private function generateString($length = 8): string
    {
        $characters = '23456789abcdefghkmnpqstuvwxyzABCDEFGHKLMNPRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    private function generateImage(string $text): \Intervention\Image\Image
    {
        $image = Image::make(storage_path('app/public/background.jpg'));
        $image->fill('#dbdbdb');
        $letters = str_split($text);

        foreach ($letters as $x => $xValue) {
            $image->text($xValue, 80 + ($x * random_int(18, 20)), 16 + random_int(0, 10), function (Font $font) {
                $font->file(storage_path('app/public/font.ttf'));
                $font->size(30);
                $font->align('center');
                $font->valign('top');
                $font->angle(random_int(-45, 45));
                $font->color("rgba(0,0,0, ".random_int(3, 5) / 10 .")");
            });
        }
        return $image;
    }
}
