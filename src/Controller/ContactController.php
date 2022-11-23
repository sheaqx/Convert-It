<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact_index')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $html = "
            <div style='height:100%;width:100%; background-color:#dfceba;font-family:sans-serif; text-align:center;'>
                <div style='background-color:#8EBEE9;padding:40px;'>
                    <h4>
                    Exp. " . $form->getViewData()['pseudo'] . ".
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
                ->to($this->getParameter('mailer_from'))
                ->subject($form->getViewData()['subject'])
                ->html($html);

            $mailer->send($email);
            // TODO Optionally:
            // if ("message envoyé") {
            //     echo '<script type="text/javascript">
            //              window.onload = function () {
            //              if(confirm("Votre message a bien été envoyé. Vous allez être redirigé vers l\'accueil.")){
            //              window.location.href = "http://localhost:8000"
            //              }
            //             }
            //             </script>';
            // } else {
            //     echo '<script type="text/javascript">
            //     window.onload = function () { alert("Nous ne sommes pas parvenu a envoyer votre message"); }
            //    </script>';
            // };
        }

        return $this->render('pages/contact/index.html.twig', [
            'controller_name' => 'ContactController', 'form' => $form->createView(),
        ]);
    }
}
