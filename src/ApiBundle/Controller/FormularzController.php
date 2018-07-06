<?php

namespace ApiBundle\Controller;

use ApiBundle\Utils\Base64Response;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @return JsonResponse|Response
     */
    public function UploadAction(Request $request)
    {
        $formularz = $this->createFormBuilder()
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

                $daneWejsciowe = [
                    'token' => $dane['token'],
                    'login' => $dane['login'],
                    'haslo' => $dane['haslo'],
                    'pliki' => $dane['pliki']
                ];

                $pliki = [];

                /**
                 * @var $plik UploadedFile
                 */
                foreach ($dane['pliki'] as $plik){
                    $zrodlo = file_get_contents($plik->getRealPath());
                    $pliki[] = [
                        "base64" => 'data:' . $plik->getClientMimeType() . ';base64,' . base64_encode($zrodlo),
                        "pierwotna_nazwa" => $plik->getClientOriginalName()
                    ];
                }

                $daneWejsciowe['pliki'] = $pliki;

                $client = new Client([
                    'base_uri' => 'http://mojschowek.pl/api/zasob',
                    'timeout' => 10,
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json'
                    ]
                ]);

                $daneDoWysylki = [
                    'json' => $daneWejsciowe
                ];
                try{
                    $odpowiedz = $client->post('/api/zasob', $daneDoWysylki);
                }catch (\Exception $exception){
                    dump($exception->getMessage());die();
                }

                return new JsonResponse(json_decode($odpowiedz->getBody()->getContents()));
            }

        return $this->render('@Api/Formularz/upload.html.twig', array(
            'pola' => $formularz->createView()
        ));
    }

    /**
     * @Route("/download")
     * @param Request $request
     * @return Base64Response|Response
     */
    public function DownloadAction(Request $request)
    {
        $formularz = $this->createFormBuilder()
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

        $formularz->handleRequest($request);

        if($formularz->isSubmitted() && $formularz->isValid()){
            $dane = $formularz->getData();

            $daneWejsciowe = [
                'token' => $dane['token'],
                'login' => $dane['login'],
                'haslo' => $dane['haslo'],
                'id_zasobu' => $dane['id_zasobu']
            ];

            $client = new Client([
                'timeout' => 10,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ]
            ]);
            try{
                $odpowiedz = $client->get('http://mojschowek.pl/api/zasob?' . http_build_query($daneWejsciowe));
            }catch (\Exception $exception){
                dump($exception->getMessage());die();
            }

            $odp = json_decode($odpowiedz->getBody()->getContents());

            return new Response('<video controls><source src="'.$odp->base64.'"></video>');
        }

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
        $formularz = $this->createFormBuilder()
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

        if($formularz->isSubmitted() && $formularz->isValid()){
            $dane = $formularz->getData();

            $daneWejsciowe = [
                'token' => $dane['token'],
                'uzytkownik' => [
                    'login' => $dane['login'],
                    'haslo' => $dane['haslo']
                ],
                'id_zasobu' => $dane['id_zasobu']
            ];

            $client = new Client([
                'timeout' => 10,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ]
            ]);

            $odpowiedz = $client->delete('http://127.0.0.1:8000/api/zasob', [
                'form_params' => $daneWejsciowe
            ]);

            return new Response($odpowiedz->getBody());
        }

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
        $formularz = $this->createFormBuilder()
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

        if($formularz->isSubmitted() && $formularz->isValid()){
            $dane = $formularz->getData();

            $daneWejsciowe = [
                'token' => $dane['token'],
                'uzytkownik' => [
                    'login' => $dane['login'],
                    'haslo' => $dane['haslo']
                ],
                'id_zasobu' => $dane['id_zasobu'],
                'elementy_do_zmiany' => [
                    'pierwotna_nazwa' => $dane['pierwotna_nazwa'],
                    'czy_usuniety' => $dane['czy_usuniety'],
                ]
            ];

            $client = new Client([
                'timeout' => 10,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ]
            ]);

            $odpowiedz = $client->put('http://127.0.0.1:8000/api/zasob', [
                'form_params' => $daneWejsciowe
            ]);

            return new Response($odpowiedz->getBody());
        }

        return $this->render('@Api/Formularz/put.html.twig', array(
            'pola' => $formularz->createView()
        ));
    }
}
