<?php

namespace ApiBundle\Controller;

use ApiBundle\Model\Guzzle\Fabryka;
use Doctrine\DBAL\Types\BooleanType;
use GuzzleHttp\Client;
use Hshn\Base64EncodedFile\Form\Type\Base64EncodedFileType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FormularzController
 * @package ApiBundle\Controller
 * @Route("/formularz")
 */
class FormularzController extends Controller
{
    /**
     * @Route("/upload")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function UploadAction(Request $request)
    {
        $formularz = $this->get('form.factory')->createNamedBuilder('')
            ->setMethod('POST')
            ->add('login', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Użytkownik']
            ])
            ->add('haslo', PasswordType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Hasło']
            ])
            ->add('token', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'f0a6fd0c-62d5-48f1-b06c-325789694d07']
            ])
            ->add('pliki', FileType::class, [
                'label_attr' => ['class' => 'custom-file-label'],
                'multiple' => true,
                'attr' => [
                    'multiple' => 'multiple',
                    'class' => 'custom-file-input'
                ]
            ])
            ->add('uploaduj', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary float-right']
            ])
            ->getForm();

        $formularz->handleRequest($request);

        if($formularz->isSubmitted() && $formularz->isValid()){
            $dane = $formularz->getData();

            $daneDoWyslania = [
                'token' => $dane['token'],
                'login' => $dane['login'],
                'haslo' => $dane['haslo']
            ];

            $pliki = [];

            /**
             * @var $plik UploadedFile
             */
            foreach ($dane['pliki'] as $plik){
                $pliki[] = [
                    'pierwotna_nazwa' => $plik->getClientOriginalName(),
                    'base64' => 'date:' . $plik->getMimeType() . ';base64,' . base64_encode(file_get_contents($plik->getRealPath()))
                ];
            }

            $daneDoWyslania['pliki'] = $pliki;

            $klient = new Client();
            $odpowiedz = $klient->post('http://mojschowek.pl/api/zasob', [
                'json' => $daneDoWyslania
            ]);

            return new JsonResponse(json_decode($odpowiedz->getBody()->getContents()));
        }

        return $this->render('@Api/Formularz/upload.html.twig', array(
            'pola' => $formularz->createView()
        ));
    }

    /**
     * @Route("/download")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function DownloadAction(Request $request)
    {
        $formularz = $this->get('form.factory')->createNamedBuilder('')
            ->setAction($this->generateUrl('api_api_getzasob'))
            ->setMethod('GET')
            ->add('login', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Użytkownik']
            ])
            ->add('haslo', PasswordType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Hasło']
            ])
            ->add('token', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'f0a6fd0c-62d5-48f1-b06c-325789694d07']
            ])
            ->add('id_zasobu', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'bdf3f773-bd88-562e-ab23-f8a4a35b609e']
            ])
            ->add('pobierz', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary float-right']
            ])
            ->getForm();

        return $this->render('@Api/Formularz/download.html.twig', array(
            'pola' => $formularz->createView()
        ));
    }

    /**
     * @Route("/delete")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function DeleteAction(Request $request)
    {
        $formularz = $this->get('form.factory')->createNamedBuilder('')
            ->setAction($this->generateUrl('api_api_deletezasob'))
            ->setMethod('DELETE')
            ->add('login', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Użytkownik']
            ])
            ->add('haslo', PasswordType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Hasło']
            ])
            ->add('token', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'f0a6fd0c-62d5-48f1-b06c-325789694d07']
            ])
            ->add('id_zasobu', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'bdf3f773-bd88-562e-ab23-f8a4a35b609e']
            ])
            ->add('usun', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary float-right']
            ])
            ->getForm();

        return $this->render('@Api/Formularz/delete.html.twig', array(
            'pola' => $formularz->createView()
        ));
    }

    /**
     * @Route("/put")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function PutAction(Request $request)
    {
        $formularz = $this->get('form.factory')->createNamedBuilder('')
            ->setAction($this->generateUrl('api_api_putzasob'))
            ->setMethod('PUT')
            ->add('login', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Użytkownik']
            ])
            ->add('haslo', PasswordType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Hasło']
            ])
            ->add('token', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'f0a6fd0c-62d5-48f1-b06c-325789694d07']
            ])
            ->add('id_zasobu', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'bdf3f773-bd88-562e-ab23-f8a4a35b609e']
            ])
            ->add('pierwotna_nazwa', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'nowa nazwa zasobu'],
                'required' => false
            ])
            ->add('czy_usuniety', IntegerType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'czy usuniety'],
                'required' => false
            ])
            ->add('aktualizuj', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary float-right']
            ])
            ->getForm();

        return $this->render('@Api/Formularz/put.html.twig', array(
            'pola' => $formularz->createView()
        ));
    }
}
