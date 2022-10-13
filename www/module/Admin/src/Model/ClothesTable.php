<?php

namespace Admin\Model;

use RuntimeException;
use Laminas\Db\Sql\Select;
use Laminas\Db\TableGateway\TableGatewayInterface;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;

class ClothesTable

{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
        $this->table = $this->tableGateway->table;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function fetchAllFull()
    {

        //		SELECT c.*, t.type as name_type, co.color as name_color 
        //		FROM `clothes` c
        //		left join `type` t on c.type = t.`id`
        //		left join `color` co on c.color = co.`id`
        // SELECT *
        // FROM table1
        // LEFT OUTER JOIN table2 ON table1.id = table2.fk_id

        $resultSet = $this->tableGateway->select(function (Select $select) {

            $select->join(array('co' => 'color'), 'co.id = ' . $this->table . '.color', ['name_color' => 'color'], 'left');

            $select->join(array('ty' => 'type'), 'ty.id = ' . $this->table . '.type', ['name_type' => 'type'], 'left');

            $select->join(array('fa' => 'fabric'), 'fa.id = ' . $this->table . '.fabric', ['name_fabric' => 'fabric'], 'left');

            $select->join(array('si' => 'size'), 'si.id = ' . $this->table . '.size', ['name_size' => 'size'], 'left');

            $select->order([$this->tableGateway->table . '.id ASC']);

            // s($this->tableGateway, $select);

        });

        return $resultSet;
    }





    public function getClothes($id)
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

    public function saveClothes(Clothes $clothes)
    {
        $data = [
            'type' => $clothes->type,
            'color'  => $clothes->color,
            'fabric'  => $clothes->fabric,
            'size'  => $clothes->size,
            // 'img' => $clothes->img,
            'description' => $clothes->description,
        ];

        if (!empty($clothes->img)) {
            $data['img'] = $clothes->img;
            return;
        }
        $id = (int) $clothes->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->getClothes($id);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update album with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteClothes($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}
