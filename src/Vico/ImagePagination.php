<?php
namespace Vico;

use App\Entity\Picture;
use App\Entity\Product;
use App\Vico\UrlHelper;
use Vico\Attachment\ProductAttachment;




class ImagePagination
{

	/**
	 * @var Picture[]
	 */
	private $pictures = [];

	private $count;

	private $perPage = 1;

	private $currentPage;

	private $offset;

	/**
	 * @var UrlHelper
	 */
	private $url_helper;

	public function __construct(Product $product, UrlHelper $url_helper)
	{
		$this->url_helper = $url_helper;
		$this->currentPage = $this->url_helper->getPositiveInt('img', 1);

		$pictures = $product->getPictures();
		$i = 1;
		foreach($pictures as $picture)
		{
			if($picture->getFirst())
			{
				$this->pictures[0] = $picture;
			}
			else
			{
				$this->pictures[$i] = $picture;
				$i++;
			}
		}
		$this->count = count($this->pictures);

		$this->offset = $this->perPage * $this->currentPage - $this->perPage;
	}

	
	public function view():string
	{
		return <<<HTML
				<div style="text-align: center;" class="row mb-4 align-items-center">
					<div class="col-3" style="text-align: end;">
						{$this->previousLink()}
					</div>
					<div class="col-6">
						{$this->image_view(($this->offset), 'medium', true)}
					</div>
					<div class="col-3" style="text-align: start;">
						{$this->nextLink()}
					</div>
				</div>
				
				<div style="text-align: center;">
					{$this->image_view(($this->offset - 1), 'nano')}
					{$this->image_view(($this->offset), 'mini')}
					{$this->image_view(($this->offset + 1), 'nano')}
				</div>
				HTML;
	}

	/**
	 * @param bool|null $first
	 */
	private function image_view(int $offset, string $format, $first = false):?string 
	{
		if($this->count <= 0)
		{
			if($format !== 'medium') 
			{
				return null;
			}
			$name = 'default';
			$link = false;
		}
		elseif($offset < 0 OR $offset >= $this->count OR !isset($this->pictures[$offset]))
		{
			$name = 'black';
			$link = false;
		}
		else
		{
			$name = $this->pictures[$offset]->getName();
			$link = true;
		}
		$img = '<img style="max-width: 100%;" src="'.ProductAttachment::PRODUCT_PIC_URL . $name . '_' . $format . ProductAttachment::EXTENSION.'">';
		if($link)
		{
			$href = $this->url_helper->modif_get(null, ['img' => $offset]);
			if($first)
			{
				$href = ProductAttachment::PRODUCT_PIC_URL . $name . '_maxi' . ProductAttachment::EXTENSION;
			}
			return '<a href="'.$href.'">'.$img.'</a>';
		}
		return $img;
	}
	
	private function previousLink():?string 
	{
		if($this->count <= 0)
		{
			return null;
		}	
		$previous_href = $this->url_helper->modif_get(null, ['img' => $this->currentPage - 1]);
		$previous_class = $this->currentPage <= 1 ? 'disabled': '';
		return <<<HTML
			<span class="page-item $previous_class">
				<a style="display: inline;" class="page-link" href="$previous_href" aria-label="Previous" $previous_class>
					<span aria-hidden="true">&laquo;</span>
					<span class="sr-only"></span>
				</a>
			</span>
			HTML;
	}

	private function nextLink():?string
	{	
		if($this->count <= 0)
		{
			return null;
		}
		$next_href = $this->url_helper->modif_get(null, ['img' => $this->currentPage + 1]);
		$next_class = $this->currentPage >= $this->count ? 'disabled': '';
		return <<<HTML
			<span class="page-item $next_class">
				<a style="display: inline;" class="page-link" href="$next_href" aria-label="Next">
					<span aria-hidden="true">&raquo;</span>
					<span class="sr-only"></span>
				</a>
			</span>
			HTML;
	}

}