<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
        $formularz = $this->createFormBuilder()
            ->setAction($this->generateUrl('api_api_getupload'))
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
            ->add('plik', FileType::class, [
                'label_attr' => ['class' => 'custom-file-label'],
                'multiple' => true,
                'attr' => [
                    'accept' => 'image/*',
                    'multiple' => 'multiple',
                    'class' => 'custom-file-input'
                ]
            ])
            ->add('uploaduj', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary float-right']
            ])
            ->getForm();

        return $this->render('@Api/Formularz/upload.html.twig', array(
            'pola' => $formularz->createView()
        ));
    }

    /**
     * @Route("/download")
     */
    public function DownloadAction()
    {
        return $this->render('ApiBundle:Formularz:download.html.twig', array(
            // ...
        ));
    }
}
