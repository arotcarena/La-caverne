<?php
namespace Vico\Form;

use App\Entity\Product;
use App\Repository\BrandRepository;
use App\Vico\Form\Form;
use App\Repository\CategoryRepository;
use App\Vico\Validator\ProductValidator;
use Vico\Attachment\ProductAttachment;

class ProductForm extends Form
{
    public function __construct(CategoryRepository $categoryRepository, BrandRepository $brandRepository, Product $product = null)
    {
        parent::__construct($product);
        $this->validator = new ProductValidator();

        $categories = $categoryRepository->findAll();
        $category_ids = [];
        $category_names = [];
        foreach($categories as $category)
        {
            $category_ids[] = $category->getId();
            $category_names[] = $category->getName();
        }

        $brands = $brandRepository->findAll();
        $brand_ids = [];
        $brand_names = [];
        foreach($brands as $brand)
        {
            $brand_ids[] = $brand->getId();
            $brand_names[] = $brand->getName();
        }

        $pictures = $product->getPictures();
        $picture_names = [];
        $labels = [];
        foreach($pictures as $picture)
        {
            $picture_names[] = $picture->getName();
            $labels[] = '<img src="'.ProductAttachment::PRODUCT_PIC_URL.$picture->getName().'_nano'.ProductAttachment::EXTENSION.'">';
        }

        $this->getBuilder()
                ->setEnctype('multipart/form-data')
                ->addChoice('radio', 'first_pic_choice', $picture_names, $labels, 'Choix de l\'image à la une', true)
                ->addChoice('checkbox', 'toDelete_pics', $picture_names, $labels, 'Supprimer une image', true)
                ->addFile('uploaded_pic', 'Ajouter des photos (max. 7)', true)
                ->addSelect('brand_id', 'Marque', $brand_ids, $brand_names, 'aucune')
                ->addInput('text', 'model', 'Modèle')
                ->addSelect('category_id', 'Catégorie', $category_ids, $category_names, 'aucune')
                ->addInput('number', 'price', 'Prix')
                ->addTextarea('description', 'Description')
                ->addInput('number', 'stock', 'En stock')
                ->addButton('Modifier', 'btn-primary');
    }
}
