<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Car Entity
 *
 * @property int $id
 * @property string $make
 * @property string $model
 * @property int $year
 * @property string $color
 * @property int $seats
 * @property string $fuel
 * @property int $odometer
 * @property int $transmission
 * @property string $drivetype
 * @property string $enginetype
 * @property int $enginesize
 * @property string $induction
 * @property int $cylinder
 * @property string $power
 * @property int $gear
 * @property string $geartype
 * @property int $fuelcap
 * @property string $fuelconsume
 * @property string $measures
 * @property string $audiodesc
 * @property string $safety
 * @property string $convenience
 * @property string $lightsview
 * @property string $otherspecs
 * @property string $image
 * @property string $shortdesc
 * @property string $description
 * @property float $ddprice
 * @property float $kmprice
 * @property bool $available
 * @property string $type
 * @property string $parking
 * @property string $slot
 * @property float $latitude
 * @property float $longitude
 *
 * @property \App\Model\Entity\Browsing[] $browsings
 * @property \App\Model\Entity\Rental[] $rentals
 */
class Car extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];
}
