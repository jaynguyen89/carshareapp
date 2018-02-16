<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Customer Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $address
 * @property string $cardholder
 * @property string $cardcode
 * @property \Cake\I18n\FrozenDate $carddate
 * @property string $cardaddress
 * @property string $paypal
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Browsing[] $browsings
 * @property \App\Model\Entity\Message[] $messages
 * @property \App\Model\Entity\Rental[] $rentals
 * @property \App\Model\Entity\Request[] $requests
 */
class Customer extends Entity
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
