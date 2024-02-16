<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Vico\Attachment\ProductAttachment;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\ManyToOne(targetEntity: Brand::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private $brand;

    #[ORM\Column(type: 'string', length: 255)]
    private $model;

    #[ORM\Column(type: 'string', length: 255)]
    private $description;

    #[ORM\Column(type: 'integer')]
    private $price;

    #[ORM\Column(type: 'integer')]
    private $stock;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private $category;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Picture::class, orphanRemoval: true, cascade: ['persist'])]
    private $pictures;

    

    /**
     * @var array
     */
    private $uploaded_pic = [];

    /**
     * @var array
     */
    private $toDelete_pics = [];


    public function __construct()
    {
        $this->pictures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug():string
    {
        return strtolower(implode('-', explode(' ', $this->getTitle())));
    }

    public function getBrand():?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function getPriceFormatted(): ?string 
    {
        if($this->price === null)
        {
            return null;
        }
        return number_format($this->price, 0, ',', ' '). ' €';
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Picture>
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->setProduct($this);
        }

        return $this;
    }

    /**
     * @param Picture
     */
    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getProduct() === $this) {
                $picture->setProduct(null);
            }
        }

        return $this;
    }


    /**
     * Get the value of uploaded_pic
     *
     * @return  array
     */ 
    public function getUploaded_pic()
    {
        return $this->uploaded_pic;
    }

    /**
     * Set the value of uploaded_pic
     *
     * @param  array  $uploaded_pic
     *
     * @return  self
     */ 
    public function setUploaded_pic(array $uploaded_pic)
    {
        $this->uploaded_pic = $uploaded_pic;

        return $this;
    }

    /**
     * Get the value of toDelete_pics
     *
     * @return  array
     */ 
    public function getToDelete_pics()
    {
        return $this->toDelete_pics;
    }

    /**
     * Set the value of toDelete_pics
     *
     * @param  array  $toDelete_pics
     *
     * @return  self
     */ 
    public function setToDelete_pics($toDelete_pics)
    {
        $this->toDelete_pics = $toDelete_pics;

        return $this;
    }


    /**
     * Set the value of first_pic_choice
     *
     * @param  string|null  $first_pic_choice
     *
     * @return  self
     */ 
    public function setFirst_pic_choice($first_pic_choice)
    {
        $this->first_pic_choice = $first_pic_choice;
        foreach($this->getPictures() as $picture)
        {
            if($picture->getName() === $first_pic_choice)
            {
                $picture->setFirst(true);
            }
            elseif($picture->getFirst() === true)
            {
                $picture->setFirst(false);
            }
        }

        return $this;
    }

    public function getFirst_pic_choice():?string 
    {
        foreach($this->getPictures() as $picture)
        {
            if($picture->getFirst())
            {
                return $picture->getName();
            }
        }
        return null;
    }

    public function getBrand_id():int
    {
        return $this->getBrand()->getId();
    }

    public function getCategory_id():int
    {
        return $this->getCategory()->getId();
    }

    public function getFirst_pic_url(string $format):?string 
    {
        foreach($this->getPictures() as $picture)
        {
            if($picture->getFirst())
            {
                return ProductAttachment::PRODUCT_PIC_URL.$picture->getName().'_'.$format.ProductAttachment::EXTENSION;
            }
        }
        return ProductAttachment::PRODUCT_PIC_URL.'default_'.$format.ProductAttachment::EXTENSION;
    }
    
    public function getFirst_pic_name():?string 
    {
        foreach($this->getPictures() as $picture)
        {
            if($picture->getFirst())
            {
                return $picture->getName();
            }
        }
        return null;
    }

}
