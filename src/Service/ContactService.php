<?php

namespace App\Service;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ContactService
{
    public function __construct(private readonly MailerInterface $mailer, private readonly string $mailerFrom)
    {
    }

    public function sendContactMail(FormInterface $form): void
    {
        $html = "
    <div style='height:100%;width:100%; background-color:#dfceba;font-family:sans-serif; text-align:center;'>
        <div style='background-color:#8EBEE9;padding:40px;'>
            <h4>
            Exp. " . $form->get('pseudo')->getData() . ".
            </h4>
            <h5>email: " . $form->getViewData()['email'] . " :
            </h5>
        </div>
        <div>
            <h4>
            Message :
            </h4>
            <p>" . $form->getViewData()['message'] . "
            </p>
        </div>
    </div>";
        $email = (new Email())
            ->from($form->getViewData()['email'])
            ->to($this->mailerFrom)
            ->subject($form->getViewData()['subject'])
            ->html($html);

        $this->mailer->send($email);
    }
}
