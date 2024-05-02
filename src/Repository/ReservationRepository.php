<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use App\Entity\Reservation;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 * @Repository()
 */
class ReservationRepository extends EntityRepository
{
    // Custom repository methods
}