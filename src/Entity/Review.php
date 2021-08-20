<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     attributes={
 *         "order"={"published": "DESC"},
 *         "pagination_client_enabled"=true,
 *         "pagination_client_items_per_page"=true
 *     },
 *     itemOperations={
 *         "get",
 *         "put"={
 *             "access_control"="is_granted('ROLE_EDITOR') or (is_granted('ROLE_CLIENT') and object.getSeller() == user)"
 *         }
 *     },
 *     collectionOperations={
 *         "get",
 *         "post"={
 *             "access_control"="is_granted('ROLE_CLIENT')",
 *             "normalization_context"={
 *                 "groups"={"get-review-with-seller"}
 *             }
 *         },
 *         "api_product_reviews_get_subresource"={
 *             "normalization_context"={
 *                 "groups"={"get-review-with-seller"}
 *             }
 *         }
 *     },
 *     denormalizationContext={
 *         "groups"={"post"}
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ReviewRepository")
 */
class Review implements SellerEntityInterface, PublishedDateEntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get-review-with-seller"})
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Groups({"post", "get-review-with-seller"})
     * @Assert\NotBlank()
     * @Assert\Length(min=5, max=3000)
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"get-review-with-seller"})
     */
    private $published;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="reviews")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get-review-with-seller"})
     */
    private $seller;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="reviews")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"post"})
     */
    private $product;

    public function getId()
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPublished(): ?\DateTimeInterface
    {
        return $this->published;
    }

    public function setPublished(\DateTimeInterface $published): PublishedDateEntityInterface
    {
        $this->published = $published;

        return $this;
    }

    /**
     * @return User
     */
    public function getSeller(): ?User
    {
        return $this->seller;
    }

    /**
     * @param UserInterface $seller
     */
    public function setSeller(UserInterface $seller): SellerEntityInterface
    {
        $this->seller = $seller;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function __toString()
    {
        return substr($this->content, 0, 20) . '...';
    }


}
