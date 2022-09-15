<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use App\ApiPlatform\Filter\UuidSearchFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\Validator\Constraints\User as UserValidator;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\HasLifecycleCallbacks()
 * @Vich\Uploadable
 * @UniqueEntity("email", message="registration.email_exists")
 * @ApiFilter(SearchFilter::class, properties={"email": "partial", "seoName": "exact", "fullName": "partial", "type": "exact"})
 * @ApiFilter(UuidSearchFilter::class, properties={"uuid": "exact", "userCompanyProfiles.company.uuid": "exact"})
 * @ApiFilter(BooleanFilter::class, properties={"userCompanyProfiles.enabled"})
 * @ApiFilter(OrderFilter::class, properties={"fullName", "displayName"})
 * @ApiResource(
 *   normalizationContext={"groups"={"read"}}
 * )
 */
class User extends AbstractEntity implements UserInterface
{
    use UuidTrait;

    const PROVIDER_MANUAL = 'manual';
    const PROVIDER_GOOGLE = 'google';
    const BOT_USER_TYPE = 0;
    const NORMAL_USER_TYPE = 1;
    const DEFAULT_USER_STATUS = 1;
    const ROLE_API_CLIENT = 'ROLE_API_CLIENT';

    /**
     * @ORM\Column(type="string", length=64, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Groups({"read", "anon", "signup"})
     */
    private string $email;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank()
     * @Groups({"read", "write", "post", "anon"})
     */
    private string $fullName;

    /**
     * @ORM\Column(type="string", nullable=true, length=64)
     * @Groups({"read", "write", "anon"})
     */
    private ?string $displayName = null;

    /**
     * @Groups({"write"})
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $password = null;

    /**
     * @Groups({"patch"})
     */
    private ?string $oldPassword = null;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     * @Groups({"read", "write"})
     */
    private ?string $phone = null;

    /**
     * @Assert\Timezone()
     * @Groups({"read", "write","anon"})
     * @ORM\Column(type="string", length=32, nullable=true, options={"default": "UTC"})
     */
    private string $timeZone = "UTC";

    /**
     * @Groups({"read", "write", "anon"})
     * @ORM\Column(type="string", length=400, nullable=true)
     */
    private ?string $summary = null;

    /**
     * @Groups({"read"})
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected ?DateTimeInterface $lastLogin = null;

    /**
     * M for male and F for female
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    protected ?string $gender = null;

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
     * @ORM\OneToMany(targetEntity=UserImage::class, mappedBy="user")
     */
    private $userImages;


    public function __construct() {
        $currentDateTime = new \DateTime('now');
        $this->created = $currentDateTime;
        $this->updated = $currentDateTime;
        $this->userImages = new ArrayCollection();
    }

    public function getEmail(): ?string
    {
        return isset($this->email) ? $this->email : null;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;
        return $this;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(?string $displayName): self
    {
        $this->displayName = $displayName;
        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(?string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;
        return $this;
    }

    public function getTimeZone(): string
    {
        return $this->timeZone;
    }

    public function setTimeZone(string $timeZone): self
    {
        $this->timeZone = $timeZone;
        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;
        return $this;
    }

    /**
     * @param $role
     * @return bool
     */
    public function hasRole($role)
    {
        return in_array($role, $this->roles);
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;
        return $this;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->getEmail();
    }

    public function eraseCredentials()
    {
        //todo
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
            $userImage->setUser($this);
        }

        return $this;
    }

    public function removeUserImage(UserImage $userImage): self
    {
        if ($this->userImages->removeElement($userImage)) {
            // set the owning side to null (unless already changed)
            if ($userImage->getUser() === $this) {
                $userImage->setUser(null);
            }
        }

        return $this;
    }
}
