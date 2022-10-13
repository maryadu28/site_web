<?php

namespace Admin\Model;

use RuntimeException;
use Laminas\Db\Sql\Select;
// use Laminas\InputFilter\InputFilterAwareInterface;
// use Laminas\InputFilter\InputFilterInterface;

use Laminas\Db\TableGateway\TableGatewayInterface;

class AdminTable
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



    //SELECT `admin`.*, `gr`.`name` AS `name_group` 
    // FROM `admin`
    //  LEFT JOIN `group` AS `gr` ON `gr`.`id` = `admin`.`id_group` ORDER BY `admin`.`id` ASC

    public function fetchAllFull()
    {
        $resultSet = $this->tableGateway->select(function (Select $select) {

            $select->join(array('gr' => 'group'), 'gr.id = ' . $this->table . '.id_group', ['name_group' => 'name'], 'left');

            $select->order([$this->tableGateway->table . '.id ASC']);
            //s($this->tableGateway, $select);
        });

        return $resultSet;
    }


    public function getAdmin($id)
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
    /*
	 public function getAdmin($name)
    {
        $name =  $name;
        $rowset = $this->tableGateway->select(['name' => $name]);
        $row = $rowset->current();
        if (!$row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $name
            ));
        }

        return $row;
    }
	*/
    public function getLogin($login, $password)
    {
        $login =  $login;
        $password =  $password;
        // echo $password ;

        $rowset = $this->tableGateway->select(['password' => $password, 'login' => $login]);

        $row = $rowset->current();
        if (!$row) {
            return false;
        }

        return $row;
    }
    public function saveAdmin(Admin $admin)
    {
        $data = [
            'name' => $admin->name,
            'id_group'  => $admin->id_group,
            'password' => $admin->password,
            'login' => $admin->login,
            'email' => $admin->email
            //'img' => $admin->img,
        ];

        if (!empty($admin->img)) {
            $data['img'] = $admin->img;
        }

        $id = (int) $admin->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->getAdmin($id);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update admin with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteAdmin($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}
