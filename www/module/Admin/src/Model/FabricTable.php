<?php

namespace Admin\Model;

use RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;

class FabricTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function getFabric($id)
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

    public function saveFabric(Fabric $fabric)
    {
        $data = [
            'fabric' => $fabric->fabric,
           
        ];

        $id = (int) $fabric->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->getFabric($id);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update album with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteFabric($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}

