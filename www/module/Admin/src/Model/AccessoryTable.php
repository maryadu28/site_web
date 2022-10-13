<?php

namespace Admin\Model;

use RuntimeException;
use Laminas\Db\Sql\Select;
use Laminas\Db\TableGateway\TableGatewayInterface;
use Laminas\I18n\Filter\NumberParse;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;

class AccessoryTable

{
    private $tableGateway;
    private $compteur = 0;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
        $this->table = $this->tableGateway->table;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }
    // fonction qui récupere un seul article accessoire 
    public function fetchOne()
    {


        $resultSet = $this->tableGateway->select(function (Select $select) {

            $select->join(array('co' => 'color'), 'co.id = ' . $this->table . '.id_color', ['name_color' => 'color'], 'left');

            $select->join(array('ma' => 'material'), 'ma.id = ' . $this->table . '.id_material', ['name_material' => 'material'], 'left');

            $select->join(array('m' => 'brand'), 'm.id = ' . $this->table . '.id_brand', ['name_brand' => 'brand'], 'left');

            $select->join(array('ca' => 'category'), 'ca.id = ' . $this->table . '.id_category', ['name_category' => 'category'], 'left');


            $select->order([$this->tableGateway->table . '.id ASC'])->limit(1)->offset(0);

            //s($this->tableGateway, $select);
        });

        return $resultSet;
    }

    // fonction qui récupère tous les articles accessoire
    public function fetchAllFull()
    {

        $resultSet = $this->tableGateway->select(function (Select $select) {

            $select->join(array('co' => 'color'), 'co.id = ' . $this->table . '.id_color', ['name_color' => 'color'], 'left');

            $select->join(array('ma' => 'material'), 'ma.id = ' . $this->table . '.id_material', ['name_material' => 'material'], 'left');

            $select->join(array('m' => 'brand'), 'm.id = ' . $this->table . '.id_brand', ['name_brand' => 'brand'], 'left');

            $select->join(array('ca' => 'category'), 'ca.id = ' . $this->table . '.id_category', ['name_category' => 'category'], 'left');


            $select->order([$this->tableGateway->table . '.id ASC']);

            //s($this->tableGateway, $select);
        });

        return $resultSet;
    }
    //  fonction qui récupère un article après l'autre lorsqu'on clique sur le boutton 
    public function fetchAllFullOne($compteur)
    {


        $this->compteur = (int)($compteur);
        //d($compteur);


        $resultSet = $this->tableGateway->select(function (Select $select) {

            $select->join(array('co' => 'color'), 'co.id = ' . $this->table . '.id_color', ['name_color' => 'color'], 'left');

            $select->join(array('ma' => 'material'), 'ma.id = ' . $this->table . '.id_material', ['name_material' => 'material'], 'left');

            $select->join(array('m' => 'brand'), 'm.id = ' . $this->table . '.id_brand', ['name_brand' => 'brand'], 'left');

            $select->join(array('ca' => 'category'), 'ca.id = ' . $this->table . '.id_category', ['name_category' => 'category'], 'left');


            $select->order([$this->tableGateway->table . '.id ASC']);

            //s($this->tableGateway, $select);

            if ($this->compteur == 1) {

                $select->limit(1)->offset(0);
            }

            if ($this->compteur == 2) {

                $select->limit(1)->offset(1);
            } else {
                // ça renvoie à chaque fois lorsqu'on clique sur le boutton au max une photo  (limit)
                $select->limit(1)->offset($this->compteur + 1);
            }
        });

        return $resultSet;
    }

    public function getAccessory($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();

        if (!$row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row;
    }

    public function saveAccessory(Accessory $accessory)
    {
        $data = [
            'id_material' => $accessory->id_material,
            'id_color'  => $accessory->id_color,
            'id_brand'  => $accessory->id_brand,
            'id_category'  => $accessory->id_category,
            'price'  => $accessory->price,
            'name' => $accessory->name,
            'reference' => $accessory->reference,
            'description' => $accessory->description,
            //'img'=>$accessory->img,

        ];

        if (!empty($accessory->img)) {
            $data['img'] = $accessory->img;
        }

        $id = (int) $accessory->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->getAccessory($id);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update album with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteAccessory($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}
