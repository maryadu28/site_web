<?php

namespace Admin\Model;

use RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;

class BookTable
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

    public function getBook($id)
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

    public function saveBook(Book $book)
    {
        $data = [
            'author' => $book->author,
            'title'  => $book->title,
            'summary'  => $book->summary,
        ];

        $id = (int) $book->id;

        if ($id === 0) {
            $this->tableGateway->insert($data);
            return;
        }

        try {
            $this->getBook($id);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update album with identifier %d; does not exist',
                $id
            ));
        }

        $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteBook($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}
