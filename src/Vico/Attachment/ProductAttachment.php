<?php
namespace Vico\Attachment;

use App\Entity\Picture;
use Vico\Config;
use App\Entity\Product;
use Intervention\Image\ImageManager;
use Doctrine\Common\Collections\Collection;

class ProductAttachment
{
    /**
     * @var ImageManager
     */
    private static $imageManager;

    public const PRODUCT_PIC_URL = '../../../images/products/';
    
    public const EXTENSION = '.jpg';

    private const FORMATS = [
        'maxi' => [
            'width' => 600,
            'height' => null
        ],
        'medium' => [
            'width' => null,
            'height' => 350
        ],
        'mini' => [
            'width' => null,
            'height' => 175
        ],
        'nano' => [
            'width' => null,
            'height' => 100
        ]
    ];

    public static function getProductPicPath():string 
    {
        return dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'products';
    }
    

    /**
     * appelé en cas de mise a jour 
     */
    public static function updateImages(Product $product):void 
    {
        //on supprime les fichiers des toDelete_images 
        self::deleteImages($product->getToDelete_pics());
        //on supprime les toDelete_images dans product
        foreach($product->getPictures() as $picture)
        {
            if(in_array($picture->getName(), $product->getToDelete_pics()))
            {
                $product->removePicture($picture);
            }
        }
        //et on upload les nouvelles images
        self::uploadImages($product);
    }

    /**
     * FONCTION A APPELER EN CAS DE SUPPRESSION D UN PRODUIT
     * @param Product $product
     */
    public static function delete(Product $product):void 
    {
        self::deleteImages($product->getPictures());
        //a compléter si nécessaire pour supprimer d'autres attachments autres que les images
    }

    /**
     * FONCTION A APPELER EN CAS D UPDATE POUR SUPPRIMER CERTAINES IMAGES
     * @param Collection|array      (collection de pictures ou array de picture_name)
     */
    public static function deleteImages($data):void
    {
        if(!is_array($data))
        {  
            $names = [];
            foreach($data as $picture)
            {
                $names[] = $picture->getName();
            }
        }
        else
        {
            $names = $data;
        }
        
        foreach($names as $name)
        {
            if(!empty($name))
            {
                $image_path = self::getProductPicPath() . DIRECTORY_SEPARATOR . $name;
                foreach(self::FORMATS as $format => $values)
                {
                    if(file_exists($image_path . '_' . $format . self::EXTENSION))
                    {
                        unlink($image_path . '_' . $format . self::EXTENSION);
                    }
                }
            }
        }
    }

    /**
     * sauvegarde les images téléchargées et hydrate le product avec les images_name
     */
    public static function uploadImages(Product $product):void
    {
        /** @var array 'tmp_name'[] */
        $tmp_names = $product->getUploaded_pic()['tmp_name'];

        self::verifyOrCreate_directory(self::getProductPicPath());
        foreach($tmp_names as $tmp_name)
        {
            if(!empty($tmp_name))
            {
                $name = uniqid('', true);
                self::save($tmp_name, $name);
                $picture = (new Picture())->setName($name);
                if(count($product->getPictures()) < 1)
                {   //si le produit n'a encore aucune image on définit l image ajoutée comme image principale
                    $picture->setFirst(true);
                }
                $product->addPicture($picture);
            }
        }
    }

    private static function verifyOrCreate_directory(string $directory_path):void
    {
        if(!file_exists($directory_path))
        {
            mkdir($directory_path, 0777, true);
        }
    }
    /**
     * @param array|null $formats  (par défault tous les formats)
     */
    private static function save(string $image_path, string $image_name, ?array $formats = ['nano', 'mini', 'medium', 'maxi']):void
    {
        foreach($formats as $format)
        {
            self::getImageManager()
                ->make($image_path)
                ->resize(self::FORMATS[$format]['width'], self::FORMATS[$format]['height'], function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save(self::getProductPicPath() . DIRECTORY_SEPARATOR . $image_name . '_' . $format . self::EXTENSION);
        }                      
    }
    private static function getImageManager():ImageManager
    {
        if(self::$imageManager === null)
        {
            self::$imageManager = new ImageManager(['driver' => 'gd']);
        }
        return self::$imageManager;
    }
}