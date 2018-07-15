<?php

namespace ApiBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
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

            if($this->wielkoscWszystkichZalaczonychPlikow($dane['pliki']) >= 2){
                try{
                    $odpowiedz = $this->wyslijPakietami($dane['pliki'], $daneDoWyslania);
                }catch (\Exception $exception){
                    die($exception);
                }
            }else{
                $odpowiedz = $this->wyslijBezPakietow($daneDoWyslania, $dane['pliki']);
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

    /**
     * @param ArrayCollection $kolekcjaUploadedFile UploadedFile
     * @return int
     */
    private function wielkoscWszystkichZalaczonychPlikow($kolekcjaUploadedFile)
    {
        $rozmiarSumaryczny = 0;
        /**
         * @var $plik UploadedFile
         */
        foreach ($kolekcjaUploadedFile as $plik){
            $rozmiarSumaryczny = $rozmiarSumaryczny + $plik->getClientSize();
        }

        return $rozmiarSumaryczny;
    }

    /**
     * @param ArrayCollection $kolekcjaUploadedFile UploadedFile
     * @param $struktura
     * @throws \Exception
     */
    private function wyslijPakietami($kolekcjaUploadedFile, $struktura)
    {
        $polaczenie = $this->otworzPolaczenieWysylkiPakietow();
        /**
         * @var $plik UploadedFile
         */
        foreach ($kolekcjaUploadedFile as $plik){
            $handle = fopen($plik->getRealPath(), "r");

            $struktura['dane_wejsciowe'] = [];
            $struktura['dane_wejsciowe']['szkic'] = true;
            $struktura['dane_wejsciowe']['pierwotna_nazwa'] = $plik->getClientOriginalName();
            $struktura['dane_wejsciowe']['mime_type'] = $plik->getClientMimeType();
            $struktura['dane_wejsciowe']['rozmiar'] = $plik->getClientSize();
            $struktura['dane_wejsciowe']['koniec'] = false;
            $struktura['dane_wejsciowe']['strumien'] = false;

            $init = $this->wyslijPakiet($polaczenie, 'patch', $struktura);

            if($init->status !== 1){
                throw new \Exception("Bład komunikacji");
            }
            $struktura['dane_wejsciowe'] = [];
            $struktura['dane_wejsciowe']['id_zasobu'] = $init->id_zasobu;
            $struktura['dane_wejsciowe']['szkic'] = false;
            $struktura['dane_wejsciowe']['strumien'] = true;
            $struktura['dane_wejsciowe']['koniec'] = false;
            if ($handle) { $i = 0;
                while (($line = fgets($handle))) {
                    $struktura['pakiet'] = $line;
                    $this->wyslijPakiet($polaczenie, 'patch', $struktura);$i++;
                }
                fclose($handle);
                $struktura['dane_wejsciowe'] = [];
                $struktura['dane_wejsciowe']['id_zasobu'] = $init->id_zasobu;
                $struktura['dane_wejsciowe']['szkic'] = false;
                $struktura['dane_wejsciowe']['strumien'] = false;
                $struktura['dane_wejsciowe']['koniec'] = true;

                $this->wyslijPakiet($polaczenie, 'patch', $struktura);

            } else {
                throw new \Exception("Nie mogę otworzyć pliku");
            }
        }
    }

    /**
     * @param $struktura
     * @param ArrayCollection $kolekcjaUploadedFile
     * @return \Psr\Http\Message\ResponseInterface
     */
    private function wyslijBezPakietow($struktura, $kolekcjaUploadedFile)
    {
        $pliki = [];
        /**
         * @var $plik UploadedFile
         */
        foreach ($kolekcjaUploadedFile as $plik){
            $pliki[] = [
                'pierwotna_nazwa' => $plik->getClientOriginalName(),
                'base64' => 'data:' . $plik->getMimeType() . ';base64,' . base64_encode(file_get_contents($plik->getRealPath())),
            ];
        }
        $struktura['pliki'] = $pliki;

        $klient = new Client(['timeout' => 15]);
        $odpowiedz = $klient->post('http://mojschowek.pl/api/zasob', [
            'json' => $struktura
        ]);
        return $odpowiedz;
    }

    /**
     * @param $uchwyt Client
     * @param $metoda
     * @param $struktura
     * @return mixed
     */
    private function wyslijPakiet($uchwyt, $metoda, $struktura){
//        $uchwyt->$metoda('http://mojschowek.pl/api/zasob', [
//            'json' => $struktura
//        ]);
        return json_decode(json_encode(['status' => 1, 'id_zasobu' => 'niby_id']));
    }

    private function otworzPolaczenieWysylkiPakietow(){
        return new Client(['timeout' => 15]);
    }
}
