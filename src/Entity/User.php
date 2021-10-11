<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields="name", message="This user name already exist!")
 */
class User extends Entity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", options={"unsigned": true})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\NotBlank
     * @Assert\Length(min = 4)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Group", inversedBy="users", cascade={"persist", "remove"})
     * @ORM\JoinTable(
     *     name="users_groups",
     *     joinColumns={
     *          @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *          @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     *     }
     * )
     * @ORM\JoinColumn(nullable=false)
     */
    private $groups;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->groups = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return User
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getGroups(): ?array
    {
        return $this->groups->toArray();
    }

    /**
     * @param Collection $groups
     * @return User
     */
    public function setGroups(Collection $groups): self
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * @param Group $group
     * @return User
     */
    public function addGroup(Group $group): self
    {
        $this->groups->add($group);

        return $this;
    }

    /**
     * @param Group $group
     * @return bool
     */
    public function removeGroup(Group $group): bool
    {
        return $this->groups->removeElement($group);
    }
}

