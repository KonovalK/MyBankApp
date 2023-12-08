<?php

namespace App\EntityListener;

use App\Entity\Transaction;
use App\Services\MailerService;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class TransactionEntityListener
{

    /**
     * @param MailerService $mailerService
     */
    public function __construct(private MailerService $mailerService){}

    /**
     * @param Transaction $transaction
     * @param LifecycleEventArgs $eventArgs
     * @return void
     */
    public function prePersist(Transaction $transaction, LifecycleEventArgs $eventArgs): void
    {
//        $sender = $transaction->getSender();
//        $receiver=$transaction->getreceiver();
//
//        $userSender = [
//            'userEmail' => $sender->getEmail(),
//            'name' => $sender->getName(),
//            'surName' => $sender->getSurname(),
//        ];
//
//        $userReceiver = [
//            'userEmail' => $receiver->getEmail(),
//            'name' => $receiver->getName(),
//            'surName' => $receiver->getSurname(),
//        ];
//
//        $transactionInfo = [
//            'from' => $sender->getName().' '.$sender->getSurname(),
//            'to' => $receiver->getName().' '.$receiver->getSurname(),
//            'summa' => $transaction->getSumma(),
//            'description' => $transaction->getDescription(),
//            'date' => $transaction->getDate(),
//        ];
//
//        //Send mail to Sender
//        $this->mailerService->SendMailFunc($userSender, $transactionInfo);
//
//        //Send mail to Receiver
//        $this->mailerService->SendMailFunc($userReceiver, $transactionInfo);
    }
}