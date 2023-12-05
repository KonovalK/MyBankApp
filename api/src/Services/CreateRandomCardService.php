<?php

namespace App\Services;

use Dompdf\Dompdf;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class CreateRandomCardService
{

    public function __construct(){}

    public function GenerateRandomCardNum($type):string
    {

        $bin = ($type == 'mastercard') ? '5' : '4'; //Либо виза либо мастеркард
        $length = 16; // Длина номера карты

        // Генерация оставшихся цифр
        for ($i = strlen($bin); $i < $length - 1; $i++) {
            $bin .= rand(0, 9);
        }

        // Расчет последней цифры (контрольной суммы) с использованием алгоритма Luhn
        $checksum = 0;
        for ($i = strlen($bin) - 1; $i >= 0; $i--) {
            $digit = (int)$bin[$i];
            if ($i % 2 == 0) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            $checksum += $digit;
        }

        $bin .= (10 - ($checksum % 10)) % 10;

        return $bin;
    }

    function generateExpirationDate():string
    {
        $currentYear = intval(date("y"));
        $expirationYear = rand($currentYear, $currentYear + 5); // Генерация года в диапазоне от текущего до текущего + 5 лет
        $expirationMonth = str_pad(rand(1, 12), 2, "0", STR_PAD_LEFT); // Генерация месяца с ведущим нулем при необходимости

        return $expirationMonth . "/" . $expirationYear;
    }
}