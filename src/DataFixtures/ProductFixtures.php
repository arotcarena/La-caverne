<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use App\Entity\Picture;
use App\Entity\Product;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProductFixtures extends Fixture
{


    public function load(ObjectManager $manager): void
    {
        $product = new Product();

        $brandRepository =  $manager->getRepository(Brand::class);
        $categoryRepository = $manager->getRepository(Category::class);

        $brands = $brandRepository->findAll();
        $categories = $categoryRepository->FindAll();

        $b = count($brands);
        $c = count($categories);

        $lorem = 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. 
                Quas animi sint laborum minima nobis, veritatis praesentium mollitia dolorem a vel? 
                Quia nesciunt ad ratione aperiam laboriosam. A delectus fugit voluptate!
                Lorem ipsum, dolor sit amet consectetur adipisicing elit. 
                Quas animi sint laborum minima nobis, veritatis praesentium mollitia dolorem a vel? 
                Quia nesciunt ad ratione aperiam laboriosam. A delectus fugit voluptate!
                Lorem ipsum, dolor sit amet consectetur adipisicing elit. 
                Quas animi sint laborum minima nobis, veritatis praesentium mollitia dolorem a vel? 
                Quia nesciunt ad ratione aperiam laboriosam. A delectus fugit voluptate!';

        $color = [
            'jaune', 'vert', 'rouge', 'bleu', 'violet', 'marron', 'gris', 'noir', 'blanc', 'rose'
        ];

        for ($i=0; $i < 90 ; $i++) 
        { 
            $brand = $brands[random_int(0, ($b - 1))];
            $category = $categories[random_int(0, ($c - 1))];
            $model = substr(str_shuffle(str_repeat('AZERTYUIOPQSDFGHJKLMWXCVBN0123456789-_', 50)), 0, random_int(1, 6));
            $description = substr($lorem, 100, 150);
            $price = random_int(100, 1500);
            $stock = random_int(1, 20);
            $title = $brand->getName(). ' '.$model.' '.$color[random_int(0, 9)];


            $product = (new Product())
                        ->setTitle($title)
                        ->setCategory($category)
                        ->setBrand($brand)
                        ->setModel($model)
                        ->setDescription($description)
                        ->setPrice($price)
                        ->setStock($stock)
                        ->addPicture((new Picture())->setName('default')->setFirst(true));
                        

            
            $manager->persist($product);
        }



        $manager->flush();
    }
}
