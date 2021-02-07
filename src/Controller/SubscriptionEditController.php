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

class SubscriptionEditController extends AbstractController
{
    /**
     * @Route("/subscription/edit/{id}", name="subscription_edit")
     * @param Request $request
     * @return Response
     */
    public function editSubscribe(Request $request, $id): Response
    {

        // creates a task object and initializes some data for this example
        $subscribe = new Subscribe();

        if($id) {
            //Search for the row based on the Id
            if (($handle = fopen("file.csv", "r+")) !== FALSE) {
                $row = 0;
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $num = count($data);
                    for ($c = 0; $c < $num; $c++) {
                        if ($c == 4 && $data[$c] == $id) {
                            $rowdelete = $row;
                        }
                        $res[$row][$c] = $data[$c];
                    }
                    $row++;
                }
                fclose($handle);
            }
        }


        $subscribe->name = $res[$rowdelete][0];
        $subscribe->email = $res[$rowdelete][1];
        $subscribe->news = unserialize($res[$rowdelete][2]);
        $subscribe->id= $rowdelete;
        $subscribe->data = $res;

        //public $res;
        $form = $this->createForm(SubscribeType::class, $subscribe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $subscribe = $form->getData();

            $deletedata = $subscribe->data;
            $id = $subscribe->id;

            //Delete the row which we edit and add the remaining to the csv
            unset($deletedata[$id]);
            $fp = fopen('file.csv', 'w+');
            foreach ($deletedata as $fields) {
                fputcsv($fp, $fields);
            }
            fclose($fp);

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $num =0;

            if(file_exists("file.csv")) {
                $row = 0;
                $mycsvfile = array(); //define the main array.
                if (($handle = fopen("file.csv", "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $num = count($data);
                        $row++;
                        $mycsvfile[] = $data; //add the row to the main array.
                    }
                    fclose($handle);
                }
                $row = $row-1;
                $num = $mycsvfile[$row][4]+1;
            }else{
                $num=1;
            }
            $result = array();
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
                $result[] = $subval;
            }
            $fp = fopen('file.csv', 'a+');
            fputcsv($fp, $result);

            return $this->redirectToRoute('list');
        }

        return $this->render('subscribe/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}