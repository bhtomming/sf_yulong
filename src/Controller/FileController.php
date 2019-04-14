<?php
/**
 * Created by PhpStorm.
 * User: 烽行天下
 * Date: 2019/4/14
 * Time: 12:04
 * Site: http://www.drupai.com
 */

namespace App\Controller;


use App\Servers\FileManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AddressController
 * @package AppBundle\Controller
 * @Route("/file")
 */
class FileController extends AbstractController
{
    /**
     * @Route("/view", name="file_view")
     */
    public function view(){
        $finder = $this->getFinder();
        $imgPath = $this->getParameter('app.path.article_images');
        $images = [];
        foreach ($finder as $file){
            $image['name'] = substr($file->getFilename(),0,strpos($file->getFilename(),'.'.$file->getExtension()));
            $image['path'] = $imgPath.'/'.$file->getFilename();
            $images[] = $image;
        }
        return $this->render('default/file_browser.html.twig',array(
            'images' => $images
        ));
    }

    /**
     * @Route("/upload", name="file_upload")
     */
    public function upload(Request $request){
        $file = $request->files->get('upload');
        $token = $request->request->get('token');
        $path = $this->getParameter('kernel.project_dir').'/public'.$this->getParameter('app.path.article_images');
        $fileManager = new FileManager($path);
        $fileName = $fileManager->upload($file);

        $data  = [
            'uploaded' => 1,
            'fileName' => $fileName,
            'url' => $this->getParameter('app.path.article_images').'/'.$fileName,
        ];
        return new JsonResponse($data);
    }

    /**
     * @Route("/del", name="file_del");
     */
    public function imagesDel(Request $request){
        $data = [
            'status' => 202,
        ];
        $fileName = $this->getParameter('kernel.project_dir').'/public'.$request->request->get('fileName');
        if(file_exists($fileName)){
            unlink($fileName);
            $data['status'] = 200;
        }
        return new JsonResponse($data);
    }

    /**
     * @Route("/modify", name="file_mod");
     */
    public function imagesMod(Request $request){
        $data = [
            'status' => 202,
        ];

        $oldFile = $this->getParameter('kernel.project_dir').'/public'.$request->request->get('filePath');
        $newName = $this->getParameter('kernel.project_dir').'/public'.$this->getParameter('app.path.article_images').'/'.$request->request->get('fileName');

        if(file_exists($oldFile)){
            $extend = substr($oldFile,strrpos($oldFile,'.'));
            rename($oldFile,$newName.$extend);
            $data['status'] = 200;
        }
        return new JsonResponse($data);
    }

    public function getFinder(){
        $finder = new Finder();
        $path = $this->getParameter('kernel.project_dir').'/public'.$this->getParameter('app.path.article_images');
        $finder->files()->in($path);
        return $finder;
    }

}