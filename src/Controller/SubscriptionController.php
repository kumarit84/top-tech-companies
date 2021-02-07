<?php

namespace App\Controller;

use App\Entity\Subscribe;
use App\Form\Type\SubscribeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionController extends AbstractController
{
    /**
     * @Route("/subscription", name="subscription")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {

        // creates a task object and initializes some data for this example
        $subscribe = new Subscribe();
        //$subscribe->name = 'dfdf';

        //public $res;
        $form = $this->createForm(SubscribeType::class, $subscribe);

        $res= array();
        $subval = '';
        $form->handleRequest($request);

        //On submit save the content to file
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $subscribe = $form->getData();


            $num =0;
            if(file_exists("file.csv")) {
                $row = 0;
                $mycsvfile = array(); //define the main array.
                if (($handle = fopen("file.csv", "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $num = count($data);
                        $mycsvfile[] = $data; //add the row to the main array.
                        $row++;
                    }
                    fclose($handle);
                }
                if($row == 0){
                    $num = 1;
                }else{
                    $row = $row-1;
                    $num = $mycsvfile[$row][4]+1;
                }
            }else{
                $num=1;
            }
            //Save the data to csv file
            foreach(['name', 'email', 'news','date','id'] as $field){
                if($field == 'date') {
                    $subval = date('d-m-Y');
                }elseif($field == 'id'){
                    $subval = $num;
                }elseif(is_array($subscribe->{$field})){
                    $subval = serialize($subscribe->news);
                }else{
                    $subval = $subscribe->{$field};
                }
                $res[] = $subval;
            }
            $fp = fopen('file.csv', 'a+');
            fputcsv($fp, $res);

            return $this->redirectToRoute('list');
        }

        return $this->render('subscribe/index.html.twig', [
            'form' => $form->createView(),
        ]);

    }
}