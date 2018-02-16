<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Rental Entity
 *
 * @property int $id
 * @property int $customer_id
 * @property int $car_id
 * @property string $type
 * @property int $duration
 * @property \Cake\I18n\FrozenTime $fromdate
 * @property \Cake\I18n\FrozenTime $todate
 * @property string $note
 * @property string $reference
 * @property float $value
 * @property bool $paid
 * @property bool $done
 * @property float $fine
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Customer $customer
 * @property \App\Model\Entity\Car $car
 * @property \App\Model\Entity\Request[] $requests
 */
class Rental extends Entity
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
