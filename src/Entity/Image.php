<?php

namespace App\Entity;

use DateTimeInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ImageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Index;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @Table(name="image",indexes={
 *     @Index(name="image_idx", columns={"name", "name"})
 * })
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 */
class Image extends AbstractEntity
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $source;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, mappedBy="Image")
     */
    private $tags;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $name;

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

    /**
     * @ORM\OneToMany(targetEntity=UserImage::class, mappedBy="image")
     */
    private $userImages;


    public function __construct() {
        $this->tags = new ArrayCollection();
        $currentDateTime = new \DateTime('now');
        $this->created = $currentDateTime;
        $this->updated = $currentDateTime;
        $this->userImages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): self
    {
        $this->source = $source;

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addImage($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->removeElement($tag)) {
            $tag->removeImage($this);
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, UserImage>
     */
    public function getUserImages(): Collection
    {
        return $this->userImages;
    }

    public function addUserImage(UserImage $userImage): self
    {
        if (!$this->userImages->contains($userImage)) {
            $this->userImages[] = $userImage;
            $userImage->setImage($this);
        }

        return $this;
    }

    public function removeUserImage(UserImage $userImage): self
    {
        if ($this->userImages->removeElement($userImage)) {
            // set the owning side to null (unless already changed)
            if ($userImage->getImage() === $this) {
                $userImage->setImage(null);
            }
        }

        return $this;
    }
}
