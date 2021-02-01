<?php

namespace App\Entity;

use App\Repository\MastersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MastersRepository::class)
 */
class Masters
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank(message="Vardo laukelis egali būti tuščias.")
     * @Assert\Length(
     *      min = 3,
     *      max = 64,
     *      minMessage = "Vardas per trumpas. Turi būti bent {{ limit }} raidės",
     *      maxMessage = "Vardas per ilgas. Telpa tik {{ limit }} raidės"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank(message="Pavardės laukelis negali buti tuščias")
     * @Assert\Length(
     *      min = 3,
     *      max = 64,
     *      minMessage = "Pavardė per trumpa. Turi būti bent {{ limit }} raidės",
     *      maxMessage = "Pavardė per ilga. Telpa tik {{ limit }} raidės"
     * )
     */
    private $surname;

    /**
     * @ORM\OneToMany(targetEntity=Outfits::class, mappedBy="master")
     */
    private $outfits;

    public function __construct()
    {
        $this->outfits = new ArrayCollection();
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * @return Collection|Outfits[]
     */
    public function getOutfits(): Collection
    {
        return $this->outfits;
    }

    public function addOutfit(Outfits $outfit): self
    {
        if (!$this->outfits->contains($outfit)) {
            $this->outfits[] = $outfit;
            $outfit->setMaster($this);
        }

        return $this;
    }

    public function removeOutfit(Outfits $outfit): self
    {
        if ($this->outfits->removeElement($outfit)) {
            // set the owning side to null (unless already changed)
            if ($outfit->getMaster() === $this) {
                $outfit->setMaster(null);
            }
        }

        return $this;
    }
}
