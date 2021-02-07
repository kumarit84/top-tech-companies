<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ListController extends AbstractController
{
    /**
     * @Route("/list", name="list")
     */
    public function index(Request $request)
    {
        $sort = $request->query->get('sort');
        $orderby = $request->query->get('orderby');

        $data = array();
        $num = 0;
        $res = array();
        $val = array('name', 'email', 'cat', 'date', 'id');
        if (file_exists("file.csv")) {
            if (($handle = fopen("file.csv", "r")) !== FALSE) {
                $i = 0;
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $num = count($data);
                    for ($c = 0; $c < $num; $c++) {
                        if ($val[$c] == 'cat') {
                            $data[$c] = implode(",", unserialize($data[$c]));
                        }
                        $res[$i][$val[$c]] = $data[$c];
                    }
                    $i++;
                }
                fclose($handle);
            }
        }

        if ($sort) {
            if ($sort == 'date') {
                array_multisort(array_map('strtotime', array_column($res, 'date')),
                    SORT_DESC,
                    $res);
            } else {
                $col = array_column($res, $sort);
                array_multisort($col, CONSTANT($orderby), $res);
            }
        }


        return $this->render('list/index.html.twig', [
            'subscribers' => $res,
            'sortitem' => $sort,
            'order' => $orderby,
        ]);
    }

}
