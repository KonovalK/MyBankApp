<?php

namespace App\Services;

use Dompdf\Dompdf;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

/**
 *
 */
class MailerService
{

    /**
     * @param MailerInterface $mailer
     */
    public function __construct(private MailerInterface $mailer){}

    /**
     * @param $user
     * @param $info
     * @return void
     */
    public function SendMailFunc($user, $info):void
    {

        $email = (new TemplatedEmail())
            ->from('no-reply@ail.com')
            ->to($user['userEmail'])
            ->subject('Ваша транзакція!')
            ->htmlTemplate('mailTemplate.twig')
            ->context([
                'from' => $info['from'],
                'to' => $info['to'],
                'summa'=>$info['summa'],
                'description'=>$info['description'],
                'date'=>$info['date'],
            ]);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($email->getHtmlBody());
        $dompdf->render();
        $output = $dompdf->output();

        $email->attach($output, 'transaction.pdf');

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $e->getMessage();
        }
    }

    /**
     * @param $user
     * @param $info
     * @return void
     */
    public function SendVerificationMail($user, $info):void
    {

        $email = (new TemplatedEmail())
            ->from('no-reply@bankapp.com')
            ->to($user['userEmail'])
            ->subject('Підтвердження пошти!')
            ->htmlTemplate('verificationTemplate.twig')
            ->context([
                'code' => $info['code'],
            ]);

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $e->getMessage();
        }
    }
}