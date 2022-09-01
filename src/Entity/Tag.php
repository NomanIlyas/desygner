<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Index;

/**
 * @ApiResource()
 * @Table(name="tag",indexes={
 *     @Index(name="tag_idx", columns={"name", "name"})
 * })
 * @ORM\Entity(repositoryClass=TagRepository::class)
 */
class Tag extends AbstractEntity
{
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Image::class, inversedBy="tags")
     */
    private $Image;

    public function __construct()
    {
        $this->Image = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImage(): Collection
    {
        return $this->Image;
    }

    public function addImage(Image $image): self
    {
        if (!$this->Image->contains($image)) {
            $this->Image[] = $image;
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        $this->Image->removeElement($image);

        return $this;
    }
}
