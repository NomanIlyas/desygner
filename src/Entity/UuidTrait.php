<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

trait UuidTrait
{
    /**
     * The internal primary identity key.
     * @ORM\Column(type="uuid", unique=true)
     * @ApiProperty(identifier=true)
     */
    protected UuidInterface $uuid;

    public function getUuid(): ?UuidInterface
    {
        return isset($this->uuid) ? $this->uuid : null;
    }

    public function setUuid(UuidInterface $uuid): self
    {
        $this->uuid = $uuid;
        return $this;
    }
}
