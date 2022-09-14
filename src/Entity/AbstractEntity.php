<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use DateTimeInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

abstract class AbstractEntity
{
    /**
     * @ORM\Column(name="id", type="integer", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ApiProperty(identifier=false)
     */
    protected int $id;

    /**
     * @ORM\Column(name="created", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="create")
     */
    protected ?DateTimeInterface $created;

    /**
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     */
    protected ?DateTimeInterface $updated;

    public function getId(): ?int
    {
        return isset($this->id) ? $this->id : null;
    }

    public function getCreated(): DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(DateTimeInterface $created): self
    {
        $this->created = $created;
        return $this;
    }

    public function getUpdated(): DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(\DateTimeInterface $updated): self
    {
        $this->updated = $updated;
        return $this;
    }
}
