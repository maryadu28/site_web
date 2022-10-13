<?php

namespace Admin\Model;

use RuntimeException;
use Laminas\Db\Sql\Select;
use Laminas\Db\TableGateway\TableGatewayInterface;
use Laminas\InputFilter\InputFilterAwareInterface;
use Laminas\InputFilter\InputFilterInterface;

class ShoesTable

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
        //		FROM `shoes` c
        //		left join `type` t on c.type = t.`id`
        //		left join `color` co on c.color = co.`id`
        // SELECT *
        // FROM table1
        // LEFT OUTER JOIN table2 ON table1.id = table2.fk_id

        $resultSet = $this->tableGateway->select(function (Select $select) {

            $select->join(array('co' => 'color'), 'co.id = ' . $this->table . '.color', ['name_color' => 'color'], 'left');

            $select->join(array('m' => 'brand'), 'm.id = ' . $this->table . '.brand', ['name_brand' => 'brand'], 'left');;


            $select->order([$this->tableGateway->table . '.id ASC']);

            // s($this->tableGateway, $select);
            /*   SELECT `shoes`.*, `co`.`color` AS `name_color`, 
           `ty`.`type` AS `name_type`, `fa`.`fabric` AS `name_fabric`,
            `si`.`size` AS `name_size` 
            FROM `shoes` 
            LEFT JOIN `color` AS `co` ON `co`.`id` = `shoes`.`color`
            LEFT JOIN `type` AS `ty` ON `ty`.`id` = `shoes`.`type` 
            LEFT JOIN `fabric` AS `fa` ON `fa`.`id` = `shoes`.`fabric` 
            LEFT JOIN `size` AS `si` ON `si`.`id` = `shoes`.`size` 
            ORDER BY `shoes`.`id` ASC*/
        });
        //  d($resultSet);
        return $resultSet;
    }





    public function getShoes($id)
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

    public function saveShoes(Shoes $shoes)
    {
        $data = [
            'shoesSize' => $shoes->shoesSize,
            'typeShoes'  => $shoes->typeShoes,
            'color'  => $shoes->color,
            'brand'  => $shoes->brand,
            'img' => $shoes->img,

        ];
        /*
        if (!empty($shoes->img)) {
            $data['img'] = $shoes->img;
            //d($shoes->img);
            return;
        }*/
        $id = (int) $shoes->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);

            return;
        }

        try {
            $this->getShoes($id);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update album with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteShoes($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}
