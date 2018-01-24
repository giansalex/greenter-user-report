<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 08/10/2017
 * Time: 19:13.
 */

namespace Greenter\App\Controllers;

use Greenter\App\Models\Setting;
use Greenter\App\Models\User;
use Greenter\App\Repository\UserRepository;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;
use Slim\Views\Twig;

class HomeController
{
    /**
     * @var Twig
     */
    private $view;
    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var string
     */
    private $pathUpload;

    public function __construct(Twig $view, UserRepository $repository, $pathUpload)
    {
        $this->view = $view;
        $this->repository = $repository;
        $this->pathUpload = $pathUpload;
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index($request, $response, $args)
    {
        return $this->view->render($response, 'home/index.html.twig', $args);
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function setting($request, $response, $args)
    {
        /** @var $user User */
        $user = $request->getAttribute('user');
        $setting = $this->repository->getSetting($user->getId());
        $params = [];
        if ($request->isPost()) {
            $files = $request->getUploadedFiles();
            /** @var $uploadedFile UploadedFile */
            $uploadedFile = $files['logo'];
            if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                $filename = $this->moveUploadedFile($this->pathUpload, $uploadedFile);
                if ($setting->getLogo()) {
                    $old_file = $this->pathUpload.DIRECTORY_SEPARATOR.$setting->getLogo();
                    unlink($old_file);
                }

                $setting = new Setting();
                $setting->setLogo($filename)
                    ->setIdUser($user->getId())
                    ->setParameters([]);

                $this->repository->saveSetting($setting);
                $params['saved'] = true;
            }
        }

        $params['setting'] = $setting;

        return $this->view->render($response, 'home/setting.html.twig', $params);
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function image($request, $response, $args)
    {
        /** @var $user User */
        $user = $request->getAttribute('user');
        $setting = $this->repository->getSetting($user->getId());

        if (empty($setting->getLogo())) {
            return $response->withStatus(404);
        }
        $image = $this->pathUpload.DIRECTORY_SEPARATOR.$setting->getLogo();

        $response = $response->withHeader('Content-Type', $this->getMimeType($setting->getLogo()));
        $response->getBody()->write(file_get_contents($image));

        return $response;
    }

    private function moveUploadedFile($directory, UploadedFile $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = md5(uniqid('i_'));
        $filename = sprintf('%s.%0.8s', $basename, $extension);

        $uploadedFile->moveTo($directory.DIRECTORY_SEPARATOR.$filename);

        return $filename;
    }

    public function getMimeType($filename)
    {
        $ext = strtolower(array_pop(explode('.', $filename)));

        return 'image/'.$ext;
    }
}
