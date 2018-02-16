<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Cars Model
 *
 * @property \App\Model\Table\BrowsingsTable|\Cake\ORM\Association\HasMany $Browsings
 * @property \App\Model\Table\RentalsTable|\Cake\ORM\Association\HasMany $Rentals
 *
 * @method \App\Model\Entity\Car get($primaryKey, $options = [])
 * @method \App\Model\Entity\Car newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Car[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Car|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Car patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Car[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Car findOrCreate($search, callable $callback = null, $options = [])
 */
class CarsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('cars');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('Browsings', [
            'foreignKey' => 'car_id'
        ]);
        $this->hasMany('Rentals', [
            'foreignKey' => 'car_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->allowEmpty('make');

        $validator
            ->allowEmpty('model');

        $validator
            ->integer('year')
            ->allowEmpty('year');

        $validator
            ->allowEmpty('color');

        $validator
            ->integer('seats')
            ->allowEmpty('seats');

        $validator
            ->allowEmpty('fuel');

        $validator
            ->integer('odometer')
            ->allowEmpty('odometer');

        $validator
            ->integer('transmission')
            ->allowEmpty('transmission');

        $validator
            ->allowEmpty('drivetype');

        $validator
            ->allowEmpty('enginetype');

        $validator
            ->integer('enginesize')
            ->allowEmpty('enginesize');

        $validator
            ->allowEmpty('induction');

        $validator
            ->integer('cylinder')
            ->allowEmpty('cylinder');

        $validator
            ->allowEmpty('power');

        $validator
            ->integer('gear')
            ->allowEmpty('gear');

        $validator
            ->allowEmpty('geartype');

        $validator
            ->integer('fuelcap')
            ->allowEmpty('fuelcap');

        $validator
            ->allowEmpty('fuelconsume');

        $validator
            ->allowEmpty('measures');

        $validator
            ->allowEmpty('audiodesc');

        $validator
            ->allowEmpty('safety');

        $validator
            ->allowEmpty('convenience');

        $validator
            ->allowEmpty('lightsview');

        $validator
            ->allowEmpty('otherspecs');

        $validator
            ->allowEmpty('image');

        $validator
            ->allowEmpty('shortdesc');

        $validator
            ->allowEmpty('description');

        $validator
            ->numeric('ddprice')
            ->allowEmpty('ddprice');

        $validator
            ->numeric('kmprice')
            ->allowEmpty('kmprice');

        $validator
            ->boolean('available')
            ->allowEmpty('available');

        $validator
            ->allowEmpty('type');

        $validator
            ->allowEmpty('parking');

        $validator
            ->allowEmpty('slot');

        $validator
            ->numeric('latitude')
            ->allowEmpty('latitude');

        $validator
            ->numeric('longitude')
            ->allowEmpty('longitude');

        return $validator;
    }
}
