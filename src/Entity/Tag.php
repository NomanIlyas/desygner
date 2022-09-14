<?php

namespace App\Entity;

use DateTimeInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Index;
use Symfony\Component\Serializer\Annotation\Groups;

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

    /**
     * @Groups({"read"})
     * @ORM\Column(type="datetime")
     */
    protected ?DateTimeInterface $created;

    /**
     * @Groups({"read"})
     * @ORM\Column(type="datetime")
     */
    protected ?DateTimeInterface $updated;


    public function __construct() {
        $this->Image = new ArrayCollection();
        
        $currentDateTime = new \DateTime('now');
        $this->created = $currentDateTime;
        $this->updated = $currentDateTime;
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
